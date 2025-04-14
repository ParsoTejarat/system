<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\EmployeeRequest;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class EmployeeRequestController extends Controller
{

    public function index()
    {

        $this->authorize('employee-request-list');
        if (!request()->has('type')) {
            return redirect()->route('employee-requests.index', ['type' => 'request_documents']);
        }
        $employeeRequests = EmployeeRequest::query();
        $type = \request()->get('type') ?? 'request_documents';

        if ($code = request()->code) {
            $employeeRequests->where('code', 'like', "%{$code}%");
        }

        if ($title = request()->title) {
            $employeeRequests->where('title', 'like', "%{$title}%");
        }

        if ($status = request()->status) {
            $employeeRequests->where('status', $status);
        }

        if (auth()->user()->isAdmin() || auth()->user()->isAccountant() || auth()->user()->isCEO()) {
            $employeeRequests = $employeeRequests->where('type', $type)->latest()->paginate(30);

        } else {
            $employeeRequests = $employeeRequests->where(['type' => $type, 'employee_id'=> auth()->id()])->latest()->paginate(30);

        }

        return view('panel.employee_requests.index', compact(['employeeRequests', 'type']));
    }


    public function create()
    {
        $this->authorize('employee-request-create');

        if (!request()->has('type')) {
            return redirect()->route('employee-requests.create', ['type' => 'request_documents']);
        }
        $type = \request()->get('type') ?? 'request_documents';

        return view('panel.employee_requests.create', compact(['type']));
    }


    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('employee-request-create');
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->employee_id = auth()->id();
        $employeeRequest->code = $this->generateCode();
        $employeeRequest->title = $request->title;
        $employeeRequest->type = $request->type;
        $employeeRequest->amount = $request->amount;
        $employeeRequest->priority = $request->priority;
        $employeeRequest->employee_description = $request->employee_description;
        if ($request->hasFile('file')) {
            $employeeRequest->employee_attachment_path = upload_file($request->file('file'), 'employee-requests');
        }
        $employeeRequest->status = 'pending';
        $employeeRequest->save();
        $this->sendSmsToUser(auth()->user(), $employeeRequest->type);
        $this->sendSmsToManager(auth()->user(), $employeeRequest->type);
        $this->sendNotificationToManager($employeeRequest->type, auth()->user());
        alert()->success('درخواست مورد نظر با موفقیت ثبت شد', 'ثبت درخواست');
        activity_log('employee-request-create', __METHOD__, [$request->all(), $employeeRequest]);
        return redirect()->route('employee-requests.index', ['type' => $employeeRequest->type]);

    }


    public function show(EmployeeRequest $employeeRequest)
    {
        return view('panel.employee_requests.show', compact(['employeeRequest']));
    }


    public function edit(EmployeeRequest $employeeRequest)
    {
        $this->authorize('employee-request-edit');
        return view('panel.employee_requests.edit', compact(['employeeRequest']));

    }


    public function update(StoreEmployeeRequest $request, EmployeeRequest $employeeRequest)
    {
        $this->authorize('employee-request-edit');
        $employeeRequest->title = $request->title;
        $employeeRequest->priority = $request->priority;
        $employeeRequest->amount = $request->amount;
        $employeeRequest->employee_description = $request->employee_description;
        if ($request->hasFile('file')) {
            $employeeRequest->employee_attachment_path = upload_file($request->file('file'), 'employee-requests');
        }
        $employeeRequest->status = 'pending';
        $employeeRequest->save();
        alert()->success('درخواست مورد نظر با موفقیت ویرایش شد', 'ویرایش درخواست');
        activity_log('employee-request-edit', __METHOD__, [$request->all(), $employeeRequest]);
        return redirect()->route('employee-requests.index', ['type' => $employeeRequest->type]);

    }


    public function destroy(EmployeeRequest $employeeRequest)
    {
        $this->authorize('employee-request-delete');
        if ($employeeRequest->answered_at != null) {
            $employeeRequest->delete();
            alert()->success('درخواست مورد نظر با موفقیت حذف شد', 'حذف درخواست');
            activity_log('employee-request-delete', __METHOD__, $employeeRequest);
        } else {
            alert()->warning('امکان حذف این درخواست وجود ندارد. تنها درخواست‌های پاسخ‌داده‌شده قابل حذف هستند.', 'خطا در حذف');
        }

        return redirect()->route('employee-requests.index', ['type' => $employeeRequest->type]);


    }

    public function employeeAction(Request $request)
    {
        $this->authorize('employee-request-action');
        $status = $request->input('status');

        $baseRules = [
            'status' => ['required', Rule::in(array_keys(\App\Models\EmployeeRequest::STATUS)), 'not_in:pending'],
        ];

        if ($status === 'approved') {
            $baseRules['approver_attachment_path'] = 'required';
        } elseif ($status === 'not_approved') {
            $baseRules['approver_description'] = 'required';
        }

        $messages = [
            'status.required' => 'وضعیت درخواست الزامی است.',
            'status.in' => 'وضعیت انتخاب‌شده معتبر نیست.',
            'status.not_in' => 'امکان انتخاب وضعیت "در انتظار بررسی" وجود ندارد.',

            'approver_attachment_path.required' => 'لطفاً فایل پیوست را برای تایید درخواست آپلود کنید.',
            'approver_attachment_path.file' => 'فایل پیوست نامعتبر است.',
            'approver_attachment_path.mimes' => 'فرمت فایل مجاز نیست. فرمت‌های مجاز: jpg, jpeg, png, pdf, doc, docx',
            'approver_attachment_path.max' => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',

            'approver_description.required' => 'لطفاً علت رد درخواست را در قسمت توضیحات وارد کنید.',
            'approver_description.string' => 'توضیحات باید به صورت متن باشد.',
            'approver_description.max' => 'توضیحات نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ];

        $request->validate($baseRules, $messages);

        $employeeRequest = EmployeeRequest::findOrFail($request->input('employee_requests_id'));

        if ($request->hasFile('approver_attachment_path')) {
            $employeeRequest->approver_attachment_path = upload_file($request->file('approver_attachment_path'), 'employee-requests');
        }

        $employeeRequest->status = $request->input('status');
        $employeeRequest->approver_description = $request->input('approver_description');
        $employeeRequest->approver_id = auth()->id();
        $employeeRequest->answered_at = now();

        $employeeRequest->save();
        $this->sendSmsForResult($employeeRequest->status, $employeeRequest->employee_id, $employeeRequest->type);
        $this->sendNotificationForResult($employeeRequest->status, $employeeRequest->employee_id, $employeeRequest->type, $employeeRequest->code);
        alert()->success('نتیجه در خواست ثبت شد', 'ثبت نتیجه درخواست');

        activity_log('employee-request-action', __METHOD__, $employeeRequest);

        return redirect()->route('employee-requests.index', ['type' => $employeeRequest->type]);
    }


    private function sendSmsToUser(User $user, $type)
    {
        //   316996 اطلاع رسانی ثبت درخواست

        sendSMS(316996, $user->phone, [(string)$user->fullName(), (string)EmployeeRequest::REQUEST_FOR[$type]]);
    }

    private function sendSmsToManager(User $user, $type)
    {
//        317002 اطلاع رسانی درخواست در پرتال
        $permissionsId = Permission::whereIn('name', ['ceo', 'admin', 'accountant'])->pluck('id');
        $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
            $q->whereIn('permission_id', $permissionsId);
        })->pluck('id');
        $managers = User::whereIn('role_id', $roles_id)->get();
        foreach ($managers as $manager) {
            sendSMS(317002, $manager->phone, [(string)$manager->fullName(), (string)EmployeeRequest::REQUEST_FOR[$type], (string)$user->fullName()]);
        }


    }

    private function sendSmsForResult($status, $user_id, $type)
    {
        $user = User::findOrFail($user_id);
        if ($status === 'approved') {
            sendSMS(316997, $user->phone, [(string)$user->fullName(), (string)EmployeeRequest::REQUEST_FOR[$type]]);
        } else {
            sendSMS(316999, $user->phone, [(string)$user->fullName(), (string)EmployeeRequest::REQUEST_FOR[$type]]);
        }
//
    }


    private function sendNotificationToManager($type, User $user)
    {
        $type = EmployeeRequest::REQUEST_FOR[$type];
        $permissionsId = Permission::whereIn('name', ['ceo', 'admin', 'accountant'])->pluck('id');
        $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
            $q->whereIn('permission_id', $permissionsId);
        })->pluck('id');
        $managers = User::whereIn('role_id', $roles_id)->get();
        $url = route('orders.index');
        $title = "درخواست " . $type;
        $notif_message = "یک درخواست " . $type . " توسط همکار " . $user->fullName() . " ثبت شد";
        Notification::send($managers, new SendMessage($notif_message, $url, $title));
//            dd("test");
    }

    private function sendNotificationForResult($status, $user_id, $type, $code)
    {
        $type = EmployeeRequest::REQUEST_FOR[$type];

        $user = User::whereIn('id', [$user_id])->get();
        $url = url('/');
        $title = "نتیجه درخواست " . $type;
        $notif_message = "";
        if ($status === 'approved') {

            $notif_message = " درخواست " . $type . " شما با شناسه " . $code . " تایید شد";
        } else {
            $notif_message = " درخواست " . $type . " شما با شناسه " . $code . " تایید شد";

        }
        Notification::send($user, new SendMessage($notif_message, $url, $title));
    }


    public function generateCode()
    {
        $code = '777' . str_pad(rand(0, 999999), 5, '0', STR_PAD_LEFT);

        while (EmployeeRequest::where('code', $code)->lockForUpdate()->exists()) {
            $code = '777' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }


}
