<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Proveedores';

    public static function form(Form $form): Form
    {
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
                    ->rule('regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/')
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

                Forms\Components\TextInput::make('telefono')
                ->label('Teléfono')
                ->required()
                ->maxLength(20)
                ->tel()
                ->rule('regex:/^\+?[0-9\s\-]{7,20}$/')  
                ->rule(function (callable $get) {         
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $existe = Supplier::where('telefono', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($existe) {
                            $fail('El teléfono ya está registrado para otro proveedor.');
                        }
                    };
                }),

                Forms\Components\TextInput::make('cuit')
                ->label('Cuit')
                ->required()
                ->maxLength(20)
                ->tel()
                ->rule('regex:/^\+?[0-9\s\-]{7,20}$/')  
                ->rule(function (callable $get) {         
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $idActual = $get('id');
                        $existe = Supplier::where('cuit', $value)
                            ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                            ->exists();

                        if ($existe) {
                            $fail('El Cuit ya está registrado para otro proveedor.');
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
                ->preload() 
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
