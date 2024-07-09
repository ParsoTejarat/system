<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndicatorRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;

class PaymentOrderController extends Controller
{

    public function index()
    {
        $type = request()->type;
        if (!isset($type)) {
            return redirect(route('payments_order.index', ['type' => 'payments']));
        }
        $payments_order = PaymentOrder::with('user')->where('type', $type)->latest()->paginate(30);
        return view('panel.payments_order.index', compact(['payments_order', 'type']));
    }


    public function create()
    {

        $type = request()->type;
        if (!isset($type)) {
            return redirect(route('payments_order.create', ['type' => 'payments']));
        }
        $type = request()->type;
        return view('panel.payments_order.create', compact(['type']));
    }


    public function store(StorePaymentRequest $request)
    {
//        dd($request->all(), $this->generateNumber());
        $payment = new PaymentOrder();
        $payment->type = $request->type;
        $payment->amount = $request->amount;
        $payment->number = $this->generateNumber();
        $payment->amount_words = $request->amount_words;
        $payment->invoice_number = $request->invoice_number??0;
        $payment->for = $request->for;
        $payment->to = $request->to;
        $payment->from = $request->from;
        $payment->bank_name = $request->bank_name;
        $payment->site_name = $request->site_name;
        $payment->bank_number = $request->bank_number;
        $payment->is_online_payment = $request->is_online_payment === 'true' ? true : false;
        $payment->user_id = auth()->id();
        $payment->save();
        alert()->success('درخواست شما ثبت شد و در انتظار تایید قرار گرفت.', 'موفقیت آمیز');
        return redirect()->route('payments_order.index', ['type' => $payment->type]);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $type = request()->type;
        if (!isset($type)) {
            return redirect()->route('payments_order.edit', ['type' => 'payments', 'payments_order' => $id]);
        }
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function generateNumber()
    {
        $lastNumber = PaymentOrder::max('number');

        if ($lastNumber === null) {
            return 1000;
        } else {
            return $lastNumber + 1;
        }
    }

}
