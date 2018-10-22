<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function cafes()
    {
        return $this->belongsTo(Cafe::class, 'cafe_id', 'id');
    }

    public function by()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
}
