<?php

namespace App\Filament\Resources\ExpenseCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class ExpenseCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('jenis_iuran')
                    ->required(),
                TextInput::make('jumlah')
                    ->required(),
                DatePicker::make('tanggal_pembayaran')
                    ->required(),
            ]);
    }
}
