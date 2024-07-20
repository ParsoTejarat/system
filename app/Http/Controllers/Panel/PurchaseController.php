<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::latest()->paginate(30);
        return view('panel.purchase.index', compact(['purchases']));
    }

    public function status($id)
    {
        $purchase = Purchase::whereId($id)->firstOrFail();
        return view('panel.purchase.status', compact(['purchase']));
    }

    public function storePurchaseStatus(Request $request)
    {
        $data = $this->validate([
            'count' =>'required',
            '' =>'required',
        ], [

        ]);
    }
}
