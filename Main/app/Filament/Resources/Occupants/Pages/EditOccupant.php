<?php

namespace App\Filament\Resources\Occupants\Pages;

use App\Filament\Resources\Occupants\OccupantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOccupant extends EditRecord
{
    protected static string $resource = OccupantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
