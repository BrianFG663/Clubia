<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
        protected static ?string $navigationLabel = 'Productos';
        protected static ?string $navigationGroup = '💸Ventas e Inventario';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucwords(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $idActual = $get('id');
                            $actividadExistente = \App\Models\Product::where('nombre', $value)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($actividadExistente) {
                                $fail('Ya hay un producto registrado con este nombre.');
                            }
                        };
                    }),

                Forms\Components\TextInput::make('descripcion')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucwords(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),

                Forms\Components\Select::make('category_id')
                    ->label('Seleccione categoria')
                    ->relationship('Category', 'nombre')
                    ->required()
                    ->columnSpan(1)
                    ->searchable(false)
                    ->preload(false),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio del producto')
                    ->required()
                    ->numeric()
                    ->maxValue(2147483647)
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            if ($value < 0) {
                                $fail('El precio del arancel no puede ser negativo.');
                            }
                        };
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('nombre')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('descripcion')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('category.nombre')
                    ->label('categoria')
                    ->sortable()
                    ->searchable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('precio')
                    ->label('Precio del producto')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2, ',', '.'))
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
