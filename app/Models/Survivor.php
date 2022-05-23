<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'age', 'latitude', 'longitude', 'gender_id', 'is_nfected'
    ];

    public function items() {
        return $this->belongsToMany(Item::class, 'item_survivors', 'survivor_id', 'item_id')->withPivot('amount');
    }
}
