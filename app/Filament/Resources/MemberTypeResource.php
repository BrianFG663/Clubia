<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberTypeResource\Pages;
use App\Filament\Resources\MemberTypeResource\RelationManagers;
use App\Models\MemberType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberTypeResource extends Resource
{
    protected static ?string $model = MemberType::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Tipos de socio';
    protected static ?string $navigationGroup = '🧍Socios y Actividades';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $idActual = $get('id');
                            $institucionId = $get('institution_id');

                            $existe = \App\Models\MemberType::where('nombre', $value)
                                ->where('institution_id', $institucionId)
                                ->when($idActual, fn($query) => $query->where('id', '!=', $idActual))
                                ->exists();

                            if ($existe) {
                                $fail('Ya existe un tipo de socio con ese nombre en esta institución.');
                            }
                        };
                    }),

                Forms\Components\TextInput::make('arancel')
                    ->label('Precio del arancel')
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


                Forms\Components\Select::make('institution_id')
                    ->label('Seleccione institucion')
                    ->relationship('Institution', 'nombre')
                    ->required()
                    ->columnSpan(2)
                    ->searchable(false)
                    ->preload(false),
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

                Tables\Columns\TextColumn::make('arancel')
                    ->label('Precio del arancel')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2, ',', '.'))
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('institution.nombre')
                    ->label('Institucion')
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListMemberTypes::route('/'),
            'create' => Pages\CreateMemberType::route('/create'),
            'edit' => Pages\EditMemberType::route('/{record}/edit'),
        ];
    }
}
