<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailsRelationManager;

use App\Models\Order;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Órdenes';

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('OrdenTabs')
                    ->tabs([
                        Tabs\Tab::make('Orden')
                            ->schema([
                                Hidden::make('user_id')
                                    ->default(fn() => Auth::id()),

                                Select::make('supplier_id')
                                    ->label('Proveedor')
                                    ->options(Supplier::pluck('nombre', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->columnSpan('full'),

                                DatePicker::make('fecha')
                                    ->label('Fecha')
                                    ->required()
                                    ->default(now())
                                    ->columnSpan('full'),

                                TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan('full')
                                    ->rule(function ($get) {
                                        $calculatedTotal = collect($get('orderDetails') ?? [])
                                            ->sum(fn($item) => ($item['cantidad'] ?? 0) * ($item['precio_unitario'] ?? 0));

                                        return function ($attribute, $value, $fail) use ($calculatedTotal) {
                                            if ($value != $calculatedTotal) {
                                                $fail("El total ingresado ($value) no coincide con la suma de los detalles ($calculatedTotal).");
                                            }
                                        };
                                    }),
                            ])
                            ->columns(1),

                        Tabs\Tab::make('Detalles')
                            ->schema([
                                Repeater::make('orderDetails')
                                    ->label('Detalles')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('nombre_producto')
                                            ->label('Producto')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan('full'),

                                        TextInput::make('cantidad')
                                            ->label('Cantidad')
                                            ->numeric()
                                            ->required()
                                            ->columnSpan('full'),

                                        TextInput::make('precio_unitario')
                                            ->label('Precio Unitario')
                                            ->numeric()
                                            ->required()
                                            ->columnSpan('full')
                                            ->reactive(),
                                    ])
                                    ->createItemButtonLabel('Agregar detalle')
                                    ->columns(1)
                                    ->columnSpan('full'),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan('full'),
            ]);
    }


        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('id')
                        ->label('ID')
                        ->sortable()
                        ->alignCenter(),

                    Tables\Columns\TextColumn::make('supplier.nombre')
                        ->label('Proveedor')
                        ->searchable()
                        ->sortable()
                        ->alignCenter(),

                    Tables\Columns\TextColumn::make('fecha')
                        ->label('Fecha')
                        ->date('d/m/Y')
                        ->sortable()
                        ->alignCenter(),

                    Tables\Columns\TextColumn::make('total')
                        ->label('Total')
                        ->money('ARS', true)
                        ->sortable()
                        ->alignCenter(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Creado')
                        ->dateTime('d/m/Y H:i')
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    Tables\Filters\SelectFilter::make('supplier_id')
                        ->label('Proveedor')
                        ->options(Supplier::pluck('nombre', 'id')),

                    Tables\Filters\Filter::make('fecha')
                        ->form([
                            Forms\Components\DatePicker::make('from')->label('Desde'),
                            Forms\Components\DatePicker::make('until')->label('Hasta'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when($data['from'], fn($q) => $q->whereDate('fecha', '>=', $data['from']))
                                ->when($data['until'], fn($q) => $q->whereDate('fecha', '<=', $data['until']));
                        }),
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
        
        ];
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
