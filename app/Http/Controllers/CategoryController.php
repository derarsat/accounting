<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
        $categories = CategoryResource::collection(Category::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($categories, true);
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
                'name' => 'required|unique:categories',
                'branch_id' => 'required|exists:branches,id',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Category::create($request->all());
        $this->addEvent(eventModel::CategoryEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Category added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:categories,name,' . $category->id,
                'branch_id' => 'required|exists:branches,id',

            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        $category->update($request->all());
        $this->addEvent(eventModel::CategoryEvent, $request->name . ' updated to ' . $category->name);
        return $this->sendResponse(["message" => "Category update successfully"], true, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
