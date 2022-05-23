<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSurvivor extends Model
{
    use HasFactory;

    protected $fillable = [
        'survivor_id', 'item_id', 'amount'
    ];

    public function survivors()
    {
        return $this->hasMany(Survivor::class, 'id', 'survivor_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'id', 'item_id');
    }
}
