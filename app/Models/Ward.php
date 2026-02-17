<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name', 'capacity'];

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
