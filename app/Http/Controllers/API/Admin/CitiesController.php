<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Admin\AddCityRequest;
use App\Http\Requests\Admin\EditCityRequest;
use App\Models\Cafe;
use App\Models\City;
use App\Http\Controllers\Controller;

class CitiesController extends Controller
{
    /*
  |-------------------------------------------------------------------------------
  | Gets All Cities
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/cities
  | Method:         GET
  | Description:    Gets all cities in the application
  */
    public function getCities()
    {
        $cities = City::all();
        return response()->json($cities);
    }

    /*
    |-------------------------------------------------------------------------------
    | Gets An Individual City
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/cities/{id}
    | Method:         GET
    | Description:    Gets an individual city
    */
    public function getCity(City $city)
    {
        $city = City::where('id', '=', $city->id)
            ->with(['cafes' => function ($query) {
                $query->with('company');
            }])
            ->first();
        return response()->json($city);
    }

    /*
    |-------------------------------------------------------------------------------
    | Adds a City
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/cities
    | Method:         POST
    | Description:    Adds a city
    */
    public function postAddCity(AddCityRequest $request)
    {
        $city = new City();
        $city->name = $request->get('name');
        $city->state = $request->get('state');
        $city->country = $request->get('country');
        $city->latitude = $request->get('latitude');
        $city->longitude = $request->get('longitude');
        $city->slug = $city->name;
        $city->save();
        return response()->json($city);
    }

    /*
    |-------------------------------------------------------------------------------
    | Updates a City
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/cities/{city}
    | Method:         PUT
    | Description:    Updates a city
    */
    public function putUpdateCity(EditCityRequest $request, City $city)
    {
        $city = City::where('id', '=', $city->id)->first();
        $city->name = $request->get('name');
        $city->state = $request->get('state');
        $city->country = $request->get('country');
        $city->latitude = $request->get('latitude');
        $city->longitude = $request->get('longitude');
        $city->save();
        return response()->json($city);
    }

    /*
    |-------------------------------------------------------------------------------
    | Deletes a City
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/cities/{city}
    | Method:         DELETE
    | Description:    Delets a city
    */
    public function deleteCity(City $city)
    {
        $cafesInCity = Cafe::where('city_id', '=', $city->id)->get();
        foreach ($cafesInCity as $cafe) {
            $cafe->city_id = null;
            $cafe->save();
        }
        $city->delete();
        return response()->json(null, 204);
    }
}
