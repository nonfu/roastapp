<?php

namespace app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCafeRequest;
use App\Models\Cafe;
use App\Utilities\GaodeMaps;
use Illuminate\Http\Request;

class CafesController extends Controller
{
    /*
     |-------------------------------------------------------------------------------
     | Get All Cafes
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Method:         GET
     | Description:    Gets all of the cafes in the application
    */
    public function getCafes()
    {
        $cafes = Cafe::with('brewMethods')->get();

        return response()->json($cafes);
    }

    /*
     |-------------------------------------------------------------------------------
     | Get An Individual Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes/{id}
     | Method:         GET
     | Description:    Gets an individual cafe
     | Parameters:
     |   $id   -> ID of the cafe we are retrieving
    */
    public function getCafe($id)
    {
        $cafe = Cafe::where('id', '=', $id)->with('brewMethods')->first();
        return response()->json($cafe);
    }

    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
    public function postNewCafe(StoreCafeRequest $request)
    {
        $cafe = new Cafe();

        $cafe->name = $request->input('name');
        $cafe->address = $request->input('address');
        $cafe->city = $request->input('city');
        $cafe->state = $request->input('state');
        $cafe->zip = $request->input('zip');
        $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
        $cafe->latitude = $coordinates['lat'];
        $cafe->longitude = $coordinates['lng'];
        $cafe->save();

        $brewMethods = $request->input('brew_methods');
        $cafe->brewMethods()->sync($brewMethods);

        return response()->json($cafe, 201);
    }
}