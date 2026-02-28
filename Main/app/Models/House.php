<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable = [
    'house_id',
    'nomor_rumah',
    'status_rumah',
    'keterangan',
];
public function payment()
{
    return $this->hasMany(\App\Models\Payment::class);
}
public function expense()
{
    return $this->hasMany(\App\Models\Expense::class);
}
public function occupant()
{
    return $this->hasMany(\App\Models\Expense::class);
}   

}
