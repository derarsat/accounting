<?php

namespace App\Helper;

trait SystemEvent
{
    public function addEvent(eventModel $model, $info, $branch = null): void
    {
        $system_event = new \App\Models\SystemEvent();
        $system_event->model = $model;
        $system_event->info = $info;
        // $system_event->user_id = auth()->user()->id;
        $system_event->user = "derar";
        if (isset($branch)) {
            $system_event->branch_id = $branch;
        }
        $system_event->save();
    }
}
