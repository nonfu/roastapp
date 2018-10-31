<?php

namespace App\Policies;

use App\Models\Cafe;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CafePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * If a user is an admin or a super admin they can create
     * a cafe.
     *
     * @param User $user
     * @param Company $company
     * @return boolean
     */
    public function create(User $user, Company $company)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($company != null && $user->companiesOwned->contains($company->id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If a user is an admin or super admin OR they own the cafe
     * company then can edit the cafe.
     *
     * @param User $user
     * @param Cafe $cafe
     * @return boolean
     */
    public function update(User $user, Cafe $cafe)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($user->companiesOwned->contains($cafe->company_id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If a user is an admin or super admin OR they own the cafe company
     * then they can delete the cafe.
     *
     * @param User $user
     * @param Cafe $cafe
     * @return boolean
     */
    public function delete(User $user, Cafe $cafe)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($user->companiesOwned->contains($cafe->company_id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the user is an admin they can load a company. If the user
     * owns the company they can view the company as well.
     *
     * @param User $user
     * @param Cafe $cafe
     * @return bool
     */
    public function view(User $user, Cafe $cafe)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($user->companiesOwned->contains($cafe->company_id)) {
            return true;
        } else {
            return false;
        }
    }
}
