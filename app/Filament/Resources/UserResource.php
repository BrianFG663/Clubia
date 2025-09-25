<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Spatie\Permission\Models\Role;
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
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Carbon;

class UserResource extends Resource implements HasShieldPermissions
{


    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Empleados de la institucion';
    protected static ?string $navigationGroup = '🏛️Administración Institucional';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) User::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'primary'; // Podés usar 'success', 'warning', 'danger', etc.
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucfirst(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),

            Forms\Components\TextInput::make('apellido')->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(ucfirst(strtolower($state)));
                })
                ->dehydrateStateUsing(fn($state) => ucfirst(strtolower($state))),

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

            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('add_role')
                    ->label('Agregar rol')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->native(false),

                Forms\Components\Select::make('institution_id')
                    ->label('Institución')
                    ->options(\App\Models\Institution::all()->pluck('nombre', 'id'))
                    ->required()
                    ->searchable()
                    ->native(false),
            ]),

            Forms\Components\Select::make('remove_role')
                ->label('Eliminar rol')
                ->options(fn($record) => $record?->roles?->pluck('name', 'id') ?? [])
                ->searchable()
                ->native(false)
                ->visible(fn(string $operation) => $operation === 'edit'),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles')
                    ->label('Roles')
                    ->getStateUsing(fn($record) => $record->getRoleNames()->join(', '))
                    ->badge()
                    ->color('primary'),
            ])

            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modificar'),
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
