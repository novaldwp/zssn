<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'point'
    ];

    public function survivors() {
        return $this->belongsToMany(Survivor::class, 'item_survivors', 'survivor_id', 'item_id');
    }
}
