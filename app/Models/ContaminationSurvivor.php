<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaminationSurvivor extends Model
{
    use HasFactory;

    protected $fillable = [
        'survivor_id', 'report_by'
    ];
}
