<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MaterialResource;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    use SystemEvent;
    use ResponseTemplate;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $materials = MaterialResource::collection(Material::orderBy('updated_at', 'DESC')->get());
        return $this->sendResponse($materials, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:materials',
                'branch_id' => 'required|exists:branches,id',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }
        Material::create($request->all());
        $this->addEvent(eventModel::MaterialEvent, $request->name . ' created');
        return $this->sendResponse(["message" => "Material added successfully"], true, []);
    }
    /**
     * Display the specified resource.
     *
     * @param Material $material
     * @return Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Material $material
     * @return Response
     */
    public function edit(Material $material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Material $material
     * @return void
     */
    public function update(Request $request, Material $material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Material $material
     * @return Response
     */
    public function destroy(Material $material)
    {
        //
    }
}
