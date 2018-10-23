<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompaniesController extends Controller
{
    public function getCompanySearch(Request $request)
    {
        $term = $request->input('search');

        $companies = Company::where('name', 'LIKE', '%' . $term . '%')
            ->withCount('cafes')
            ->get();

        return response()->json(['companies' => $companies]);
    }
}
