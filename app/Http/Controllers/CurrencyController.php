<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
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
        $currencies = CurrencyResource::collection(Currency::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($currencies, true);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:currencies',
                'symbol' => 'required|unique:currencies',
                'rate' => 'required|numeric',
                'amount' => 'required|numeric'
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Currency::create($request->all());
        $this->addEvent(eventModel::CurrencyEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Currency added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Currency $currency
     * @return JsonResponse
     */
    public function update(Request $request, Currency $currency): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:currencies,name,'.$currency->id,
                'symbol' => 'required|unique:currencies,symbol,'.$currency->id,
                'rate' => 'required|numeric',
                'amount' => 'required|numeric'
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }
        $this->addEvent(eventModel::CurrencyEvent, "$request->name updated rate from $currency->rate to $request->rate");
        $currency->update($request->all());
        return $this->sendResponse(["message"=> "Currency updated successfully"],true,[]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
    }
}
