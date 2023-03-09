<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use SystemEvent;
    use ResponseTemplate;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $sort_by = $request->sort;
        $order_by = $request->order;
        $per_page = $request->per_page;
        $page = $request->page;
        $products = Product::orderBy($sort_by, $order_by)->paginate($per_page, ['*'], 'page', $page);
        $response = ProductResource::collection($products)->response()->getData(true);
        return $this->sendResponse($response, true);
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
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:products',
                'material_id' => 'required|exists:materials,id',
                'location' => 'required',
                'weight' => 'required',
                'branch_id' => 'required|exists:branches,id',
                'category_id' => 'required|exists:categories,id',
                'alert_when_remaining' => 'required',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        Product::create($request->all());
        $this->addEvent(eventModel::ProductEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Product added successfully"], true, []);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:products,name,'.$request->id,
                'material_id' => 'required|exists:materials,id',
                'location' => 'required',
                'weight' => 'required',
                'branch_id' => 'required|exists:branches,id',
                'category_id' => 'required|exists:categories,id',
                'alert_when_remaining' => 'required',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }

        $product->update($request->all());
        $this->addEvent(eventModel::ProductEvent, $request->name . ' updated to ' . $product->name);

        return $this->sendResponse(["message" => "Product added successfully"], true, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
