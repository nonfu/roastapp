<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Action;
use App\Models\Cafe;
use App\Services\ActionService;
use App\Services\CafeService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActionsController extends Controller
{
    /**
     * 获取所有待审核未处理操作
     */
    public function getActions()
    {
        // 如果是后台管理员则返回所有未处理操作
        if (Auth::user()->permission >= User::ROLE_ADMIN) {
            $actions = Action::with('cafe')
                ->with('company')
                ->where('status', '=', Action::STATUS_PENDING)
                ->with('by')
                ->get();
        } else {
            // 否则返回归属于该用户的待处理操作
            $actions = Action::with('cafe')
                ->with('company')
                ->whereIn('company_id', Auth::user()->companiesOwned()->pluck('id')->toArray())
                ->where('status', '=', Action::STATUS_PENDING)
                ->with('by')
                ->get();
        }

        return response()->json($actions);
    }

    /**
     * 执行待处理变更并通过这条审核
     * @param Action $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function putApproveAction(Action $action)
    {
        if (Auth::user()->cant('approve', $action)) {
            abort(403, '该用户没有通过审核权限');
        }
        $cafeService = new CafeService();
        $actionService = new ActionService();
        // 根据操作类型分类处理
        switch ($action->type) {
            case 'cafe-added':
                // 反序列化咖啡店数据
                $newActionData = json_decode($action->content, true);
                // 执行变更
                $cafeService->addCafe($newActionData, $action->user_id);

                // 操作完成后通过这条审核
                $actionService->approveAction($action, Auth::user()->id);
                break;
            case 'cafe-updated':
                // 反序列化咖啡店更新数据
                $actionData = json_decode($action->content, true);

                // 获取更新后数据
                $updatedActionData = $actionData['after'];
                // 执行变更
                $cafeService->editCafe($action->cafe_id, $updatedActionData, $action->user_id);

                // 通过这条审核
                $actionService->approveAction($action, Auth::user()->id);
                break;
            case 'cafe-deleted':
                // 获取要删除的咖啡店数据
                $cafe = $cafe = Cafe::where('id', '=', $action->cafe_id)->first();
                // 执行变更
                $cafe->delete();

                // 通过这条审核
                $actionService->approveAction($action, Auth::user()->id);
                break;
        }

        // 返回响应
        return response()->json('', 204);
    }

    /**
     * 拒绝执行变更请求.
     *
     * @param \App\Models\Action $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function putDenyAction(Action $action)
    {
        if (Auth::user()->cant('deny', $action)) {
            abort(403, '该用户没有拒绝审核权限');
        }
        // 拒绝这条变更请求
        $actionService = new ActionService();
        $actionService->denyAction($action, Auth::user()->id);

        // 返回响应
        return response()->json('', 204);
    }
}
