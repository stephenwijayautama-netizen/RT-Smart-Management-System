<?php

namespace App\Filament\Resources\HouseOccupantHistories\Pages;

use App\Filament\Resources\HouseOccupantHistories\HouseOccupantHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHouseOccupantHistory extends EditRecord
{
    protected static string $resource = HouseOccupantHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
