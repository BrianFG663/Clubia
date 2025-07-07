<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubActivityResource\Pages;
use App\Filament\Resources\SubActivityResource\RelationManagers;
use App\Models\SubActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubActivityResource extends Resource
{
    protected static ?string $model = SubActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?int $navigationSort = 6;
            protected static ?string $navigationGroup = '📅Actividades';

    protected static ?string $navigationLabel = 'Sub-activivdades';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $idActual = $get('id');

                            $nombreExistente = \App\Models\SubActivity::where('nombre', $value)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($nombreExistente) {
                                $fail('Nombre ya vinculado con otra Sub-Actividad.');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('descripcion')->required(),
                Forms\Components\TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->maxValue(2147483647)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('descripcion')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('monto')
                    ->label('Precio del arancel')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2, ',', '.'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('activity.nombre')
                    ->label('Actividad')
                    ->sortable()
                    ->searchable(),
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSubActivities::route('/'),
            'create' => Pages\CreateSubActivity::route('/create'),
            'edit' => Pages\EditSubActivity::route('/{record}/edit'),
        ];
    }
}
