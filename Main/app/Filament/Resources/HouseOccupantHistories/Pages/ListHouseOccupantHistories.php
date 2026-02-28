<?php

namespace App\Filament\Resources\HouseOccupantHistories\Pages;

use App\Filament\Resources\HouseOccupantHistories\HouseOccupantHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHouseOccupantHistories extends ListRecords
{
    protected static string $resource = HouseOccupantHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
