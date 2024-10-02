<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izinterlambat extends Model
{
    use HasFactory;
    protected $table = "hrd_izinterlambat";
    protected $primaryKey = 'kode_izin_terlambat';
    protected $guarded = [];
}
