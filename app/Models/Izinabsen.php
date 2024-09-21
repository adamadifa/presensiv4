<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izinabsen extends Model
{
    use HasFactory;
    protected $table = "hrd_izinabsen";
    protected $primaryKey = 'kode_izin';
    protected $guarded = [];
}
