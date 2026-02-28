<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'jenis_iuran',
        'jumlah',
        'tanggal_pembayaran'
    ];
    public function expenses()
{
    return $this->hasMany(\App\Models\Expense::class);
}
    public function payments()
{
    return $this->hasMany(\App\Models\Payment::class);
}
}
