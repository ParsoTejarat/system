<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $this->authorize('customer-order-list');
        if (auth()->user()->isAdmin() || auth()->user()->isAccountant() || auth()->user()->isCEO() || auth()->user()->isSalesManager()) {
            $orders = Order::latest()->paginate(30);
//            dd('test');
        } else {
            $orders = Order::where('user_id', auth()->id())->latest()->paginate(30);
        }
        $customers = Customer::all(['id', 'name']);
        return view('panel.orders.index', compact(['orders', 'customers']));
    }


    public function create()
    {
        $this->authorize('customer-order-create');

        return view('panel.orders.create');
    }


    public function store(StoreOrderRequest $request)
    {
        $this->authorize('customer-order-create');


        $invoiceData = $this->sortData($request);
        $order = new Order();
        $order->description = $request->description;
        $order->req_for = $request->req_for;
        $order->user_id = auth()->id();
        $order->customer_id = $request->buyer_name;
        $order->products = json_encode($invoiceData);
        $order->save();
        activity_log('create-orders', __METHOD__, [$request->all(), $order]);
        alert()->success('سفارش مورد نظر با موفقیت ثبت شد', 'ثبت سفارش');
        return redirect()->route('orders.edit', $order->id);

    }


    public function show(Order $order)
    {
        return view('panel.orders.printable', compact(['order']));
    }


    public function edit(Order $order)
    {
        $this->authorize('customer-order-edit');

        // edit own invoice OR is admin
        $this->authorize('edit-order-customer', $order);
        if (auth()->user()->isAccountant()) {
            return back();
        }
        return view('panel.orders.edit', compact('order'));
    }


    public function update(Request $request, Order $order)
    {
        $this->authorize('customer-order-edit');

        // edit own invoice OR is admin
        $this->authorize('edit-order-customer', $order);


        $invoiceData = $this->sortData($request);
        $order->description = $request->description;
        $order->req_for = $request->req_for;
        $order->user_id = auth()->id();
        $order->customer_id = $request->buyer_name;
        $order->products = json_encode($invoiceData);
        $order->save();
        activity_log('edit-orders', __METHOD__, [$request->all(), $order]);
        alert()->success('سفارش مورد نظر با موفقیت ویرایش شد', 'ویرایش سفارش');
        return redirect()->route('orders.edit', $order->id);
    }


    public function destroy(Order $order)
    {
        $this->authorize('customer-order-delete');

        // log
        activity_log('delete-invoice', __METHOD__, $order);

        $order->delete();
        return back();
    }

    private function sortData($request)
    {
        $products = [];
        if (isset($request->products) && is_array($request->products)) {
            foreach ($request->products as $index => $product) {
                $products[] = [
                    'products' => $product,
                    'colors' => $request->colors[$index],
                    'counts' => $request->counts[$index],
                    'units' => $request->units[$index],
                    'prices' => $request->prices[$index],
                    'total_prices' => $request->total_prices[$index],
                ];
            }
        }


        $other_products = [];
        if (isset($request->other_products) && is_array($request->other_products)) {
            foreach ($request->other_products as $index => $other_product) {
                $other_products[] = [
                    'other_products' => $other_product,
                    'other_colors' => $request->other_colors[$index],
                    'other_counts' => $request->other_counts[$index],
                    'other_units' => $request->other_units[$index],
                    'other_prices' => $request->other_prices[$index],
                    'other_total_prices' => $request->other_total_prices[$index],
                ];
            }
        }
        return [
            'products' => $products,
            'other_products' => $other_products,
        ];
    }
}
