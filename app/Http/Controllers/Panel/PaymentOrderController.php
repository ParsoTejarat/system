<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndicatorRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;
use PDF as PDF;

class PaymentOrderController extends Controller
{

    public function index()
    {
        $this->authorize('order-payment-list');
        $type = request()->type;
        if (!isset($type)) {
            return redirect(route('payments_order.index', ['type' => 'payments']));
        }
        $payments_order = PaymentOrder::with('user')->where('type', $type)->latest()->paginate(30);
        return view('panel.payments_order.index', compact(['payments_order', 'type']));
    }


    public function create()
    {
        $this->authorize('order-payment-create');

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
        $this->authorize('order-payment-create');
        $payment = new PaymentOrder();
        $payment->type = $request->type;
        $payment->amount = $request->amount;
        $payment->number = $this->generateNumber();
        $payment->amount_words = $request->amount_words;
        $payment->invoice_number = $request->invoice_number ?? 0;
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

    }


    public function edit($id)
    {
        $this->authorize('order-payment-edit');
        $type = request('type');
        if (!isset($type)) {
            return redirect()->route('payments_order.edit', ['type' => 'payments', 'payments_order' => $id]);
        }
        $order_payment = PaymentOrder::where(['id' => $id, 'status' => 'pending'])->firstOrFail();
        return view('panel.payments_order.edit', compact(['order_payment', 'type']));


    }


    public function update(StorePaymentRequest $request, $id)
    {

        $this->authorize('order-payment-edit');
        $paymentOrder = PaymentOrder::where(['id' => $id, 'status' => 'pending'])->firstOrFail();
        $paymentOrder->amount = $request->amount;
        $paymentOrder->amount_words = $request->amount_words;
        $paymentOrder->invoice_number = $request->invoice_number ?? 0;
        $paymentOrder->for = $request->for;
        $paymentOrder->to = $request->to;
        $paymentOrder->from = $request->from;
        $paymentOrder->bank_name = $request->bank_name;
        $paymentOrder->site_name = $request->site_name;
        $paymentOrder->bank_number = $request->bank_number;
        $paymentOrder->is_online_payment = $request->is_online_payment === 'true' ? true : false;
        $paymentOrder->save();
        alert()->success('درخواست شما ویرایش و در انتظار تایید قرار گرفت.', 'موفقیت آمیز');
        return redirect()->route('payments_order.index', ['type' => $paymentOrder->type]);

    }


    public function destroy($id)
    {
        $this->authorize('order-payment-delete');
        $type = request('type');
        $order_payment = PaymentOrder::where(['id' => $id, 'status' => 'pending', 'type' => $type])->firstOrFail();
        $order_payment->delete();
        alert()->success('دستور با موفقیت حذف شد.', 'موفقیت آمیز');
        return redirect()->route('payments_order.index', ['type' => $order_payment->type]);
    }

    public function generateNumber()
    {
        $lastNumber = PaymentOrder::withTrashed()->max('number');

        if ($lastNumber === null) {
            return 1000;
        } else {
            return $lastNumber + 1;
        }
    }


    public function statusOrderPayment(Request $request)
    {
        $this->authorize('ceo');
        $oreder_payment_approved = PaymentOrder::where(['id' => $request->payment_id, 'status' => 'pending'])->firstOrFail();
        $oreder_payment_approved->update([
            'status' => $request->status,
            'description' => $request->desc,
        ]);
        alert()->success('وضعیت تعیین شد.', 'موفقیت آمیز');
        return redirect()->back();
    }

    public function downloadOrderPaymentPdf($id)
    {
        $orderPayment = PaymentOrder::whereId($id)->firstOrFail();
        if ($orderPayment->type == 'payments') {
            return $this->generatePdfFilePardakht($orderPayment);
        } else {
            return $this->generatePdfFileDaryaft($orderPayment);
        }
    }

    public function generatePdfFilePardakht($orderPayment)
    {
        $date = verta($orderPayment->created_at)->year . '/' . verta($orderPayment->created_at)->month . '/' . verta($orderPayment->created_at)->day;

        $backgroundImage = public_path('/assets/images/pardakht.png');

        $pdf = PDF::loadView('panel.payments_order.pdf_pardakht', ['orderPayment' => $orderPayment, 'date' => $date], [], [
            'format' => 'A5',
            'orientation' => 'P',
            'default_font_size' => '10',
            'default_font' => 'nazanin',
            'display_mode' => 'fullpage',
            'watermark_text_alpha' => 1,
            'watermark_image_path' => $backgroundImage,
            'watermark_image_alpha' => 1,
            'watermark_image_size' => [148, 210],
            'show_watermark_image' => true,
            'watermarkImgBehind' => true,
        ]);

//        return view('panel.payments_order.pdf', compact(['orderPayment','date']));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $orderPayment->number . '.pdf"');
    }

    public function generatePdfFileDaryaft($orderPayment)
    {
        $date = verta($orderPayment->created_at)->year . '/' . verta($orderPayment->created_at)->month . '/' . verta($orderPayment->created_at)->day;

        $backgroundImage = public_path('/assets/images/daryaft.png');

        $pdf = PDF::loadView('panel.payments_order.pdf_daryaft', ['orderPayment' => $orderPayment, 'date' => $date], [], [
            'format' => 'A5',
            'orientation' => 'P',
            'default_font_size' => '10',
            'default_font' => 'nazanin',
            'display_mode' => 'fullpage',
            'watermark_text_alpha' => 1,
            'watermark_image_path' => $backgroundImage,
            'watermark_image_alpha' => 1,
            'watermark_image_size' => [148, 210],
            'show_watermark_image' => true,
            'watermarkImgBehind' => true,
        ]);

//        return view('panel.payments_order.pdf', compact(['orderPayment','date']));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $orderPayment->number . '.pdf"');
    }


}
