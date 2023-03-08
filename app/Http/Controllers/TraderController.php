<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\TraderResource;
use App\Models\Product;
use App\Models\Trader;
use App\Http\Requests\StoreTraderRequest;
use App\Http\Requests\UpdateTraderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TraderController extends Controller
{
    use SystemEvent;
    use ResponseTemplate;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $traders = TraderResource::collection(Trader::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($traders, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:traders',
                'phone' => 'required|unique:traders',
                'address' => 'required',
                'to_collect' => 'numeric',
                'to_pay' => 'numeric',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Trader::create($request->all());
        $this->addEvent(eventModel::TraderEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "trader added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Trader $trader
     * @return \Illuminate\Http\Response
     */
    public function show(Trader $trader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Trader $trader
     * @return \Illuminate\Http\Response
     */
    public function edit(Trader $trader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *

     */
    public function update(Request $request, Trader $trader)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:traders,name' . $trader->id,
                'phone' => 'required|unique:traders,phone,' . $trader->id,
                'address' => 'required',
                'to_collect' => 'numeric',
                'to_pay' => 'numeric',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        $this->addEvent(eventModel::TraderEvent, "Trader $trader->name updated, new values (name: $request->name, address: $request->address, phone: $request->phone)");
        $trader->update($request->all());
        return $this->sendResponse(["message" => "trader added successfully"], true, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Trader $trader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trader $trader)
    {
        //
    }
}
