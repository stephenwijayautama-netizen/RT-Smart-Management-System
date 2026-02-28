<?php

namespace App\Filament\Resources\Occupants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class OccupantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('house_id')
                    ->required()
                    ->relationship('house', 'nomor_rumah'),
               TextInput::make('user_id')
                    ->default(fn () => \App\Models\Occupant::max('user_id') + 1)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                TextInput::make('nama_lengkap')
                    ->required()
                    ->unique(ignoreRecord: true),
               FileUpload::make('foto_ktp')
                    ->label('Foto KTP')
                    ->image()
                    ->directory('ktp')
                    ->nullable(),
                Select::make('status_penghuni')
                    ->options(['TETAP' => 'T e t a p', 'KONTRAK' => 'K o n t r a k'])
                    ->required(),
                TextInput::make('nomor_telepon')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('status_menikah')
                    ->options(['SUDAH' => 'S u d a h', 'BELUM' => 'B e l u m'])
                    ->required(),
                
            ]);
    }
}
