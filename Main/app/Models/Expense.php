<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'house_id',
        'occupant_id',
        'category_id',
        'durasi',
        'jumlah',
        'status',
        'metode_pembayaran',
        'payment_id',
        'payment_url',
        'payment_status',
        'tanggal_pembayaran',
        'user_id',
    ];
    public function category()
{
    return $this->belongsTo(\App\Models\ExpenseCategory::class);
}

 public function house()
{
    return $this->belongsTo(\App\Models\House::class);
}
    public function occupant()
{
    return $this->belongsTo(\App\Models\Occupant::class);
}
}

