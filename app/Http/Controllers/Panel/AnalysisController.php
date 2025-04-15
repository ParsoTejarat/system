<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $analysises = Analysis::selectRaw('product_id, category_id, brand_id, SUM(count) as total_count')
            ->groupBy('product_id', 'category_id', 'brand_id')
            ->with(['product', 'category', 'brand'])
            ->paginate(40);

        $topUsers = \App\Models\Order::select('user_id', DB::raw('count(*) as total'))
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

        $topCustomers = \App\Models\Order::select('customer_id', DB::raw('count(*) as total'))
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('total')
            ->take(5)
            ->get();


        return view('panel.analysis.index', compact(['analysises', 'topUsers', 'topCustomers']));
    }
}
