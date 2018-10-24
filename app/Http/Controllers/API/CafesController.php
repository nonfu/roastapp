<?php

namespace app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditCafeRequest;
use App\Http\Requests\StoreCafeRequest;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\Company;
use App\Models\Tag;
use App\Services\ActionService;
use App\Services\CafeService;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CafesController extends Controller
{
    protected $actionService;
    protected $cafeService;

    public function __construct()
    {
        $this->actionService = new ActionService();
        $this->cafeService = new CafeService();
    }

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
        $cafes = Cafe::with('brewMethods')
            ->with(['tags' => function ($query) {
                $query->select('tag');
            }])
            ->with('company')
            ->withCount('userLike')
            ->withCount('likes')
            ->get();

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
        $cafe = Cafe::where('id', '=', $id)
            ->with('brewMethods')
            ->withCount('userLike')
            ->with('tags')
            ->with(['company' => function ($query) {
                $query->withCount('cafes');
            }])
            ->withCount('likes')
            ->first();

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
        $companyID = $request->input('company_id');
        $company = Company::where('id', '=', $companyID)->first();
        $company = $company == null ? new Company() : $company;

        if (Auth::user()->can('create', [Cafe::class, $company])) {
            $cafe = $this->cafeService->addCafe($request->all(), Auth::user()->id);

            // 具备新增权限自动审核通过
            $this->actionService->createApprovedAction(null, $cafe->company_id, 'cafe-added', $request->all(), Auth::user()->id);

            $company = Company::where('id', '=', $cafe->company_id)
                ->with('cafes')
                ->first();

            return response()->json($company, 201);
        } else {
            // 不具备新增权限需要等待后台审核通过才能新增这个咖啡店
            $this->actionService->createPendingAction(null, $request->get('company_id'), 'cafe-added', $request->all(), Auth::user()->id);
            return response()->json(['cafe_add_pending' => $request->get('company_name')], 202);
        }
    }

    // 获取咖啡店编辑表单数据
    public function getCafeEditData($id)
    {
        $cafe = Cafe::where('id', '=', $id)
            ->with('brewMethods')
            ->withCount('userLike')
            ->with(['company' => function ($query) {
                $query->withCount('cafes');
            }])
            ->first();
        return response()->json($cafe);
    }

    // 更新咖啡店数据
    public function putEditCafe($id, EditCafeRequest $request)
    {
        $cafe = Cafe::where('id', '=', $id)->with('brewMethods')->with('company')->first();
        if (!$cafe) {
            abort(404);
        }

        // 保存修改之前/之后的咖啡店数据
        $content['before'] = $cafe;
        $content['after'] = $request->all();

        if (Auth::user()->can('update', $cafe)) {
            // 具备更新权限自动审核通过
            $this->actionService->createApprovedAction($cafe->id, $cafe->company_id, 'cafe-updated', $content, Auth::user()->id);

            $updatedCafe = $this->cafeService->editCafe($cafe->id, $request->all(), Auth::user()->id);

            $company = Company::where('id', '=', $updatedCafe->company_id)
                ->with('cafes')
                ->first();

            return response()->json($company, 200);

        } else {
            // 不具备更新权限需要等待后台审核通过才能更新这个咖啡店
            $this->actionService->createPendingAction($cafe->id, $cafe->company_id, 'cafe-updated', $content, Auth::user()->id);
            return response()->json(['cafe_updates_pending' => $request->get('company_name')], 202);
        }
    }

    // 删除咖啡店
    public function deleteCafe($id)
    {
        $cafe = Cafe::where('id', '=', $id)->with('company')->first();
        if (!$cafe) {
            abort(404);
        }

        if (Auth::user()->can('delete', $cafe)) {
            // 具备删除权限自动审核通过
            $this->actionService->createApprovedAction($cafe->id, $cafe->company_id, 'cafe-deleted', '', Auth::user()->id);

            $cafe->delete();
            return response()->json(['message' => '删除成功'], 204);
        } else {
            // 不具备删除权限需要等后台审核通过后才能执行删除操作
            $this->actionService->createPendingAction($cafe->id, $cafe->company_id, 'cafe-deleted', '', Auth::user()->id);
            return response()->json(['cafe_delete_pending' => $cafe->company->name], 202);
        }
    }

    public function postLikeCafe($cafeID)
    {
        $cafe = Cafe::where('id', '=', $cafeID)->first();
        $cafe->likes()->attach(Auth::user()->id, [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return response()->json(['cafe_liked' => true], 201);
    }

    public function deleteLikeCafe($cafeID)
    {
        $cafe = Cafe::where('id', '=', $cafeID)->first();

        $cafe->likes()->detach(Auth::user()->id);

        return response(null, 204);
    }

    /**
     * 给咖啡店添加标签
     * @param $request
     * @param $cafeID
     * @return JsonResponse
     */
    public function postAddTags(Request $request, $cafeID)
    {
        // 从请求中获取标签信息
        $tags = $request->input('tags');
        $cafe = Cafe::find($cafeID);

        // 将咖啡店和标签关联起来
        Tagger::tagCafe($cafe, $tags, Auth::user()->id);

        $cafe = Cafe::where('id', '=', $cafeID)
            ->with('brewMethods')
            ->with('userLike')
            ->with('tags')
            ->first();

        return response()->json($cafe, 201);
    }

    /**
     * 删除咖啡店上的标签
     * @param $cafeID
     * @param $tagID
     * @return Response
     */
    public function deleteCafeTag($cafeID, $tagID)
    {
        DB::table('cafes_users_tags')->where('cafe_id', $cafeID)->where('tag_id', $tagID)->where('user_id', Auth::user()->id)->delete();
        return response(null, 204);
    }
}