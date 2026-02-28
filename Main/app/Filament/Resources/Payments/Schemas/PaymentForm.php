<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('house_id')
                    ->required()
                    ->relationship('house', 'nomor_rumah'),
               DatePicker::make('tanggal_bayar'),
                Select::make('nama_lengkap')
                    ->required()
                    ->relationship('occupant', 'nama_lengkap'),
                 Select::make('category_id')
                    ->label('Jenis Iuran')
                    ->relationship('category', 'jenis_iuran')
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['LUNAS' => 'L u n a s', 'BELUM' => 'B e l u m'])
                    ->default('BELUM')
                    ->required(),
                
            ]);
    }
}
