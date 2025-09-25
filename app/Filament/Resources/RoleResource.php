<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use App\Filament\Resources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Spatie\Permission\Models\Permission;    
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class RoleResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return '⚙️Permisos del Administrador';
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function getShieldFormComponents(): array
{
    // Asegurarse de que el permiso exista
    Permission::firstOrCreate(['name' => 'access_admin_panel']);

    // Obtener los permisos que Shield ya genera
    $shieldPermissions = Permission::query()
        ->where(function ($query) {
            $query->where('name', 'like', 'page_%')
                  ->orWhere('name', 'like', 'resource_%')
                  ->orWhere('name', 'like', 'widget_%');
        })
        ->pluck('name', 'name');

    // Agregar el permiso personalizado
    $customPermissions = Permission::where('name', 'access_admin_panel')->pluck('name', 'name');

    // Combinar todos los permisos
    $allPermissions = $shieldPermissions->merge($customPermissions)->unique();

    return [
        Forms\Components\CheckboxList::make('permissions')
            ->label(__('filament-shield::filament-shield.field.permissions'))
            ->options($allPermissions)
            ->default(fn($record) => $record?->permissions->pluck('name')->toArray() ?? ['access_admin_panel'])
            ->columns(2),
    ];
}

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label(__('filament-shield::filament-shield.field.name'))
                                ->unique(
                                    ignoreRecord: true,
                                    modifyRuleUsing: fn(Unique $rule) => Utils::isTenancyEnabled()
                                        ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id)
                                        : $rule
                                )
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('guard_name')
                                ->label(__('filament-shield::filament-shield.field.guard_name'))
                                ->default(Utils::getFilamentAuthGuard())
                                ->nullable()
                                ->maxLength(255),

                            Forms\Components\Select::make(config('permission.column_names.team_foreign_key'))
                                ->label(__('filament-shield::filament-shield.field.team'))
                                ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                                ->default([Filament::getTenant()?->id])
                                ->options(fn(): Arrayable => Utils::getTenantModel()
                                    ? Utils::getTenantModel()::pluck('name', 'id')
                                    : collect())
                                ->hidden(fn(): bool => !(static::shield()->isCentralApp() && Utils::isTenancyEnabled()))
                                ->dehydrated(fn(): bool => !(static::shield()->isCentralApp() && Utils::isTenancyEnabled())),

                            ShieldSelectAllToggle::make('select_all')
                                ->onIcon('heroicon-s-shield-check')
                                ->offIcon('heroicon-s-shield-exclamation')
                                ->label(__('filament-shield::filament-shield.field.select_all.name'))
                                ->helperText(fn(): HtmlString => new HtmlString(__('filament-shield::filament-shield.field.select_all.message')))
                                ->dehydrated(fn(bool $state): bool => $state),
                        ])
                        ->columns([
                            'sm' => 2,
                            'lg' => 3,
                        ]),
                ]),
            static::getShieldFormComponents(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('font-medium')
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label(__('filament-shield::filament-shield.column.guard_name')),

                Tables\Columns\TextColumn::make('team.name')
                    ->default('Global')
                    ->badge()
                    ->color(fn(mixed $state): string => str($state)->contains('Global') ? 'gray' : 'primary')
                    ->label(__('filament-shield::filament-shield.column.team'))
                    ->searchable()
                    ->visible(fn(): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->colors(['success']),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? static::$cluster;
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.role.label');
    }

    public static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.role.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return Utils::getResourceNavigationSort();
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Utils::getSubNavigationPosition() ?? static::$subNavigationPosition;
    }

    public static function getSlug(): string
    {
        return Utils::getResourceSlug();
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable()
            && count(static::getGloballySearchableAttributes())
            && static::canViewAny();
    }
}
