<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Order;
use App\Models\Product;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $analysises = Analysis::query();

        if ($request->filled('product_id')) {
            $analysises->where('product_id', $request->product_id);
        }

        if ($request->filled('category_id')) {
            $analysises->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $analysises->where('brand_id', $request->brand_id);
        }

        if ($request->filled('from_date')) {
            $from_date = Verta::parse($request->from_date)->toCarbon()->toDateString();
            $analysises->whereDate('created_at', '>=', $from_date);
        }

        if ($request->filled('to_date')) {
            $to_date = Verta::parse($request->to_date)->toCarbon()->toDateString();
            $analysises->whereDate('created_at', '<=', $to_date);
        }

        $analysises = $analysises->selectRaw('product_id, category_id, brand_id, SUM(count) as total_count')
            ->groupBy('product_id', 'category_id', 'brand_id')
            ->with(['product', 'category', 'brand'])
            ->orderByDesc('total_count')
            ->paginate(40);

        $topUsers = Order::select('user_id', DB::raw('count(*) as total'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'name' => optional($order->user)->fullName(),
                    'total' => $order->total
                ];
            });

        $topCustomers = Order::select('customer_id', DB::raw('count(*) as total'))
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('total')
            ->take(5)
            ->get();


        return view('panel.analysis.index', compact(['analysises', 'topUsers', 'topCustomers']));
    }

    public function showProductAnalysis($product_id)
    {
        $product = Product::withCount(['trackingCodes' => function ($query) {
            $query->whereNull('exit_time');
        }])->findOrFail($product_id);

        $analysises = Analysis::where('product_id', $product_id)->latest()->paginate(50);

        $firstAnalysisTime = Analysis::where('product_id', $product_id)->min('created_at');
        $lastAnalysisTime = Analysis::where('product_id', $product_id)->max('created_at');

        return view('panel.analysis.show', compact(['analysises', 'product', 'firstAnalysisTime', 'lastAnalysisTime']));
    }
}
