<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisiizincuti extends Model
{
    use HasFactory;
    protected $table = "hrd_izincuti_disposisi";
    protected $primaryKey = "kode_disposisi";
    protected $guarded = [];
    public $incrementing  = false;
}
