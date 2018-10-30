<?php

namespace App\Utilities;

use App\Models\City;
use GuzzleHttp\Client;

class GaodeMaps
{
    /**
     * 通过真实地址获取对应的经纬度
     * @param $address
     * @param $city
     * @param $state
     * @param $zip
     * @return mixed
     */
    public static function geocodeAddress($address, $city, $state)
    {
        // 构建地理编码 API 请求 URL
        $address = urlencode($state . $city . $address); // 省、市、区、详细地址
        $apiKey = config('services.gaode.ws_api_key'); // WebService API Key
        // 默认返回 JSON 格式响应
        $url = 'https://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . $apiKey;

        // 创建 Guzzle HTTP 客户端发起请求
        $client = new Client();

        // 发送请求并获取响应数据
        $geocodeResponse = $client->get($url)->getBody();
        $geocodeData = json_decode($geocodeResponse);

        // 初始化地理编码位置
        $coordinates['lat'] = null;
        $coordinates['lng'] = null;

        // 如果响应数据不为空则解析出经纬度
        if (!empty($geocodeData)
            && $geocodeData->status  // 0 表示失败，1 表示成功
            && isset($geocodeData->geocodes)
            && isset($geocodeData->geocodes[0])) {
            list($longitude, $latitude) = explode(',', $geocodeData->geocodes[0]->location);
            $coordinates['lat'] = $latitude;  // 纬度s
            $coordinates['lng'] = $longitude; // 经度
        }

        // 返回地理编码位置数据
        return $coordinates;
    }

    /**
     * 通过经纬度反查距离最近的城市
     * @param $name
     * @param $latitude
     * @param $longitude
     * @return int|null
     */
    public static function findClosestCity($name, $latitude, $longitude)
    {
        $cities = City::where('name', 'LIKE', $name . '%')->get();

        // 检查距离信息
        if ($cities && count($cities) == 1) {
            return $cities[0]->id;
        } else {
            $apiKey = config('services.gaode.ws_api_key'); // WebService API Key
            $location = $longitude . ',' . $latitude;
            $url = 'https://restapi.amap.com/v3/geocode/regeo?location=' . $location . '&key=' . $apiKey;
            // 创建 Guzzle HTTP 客户端发起请求
            $client = new Client();

            // 发送请求并获取响应数据
            $regeocodeResponse = $client->get($url)->getBody();
            $regeocodeData = json_decode($regeocodeResponse);
            if (empty($regeocodeData) || $regeocodeData->status == 0) {
                return null;
            }

            if ($cities) {
                foreach ($cities as $city) {
                    if ($city->name == $regeocodeData->regeocode->addressComponent->city) {
                        return $city->id;
                    }
                }
            }

            $city = new City();
            // 直辖市city字段为空数组
            if (!$regeocodeData->regeocode->addressComponent->city) {
                $city->name = $regeocodeData->regeocode->addressComponent->province;
            } else {
                $city->name = $regeocodeData->regeocode->addressComponent->city;
            }
            $city->slug = $city->name;
            $city->state = $regeocodeData->regeocode->addressComponent->province;
            $city->country = $regeocodeData->regeocode->addressComponent->country;

            // 获取城市经纬度
            $url = 'https://restapi.amap.com/v3/config/district?keywords=' . $city->name . '&key=' . $apiKey;
            $districtResponse = $client->get($url)->getBody();
            $districtData = json_decode($districtResponse);
            if (!empty($districtData) && $districtData->status == 1) {
                list($lng, $lat) = explode(',', $districtData->districts[0]->center);
                $city->latitude = $lat;
                $city->longitude = $lng;
            }

            $city->save();

            return $city->id;
        }

    }
}