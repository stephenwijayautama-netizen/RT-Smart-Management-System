<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseOccupantHistory extends Model
{
    protected $fillable = [
        'house_id',
        'occupant_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_aktif',
    ];
    public function house()
    {
        return $this->belongsTo(\App\Models\House::class);
    }
    public function occupant()
    {
        return $this->belongsTo(\App\Models\Occupant::class);
    }
}
