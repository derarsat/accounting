<?php

namespace App\Http\Controllers;

use App\Helper\ResponseTemplate;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\QuantityResource;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Quantity;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    use ResponseTemplate;

    public function getProductExtras(): \Illuminate\Http\JsonResponse
    {
        $categories = CategoryResource::collection(Category::all());
        $branches = BranchResource::collection(Branch::all());
        $quantities = QuantityResource::collection(Quantity::all());
        $expenses = ExpenseResource::collection(Expense::all());
        $currencies = CurrencyResource::collection(Currency::all());
        $data = [
            "currencies" => $currencies,
            "categories" => $categories,
            "branches" => $branches,
            "quantities" => $quantities,
            "expenses" => $expenses,
        ];
        return $this->sendResponse($data, true, []);
    }
}
