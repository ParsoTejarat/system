<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandsRequests;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        $this->authorize('brands-list');

        $brands = Brand::latest()->paginate(30);
        return view('panel.brands.index', compact(['brands']));
    }


    public function create()
    {
        $this->authorize('brands-create');

        $categories = Category::all();
        return view('panel.brands.create', compact(['categories']));
    }


    public function store(StoreBrandsRequests $request)
    {
//           return $request->all();
        $this->authorize('brands-create');

        $brand = new Brand();
        $brand->user_id = auth()->id();
        $brand->name = $request->name;
        $brand->name_en = $request->name_en;
        $brand->save();
        $brand->categories()->sync($request->categories);
        alert()->success('برند با موفقیت اضافه شود.', 'موفقیت آمیز');
        return redirect()->route('brands.index');
    }


    public function show($id)
    {
        //
    }


    public function edit(Brand $brand)
    {
        $this->authorize('brands-edit');

        $categories = Category::all();
        return view('panel.brands.edit', compact(['categories', 'brand']));
    }


    public function update(StoreBrandsRequests $request, Brand $brand)
    {
        $this->authorize('brands-edit');

        $brand->user_id = auth()->id();
        $brand->name = $request->name;
        $brand->name_en = $request->name_en;
        $brand->save();
        $brand->categories()->sync($request->categories);
        alert()->success('برند با موفقیت ویرایش شود.', 'موفقیت آمیز');
        return redirect()->route('brands.index');
    }


    public function destroy(Brand $brand)
    {

        $this->authorize('brands-delete');

        if ($brand->products()->count() > 0) {
            alert()->error('این برند به دارای کالا است و قابل حذف نمی‌باشد.', 'خطا');
            return redirect()->route('brands.index');
        }

        $brand->delete();

        alert()->success('برند با موفقیت حذف شد.', 'موفقیت آمیز');
        return redirect()->route('brands.index');
    }
}
