<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationLabel = 'Registro de ventas';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = '🛒Administracion de Ventas';
    protected static ?int $navigationSort = 6;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_full_name')
                    ->label('Encargado de venta')
                    ->getStateUsing(fn($record) => $record->user->nombre . ' ' . $record->user->apellido)
                    ->searchable(['user.nombre', 'user.apellido'])
                    ->alignCenter(),


                Tables\Columns\TextColumn::make('Cantidad de productos')
                    ->label('Cantidad de productos')
                    ->getStateUsing(fn($record) => $record->saleDetails->sum('cantidad'))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('Productos')
                    ->label('Productos')
                    ->getStateUsing(function ($record) {
                        return $record->saleDetails
                            ->map(fn($detail) => $detail->product->nombre ?? '')
                            ->filter()
                            ->join(', ');
                    })
                    ->alignCenter()
                    ->limit(50)
                    ->tooltip(fn($state) => $state),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y')  // formato día/mes/año
                    ->sortable()
                    ->alignCenter(),


                Tables\Columns\TextColumn::make('total')
                    ->label('Total de la venta')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2, ',', '.'))
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
