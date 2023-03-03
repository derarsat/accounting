<?php

namespace App\Http\Controllers;

use App\Helper\ResponseTemplate;
use App\Models\SystemEvent;
use Illuminate\Http\Request;

class SystemEventController extends Controller
{
    use ResponseTemplate;

    public function index(): \Illuminate\Http\JsonResponse
    {
        $events = SystemEvent::orderBy('updated_at', 'DESC')->get();
        return $this->sendResponse($events, true, []);
    }
}
