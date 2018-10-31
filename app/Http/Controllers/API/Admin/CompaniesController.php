<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Admin\EditCompanyRequest;
use App\Models\Company;
use App\Services\ActionService;
use App\Services\CompanyService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    /**
     * 获取所有公司列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request)
    {
        if (Auth::user()->permission >= User::ROLE_ADMIN) {
            if ($request->has('search')) {
                $companies = Company::withCount('cafes')
                    ->withCount(['actions' => function ($query) {
                        $query->where('status', '=', 0)
                            ->with('by');
                    }])
                    ->with('ownedBy')
                    ->where('name', 'LIKE', '%' . $request->get('search') . '%')
                    ->get();
            } else {
                $companies = Company::withCount('cafes')
                    ->withCount(['actions' => function ($query) {
                        $query->where('status', '=', 0)
                            ->with('by');
                    }])
                    ->with('ownedBy')
                    ->get();
            }
        } else {
            $companies = Company::withCount('cafes')
                ->withCount(['actions' => function ($query) {
                    $query->where('status', '=', 0)
                        ->with('by');
                }])
                ->with('ownedBy')
                ->whereIn('id', Auth::user()->companiesOwned()->pluck('id')->toArray())
                ->get();
        }
        return response()->json($companies);
    }

    /**
     * 获取指定公司
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompany(Company $company)
    {
        $company = Company::with(['cafes' => function ($query) {
            $query->withCount(['actions' => function ($query) {
                $query->where('status', '=', 0)
                    ->with('by');
            }]);
        }])
            ->with('ownedBy')
            ->where('id', '=', $company->id)
            ->first();
        return response()->json($company);
    }

    /**
     * 更新公司
     * @param Company $company
     * @param EditCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function putUpdateCompany(Company $company, EditCompanyRequest $request)
    {
        $beforeCompany = Company::where('id', '=', $company->id)
            ->with('ownedBy')
            ->first();

        $companyService = new CompanyService();
        $companyService->updateCompany($company->id, $request->all());

        $updatedCompany = Company::where('id', '=', $company->id)
            ->with('ownedBy')
            ->with(['cafes' => function ($query) {
                $query->withCount(['actions' => function ($query) {
                    $query->where('status', '=', 0)
                        ->with('by');
                }]);
            }])
            ->first();

        $content['before'] = $beforeCompany;
        $content['after'] = Company::where('id', '=', $company->id)
            ->with('ownedBy')
            ->first();

        $actionService = new ActionService();
        $actionService->createApprovedAction(null, $company->id, 'company-updated', $content, Auth::user()->id);

        return response()->json($updatedCompany);
    }
}
