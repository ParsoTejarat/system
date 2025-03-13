<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalePriceRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\SalePriceRequest;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class SalePriceRequestController extends Controller
{

    public function index()
    {
        $type = request()->get('type') ?? 'systematic_sales';
        $saleprice_requests = SalePriceRequest::query();

        if (Gate::allows('ceo') || Gate::allows('admin')) {
            $saleprice_requests = $saleprice_requests->where('type', $type)->latest()->paginate(30);
        } else {
            $saleprice_requests = $saleprice_requests->where('type', $type)->where('user_id', auth()->id())->latest()->paginate(30);
        }

        return view('panel.sale-price-requests.index', compact('saleprice_requests'));
    }


    public function create()
    {
        $products = Product::all();
        return view('panel.sale-price-requests.create', compact('products'));
    }


    public function store(StoreSalePriceRequest $request)
    {
        $items = [];

        foreach ($request->products as $key => $productId) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->title,
//                    'product_model' => $product->productModels->slug,
                    'category_name' => $product->category->name,
                    'product_price' => $request->product_price[$key],
                    'count' => $request->counts[$key],
                ];
            }
        }
        $totalPrice = collect($items)->sum(function ($item) {
            return $item['product_price'] * $item['count'];
        });
        $data = [
            'user_id' => auth()->id(),
            'customer_id' => $request->customer,
            'code' => $this->generateCode(),
            'payment_type' => $request->payment_type,
            'status' => 'pending',
            'description' => $request->description,
            'products' => json_encode($items),
            'price' => $totalPrice,
            'type' => auth()->user()->role->name,
        ];

        if (auth()->user()->role->name == 'systematic_sales') {
            $data['date'] = $request->date;
            $data['hour'] = $request->hour;
            $data['need_no'] = $request->need_no;
        }
        SalePriceRequest::create($data);
//        $customer = Customer::find($request->customer);
        alert()->success('درخواست فروش با موفقیت ثبت شد', 'ثبت درخواست فروش');
        return redirect(url('/panel/sale_price_requests?type=' . auth()->user()->role->name));
    }


    public function show(SalePriceRequest $sale_price_request)
    {
        $type = request()->get('type');
        $items = collect(json_decode($sale_price_request->items))->map(function ($item) {
            $price = DB::table('price_list')
                ->where('product_id', $item->product_id)
                ->where('seller_id', 4)
                ->value('price');
            $item->price = $items['price'] ?? 0;
            $item->system_price = $price ?? 0;
            return $item;
        });

        return view('panel.sale-price-requests.show', [
            'sale_price_request' => $sale_price_request->fill(['items' => $items]),
        ]);
    }


    public function edit(SalePriceRequest $sale_price_request)
    {
        $products = \App\Models\Product::with(['category', 'productModels'])->where('status', '=', 'approved')->get();

        $items = collect(json_decode($sale_price_request->items))->map(function ($item) {
            $price = DB::table('price_list')
                ->where('product_id', $item->product_id)
                ->where('seller_id', 4)
                ->value('price');
            $item->price = $item->price ?? 0;
            $item->system_price = $price ?? 0;
            return $item;
        });

        // ارسال داده به ویو
        return view('panel.sale-price-requests.edit', [
            'sale_price_request' => $sale_price_request->fill(['items' => $items]),
            'products' => $products, // ارسال محصولات به ویو
        ]);
    }


    public function update(StoreSalePriceRequest $request, SalePriceRequest $sale_price_request)
    {
        $items = [];

        foreach ($request->products as $key => $productId) {
            $product = Product::with('category', 'productModels')->find($productId);
            if ($product) {
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_model' => $product->productModels->slug,
                    'category_name' => $product->category->slug,
                    'product_price' => $request->product_price[$key],
                    'count' => $request->counts[$key],
                ];
            }
        }
        $totalPrice = collect($items)->sum(function ($item) {
            return $item['product_price'] * $item['count'];
        });
        $sale_price_request->update([
            'products' => json_encode($items),
            'status' => 'pending',
            'description' => $request->description,
            'customer_id' => $request->customer,
            'date' => $request->date,
            'hour' => $request->hour,
            'price' => $totalPrice,
            'code' => $this->generateCode(),
            'payment_type' => $request->payment_type,
            'need_no' => $request->need_no,
            'shipping_cost' => $request->shipping_cost,
//          'type' => auth()->user()->role->name
        ]);

        // notification sent to ceo
        alert()->success('درخواست فروش با موفقیت ویرایش شد', 'ویرایش درخواست فروش');
        return redirect(url('/panel/sale_price_requests?type=' . $sale_price_request->type));
    }

    public function action(SalePriceRequest $sale_price_request)
    {
        if (!Gate::allows('ceo') && !Gate::allows('admin')) {
            alert()->error('شما به این قسمت دسترسی ندارید', 'عدم دسترسی');
            return back();
        }
        $items = collect(json_decode($sale_price_request->items))->map(function ($item) {
            $price = DB::table('price_list')
                ->where('product_id', $item->product_id)
                ->where('seller_id', 4)
                ->value('price');
            $item->price = $items['price'] ?? 0;
            $item->system_price = $price ?? 0;
            return $item;
        });

        // ارسال داده به ویو
        return view('panel.sale-price-requests.action', [
            'sale_price_request' => $sale_price_request->fill(['items' => $items]),
        ]);
    }

    public function actionStore(Request $request)
    {
        if (!Gate::allows('ceo') && !Gate::allows('admin')) {
            alert()->error('شما به این قسمت دسترسی ندارید', 'عدم دسترسی');
            return back();
        }
        $sale_price_request = SalePriceRequest::findOrfail($request->sale_id);
        $items = [];
        $OrderItems = [];
        foreach (json_decode($sale_price_request->products, true) as $key => $item) {
            $product = Product::with('category')->find($item['product_id']);
            if ($product) {
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_model' => $product->productModels->slug,
                    'category_name' => $product->category->slug,
                    'count' => $request->count[$key],
                    'final_price' => str_replace(',', '', $request->final_price[$key] ?? 0),
                    'product_price' => str_replace(',', '', $request->product_price[$key] ?? 0),
                ];
                $finalPrice = str_replace(',', '', $request->final_price[$key] ?? 0);
                $OrderItems[] = [
                    'products' => (integer)$product->id,
                    'colors' => 'black',
                    'counts' => (integer)$request->count[$key],
                    'units' => 'number',
                    'prices' => $finalPrice,
                    'total_prices' => (integer)$request->count[$key] * $finalPrice,
                ];
            }
        }
