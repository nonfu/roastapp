<?php

namespace App\Services;

use App\Models\Action;
use Illuminate\Support\Carbon;

class ActionService
{
    public function createPendingAction($cafeID, $companyID, $type, $content, $userId)
    {
        $action = new Action();

        $action->cafe_id = $cafeID;
        $action->company_id = $companyID;
        $action->user_id = $userId;
        $action->status = 0;
        $action->type = $type;
        $action->content = json_encode($content);

        $action->save();
    }

    public function createApprovedAction($cafeID, $companyID, $type, $content, $userId)
    {
        $action = new Action();

        $action->cafe_id = $cafeID;
        $action->company_id = $companyID;
        $action->user_id = $userId;
        $action->status = 1;
        $action->type = $type;
        $action->content = json_encode($content);
        $action->processed_by = $userId;
        $action->processed_on = Carbon::now();

        $action->save();
    }
}