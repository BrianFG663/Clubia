<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationLabel = 'Socios';
        protected static ?string $navigationGroup = '👥Administracion de Socios';

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucfirst(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),
            Forms\Components\TextInput::make('apellido')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucfirst(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),
            Forms\Components\TextInput::make('dni')
                ->label('DNI')
                ->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $dniExistente = \App\Models\Partner::where('dni', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($dniExistente) {
                            $fail('El DNI ya está registrado por otro socio.');
                        }
                    };
                })
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) {
                        $length = strlen((string) $value);

                        if ($length < 7) {
                            $fail('El numero ingresado debe tener al menos 7 dígitos (X.XXX.XXX).');
                        }

                        if ($length > 8) {
                            $fail('El numero ingresado no puede tener más de 8 dígitos (XX.XXX.XXX).');
                        }
                    };
                }),


            Forms\Components\Hidden::make('state_id')
                ->default(1),

            Forms\Components\TextInput::make('direccion')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucwords(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),
            Forms\Components\TextInput::make('ciudad')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucwords(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),
            Forms\Components\TextInput::make('telefono')->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $telefonoExistente = \App\Models\Partner::where('telefono', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($telefonoExistente) {
                            $fail('Telefono ya vinculado con otro socio.');
                        }
                    };
                }),
            Forms\Components\TextInput::make('email')->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $emailExistente = \App\Models\Partner::where('email', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($emailExistente) {
                            $fail('Correo electronico ya vinculado con otro socio.');
                        }
                    };
                }),

            Forms\Components\DatePicker::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->required()
                ->reactive(),

            Forms\Components\TextInput::make('dni_responsable')
                ->label('DNI del responsable del grupo familiar (en caso de querer ingresar a uno)')
                ->dehydrated(false)
                ->columnSpan(2)
                ->reactive()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        if (!$value) {
                            return;
                        }
                        $responsable = \App\Models\Partner::where('dni', $value)->first();
                        if (!$responsable) {
                            $fail('No existe un socio con ese DNI.');
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
                        if ($responsable->responsable_id) {
                            $set('responsable_id', $responsable->responsable_id);
                        } else {
                            $set('responsable_id', $responsable->id);
                        }

                        if (!$responsable->jefe_grupo) {
                            $responsable->jefe_grupo = true;
                            $responsable->save();
                        }
                    } else {
                        $set('responsable_id', null);
                    }
                })
                ->helperText(function ($state) {
                    if (!$state) {
                        return null;
                    }

                    $responsable = \App\Models\Partner::where('dni', $state)->first();

                    if ($responsable) {
                        if ($responsable->responsable_id) {
                            $respRelacionado = \App\Models\Partner::find($responsable->responsable_id);
                            if ($respRelacionado) {
                                return "¡ATENCION! esta persona esta en un grupo familiar a cargo de {$respRelacionado->nombre} {$respRelacionado->apellido}, sera agregado a ese grupo familiar";
                            }
                        }
                        return "¡ATENCION! Esta persona estara a cargo de: {$responsable->nombre} {$responsable->apellido}";
                    }

                    return null;
                }),

            Forms\Components\Hidden::make('responsable_id')
                ->dehydrated()
                ->required(false),


        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['responsable_id'])) {
            $responsable = \App\Models\Partner::find($data['responsable_id']);

            if ($responsable && !$responsable->jefe_grupo) {
                $responsable->jefe_grupo = true;
                $responsable->save();
            }
        }

        return $data;
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

            Tables\Columns\IconColumn::make('menor')
                ->label('Menor de Edad')
                ->boolean()
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
