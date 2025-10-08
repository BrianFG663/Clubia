<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationLabel = 'Socios';
    protected static ?string $navigationGroup = '🧍Socios y Actividades';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) Partner::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'primary'; // Podés usar 'success', 'warning', 'danger', etc.
    }

    public static function form(Form $form): Form
    {

        $path = public_path('json/marcacion.json');
        $marcacionesRaw = json_decode(file_get_contents($path), true);
        $marcaciones = array_flip($marcacionesRaw);

        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, mixed $value, \Closure $fail) {
                        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $value)) {
                            $fail('No se aceptan nombres con números.');
                        }
                    };
                })
                ->afterStateHydrated(fn($component, $state) => $component->state(ucfirst(strtolower($state))))
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),

            Forms\Components\TextInput::make('apellido')->required()
                ->afterStateHydrated(fn($component, $state) => $component->state(ucfirst(strtolower($state))))
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),

            Forms\Components\TextInput::make('dni')
                ->label('DNI')
                ->required()
                ->rule(function (callable $get) {
                    return function ($attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $dniExistente = \App\Models\Partner::where('dni', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();
                        if ($dniExistente) {
                            $fail('El DNI ya está registrado por otro socio.');
                        }
                    };
                })
                ->rule(function () {
                    return function ($attribute, $value, \Closure $fail) {
                        $length = strlen((string) $value);
                        if ($length < 7) {
                            $fail('El número ingresado debe tener al menos 7 dígitos (X.XXX.XXX).');
                        }
                        if ($length > 8) {
                            $fail('El número ingresado no puede tener más de 8 dígitos (XX.XXX.XXX).');
                        }
                    };
                }),

            Forms\Components\Hidden::make('state_id')->default(1),

            Forms\Components\TextInput::make('direccion')->required()
                ->afterStateHydrated(fn($component, $state) => $component->state(ucwords(strtolower($state))))
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),

            Forms\Components\TextInput::make('ciudad')->required()
                ->afterStateHydrated(fn($component, $state) => $component->state(ucwords(strtolower($state))))
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),

            Fieldset::make('Teléfono')
                ->schema([
                    Select::make('telefono_marcacion')
                        ->label('Marcación')
                        ->options($marcaciones)
                        ->required()
                        ->dehydrated(true),

                    TextInput::make('telefono_caracteristica')
                        ->label('Característica')
                        ->numeric()
                        ->required()
                        ->dehydrated(true),

                    TextInput::make('telefono_numero')
                        ->label('Número')
                        ->numeric()
                        ->required()
                        ->dehydrated(true)
                        ->rule(function (callable $get) {
                            return function ($attribute, $value, \Closure $fail) use ($get) {
                                if (strlen($value) > 10) {
                                    $fail('El número no puede tener más de 10 dígitos.');
                                }

                                $telefono = '(' . $get('telefono_marcacion') . ')' . $get('telefono_caracteristica') . '-' . $value;
                                $idActual = $get('id');

                                $existe = \App\Models\Partner::where('telefono', $telefono)
                                    ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                    ->exists();

                                if ($existe) {
                                    $fail('Ya existe un socio con ese número de teléfono.');
                                }
                            };
                        }),
                ])
                ->columns(3),



            Forms\Components\TextInput::make('email')
                ->required()
                ->rule(function (callable $get) {
                    return function ($attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');

                        if (trim($value) === '') {
                            $fail('El campo de correo electrónico es obligatorio.');
                            return;
                        }

                        if (!str_contains($value, '@')) {
                            $fail('El correo electrónico debe contener el símbolo "@".');
                            return;
                        }

                        $emailExistente = \App\Models\Partner::where('email', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($emailExistente) {
                            $fail('Correo electrónico ya vinculado con otro socio.');
                        }
                    };
                }),


            Forms\Components\DatePicker::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->required()
                ->reactive(),

            Forms\Components\Select::make('memberTypes')
                ->label('Seleccione tipo de socio')
                ->relationship('memberTypes', 'nombre')
                ->required()
                ->preload(),

            Forms\Components\TextInput::make('dni_responsable')
                ->label('DNI del responsable del grupo familiar (en caso de querer ingresar a uno)')
                ->dehydrated(false)
                ->reactive()
                ->rule(function () {
                    return function ($attribute, $value, \Closure $fail) {
                        if (!$value) return;
                        $responsable = \App\Models\Partner::where('dni', $value)->first();
                        if (!$responsable) {
                            $fail('No existe un socio con ese DNI.');
                            return;
                        }
                        if ($responsable->fecha_nacimiento) {
                            $edad = \Carbon\Carbon::parse($responsable->fecha_nacimiento)->age;
                            if ($edad < 18) {
                                $fail('El socio con ese DNI es menor de edad y no puede ser responsable.');
                                return;
                            }
                        } else {
                            $fail('No se puede verificar la edad del socio (fecha de nacimiento faltante).');
                        }
                    };
                })
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) {
                        $set('responsable_id', null);
                        return;
                    }
                    $responsable = \App\Models\Partner::where('dni', $state)->first();
                    if ($responsable) {
                        $set('responsable_id', $responsable->responsable_id ?? $responsable->id);
                        if (!$responsable->jefe_grupo) {
                            $responsable->jefe_grupo = true;
                            $responsable->save();
                        }
                    } else {
                        $set('responsable_id', null);
                    }
                })
                ->helperText(function ($state) {
                    if (!$state) return null;
                    $responsable = \App\Models\Partner::where('dni', $state)->first();
                    if (!$responsable) return null;
                    if ($responsable->fecha_nacimiento) {
                        $edad = \Carbon\Carbon::parse($responsable->fecha_nacimiento)->age;
                        if ($edad < 18) {
                            return "¡ATENCIÓN! El socio con ese DNI es menor de edad y no puede ser responsable.";
                        }
                    }
                    if ($responsable->responsable_id) {
                        $respRelacionado = \App\Models\Partner::find($responsable->responsable_id);
                        if ($respRelacionado) {
                            return "¡ATENCIÓN! Esta persona está en un grupo familiar a cargo de {$respRelacionado->nombre} {$respRelacionado->apellido}, será agregado a ese grupo familiar.";
                        }
                    }
                    return "¡ATENCIÓN! Esta persona estará a cargo de: {$responsable->nombre} {$responsable->apellido}";
                }),

            Forms\Components\Hidden::make('responsable_id')->dehydrated()->required(false),
        ]);
    }






    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->label('nombre')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('apellido')
                ->label('apellido')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('dni')
                ->label('Numero de documento')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('direccion')
                ->label('Dirección')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('telefono')
                ->label('Teléfono')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('state.nombre')
                ->label('Estado')
                ->sortable()
                ->color(fn($record) => optional($record->state)->id == 1 ? 'success' : 'danger')
                ->alignCenter(),


            Tables\Columns\TextColumn::make('responsable')
                ->label('Responsable')
                ->getStateUsing(function ($record) {
                    return $record->responsable
                        ? $record->responsable->nombre . ' ' . $record->responsable->apellido
                        : '-';
                })
                ->alignCenter(),
        ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modificar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionados')
                        ->icon('heroicon-o-trash'),
                ])
                    ->label('Acciones en grupo'),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
