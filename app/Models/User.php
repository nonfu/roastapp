<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const ROLE_GENERAL_USER = 0;  // 普通用户
    const ROLE_SHOP_OWNER = 1;    // 商家用户
    const ROLE_ADMIN = 2;         // 管理员
    const ROLE_SUPER_ADMIN = 3;   // 超级管理员

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id'
    ];

    // 与 Cafe 间的多对多关联
    public function likes()
    {
        return $this->belongsToMany(Cafe::class, 'users_cafes_likes', 'user_id', 'cafe_id');
    }

    // 归属此用户的公司
    public function companiesOwned()
    {
        return $this->belongsToMany(Company::class, 'company_owners', 'user_id', 'company_id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'id', 'user_id');
    }

    public function actionsProcessed()
    {
        return $this->hasMany(Action::class, 'id', 'processed_by');
    }

    // 上传的咖啡店图片
    public function cafePhotos()
    {
        return $this->hasMany(CafePhoto::class, 'id', 'cafe_id');
    }
}
