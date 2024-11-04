<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetRetribusi extends Model
{
    protected $table = 'target_retribusi';
    protected $fillable = ['tahun', 'target_amount'];
}
