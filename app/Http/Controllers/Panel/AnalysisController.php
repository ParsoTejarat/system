<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index()
    {
        $analysises = Analysis::selectRaw('product_id, category_id, brand_id, SUM(count) as total_count')
            ->groupBy('product_id', 'category_id', 'brand_id')
            ->with(['product', 'category', 'brand'])
            ->paginate(40);

        return view('panel.analysis.index', compact(['analysises']));
    }
}
