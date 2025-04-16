<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $analysises = Analysis::selectRaw('product_id, category_id, brand_id, SUM(count) as total_count')
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
