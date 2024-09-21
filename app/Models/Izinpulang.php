<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izinpulang extends Model
{
    use HasFactory;
    protected $table = 'hrd_izinpulang';
    protected $primary = 'kode_izin_pulang';
    protected $guarded = [];
}
