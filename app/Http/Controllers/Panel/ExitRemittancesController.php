<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ExitRemittance;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnBackProducts;
use App\Models\Role;
use App\Models\TrackingCode;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use PDF as PDF;


class ExitRemittancesController extends Controller
{

    public function index()
    {
        $this->authorize('exit-remittance-list');
        $exitRemittances = ExitRemittance::query();

        if ($orderCode = request()->get('order_code')) {
            $exitRemittances = $exitRemittances->where('order_code', 'like', '%' . $orderCode . '%');
        }

        if ($code = request()->get('exitRemittance_code')) {
            $exitRemittances = $exitRemittances->where('code', 'like', '%' . $code . '%');
        }
        $exitRemittances = $exitRemittances->where(['status' => 'pending'])->latest()->paginate(30);
        return view('panel.exit-remittance.index', compact(['exitRemittances']));
    }


    public function outOfWarehouse()
    {
        $this->authorize('out-of-warehouse-list');
        $exitRemittances = ExitRemittance::query();

        if ($orderCode = request()->get('order_code')) {
            $exitRemittances = $exitRemittances->where('order_code', 'like', '%' . $orderCode . '%');
        }

        if ($code = request()->get('exitRemittance_code')) {
            $exitRemittances = $exitRemittances->where('code', 'like', '%' . $code . '%');
        }

        $exitRemittances = $exitRemittances->where(['status' => 'approved'])->latest()->paginate(30);

        return view('panel.exit-remittance.index', compact(['exitRemittances']));
    }

    public function showAllReturnBackProduct()
    {
        $this->authorize('return-back-products-list');
        $returnedProducts = ReturnBackProducts::query();
        if ($orderCode = request()->get('order_code')) {
            $returnedProducts = $returnedProducts->where('order_code', 'like', '%' . $orderCode . '%');
        }
        if ($trackingCode = request()->get('tracking_code')) {
            $returnedProducts = $returnedProducts->where('tracking_code', 'like', '%' . $trackingCode . '%');
        }
        $returnedProducts = $returnedProducts->latest()->paginate(30);
        return view('panel.exit-remittance.all-returns', compact(['returnedProducts']));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function storeReturnBackProduct(Request $request)
    {
        $this->authorize('return-back-products-create');
//        return $request->all();
        $trackingCode = TrackingCode::where(['code' => $request->tracking_code])->first();
        $exitRemittance = ExitRemittance::whereId($request->exit_remittance)->first();
        $order = Order::whereId($request->order_id)->first();


        $returnBackProduct = new ReturnBackProducts();
        $returnBackProduct->user_id = auth()->id();
        $returnBackProduct->order_id = $order->id;
        $returnBackProduct->order_code = $order->code;
        $returnBackProduct->product_id = $trackingCode->product->id;
        $returnBackProduct->tracking_code = $trackingCode->code;
        $returnBackProduct->description = $request->description;
        $returnBackProduct->save();

        $trackingCode->update(['exit_time' => null]);
        $exitRemittance->update(['tracking_codes' => $this->dropCodeFromList(json_decode($exitRemittance->tracking_codes), $trackingCode->code)]);
        activity_log('return-back-products-create', __METHOD__, [$request->all(), $returnBackProduct]);


        alert()->success('کالای ' . $trackingCode->product->title . ' با شناسه ' . $trackingCode->code . '  مرجوع شد', 'موفقیت آمیز');
        return back();


    }


    public function show(ExitRemittance $exitRemittance)
    {
//        return $exitRemittance;
        return view('panel.exit-remittance.show', compact(['exitRemittance']));
    }

    public function showOutOfWarehouse($id)
    {
        $this->authorize('out-of-warehouse-list');
        $exitRemittance = ExitRemittance::findOrfail($id);
        return view('panel.exit-remittance.show-out-of-warehouse', compact(['exitRemittance']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function approvedExit(Request $request)
    {
        $this->authorize('approved-products-warehouse');
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
            'exit_remittance_id' => 'required|exists:exit_remittances,id',
            'sum_count' => 'required',
        ]);


        $file = $request->file('file_excel');

        try {
            $data = Excel::toArray([], $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            alert()->error('خطا در خواندن فایل اکسل.', 'خطا');
            return back();
        }


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
            alert()->error('فایل خالی است.', 'خطا');
            return back();
        }


        $trackingCodeCounts = array_count_values($trackingCodes);
        $duplicateCodes = array_filter($trackingCodeCounts, function ($count) {
            return $count > 1;
        });

        if (!empty($duplicateCodes)) {
            $duplicateCodesList = array_keys($duplicateCodes);
            alert()->error('کد زیر در فایل اکسل تکرار شده است: <br>' . implode('<br>', $duplicateCodesList), 'هشدار')->html()->autoclose(50000);
            return back();
        }


        if (count($trackingCodes) != $request->sum_count) {
//            dd(count($trackingCodes), $request->sum_count);
            alert()->error('تعداد شناسه ها در اکسل با مجموع کالاها مطابقت ندارد.', 'خطا');
            return back();
        }
        //get all codes and check
        $existingTrackingCodes = TrackingCode::whereIn('code', $trackingCodes)->get();
        $missingCodes = array_diff($trackingCodes, $existingTrackingCodes->pluck('code')->toArray());
        if (count($missingCodes) > 0) {
            alert()->error('کدهای زیر در انبار موجود نیست!: <br>' . implode('<br>', $missingCodes), 'هشدار')->html()->autoclose(50000);
            return back();
        }


        $codesWithExitTime = $existingTrackingCodes->filter(function ($code) {
            return !empty($code->exit_time);
        });

        //check is tracking codes existed before
        if ($codesWithExitTime->isNotEmpty()) {
            $codesWithExitTimeList = $codesWithExitTime->pluck('code')->toArray();
            alert()->error('کدهای زیر قبلاً خارج شده‌اند:<br>' . implode('<br>', $codesWithExitTimeList), 'خطا')->html()->autoclose(50000);

            return back();
        }


        foreach ($existingTrackingCodes as $trackingCode) {
            $trackingCode->exit_time = now();
            $trackingCode->save();
        }

        $exit_remittance = ExitRemittance::whereId($request->exit_remittance_id)->first();
        $exit_remittance->update([
            'tracking_codes' => json_encode($existingTrackingCodes),
            'exit_time' => now(),
            'status' => 'approved',
            'manager_id' => auth()->id(),
        ]);
        activity_log('approved-products-warehouse', __METHOD__, [$request->all(), $exit_remittance]);

        $order = Order::whereId($exit_remittance->order_id)->first();

        $order->order_status()->updateOrCreate(
            ['status' => 'waiting_for_send_exit_remittance'],
            ['orders' => 11, 'status' => 'waiting_for_send_exit_remittance']
        );
        $order->order_status()->updateOrCreate(
            ['status' => 'send_exit_remittance'],
            ['orders' => 12, 'status' => 'approved_exit_remittance']
        );
        $this->send_notif_to_storekeeper($order, $exit_remittance->code);
        $this->send_notif_to_salesmanger($order, $exit_remittance->code);
        $this->send_notif_to_accountant($order, $exit_remittance->code);
        $this->send_to_purchuse_engeener();
        alert()->success('کالاها با موفقیت از انبار خارج شدند.', 'موفقیت');
        return redirect()->route('show.outOfStock.index', $exit_remittance->id);


    }


    public function downloadPDF($id)
    {
        $exitRemittance = ExitRemittance::findOrFail($id);

        $pdf = PDF::loadView('panel.exit-remittance.exit-remittance-pdf', ['exitRemittance' => $exitRemittance], [], [
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'sahel',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 0,

        ]);
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $exitRemittance->code . '.pdf"');
    }

    public function downloadPDFExitFromWarehouse($id)
    {
        $exitRemittance = ExitRemittance::findOrFail($id);
        $pdf = PDF::loadView('panel.exit-remittance.exit-form-warehouse-pdf', ['exitRemittance' => $exitRemittance], [], [
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'sahel',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 0,

        ]);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $exitRemittance->code . '.pdf"');

    }


