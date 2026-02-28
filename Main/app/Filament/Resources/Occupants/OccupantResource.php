<?php

namespace App\Filament\Resources\Occupants;

use App\Filament\Resources\Occupants\Pages\CreateOccupant;
use App\Filament\Resources\Occupants\Pages\EditOccupant;
use App\Filament\Resources\Occupants\Pages\ListOccupants;
use App\Filament\Resources\Occupants\Schemas\OccupantForm;
use App\Filament\Resources\Occupants\Tables\OccupantsTable;
use App\Models\Occupant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OccupantResource extends Resource
{
    protected static ?string $model = Occupant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return OccupantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OccupantsTable::configure($table);
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
            'index' => ListOccupants::route('/'),
            'create' => CreateOccupant::route('/create'),
            'edit' => EditOccupant::route('/{record}/edit'),
        ];
    }
}
