<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Carbon;

class UserResource extends Resource implements HasShieldPermissions
{


    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Socios';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required(),
                Forms\Components\TextInput::make('apellido')->required(),
                Forms\Components\TextInput::make('dni')
                    ->label('DNI')
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $recordId = $get('id');

                            $dniEnUsers = \App\Models\User::where('dni', $value)
                                ->when($recordId, fn($query) => $query->where('id', '!=', $recordId))
                                ->exists();

                            $dniEnMinors = \App\Models\Minor::where('dni', $value)->exists();

                            if ($dniEnUsers || $dniEnMinors) {
                                $fail('El DNI ya está registrado por otro usuario o menor.');
                            }
                        };
                    }),
                Forms\Components\DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de nacimiento')
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) {
                            if (!$value) {
                                return; // si no hay valor, pasa required lo controla
                            }

                            $fecha = Carbon::parse($value)->timezone('America/Argentina/Buenos_Aires');
                            $mayorEdadLimite = Carbon::now('America/Argentina/Buenos_Aires')->subYears(18);

                            if ($fecha->greaterThan($mayorEdadLimite)) {
                                $fail('El usuario debe ser mayor de edad (18 años o más).');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('direccion'),
                Forms\Components\TextInput::make('ciudad'),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $recordId = $get('id');

                            $telefonoDuplicado = \App\Models\User::where('telefono', $value)
                                ->when($recordId, fn($query) => $query->where('id', '!=', $recordId))
                                ->exists();

                            if ($telefonoDuplicado) {
                                $fail('El teléfono ya está registrado por otro usuario.');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $recordId = $get('id');

                            $emailDuplicado = \App\Models\User::where('email', $value)
                                ->when($recordId, fn($query) => $query->where('id', '!=', $recordId))
                                ->exists();

                            if ($emailDuplicado) {
                                $fail('El email ya está registrado por otro usuario.');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->required(fn(string $context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state)),
                Forms\Components\Hidden::make('state_id')
                    ->default(1)
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable(),
                Tables\Columns\TextColumn::make('apellido')->searchable(),
                Tables\Columns\TextColumn::make('dni'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('state.nombre')
                    ->label('Estado')
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
