<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubActivityResource\Pages;
use App\Filament\Resources\SubActivityResource\RelationManagers;
use App\Models\SubActivity;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
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
            protected static ?string $navigationGroup = '📅Administracion de Actividades';

    protected static ?string $navigationLabel = 'Sub-activivdades';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state)))
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
                Forms\Components\TextInput::make('descripcion')
                ->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(ucwords(strtolower($state)));
                    })
                    ->dehydrateStateUsing(fn($state) => ucwords(strtolower($state))),

                Forms\Components\TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->maxValue(2147483647),

                Forms\Components\Select::make('activity_id')
                    ->label('Seleccione Actividad')
                    ->relationship('Activity', 'nombre')
                    ->required()
                    ->searchable(false)
                    ->preload(false)


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

                Tables\Columns\TextColumn::make('monto')
                    ->label('Precio del arancel')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2, ',', '.'))
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('activity.nombre')
                    ->label('Actividad')
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
            'index' => Pages\ListSubActivities::route('/'),
            'create' => Pages\CreateSubActivity::route('/create'),
            'edit' => Pages\EditSubActivity::route('/{record}/edit'),
        ];
    }
}
