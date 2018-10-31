<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // 所属用户
    public function ownedBy()
    {
        return $this->belongsToMany(User::class, 'company_owners', 'company_id', 'user_id');
    }

    // 所有关联咖啡店
    public function cafes()
    {
        return $this->hasMany(Cafe::class, 'company_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'company_id', 'id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
