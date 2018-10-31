<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Admin\AddBrewMethodsRequest;
use App\Http\Requests\Admin\EditBrewMethodsRequest;
use App\Models\BrewMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrewMethodsController extends Controller
{
    /*
  |-------------------------------------------------------------------------------
  | Gets All Brew Methods
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/brew-methods
  | Method:         GET
  | Description:    Gets all brew methods in the application
  */
    public function getBrewMethods()
    {
        $brewMethods = BrewMethod::all();
        return response()->json($brewMethods);
    }

    /*
    |-------------------------------------------------------------------------------
    | Gets A Brew Method
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/brew-methods/{method}
    | Method:         GET
    | Description:    Gets a specific brew method
    */
    public function getBrewMethod(BrewMethod $method)
    {
        return response()->json($method);
    }

    /*
    |-------------------------------------------------------------------------------
    | Adds A Brew Method
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/brew-methods
    | Method:         POST
    | Description:    Adds a brew method
    */
    public function postAddBrewMethod(AddBrewMethodsRequest $request)
    {
        $brewMethod = new BrewMethod();
        $brewMethod->method = $request->get('method');
        $brewMethod->icon = $request->get('icon');
        $brewMethod->save();
        return response()->json($brewMethod);
    }

    /*
    |-------------------------------------------------------------------------------
    | Updates A Brew Method
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/brew-methods/{method}
    | Method:         PUT
    | Description:    Updates a brew method
    */
    public function putUpdateBrewMethod(BrewMethod $method, EditBrewMethodsRequest $request)
    {
        $brewMethod = BrewMethod::where('id', '=', $method->id)->first();
        $brewMethod->method = $request->get('method');
        $brewMethod->icon = $request->get('icon');
        $brewMethod->save();
        return response()->json($brewMethod);
    }
}
