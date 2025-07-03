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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Forms\Components\TextInput::make('descripción')->required(),
                Forms\Components\TextInput::make('monto')->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
