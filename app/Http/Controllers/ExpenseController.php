<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ExpenseResource;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
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
        $expenses = ExpenseResource::collection(Expense::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($expenses, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
    {
        $expenses = ExpenseResource::collection(Expense::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($expenses, true);
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
                'name' => 'required|unique:expenses',
                'branch_id' => 'required|exists:branches,id',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Expense::create($request->all());
        $this->addEvent(eventModel::ExpenseEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Expense added successfully"], true, []);
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
