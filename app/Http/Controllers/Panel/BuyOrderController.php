<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyOrderRequest;
use App\Models\BuyOrder;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendMessage;


class BuyOrderController extends Controller
{
    public function index()
    {
        $this->authorize('buy-orders-list');

        if (Gate::any(['admin', 'ceo', 'sales-manager'])) {
            $orders = BuyOrder::latest()->paginate(30);
        } else {
            $orders = BuyOrder::where('user_id', auth()->id())->latest()->paginate(30);
        }

        return view('panel.buy-orders.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('buy-orders-create');

        if (Gate::allows('ceo')) {
            return back();
        }

        return view('panel.buy-orders.create');
    }

    public function store(StoreBuyOrderRequest $request)
    {

//        $request->dd();
        $this->authorize('buy-orders-create');

        $items = [];

        $products = $request->products;
        $counts = $request->counts;
        $prices = $request->prices;
        foreach ($products as $key => $product) {
            $items[] = [
                'product' => $product,
                'count' => $counts[$key],
                'price' => $prices[$key],
            ];
        }

        $pre_invoice = upload_file($request->pre_invoice, 'BuyOrders');
        $order = Order::where('code', $request->order)->first();

        $buy_order = BuyOrder::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'customer_id' => $request->customer_id,
            'seller' => $request->seller,
            'description' => $request->description,
            'invoice' => $pre_invoice,
            'items' => json_encode($items),
        ]);

        // log
        activity_log('create-buy-orders', __METHOD__, [$request->all(), $buy_order]);
        $this->send_notif_to_accountants($buy_order);
        alert()->success('سفارش مورد نظر با موفقیت ثبت شد', 'ثبت سفارش خرید');
        return redirect()->route('buy-orders.index');
    }

    public function show(BuyOrder $buyOrder)
    {
        $this->authorize('buy-orders-list');

        return view('panel.buy-orders.show', compact('buyOrder'));
    }

    public function edit(BuyOrder $buyOrder)
    {
        $this->authorize('buy-orders-edit');
        $this->authorize('edit-buy-orders', $buyOrder);

        if (Gate::allows('ceo') || $buyOrder->status == 'bought') {
            return back();
        }

        return view('panel.buy-orders.edit', compact('buyOrder'));
    }

    public function update(StoreBuyOrderRequest $request, BuyOrder $buyOrder)
    {


        $this->authorize('buy-orders-edit');

        $items = [];

        $products = $request->products;
        $counts = $request->counts;
        $prices = $request->prices;
        foreach ($products as $key => $product) {
            $items[] = [
                'product' => $product,
                'count' => $counts[$key],
                'price' => $prices[$key],
            ];
        }

        // log


        $pre_invoice = upload_file($request->pre_invoice, 'BuyOrders');

        $buyOrder->update([
            'seller' => $request->seller,
            'description' => $request->description,
            'invoice' => $pre_invoice,
            'items' => json_encode($items),
        ]);

        activity_log('edit-buy-orders', __METHOD__, [$request->all(), $buyOrder]);

        alert()->success('سفارش مورد نظر با موفقیت ویرایش شد', 'ویرایش سفارش خرید');
        return redirect()->route('buy-orders.index');
    }

    public function destroy(BuyOrder $buyOrder)
    {
        $this->authorize('buy-orders-delete');

        if (Gate::allows('ceo') || $buyOrder->status == 'bought') {
            return back();
        }

        // log
        activity_log('delete-buy-orders', __METHOD__, $buyOrder);

        $buyOrder->delete();
        return back();
    }

    public function changeStatus(BuyOrder $buyOrder)
    {
        if (!Gate::allows('ceo')) {
            return back();
        }

        if ($buyOrder->status == 'bought') {
            $buyOrder->update(['status' => 'orders']);
        } else {
            $buyOrder->update(['status' => 'bought']);
        }

        // log
        activity_log('buy-orders-change-status', __METHOD__, $buyOrder);

        alert()->success('وضعیت سفارش با موفقیت تغییر کرد', 'تغییر وضعیت سفارش');
        return back();
    }

    public function deleteInvoice(BuyOrder $buyOrder)
    {
        if ($buyOrder->invoice != null) {
            unlink(public_path($buyOrder->invoice));
            $buyOrder->update([
                'invoice' => null,
            ]);
        }
        activity_log('delete-invoice-buy-orders', __METHOD__, [$buyOrder]);

        return back();
    }

    public function DeleteReceiptBuyOrder(BuyOrder $buyOrder)
    {
        if ($buyOrder->invoice != null) {
            unlink(public_path($buyOrder->invoice));
            $buyOrder->update([
                'receipt' => null,
                'status' => 'order'
            ]);
        }
        activity_log('delete-receipt-buy-orders', __METHOD__, [$buyOrder]);

        return back();
    }

    public function receiptInvoiceUpload(Request $request, BuyOrder $buyOrder)
    {
        if ($buyOrder->receipt == null) {
            $pre_invoice = upload_file($request->receipt, 'BuyOrdersReceipt');
            $buyOrder->update([
                'receipt' => $pre_invoice,
                'status' => 'bought'
            ]);
        }

        activity_log('upload-receipt-buy-orders', __METHOD__, [$buyOrder]);
        alert()->success('آپلود رسید موفقیت آمیز بود.', 'موفقیت آمیز');
        return back();
    }

//    private function send_notif_to_accountants(BuyOrder $invoice)
//    {
//        $roles_id = Role::whereHas('permissions', function ($q) {
//            $q->where('name', 'accountant');
//        })->pluck('id');
//        $accountants = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();
//
//        $url = route('invoices.edit', $invoice->id);
//        $message = "سفارش خرید '{$invoice->customer->name}' ثبت شد";
//
//        Notification::send($accountants, new SendMessage($message, $url));
//    }
//
//    private function send_notif_to_sales_manager(Invoice $invoice)
//    {
//        $roles_id = Role::whereHas('permissions', function ($q) {
//            $q->where('name', 'sales-manager');
//        })->pluck('id');
//        $managers = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();
//
//        $url = route('invoices.edit', $invoice->id);
//        $message = "پیش '{$invoice->customer->name}' ثبت شد";
//
//        Notification::send($managers, new SendMessage($message, $url));
//    }
}
