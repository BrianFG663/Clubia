<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers;
use App\Models\Institution;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;
    protected static ?string $navigationGroup = '🏢Institucional';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Instituciones';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Institution::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {

        $path = public_path('json/marcacion.json');
        $marcacionesRaw = json_decode(file_get_contents($path), true);
        $marcaciones = array_flip($marcacionesRaw);

        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            if (!preg_match('/^[a-zA-Z0-9\s]+$/u', $value)) {
                                $fail('El nombre no debe contener caracteres especiales.');
                            }
                        };
                    }),

                Forms\Components\TextInput::make('ciudad')
                    ->required()
                    ->afterStateHydrated(fn($component, $state) => $component->state(ucwords(strtolower($state))))
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            if (!preg_match('/^[a-zA-Z0-9\s]+$/u', $value)) {
                                $fail('La ciudad no debe contener caracteres especiales.');
                            }
                        };
                    }),

                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->columnSpan(2)
                    ->afterStateHydrated(fn($component, $state) => $component->state(ucwords(strtolower($state))))
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            if (!preg_match('/^[a-zA-Z0-9\s]+$/u', $value)) {
                                $fail('La direccion no debe contener caracteres especiales.');
                            }
                        };
                    }),

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

                                    $existe = \App\Models\Institution::where('telefono', $telefono)
                                        ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                        ->exists();

                                    if ($existe) {
                                        $fail('Ya existe un socio con ese número de teléfono.');
                                    }
                                };
                            }),
                    ])
                    ->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('nombre')
                    ->searchable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('ciudad')
                    ->label('ciudad')
                    ->searchable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('direccion')
                    ->label('direccion')
                    ->searchable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('telefono')
                    ->searchable()
                    ->alignCenter(),
                //
            ])
            ->filters([
                //
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
            'index' => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}
