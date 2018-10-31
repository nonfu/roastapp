<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserService
{
    /**
     * 更新用户
     * @param User $user User being updated.
     * @return User
     */
    public function updateUser($user, $data)
    {
        if (isset($data['permission'])) {
            if ($data['permission'] == User::ROLE_ADMIN && Auth::user()->can('addAdmins', $user)) {
                $user->permission = User::ROLE_ADMIN;
            }

            if ($data['permission'] == User::ROLE_SUPER_ADMIN && Auth::user()->can('addSuperAdmins', $user)) {
                $user->permission = User::ROLE_SUPER_ADMIN;
            }
        }

        if (isset($data['companies'])) {

            $companyIDs = array();

            foreach ($data['companies'] as $company) {
                array_push($companyIDs, $company['id']);
            }

            $user->companiesOwned()->sync($companyIDs);

            if ($user->permission == User::ROLE_GENERAL_USER) {
                $user->permission = User::ROLE_SHOP_OWNER;
            }
        }

        $user->save();
        return $user;
    }
}