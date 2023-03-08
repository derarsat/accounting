<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Helper\walletOperationType;
use App\Http\Resources\ProductResource;
use App\Http\Resources\WalletOperationResource;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Product;
use App\Models\WalletOperation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class WalletOperationController extends Controller
{
    use SystemEvent;
    use ResponseTemplate;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request): JsonResponse
    {
        $start = Carbon::createFromTimestampMs($request->start)->addDays(1)->toDateString();
        $end = Carbon::createFromTimestampMs($request->end)->addDays(1)->toDateString();
        $sort_by = $request->sort;
        $type = $request->type;
        $order_by = $request->order;
        $per_page = $request->per_page;
        $page = $request->page;
        $operations = WalletOperation::where("type", $type === "*" ? "!=" : "=", $type === "*" ? "d" : $type)->whereBetween("created_at", [$start, $end])->orderBy($sort_by, $order_by)->paginate($per_page, ['*'], 'page', $page);
        $response = WalletOperationResource::collection($operations)->response()->getData(true);

        return $this->sendResponse($response, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'amount' => 'required|numeric',
                'rate' => 'required|numeric',
                'model_id' => 'required|numeric',
                'type' => [new Enum(walletOperationType::class)],
                'description' => 'required|string',
                'branch_id' => 'required|exists:branches,id',
                'currency_id' => 'required|exists:currencies,id',
            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }
        $data = $validator->validated();
        $currency = Currency::find($data["currency_id"]);
        $data["currency_was"] = $currency->amount;
        $currency->amount = $currency->amount - $data["amount"];
        $data["currency_became"] = $currency->amount - $data["amount"];
        $data["user_id"] = 1;
        $branch_name = Branch::find($data["branch_id"])->name;
        WalletOperation::create($data);
        $currency->save();
        $this->addEvent(eventModel::WalletEvent, $data["amount"] . $currency->symbol . " Cash out from $branch_name ");
        $this->addEvent(eventModel::CurrencyEvent, $data["amount"] . $currency->symbol . " payed as expense ");
        return $this->sendResponse(["message" => "Wallet operation added successfully"], true, []);


    }

    /**
     * Display the specified resource.
     *
     * @param WalletOperation $walletOperation
     * @return void
     */
    public function show(WalletOperation $walletOperation): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param WalletOperation $walletOperation
     * @return void
     */
    public function edit(WalletOperation $walletOperation): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWalletOperationRequest $request
     * @param WalletOperation $walletOperation
     * @return void
     */
    public function update(UpdateWalletOperationRequest $request, WalletOperation $walletOperation): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WalletOperation $walletOperation
     * @return void
     */
    public function destroy(WalletOperation $walletOperation): void
    {
        //
    }
}