//        $this->newOrder($request, $sale_price_request, $OrderItems);
        // تعیین وضعیت بر اساس نوع درخواست
        $status = $sale_price_request->type == 'systematic_sales' ? 'accepted' : 'finished';
        $sale_price_request->update([
            'acceptor_id' => auth()->id(),
            'products' => json_encode($items),
            'status' => $status,
            'price' => $request->price,
            'description' => $request->description,
            'shipping_cost' => $request->shipping_cost,
        ]);
        // notification sent to ceo

//        $customer = Customer::whereId($sale_price_request->customer_id)->first();
        // ثبت فعالیت
//        $activityData = [
//            'user_id' => auth()->id(),
//            'action' => 'تایید درخواست ' . auth()->user()->role->label,
//            'description' => 'کاربر ' . auth()->user()->family . ' (' . Auth::user()->role->label . ') درخواست فروش ' . $customer->name .' را تایید کرد.',
//            'created_at' => now(),
//        ];
//        Activity::create($activityData);
//        $this->notif_to_ceo($sale_price_request);
        alert()->success('درخواست فروش با موفقیت تایید شد', 'تایید درخواست فروش');
        return redirect()->route('sale_price_requests.index');

    }



    public function actionResult(Request $request, SalePriceRequest $sale_price_request)
    {
//        $this->authorize('Organ');

        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'row_id' => 'required|exists:sale_price_requests,id',
            'result' => 'required|in:winner,lose',
            'description' => 'nullable|string',
        ]);
        // به‌روزرسانی داده‌ها
        $sale_price_request = SalePriceRequest::findOrFail($request->row_id);
        $sale_price_request->update([
            'status' => $request->result,
            'final_result' => $request->result,
            'final_description' => $request->description,
        ]);
        // ارسال نوتیفیکیشن
//        $notifiables = User::where('id', '!=', auth()->id())->whereHas('role', function ($role) {
//            $role->whereHas('permissions', function ($q) {
//                $q->whereIn('name', ['ceo', 'sales-manager', 'admin']);
//            });
//        })->get();
//
//        $notif_title = 'ثبت نتیجه درخواست ستاد';
//        $notif_message = 'نتیجه درخواست ستاد ثبت گردید';
//        $url = route('sale_price_requests.index');
//
//        Notification::send($notifiables, new SendMessage($notif_title, $notif_message, $url));
//        $customer = Customer::whereId($sale_price_request->customer_id)->first();
        // ثبت فعالیت
//        Activity::create([
//            'user_id' => auth()->id(),
//            'action' => 'نتیجه درخواست ستاد',
//            'description' => 'کاربر ' . auth()->user()->family . ' (' . auth()->user()->role->label . ') نتیجه درخواست ستاد '. $customer->name . ' را بارگذاری کرد.',
//            'created_at' => now(),
//        ]);

        alert()->success('نتیجه نهایی با موفقیت ثبت شد', 'ثبت نتیجه');
        return redirect(url('/panel/sale_price_requests?type=' . $sale_price_request->type));
    }










    public function destroy(SalePriceRequest $sale_price_request)
    {
//        $this->authorize('sale-price-requests-delete');
//        $customer = Customer::whereId($sale_price_request->customer_id)->first();
//        Activity::create([
//            'user_id' => auth()->id(),
//            'action' => 'حذف درخواست فروش',
//            'description' => 'کاربر ' . auth()->user()->family . ' (' . auth()->user()->role->label . ') درخواست فروش '. $customer->name . ' را حذف کرد.',
//            'created_at' => now(),
//        ]);
        $sale_price_request->delete();
        alert()->success('درخواست فروش با موفقیت حذف شد', 'حذف درخواست فروش');

        return back();
    }

    public function generateCode()
    {
        $code = '777' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

        while (Order::where('code', $code)->lockForUpdate()->exists() || SalePriceRequest::where('code', $code)->lockForUpdate()->exists()) {
            $code = '777' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }





}
