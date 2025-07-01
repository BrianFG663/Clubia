<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinorResource\Pages;
use App\Filament\Resources\MinorResource\RelationManagers;
use App\Models\Minor;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class MinorResource extends Resource
{
    protected static ?string $model = Minor::class;
    protected static ?string $navigationLabel = 'Socios (-18)';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required(),
            Forms\Components\TextInput::make('apellido')->required(),
            Forms\Components\TextInput::make('dni')
                ->label('DNI')
                ->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $recordId = $get('id'); // ID del registro actual (menor) si estamos editando

                        $dniEnMinors = \App\Models\Minor::where('dni', $value)
                            ->when($recordId, fn($query) => $query->where('id', '!=', $recordId))
                            ->exists();

                        $dniEnUsers = \App\Models\User::where('dni', $value)->exists();

                        if ($dniEnMinors || $dniEnUsers) {
                            $fail('El DNI ya está registrado por otro menor o usuario.');
                        }
                    };
                }),
            Forms\Components\DatePicker::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->required()
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) {
                        if (!$value) {
                            return;
                        }

                        $fecha = Carbon::parse($value)->timezone('America/Argentina/Buenos_Aires');
                        $mayorEdadLimite = Carbon::now('America/Argentina/Buenos_Aires')->subYears(18);

                        if ($fecha->lessThanOrEqualTo($mayorEdadLimite)) {
                            $fail('El Socio menor no debe superar los 18 años');
                        }
                    };
                }),

            Forms\Components\TextInput::make('tutor_dni')
                ->label('DNI del Tutor')
                ->dehydrated(false)
                ->required()
                ->afterStateUpdated(function ($state, callable $set) {
                    $user = \App\Models\User::where('dni', $state)->first();

                    if ($user) {
                        $set('user_id', $user->id);
                        \Filament\Notifications\Notification::make()
                            ->title('Tutor asignado')
                            ->body("Este menor será registrado a nombre de: {$user->nombre} {$user->apellido}")
                            ->success()
                            ->send();
                    }
                })
                ->rule(function () {
                    return function (string $attribute, $value, \Closure $fail) {
                        if (!\App\Models\User::where('dni', $value)->exists()) {
                            $fail('No se encontró un tutor con ese DNI.');
                        }
                    };
                }),

            Forms\Components\TextInput::make('relacion')
                ->label('Relación con el Tutor')
                ->required(),

            Forms\Components\Hidden::make('user_id')
                ->required()
                ->dehydrated(true),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre')->searchable(),
            Tables\Columns\TextColumn::make('apellido')->searchable(),
            Tables\Columns\TextColumn::make('dni'),
            Tables\Columns\TextColumn::make('fecha_nacimiento')->date(),

            Tables\Columns\TextColumn::make('user_nombre_completo')
                ->label('Tutor Nombre')
                ->getStateUsing(function ($record) {
                    return $record->user ? $record->user->nombre . ' ' . $record->user->apellido : '-';
                }),

            Tables\Columns\TextColumn::make('user.dni')->label('Tutor DNI'),
            Tables\Columns\TextColumn::make('relacion')->label('Relación'),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMinors::route('/'),
            'create' => Pages\CreateMinor::route('/create'),
            'edit' => Pages\EditMinor::route('/{record}/edit'),
        ];
    }
}
