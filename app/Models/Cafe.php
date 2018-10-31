<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cafe extends Model
{
    use SoftDeletes;

    // 定义与 BrewMethod 模型间的多对多关联
    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }

    // 归属公司
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    // 与 user 间的多对对关联
    public function likes()
    {
        return $this->belongsToMany(User::class, 'users_cafes_likes', 'cafe_id', 'user_id');
    }

    public function userLike()
    {
        return $this->belongsToMany(User::class, 'users_cafes_likes', 'cafe_id', 'user_id')->where('user_id', auth('api')->id());
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'cafes_users_tags', 'cafe_id', 'tag_id');
    }

    // 咖啡店图片
    public function photos()
    {
        return $this->hasMany(CafePhoto::class, 'id', 'cafe_id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'cafe_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
