<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    protected $fillable = [
        'house_id',
        'user_id',
        'nama_lengkap',
        'foto_ktp',
        'status_penghuni',
        'nomor_telepon',
        'status_menikah',
    ];

    public function house()
    {
        return $this->belongsTo(\App\Models\House::class);
    }

    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class);
    }

    public function expense()
    {
        return $this->belongsTo(\App\Models\Expense::class);
    }
}