<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableStock extends Model
{
    protected $fillable = [
        'consumable_id',
        'quantity',
        'unit_price',
        'purchase_date',
        'reference'
    ];

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }
}
