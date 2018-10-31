<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Admin\EditCafeRequest;
use App\Models\Cafe;
use App\Models\Company;
use App\Services\ActionService;
use App\Services\CafeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CafesController extends Controller
{
    /*
    |-------------------------------------------------------------------------------
    | Gets An Individual Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/companies/{id}/cafes/{id}
    | Method:         GET
    | Description:    Gets an individual cafe
    */
    public function getCafe(Company $company, Cafe $cafe)
    {
        $cafe = Cafe::where('id', '=', $cafe->id)
            ->with(['actions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->with('brewMethods')
            ->withCount('likes')
            ->first();
        return response()->json($cafe);
    }

    /*
    |-------------------------------------------------------------------------------
    | Updates An Individual Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin/companies/{id}/cafes/{id}
    | Method:         PUT
    | Description:    Submits admin side updates for an individual cafe.
    */
    public function putUpdateCafe(Company $company, Cafe $cafe, EditCafeRequest $request)
    {
        /*
          Set the before cafe to the data that was existing,
          and the after to what was set.
        */
        $content['before'] = $cafe;
        $content['after'] = $request->all();
        /*
          Create a new cafe action and save the action for an
          admin to approve.
        */
        $actionService = new ActionService();
        $actionService->createApprovedAction($cafe->id, $cafe->company_id, 'cafe-updated', $content, Auth::user()->id);
        $cafeService = new CafeService();
        $cafeService->editCafe($cafe->id, $request->all(), Auth::user()->id);
        $cafe = Cafe::where('id', '=', $cafe->id)
            ->with(['actions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->with('brewMethods')
            ->withCount('likes')
            ->first();
        return response()->json($cafe);
    }
}