    public function dropCodeFromList($list, $code)
    {

        $filtered = array_filter($list, function ($item) use ($code) {
            return $item->code !== $code;
        });

        return json_encode(array_values($filtered));

    }


    private function send_notif_to_storekeeper(Order $order, $code)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'warehouse-keeper');
        })->pluck('id');
        $managers = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = url('/');
        $title = "حواله خروج";
        $message = "حواله خروج به شناسه " . $code . " برای شناسه مشتری " . $order->code . " ثبت گردید.";

        Notification::send($managers, new SendMessage($message, $url, $title));
    }

    private function send_notif_to_salesmanger(Order $order, $code)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'sales-manager');
        })->pluck('id');
        $managers = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = url('/');
        $title = "حواله خروج";
        $message = "حواله خروج به شناسه " . $code . " برای شناسه مشتری " . $order->code . " ثبت گردید.";

        Notification::send($managers, new SendMessage($message, $url, $title));
    }

    private function send_notif_to_accountant(Order $order, $code)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'accountant');
        })->pluck('id');
        $managers = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = url('/');
        $title = "حواله خروج";
        $message = "حواله خروج به شناسه " . $code . " برای شناسه مشتری " . $order->code . " ثبت گردید.";

        Notification::send($managers, new SendMessage($message, $url, $title));
    }

    public function send_to_purchuse_engeener()
    {
        $products = Product::latest()->withCount(['trackingCodes' => function ($query) {
            $query->whereNull('exit_time');
        }])->get();

        foreach ($products as $product) {
            if ($product->tracking_codes_count < 10) {
                $existingPurchase = Purchase::where('product_id', $product->id)
                    ->where('status', 'pending_purchase')
                    ->exists();

                if (!$existingPurchase) {
                    $purchase = new Purchase();
                    $purchase->user_id = auth()->id();
                    $purchase->product_id = $product->id;
                    $purchase->save();
                    $message = "کالای $product->title به لیست خرید اضافه گردید.";
                    $users = User::whereHas('role.permissions', function ($q) {
                        $q->whereIn('name', ['purchase-engineering', 'admin']);
                    })->get();
                    Notification::send($users, new SendMessage($message, url('/panel/purchases'), 'مهندسی خرید'));
                }
            }

        }
        return true;
    }

    public function wareHouseStockPrinter()
    {
        $products = Product::withCount(['trackingCodes' => function ($query) {
            $query->whereNull('exit_time');
        }])->orderByDesc('tracking_codes_count')
            ->latest()
            ->get();


        $pdf = PDF::loadView('panel.exit-remittance.warehouse-stock-pdf', ['products' => $products], [], [
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'sahel',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 0,

        ]);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="warehouse-' . verta() . '.pdf"');

    }

}
