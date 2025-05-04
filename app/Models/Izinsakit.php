<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izinsakit extends Model
{
    use HasFactory;
    protected $table = "hrd_izinsakit";
    protected $primaryKey = "kode_izin_sakit";
    protected $guarded = [];
    public $incrementing  = false;
}
