<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $this->authorize('warehouses-list');

        $products = Product::query();


        if ($sku = request()->get('sku')) {
            $products = $products->where('sku', 'like', '%' . $sku . '%');
        }

        if ($brand_id = request()->get('brand_id')) {
            $products = $products->where('brand_id', $brand_id);
        }

        if ($category_id = request()->get('category_id')) {
            $products = $products->where('category_id', $category_id);
        }


        $products = $products->latest()->withCount(['trackingCodes' => function ($query) {
            $query->whereNull('exit_time');
        }])->paginate(30);


        return view('panel.warehouses.index', compact(['products']));
    }

    public function create()
    {
        $this->authorize('warehouses-create');

        return view('panel.warehouses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('warehouses-create');

        $request->validate(['name' => 'required']);

        $warehouse = Warehouse::create([
            'name' => $request->name
        ]);

        // log
        activity_log('create-warehouse', __METHOD__, [$request->all(), $warehouse]);

        alert()->success('انبار با موفقیت ایجاد شد','ایجاد انبار');
        return redirect()->route('warehouses.index');
    }

    public function show(Warehouse $warehouse)
    {
        //
    }

    public function edit(Warehouse $warehouse)
    {
        $this->authorize('warehouses-edit');

        return view('panel.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $this->authorize('warehouses-edit');

        $request->validate(['name' => 'required']);

        // log
        activity_log('edit-warehouse', __METHOD__, [$request->all(), $warehouse]);

        $warehouse->update([
            'name' => $request->name
        ]);

        alert()->success('انبار با موفقیت ویرایش شد','ویرایش انبار');
        return redirect()->route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('warehouses-delete');

        if (!$warehouse->inventories()->exists()){
            // log
            activity_log('delete-warehouse', __METHOD__, $warehouse);

            $warehouse->delete();
            return back();
        }else{
            return response('پیش از حذف ابتدا کالاهای موجود در این انبار را انتقال دهید',500);
        }
    }
}
