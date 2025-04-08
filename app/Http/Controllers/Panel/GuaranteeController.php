<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuaranteeRequest;
use App\Http\Requests\UpdateGuaranteeRequest;
use App\Models\Guarantee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Notifications\SendMessage;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GuaranteeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('guarantees-list');

        // expire the guarantees where expired_at < now
        Guarantee::where('status', 'active')->where('expire_time', '<', now())->update(['status' => 'expired']);

        $guarantees = Guarantee::query()
            ->serialNumber($request->serial_number)
            ->status($request->status)
            ->latest()
            ->paginate(30);

        return view('panel.guarantees.index', compact('guarantees'));
    }

    public function create()
    {
        $this->authorize('guarantees-create');

        return view('panel.guarantees.create');
    }

    public function store(StoreGuaranteeRequest $request)
    {
        $this->authorize('guarantees-create');

        $guarantee = new Guarantee();
        $guarantee->product_id = $request->product;
        $guarantee->user_id = auth()->id();
        $guarantee->serial_number = $request->serial_number;
        $guarantee->product_identifier = $request->product_identifier;
        $guarantee->tracking_code = $request->tracking_code;
        $guarantee->status = 'active';
        $guarantee->period = 18;
        $guarantee->importing_company = $request->importing_company;
        $guarantee->start_time = now();
        $guarantee->expire_time = now()->addMonths($request->period);
        $guarantee->save();

        $this->send_notif_to_accountants_store($guarantee->product_id, $guarantee->serial_number);


        // log
        activity_log('create-guarantee', __METHOD__, [$request->all(), $guarantee]);

        alert()->success('گارانتی با موفقیت ثبت شد', 'ثبت گارانتی');
        return redirect()->route('guarantees.index');
    }

    public function show(Guarantee $guarantee)
    {
        return view('panel.guarantees.show', compact('guarantee'));
    }

    public function edit(Guarantee $guarantee)
    {
        $this->authorize('guarantees-edit');

        return view('panel.guarantees.edit', compact(['guarantee']));
    }

    public function update(UpdateGuaranteeRequest $request, Guarantee $guarantee)
    {
        $this->authorize('guarantees-edit');


        $expire_time = Verta::parse($request->expire_time)->toCarbon()->toDateString();

        $guarantee->product_id = $request->product;
        $guarantee->serial_number = $request->serial_number;
        $guarantee->product_identifier = $request->product_identifier;
        $guarantee->tracking_code = $request->tracking_code;
        $guarantee->importing_company = $request->importing_company;
        $guarantee->period = $request->period;
        $guarantee->status = $request->status;
        $guarantee->expire_time = $expire_time;

        $guarantee->save();

        // log
        $this->send_notif_to_accountants_update($guarantee->product_id, $guarantee->serial_number);
        activity_log('edit-guarantee', __METHOD__, [$request->all(), $guarantee]);

        alert()->success('گارانتی با موفقیت ویرایش شد', 'ویرایش گارانتی');
        return redirect()->route('guarantees.index');
    }

    public function destroy(Guarantee $guarantee)
    {
        $this->authorize('guarantees-delete');

        // log
        activity_log('delete-guarantee', __METHOD__, $guarantee);

        $guarantee->delete();
        return back();
    }

    public function print(Guarantee $guarantee)
    {
        return view('panel.guarantees.printable', compact(['guarantee']));
    }

//    public function serialCheck(Request $request)
//    {
//        $serial = 'PT' . $request->serial;
//
//        $guarantee = Guarantee::where('serial', $serial)->whereIn('status', ['active', 'inactive'])->first();
//
//        if ($guarantee) {
//            if (!$guarantee->inventory_report) {
//                $error = false;
//                $message = 'سریال گارانتی معتبر است';
//            } elseif ($guarantee->inventory_report->id == $request->inventory_report_id) {
//                $error = false;
//                $message = 'سریال گارانتی معتبر است';
//            } else {
//                $error = true;
//                $message = 'سریال گارانتی معتبر نیست';
//            }
//        } else {
//            $error = true;
//            $message = 'سریال گارانتی معتبر نیست';
//        }
//
//        $data = [
//            'error' => $error,
//            'message' => $message,
//        ];
//
//        return response()->json(['data' => $data]);
//    }
    private function send_notif_to_accountants_store($product, $code)
    {
        $product = Product::find($product);
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', ['accountant', 'warehouse-keeper']);
        })->pluck('id');
        $accountants = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = route('guarantees.index');
        $title = "ثبت گارانتی";
        $message = "محصول " . $product->title . " به سریال " . $code . " گارانتی شد.";

        Notification::send($accountants, new SendMessage($message, $url, $title));
    }
    private function send_notif_to_accountants_update($product, $code)
    {
        $product = Product::find($product);
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', ['accountant', 'warehouse-keeper']);
        })->pluck('id');
        $accountants = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = route('guarantees.index');
        $title = "ویرایش گارانتی";
        $message = " گارانتی محصول " . $product->title . " به سریال " . $code . " ویرایش شد.";

        Notification::send($accountants, new SendMessage($message, $url, $title));
    }

    public function guaranteesExport(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            alert()->error('شماره سریالی انتخاب نشده است','خطا');
            return back();
        }

        return Excel::download(new \App\Exports\GuaranteesExport($ids), 'Guarantees' . '_' . Str::slug(verta()->now()) . '.xlsx');
    }
}
