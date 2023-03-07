<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\QuantityResource;
use App\Http\Resources\TraderResource;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ProductVariant;
use App\Models\Quantity;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    use ResponseTemplate;
use SystemEvent;
    public function getProductExtras(): \Illuminate\Http\JsonResponse
    {
        $categories = CategoryResource::collection(Category::all());
        $branches = BranchResource::collection(Branch::all());
        $quantities = QuantityResource::collection(Quantity::all());
        $expenses = ExpenseResource::collection(Expense::all());
        $currencies = CurrencyResource::collection(Currency::all());
        $traders = TraderResource::collection(Trader::all());
        $data = [
            "currencies" => $currencies,
            "categories" => $categories,
            "branches" => $branches,
            "quantities" => $quantities,
            "expenses" => $expenses,
            "traders" => $traders,
        ];
        return $this->sendResponse($data, true, []);
    }


    public function addProductVariant(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                "quantity_id" => 'required|exists:quantities,id',
                "trader_id" => 'required|exists:traders,id',
                "quantity_value" => 'required|numeric',
                "purchased" => 'required|numeric',
                "min_price" => 'required|numeric',
                "price" => 'required|numeric',
                "tva" => 'required|boolean',
                "extra_cost" => 'numeric',
                "extra_quantity" => 'numeric',
                "weight_value" => 'required|numeric',
                "expire" => "required|date",
                "product_id" => 'required|exists:products,id',
                "image" => '',
                "barcode" => "string",
                "identifier" => "string",
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }
        $product_variant = ProductVariant::create($request->all());
        $product_name = $product_variant->product->name;
        $this->addEvent(eventModel::ProductEvent, "Product variant created for $product_name");
        return $this->sendResponse(["message" => "Product variant added successfully"], true, []);
    }
}
