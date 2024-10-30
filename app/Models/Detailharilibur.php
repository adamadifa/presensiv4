<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailharilibur extends Model
{
    use HasFactory;
    protected $table = "hrd_harilibur_detail";
    protected $guarded = [];
    public $incrementing  = false;
}
