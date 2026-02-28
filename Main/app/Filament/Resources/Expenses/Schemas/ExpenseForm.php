<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseForm
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
                Select::make('category_id')
                    ->label('Jenis Iuran')
                    ->relationship('category', 'jenis_iuran')
                    ->required(),
                Select::make('durasi')
                    ->label('Lama Pembayaran')
                    ->options([
                        1 => '1 Bulan',
                        3 => '3 Bulan',
                        6 => '6 Bulan',
                        12 => '1 Tahun',
                    ])
                    ->default(1)
                    ->required(),
                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->options(['BELUM_BAYAR' => 'Belum Bayar', 'SUDAH_BAYAR' => 'Sudah Bayar'])
                    ->default('BELUM_BAYAR')
                    ->required(),
                DatePicker::make('tanggal_pembayaran')
                    ->label('Tanggal Pembayaran'),
            ]);
    }
}
