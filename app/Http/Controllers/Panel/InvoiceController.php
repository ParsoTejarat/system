<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    const TAX_AMOUNT = 0.09;

    public function index()
    {
        $this->authorize('invoices-list');

        $invoices = Invoice::latest()->paginate(30);
        return view('panel.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $this->authorize('invoices-create');

        return view('panel.invoices.create');
    }

    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('invoices-create');

        $invoice = Invoice::create([
            'buyer_name' => $request->buyer_name,
            'economical_number' => $request->economical_number,
            'national_number' => $request->national_number,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        // create products for invoice
        $this->storeInvoiceProducts($invoice, $request);

        alert()->success('پیش فاکتور مورد نظر با موفقیت ایجاد شد','ایجاد پیش فاکتور');
        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('invoices-edit');

        return view('panel.invoices.printable', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('invoices-edit');

        return view('panel.invoices.edit', compact('invoice'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('invoices-edit');

        $invoice->update([
            'buyer_name' => $request->buyer_name,
            'economical_number' => $request->economical_number,
            'national_number' => $request->national_number,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        $invoice->products()->detach();

        // create products for invoice
        $this->storeInvoiceProducts($invoice, $request);

        alert()->success('پیش فاکتور مورد نظر با موفقیت ویرایش شد','ویرایش پیش فاکتور');
        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('invoices-delete');

        $invoice->delete();
        return back();
    }

    public function calcProductsInvoice(Request $request)
    {
        $product = Product::find($request->product_id);
        $price = $product->getPrice();
        $total_price = $price * $request->count;
        $discount_amount = 0;
        $extra_amount = 0;
        $total_price_with_off = $total_price - ($discount_amount + $extra_amount);
        $tax = (int) ($total_price * self::TAX_AMOUNT);
        $invoice_net = $tax + $total_price;

        $data = [
            'price' => $price,
            'total_price' => $total_price,
            'discount_amount' => $discount_amount,
            'extra_amount' => $extra_amount,
            'total_price_with_off' => $total_price_with_off,
            'tax' => $tax,
            'invoice_net' => $invoice_net,
        ];

        return response()->json(['data' => $data]);
    }

    private function storeInvoiceProducts(Invoice $invoice, $request)
    {
        foreach ($request->products as $key => $product_id){
            $invoice->products()->attach($product_id, [
                'count' => $request->counts[$key],
                'unit' => $request->units[$key],
                'price' => $request->prices[$key],
                'total_price' => $request->total_prices[$key],
                'discount_amount' => $request->discount_amounts[$key],
                'extra_amount' => $request->extra_amounts[$key],
                'tax' => $request->taxes[$key],
                'invoice_net' => $request->invoice_nets[$key],
            ]);
        }
    }
}
