<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    public static function getPluralModelLabel(): string{
        return 'Proveedores';
    }

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Proveedores';
    protected static ?string $navigationGroup = '📦Inventario y Compras';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {

        $path = public_path('json/marcacion.json');
        $marcacionesRaw = json_decode(file_get_contents($path), true);
        $marcaciones = array_flip($marcacionesRaw);

        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $idActual = $get('id');
                            $existe = Supplier::where('nombre', $value)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($existe) {
                                $fail('El nombre del proveedor ya existe.');
                            }
                        };
                    }),

                Forms\Components\TextInput::make('cuit')
                    ->label('Cuit/Cuil')
                    ->required()
                    ->maxLength(14)
                    ->rule(function (callable $get) {
                        return function (string $attribute, mixed $value, \Closure $fail) use ($get) {
                            // Validación de formato
                            if (!preg_match('/^\d{2}-\d{8}-\d{1}$/', $value)) {
                                $fail('El CUIT debe tener el formato XX-XXXXXXXX-X (11 dígitos con guiones).');
                                return;
                            }

                            // Validación de unicidad
                            $idActual = $get('id');
                            $existe = \App\Models\Supplier::where('cuit', $value)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($existe) {
                                $fail('El CUIT ya está registrado para otro proveedor.');
                            }
                        };
                    }),



                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->required()
                    ->maxLength(255)
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),


                Forms\Components\Select::make('condicion_id')
                    ->label('Condición IVA')
                    ->relationship('condition', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),


                Fieldset::make('Teléfono')
                    ->schema([
                        Select::make('telefono_marcacion')
                            ->label('Marcación')
                            ->options($marcaciones)
                            ->required()
                            ->columnSpan(2)
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

                                    $existe = \App\Models\Supplier::where('telefono', $telefono)
                                        ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                        ->exists();

                                    if ($existe) {
                                        $fail('Ya existe un socio con ese número de teléfono.');
                                    }
                                };
                            }),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('cuit')
                    ->label('Cuit')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modificar'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Eliminar seleccionados'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
