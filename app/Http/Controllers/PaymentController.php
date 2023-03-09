<?php

namespace App\Http\Controllers;

use App\Helper\eventModel;
use App\Helper\PaymentAndInvoiceType;
use App\Helper\ResponseTemplate;
use App\Helper\SystemEvent;
use App\Helper\walletOperationType;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\WalletOperationResource;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Trader;
use App\Models\WalletOperation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class PaymentController extends Controller
{
    use SystemEvent;
    use ResponseTemplate;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {

        $start = Carbon::createFromTimestampMs($request->start)->toDateString();
        $end = Carbon::createFromTimestampMs($request->end)->toDateString();
        $type = $request->type;
        $sort_by = $request->sort;
        $order_by = $request->order;
        $per_page = $request->per_page;
        $page = $request->page;
        $payments = Payment::where("type", $type === "*" ? "!=" : "=", $type === "*" ? "d" : $type)->whereBetween("created_at", [$start, $end])->orderBy($sort_by, $order_by)->paginate($per_page, ['*'], 'page', $page);
        $response = PaymentResource::collection($payments)->response()->getData(true);

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
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                "trader_id" => 'required|exists:traders,id',
                "branch_id" => 'required|exists:branches,id',
                "currency_id" => 'required|exists:currencies,id',
                "rate" => 'required|numeric',
                "amount" => 'required|numeric',
                'type' => [new Enum(PaymentAndInvoiceType::class)],

            ]);
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            return $this->sendResponse([], false, $errors);
        }
        $data = $validator->validated();

        $payment = new Payment();

        $payment->currency_id = $data["currency_id"];
        $payment->trader_id = $data["trader_id"];
        $payment->branch_id = $data["branch_id"];
        $payment->amount = $data["amount"];
        $payment->rate = $data["rate"];
        $payment->type = $data["type"];


        $trader = Trader::find($data["trader_id"]);
        $currency = Currency::find($data["currency_id"]);
        $amount = $data['amount'] * $data["rate"];

        $wallet_operation = new WalletOperation();
        $wallet_operation->description = "New payment";
        $wallet_operation->amount = $amount;
        //TODO
        $wallet_operation->user_id = 1;
        $wallet_operation->branch_id = $data["branch_id"];
        $wallet_operation->currency_id = $data["currency_id"];
        $wallet_operation->rate = $data["rate"];
        $wallet_operation->currency_was = $currency->amount;
        $payment->currency_was = $currency->amount;
        $payment->current_account_was = $trader->current_account;
        //
        if ($request->type == "in") {

            $currency->amount = $wallet_operation->currency_became = $payment->currency_became = $currency->amount + $amount;
            $trader->to_collect = $trader->to_collect - $amount;
            $trader->current_account = $payment->current_account_became = $trader->current_account - $amount;
            $wallet_operation->type = walletOperationType::PaymentIn;

        } else {
            $currency->amount = $wallet_operation->currency_became = $payment->currency_became = $currency->amount - $amount;
            $trader->to_pay = $trader->to_pay - $amount;
            $trader->current_account = $payment->current_account_became = $trader->current_account + $amount;
            $wallet_operation->type = walletOperationType::PaymentOut;
        }
        //
        $currency->save();
        $trader->save();
        $payment->save();
        $wallet_operation->model_id = $payment->id;
        $wallet_operation->save();

        $this->addEvent(eventModel::PaymentEvent, "Payment $request->type with $trader->name  $amount $currency->symbol, rate $request->rate ");
        $this->addEvent(eventModel::CurrencyEvent, $data["amount"] . $currency->symbol . $data["type"] . " payed as expense ");


        return $this->sendResponse(["message" => "Payment added successfully"], true, []);
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
