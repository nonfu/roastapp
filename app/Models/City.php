<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    public function cafes()
    {
        return $this->hasMany(Cafe::class, 'city_id', 'id');
    }
}
