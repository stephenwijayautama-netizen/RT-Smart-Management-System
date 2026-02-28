<?php

namespace App\Filament\Resources\Houses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('house_id')
                    ->default(fn () => 'H' . str_pad(
                        \App\Models\House::count() + 1,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ))
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('nomor_rumah')
                    ->default(fn () => \App\Models\House::max('nomor_rumah') + 1)
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('status_rumah')
                    ->options(['DIHUNI' => 'D i h u n i', 'TIDAK_DIHUNI' => 'T i d a k  d i h u n i'])
                    ->required(),
            ]);
    }
}
