<?php

namespace App\Http\Controllers;

use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\TraderResource;
use App\Models\Trader;
use App\Http\Requests\StoreTraderRequest;
use App\Http\Requests\UpdateTraderRequest;
use Illuminate\Http\JsonResponse;

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
     * @param \App\Http\Requests\StoreTraderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store( $request)
    {
        //
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
     * @param \App\Http\Requests\UpdateTraderRequest $request
     * @param \App\Models\Trader $trader
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTraderRequest $request, Trader $trader)
    {
        //
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
