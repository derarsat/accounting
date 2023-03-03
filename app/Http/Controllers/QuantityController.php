<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\QuantityResource;
use App\Models\Category;
use App\Models\Quantity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuantityController extends Controller
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
        $quantities = QuantityResource::collection(Quantity::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($quantities, true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:quantities',
                'number_of_pieces' => 'required|unique:quantities',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Quantity::create($request->all());
        $this->addEvent(eventModel::QuantityEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Quantity added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, Quantity $quantity): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:quantities,name,' . $quantity->id,
                'number_of_pieces' => 'required|unique:quantities,number_of_pieces,' . $quantity->number_of_pieces,
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        $quantity->update($request->all());
        $this->addEvent(eventModel::QuantityEvent, $request->name . ' number of pieces updated to ' . $quantity->number_of_pieces);
        return $this->sendResponse(["message" => "Quantity added successfully"], true, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
