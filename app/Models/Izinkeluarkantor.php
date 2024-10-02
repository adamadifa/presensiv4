<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izinkeluarkantor extends Model
{
    use HasFactory;
    protected $table = 'hrd_izinkeluar';
    protected $primary = 'kode_izin_keluar';
    protected $guarded = [];
}
