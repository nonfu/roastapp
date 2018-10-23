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
}