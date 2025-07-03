<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers;
use App\Models\Institution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Instituciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required(),
                Forms\Components\TextInput::make('ciudad')->required(),
                Forms\Components\TextInput::make('direccion')->required(),
                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $idActual = $get('id');

                            $telefonoExistente = \App\Models\Institution::where('telefono', $value)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($telefonoExistente) {
                                $fail('Teléfono ya vinculado con otra institución.');
                            }
                        };
                    })

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->label('nombre')
                ->searchable(),

                Tables\Columns\TextColumn::make('ciudad')
                ->label('ciudad')
                ->searchable(),

                Tables\Columns\TextColumn::make('direccion')
                ->label('direccion')
                ->searchable(),

                Tables\Columns\TextColumn::make('telefono')
                ->label('telefono')
                ->searchable(),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
