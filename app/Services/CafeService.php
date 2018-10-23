<?php

namespace App\Services;

use App\Models\Cafe;
use App\Models\Company;
use App\Utilities\GaodeMaps;

class CafeService
{
    /**
     * 添加咖啡店到数据库
     *
     * @param array $data 咖啡店数据
     * @param int $addedBy 添加者
     * @return Cafe
     */
    public function addCafe($data, $addedBy)
    {
        $companyID = isset($data['company_id']) ? $data['company_id'] : '';
        // 如果对应公司不存在，先添加之
        if ($companyID != '') {
            $company = Company::where('id', '=', $companyID)->first();
        } else {
            $company = new Company();
            $company->name = $data['company_name'];
            $company->roaster = $data['company_type'] == 'roaster' ? 1 : 0;
            $company->subscription = isset($data['subscription']) ? $data['subscription'] : 0;
            $company->website = $data['website'];
            $company->logo = '';
            $company->description = '';
            $company->added_by = $addedBy;
            $company->save();
        }

        $address = $data['address'];
        $city = $data['city'];
        $state = $data['state'];
        $zip = $data['zip'];
        $locationName = $data['location_name'];
        $brewMethods = $data['brew_methods'];
        $coordinates = GaodeMaps::geocodeAddress($address, $city, $state);
        $lat = $coordinates['lat'];
        $lng = $coordinates['lng'];
        // 创建新的咖啡店
        $cafe = new Cafe();
        $cafe->company_id = $company->id;
        $cafe->location_name = $locationName != null ? $locationName : '';
        $cafe->city_id = GaodeMaps::findClosestCity($city, $lat, $lng);
        $cafe->address = $address;
        $cafe->city = $city;
        $cafe->state = $state;
        $cafe->zip = $zip;
        $cafe->latitude = $lat;
        $cafe->longitude = $lng;
        $cafe->added_by = $addedBy;
        $cafe->tea = isset($data['tea']) ? $data['tea'] : 0;
        $cafe->matcha = isset($data['matcha']) ? $data['matcha'] : 0;
        $cafe->save();
        // 保存咖啡店支持的冲泡方法
        $cafe->brewMethods()->sync(json_decode($brewMethods));

        return $cafe;
    }

    /**
     * 更新咖啡店数据
     * @param $id
     * @param $data
     * @param $updatedBy
     * @return mixed
     */
    public function editCafe($id, $data, $updatedBy)
    {
        // 如果选择已有的公司，则更新公司信息，否则新增
        if (isset($data['company_id'])) {
            $company = Company::where('id', '=', $data['company_id'])->first();

            if (isset($data['company_name'])) {
                $company->name = $data['company_name'];
            }

            if (isset($data['company_type'])) {
                $company->roaster = $data['company_type'] == 'roaster' ? 1 : 0;
            }

            if (isset($data['subscription'])) {
                $company->subscription = $data['subscription'];
            }

            if (isset($data['website'])) {
                $company->website = $data['website'];
            }

            $company->logo = '';
            $company->description = '';

            $company->save();
        } else {
            $company = new Company();

            if (isset($data['company_name'])) {
                $company->name = $data['company_name'];
            }

            if (isset($data['company_type'])) {
                $company->roaster = $data['company_type'] == 'roaster' ? 1 : 0;
            } else {
                $company->roaster = 0;
            }

            if (isset($data['subscription'])) {
                $company->subscription = $data['subscription'];
            }

            if (isset($data['website'])) {
                $company->website = $data['website'];
            }

            $company->logo = '';
            $company->description = '';
            $company->added_by = $updatedBy;

            $company->save();
        }

        $cafe = Cafe::where('id', '=', $id)->first();
        if (isset($data['city_id'])) {
            $cityID = $data['city_id'];
        } else {
            $cityID = $cafe->city_id;
        }

        if (isset($data['address'])) {
            $address = $data['address'];
        } else {
            $address = $cafe->address;
        }

        if (isset($data['city'])) {
            $city = $data['city'];
        } else {
            $city = $cafe->city;
        }

        if (isset($data['state'])) {
            $state = $data['state'];
        } else {
            $state = $cafe->state;
        }

        if (isset($data['zip'])) {
            $zip = $data['zip'];
        } else {
            $zip = $cafe->zip;
        }

        if (isset($data['location_name'])) {
            $locationName = $data['location_name'];
        } else {
            $locationName = $cafe->location_name;
        }

        if (isset($data['brew_methods'])) {
            $brewMethods = $data['brew_methods'];
        }


        $coordinates = GaodeMaps::geocodeAddress($address, $city, $state);
        $lat = $coordinates['lat'];
        $lng = $coordinates['lng'];

        $cafe->company_id = $company->id;
        if (!$cityID) {
            $cityID = GaodeMaps::findClosestCity($city, $lat, $lng);
        }
        $cafe->city_id = $cityID;
        $cafe->location_name = $locationName != null ? $locationName : '';
        $cafe->address = $address;
        $cafe->city = $city;
        $cafe->state = $state;
        $cafe->zip = $zip;
        $cafe->latitude = $lat;
        $cafe->longitude = $lng;

        if (isset($data['matcha'])) {
            $cafe->matcha = $data['matcha'];
        }

        if (isset($data['tea'])) {
            $cafe->tea = $data['tea'];
        }

        $cafe->save();

        // 更新关联的冲泡方法
        if (isset($data['brew_methods'])) {
            $cafe->brewMethods()->sync(json_decode($brewMethods));
        }

        return $cafe;
    }
}