<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'house_id',
        'user_id',
        'jenis_iuran',
        'jumlah',
        'status',
        'tanggal_bayar',
       
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
