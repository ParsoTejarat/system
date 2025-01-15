<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\TrackingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PDO;

class ProductController extends Controller
{


    public function index()
    {
        $this->authorize('products-list');

        $products = Product::latest()->paginate(30);
        return view('panel.products.index', compact(['products']));
    }

    public function create()
    {
        $this->authorize('products-create');

        $categories = Category::all();
        return view('panel.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('products-create');

        // create product
        $product = Product::create([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'single_price' => $request->single_price,
            'creator_id' => auth()->id(),
            'brand_id' => $request->brand_id,
        ]);

        // log
        activity_log('create-product', __METHOD__, [$request->all(), $product]);

        alert()->success('محصول مورد نظر با موفقیت ایجاد شد', 'ایجاد محصول');
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $this->authorize('products-edit');
        $categories = Category::all();
        return view('panel.products.edit', compact(['product', 'categories']));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('products-edit');

        // price history
        $this->priceHistory($product, $request);

        // log
        activity_log('edit-product', __METHOD__, [$request->all(), $product]);

        // create product
        $product->update([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'single_price' => $request->single_price,
            'creator_id' => auth()->id(),
            'brand_id' => $request->brand_id,
        ]);

        alert()->success('محصول مورد نظر با موفقیت ویرایش شد', 'ویرایش محصول');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('products-delete');

        if ($product->invoices()->exists()) {
            return response('این محصول در سفارشاتی موجود است', 500);
        }

        // log
        activity_log('delete-product', __METHOD__, $product);

        $product->delete();
        return back();
    }

    public function search(Request $request)
    {
        $this->authorize('products-list');

        $products = Product::where('title', 'like', "%$request->title%")->when($request->code, function ($query) use ($request) {
            return $query->where('code', $request->code);
        })->latest()->paginate(30);

        return view('panel.products.index', compact('products'));
    }

    public function pricesHistory()
    {
        $this->authorize('price-history');

        $pricesHistory = PriceHistory::latest()->paginate(30);
        return view('panel.prices.history', compact('pricesHistory'));
    }

    public function pricesHistorySearch(Request $request)
    {
        $this->authorize('price-history');

        $products_id = Product::where('title', 'like', "%$request->title%")->pluck('id');
        $pricesHistory = PriceHistory::whereIn('product_id', $products_id)->latest()->paginate(30);

        return view('panel.prices.history', compact('pricesHistory'));
    }

    public function excel()
    {
        return Excel::download(new \App\Exports\ProductsExport, 'products.xlsx');
    }

    public function parso(Request $request)
    {
        $this->authorize('parso-products');

        $page = $request->input('page', 1);
        $response = Http::get('https://barmansystem.com/wp-json/custom-api/v1/products', [
            'page' => $page
        ]);

        if ($response->successful()) {
            $products = collect($response->json())->map(function ($item) {
                return (object)$item;
            })->all();
        } else {
            dd('Error:', $response->status());
        }

        return view('panel.products.parso', compact(['products', 'page']));
    }

    private function priceHistory($product, $request)
    {
        if ($request->system_price != $product->system_price) {
            $product->histories()->create([
                'price_field' => 'system_price',
                'price_amount_from' => $product->system_price,
                'price_amount_to' => $request->system_price,
            ]);
        }
        if ($request->partner_price_tehran != $product->partner_price_tehran) {
            $product->histories()->create([
                'price_field' => 'partner_price_tehran',
                'price_amount_from' => $product->partner_price_tehran,
                'price_amount_to' => $request->partner_price_tehran,
            ]);
        }
        if ($request->partner_price_other != $product->partner_price_other) {
            $product->histories()->create([
                'price_field' => 'partner_price_other',
                'price_amount_from' => $product->partner_price_other,
                'price_amount_to' => $request->partner_price_other,
            ]);
        }
        if ($request->single_price != $product->single_price) {
            $product->histories()->create([
                'price_field' => 'single_price',
                'price_amount_from' => $product->single_price,
                'price_amount_to' => $request->single_price,
            ]);
        }
    }


    public function trackingCodeProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $file = $request->file('file');
        $data = Excel::toArray([], $file);

        if (empty($data) || empty($data[0])) {
            alert()->error('فایل خالی است.', 'خطا');
            return back();
        }

        $rows = $data[0];

        if (strtolower($rows[0][0]) !== 'شناسه رهگیری کالا') {
            alert()->error('ساختار فایل اکسل نامعتبر است.', 'خطا');
            return back();
        }

        $trackingCodes = array_slice(array_column($rows, 0), 1);
        $trackingCodes = array_filter(array_map('trim', $trackingCodes));

        if (empty($trackingCodes)) {
            alert()::error('خطا', 'فایل خالی است.');
            return back();
        }

        $existingCodes = TrackingCode::whereIn('code', $trackingCodes)
            ->pluck('code')
            ->toArray();

        $newCodes = array_diff($trackingCodes, $existingCodes);

        $batchSize = 100;
        $batches = array_chunk($newCodes, $batchSize);

        foreach ($batches as $batch) {
            $insertData = array_map(function ($code) use ($product) {
                return [
                    'product_id' => $product->id,
                    'code' => $code,
                    'exit_time' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $batch);

            TrackingCode::insert($insertData);
        }

        if (!empty($existingCodes)) {
            alert()->warning('شناسه های زیر قبلاً ثبت شده‌اند:<br>' . implode('<br>', $existingCodes), 'هشدار')->html()->autoclose(10000);
        }

        if (!empty($newCodes)) {
            alert()->success('شناسه های زیر با موفقیت ثبت شدند:<br>' . implode('<br>', $newCodes), 'موفقیت')->html()->autoclose(10000);
        }

        return back();
    }

    public function parsoUpdatePrice(Request $request)
    {

        $url = 'https://barmansystem.com/wp-json/custom/v1/update-price';

        $response = Http::asForm()->post($url, [
            'product_id' => $request->product_id,
            'price' => $request->price
        ]);

        if ($response->successful()) {
            alert()->success('قیمت محصول با موفقیت ویرایش شد.','موفقیت آمیز');
            return back();
        } else {
            return response()->json(['message' => 'Failed to update price'], 500);
        }
    }


}
