<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->heading(__('General information'))
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(__('User'))
                            ->relationship('user', 'name')
                            ->disabled()
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),

                        Forms\Components\TextInput::make('identifier')
                            ->label(__('Identifier'))
                            ->disabled()
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),

                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options(OrderStatusEnum::toSelectOptions())
                            ->default(OrderStatusEnum::PENDING->value)
                            ->native(false)
                            ->required()
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),

                        Forms\Components\Select::make('order_type')
                            ->label(__('Order type'))
                            ->options(OrderTypeEnum::toSelectOptions())
                            ->default(OrderTypeEnum::DINE_IN->value)
                            ->native(false)
                            ->required()
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),

                        Forms\Components\Select::make('tables')
                            ->label(__('Tables'))
                            ->relationship(
                                'tables',
                                'name',
                                fn (Builder $query) => $query->available(),
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpan([
                                'default' => 4,
                            ]),

                        Forms\Components\Fieldset::make('order_details')
                            ->label(__('Order details'))
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->label(__('Subtotal'))
                                    ->prefix('$')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0)
                                    ->columnSpan([
                                        'default' => 5,
                                        'sm' => 5,
                                        'md' => 2,
                                        'lg' => 2,
                                        'xl' => 1,
                                    ]),

                                Forms\Components\TextInput::make('tax')
                                    ->label(__('Tax'))
                                    ->prefix('$')
                                    ->hint('13%')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0)
                                    ->columnSpan([
                                        'default' => 4,
                                        'sm' => 4,
                                        'md' => 2,
                                        'lg' => 2,
                                        'xl' => 1,
                                    ]),

                                Forms\Components\Select::make('tipping_percentage')
                                    ->label(__('Tipping percentage'))
                                    ->suffix('%')
                                    ->placeholder(__('Porcentage').'...')
                                    ->options([0 => '0', 5 => '5', 10 => '10', 15 => '15', 20 => '20'])
                                    ->native(false)
                                    ->default(0)
                                    ->required()
                                    ->columnSpan([
                                        'default' => 4,
                                        'sm' => 4,
                                        'md' => 2,
                                        'lg' => 2,
                                        'xl' => 1,
                                    ]),

                                Forms\Components\TextInput::make('tipping')
                                    ->label(__('Tipping'))
                                    ->prefix('$')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->columnSpan([
                                        'default' => 4,
                                        'sm' => 4,
                                        'md' => 2,
                                        'lg' => 2,
                                        'xl' => 1,
                                    ]),

                                Forms\Components\TextInput::make('total')
                                    ->label(__('Total'))
                                    ->prefix('$')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0)
                                    ->columnSpan([
                                        'default' => 4,
                                        'sm' => 4,
                                        'md' => 2,
                                        'lg' => 2,
                                        'xl' => 1,
                                    ]),
                            ])
                            ->columns(4)
                            ->columnSpan([
                                'default' => 4,
                            ]),

                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn ($record) => $record->created_at?->format('F j, Y g:i A'))
                            ->helperText(fn ($record) => $record->created_at?->diffForHumans())
                            ->label(__('Created at'))
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),

                        Forms\Components\Placeholder::make('updated_at')
                            ->content(fn ($record) => $record->updated_at?->format('F j, Y g:i A'))
                            ->helperText(fn ($record) => $record->updated_at?->diffForHumans())
                            ->label(__('Updated at'))
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 1,
                            ]),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('User'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('Identifier'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tax')
                    ->label(__('Tax'))
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tipping')
                    ->label(__('Tipping'))
                    ->description(fn ($record) => number_format((($record->tipping_percentage ?? 0) * 100), 0).'%')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->sortable()
                    ->formatStateUsing(fn ($state) => OrderStatusEnum::from($state->value)?->translatedLabel())
                    ->color(fn ($state) => OrderStatusEnum::from($state->value)->getColor())
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('F j, Y g:i A')
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('F j, Y g:i A')
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('F j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(OrderStatusEnum::toSelectOptions())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('order_type')
                    ->label(__('Order type'))
                    ->options(OrderTypeEnum::toSelectOptions())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('User'))
                    ->relationship('user', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn ($record) => $record->isEditable()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('created_at', 'desc')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
