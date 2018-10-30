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
        $action->status = Action::STATUS_PENDING;
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
        $action->status = Action::STATUS_APPROVED;
        $action->type = $type;
        $action->content = json_encode($content);
        $action->processed_by = $userId;
        $action->processed_on = Carbon::now();

        $action->save();
    }

    /**
     * Approves an action
     *
     * @param \App\Models\Action $action Action being approved.
     * @param int processedBy
     */
    public function approveAction($action, $processedBy)
    {
        $action->status = Action::STATUS_APPROVED;
        $action->processed_by = $processedBy;
        $action->processed_on = Carbon::now();
        $action->save();
    }

    /**
     * Denies an action
     *
     * @param \App\Models\Action $action Action being denied.
     * @param int $processedBy
     */
    public function denyAction($action, $processedBy)
    {
        $action->status = Action::STATUS_DENIED;
        $action->processed_by = $processedBy;
        $action->processed_on = Carbon::now();
        $action->save();
    }
}