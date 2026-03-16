<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'reorder_level'
    ];

    public function stocks()
    {
        return $this->hasMany(ConsumableStock::class);
    }
}
