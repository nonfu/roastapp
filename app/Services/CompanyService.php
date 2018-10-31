<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Cafe;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class CompanyService
{
    /**
     * Updates a company in the database.
     *
     * @param int $id ID of the company being updated
     * @param array $data Array of the data containing the updates.
     * @return Company
     */
    public function updateCompany($id, $data)
    {
        $company = Company::where('id', '=', $id)->first();

        if (isset($data['name'])) {
            $company->name = $data['name'];
        }

        if (isset($data['type'])) {
            $company->roaster = $data['type'] == 'roaster' ? 1 : 0;
        }

        if (isset($data['subscription'])) {
            $company->subscription = $data['subscription'];
        }

        if (isset($data['website'])) {
            $company->website = $data['website'];
        }

        if (isset($data['instagram_url'])) {
            $company->instagram_url = $data['instagram_url'] == '' ? null : $data['instagram_url'];
        }

        if (isset($data['facebook_url'])) {
            $company->facebook_url = $data['facebook_url'] == '' ? null : $data['facebook_url'];
        }

        if (isset($data['twitter_url'])) {
            $company->twitter_url = $data['twitter_url'] == '' ? null : $data['twitter_url'];
        }

        if (Auth::user()->can('updateOwners', $company)) {
            if (isset($data['owners'])) {

                $currentOwners = $company->ownedBy->pluck('id')->toArray();

                $updatedOwners = array();
                foreach ($data['owners'] as $owner) {
                    array_push($updatedOwners, $owner['id']);
                }

                foreach ($currentOwners as $currentOwner) {
                    if (!in_array($currentOwner, $updatedOwners)) {
                        $company->ownedBy()->detach($currentOwner);
                        self::removeOwnerPermission($currentOwner);
                    }
                }

                $newOwners = array();
                foreach ($updatedOwners as $owner) {
                    if (!in_array($owner, $currentOwners)) {
                        $company->ownedBy()->attach($owner);

                        $user = User::where('id', '=', $owner)->first();

                        if ($user->permission == User::ROLE_GENERAL_USER) {
                            $user->permission = User::ROLE_SHOP_OWNER;
                            $user->save();
                        }
                    }
                }
            }
        }

        if (isset($data['deleted']) && $data['deleted']) {
            self::flagCafesDeleted($id);
        } else {
            $company->save();
        }

        return $company;
    }

    /**
     * Updates all of a company's cafes as deleted.
     *
     * @param int $companyID ID of the company being deleted.
     */
    private static function flagCafesDeleted($companyID)
    {
        $cafes = Cafe::where('company_id', '=', $companyID)->get();

        foreach ($cafes as $cafe) {
            $cafe->delete();
        }
    }

    /**
     * Removes owner permission level from removed users.
     *
     * @param int $userID ID of he user removed from the company
     */
    private static function removeOwnerPermission($userID)
    {
        $user = User::where('id', '=', $userID)
            ->withCount('companiesOwned')
            ->first();

        if ($user->companies_owned_count == 0 && $user->permission == User::ROLE_SHOP_OWNER) {
            $user->permission = User::ROLE_GENERAL_USER;
            $user->save();
        }
    }
}