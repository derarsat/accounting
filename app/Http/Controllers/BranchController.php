<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
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
        $branches = BranchResource::collection(Branch::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($branches, true);
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
                'name' => 'required|unique:branches',
                'is_default' => 'required',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        if ($request->is_default) {
            Branch::query()->update(['is_default' => 0]);
        }

        Branch::create($request->all());
        $this->addEvent(eventModel::BranchEvent, $request->name . ' created',);
        return $this->sendResponse(["message" => "branch added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\Branch $branch
     * @return JsonResponse
     */
    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:branches',
                'is_default' => 'required',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        if ($request->is_default) {
            Branch::query()->update(['is_default' => 0]);
        }
        $this->addEvent(eventModel::BranchEvent, $branch->name . ' updated to ' . $request->name,);

        $branch->update($request->all());
        return $this->sendResponse(["message" => "branch updated successfully"], true, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
