<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers;
use App\Models\Institution;
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
    protected static ?string $navigationGroup = '🏛️Administración Institucional';

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
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),
                Forms\Components\TextInput::make('ciudad')->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),
                Forms\Components\TextInput::make('direccion')->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),
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
