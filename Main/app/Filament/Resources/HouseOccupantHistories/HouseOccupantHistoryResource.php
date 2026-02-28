<?php

namespace App\Filament\Resources\HouseOccupantHistories;

use App\Filament\Resources\HouseOccupantHistories\Pages\CreateHouseOccupantHistory;
use App\Filament\Resources\HouseOccupantHistories\Pages\EditHouseOccupantHistory;
use App\Filament\Resources\HouseOccupantHistories\Pages\ListHouseOccupantHistories;
use App\Filament\Resources\HouseOccupantHistories\Schemas\HouseOccupantHistoryForm;
use App\Filament\Resources\HouseOccupantHistories\Tables\HouseOccupantHistoriesTable;
use App\Models\HouseOccupantHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HouseOccupantHistoryResource extends Resource
{
    protected static ?string $model = HouseOccupantHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'HouseOccupantHistory';

    public static function form(Schema $schema): Schema
    {
        return HouseOccupantHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HouseOccupantHistoriesTable::configure($table);
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
            'index' => ListHouseOccupantHistories::route('/'),
            'create' => CreateHouseOccupantHistory::route('/create'),
            'edit' => EditHouseOccupantHistory::route('/{record}/edit'),
        ];
    }
}
