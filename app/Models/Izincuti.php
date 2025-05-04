<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izincuti extends Model
{
    use HasFactory;
    protected $table = "hrd_izincuti";
    protected $primaryKey = "kode_izin_cuti";
    protected $guarded = [];
    public $incrementing  = false;
}
