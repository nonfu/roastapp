<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
     * If the user is an admin they can load a company. If the user
     * owns the company they can view the company as well.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function view(User $user, Company $company)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($user->companiesOwned->contains($company->id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the user is an admin they can update a company. If the user
     * owns the company they can update the company as well.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function update(User $user, Company $company)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else if ($user->companiesOwned->contains($company->id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the user is an admin they can update owners on a company.
     *
     * @param User $user
     * @return bool
     */
    public function updateOwners(User $user)
    {
        if ($user->permission == User::ROLE_ADMIN || $user->permission == User::ROLE_SUPER_ADMIN) {
            return true;
        } else {
            return false;
        }
    }
}
