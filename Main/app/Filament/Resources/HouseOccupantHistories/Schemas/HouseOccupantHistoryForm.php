<?php

namespace App\Filament\Resources\HouseOccupantHistories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HouseOccupantHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('house_id')
                    ->required()
                    ->relationship('house', 'nomor_rumah'),
                 Select::make('occupant_id')
                    ->required()
                    ->relationship('occupant', 'nama_lengkap'),
                DatePicker::make('tanggal_masuk')
                    ->required(),
                DatePicker::make('tanggal_keluar'),
                Toggle::make('status_aktif')
                    ->required(),
            ]);
    }
}
