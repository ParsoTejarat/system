<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\EmployeeRequest;
use App\Models\Order;
use Illuminate\Http\Request;

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
            $employeeRequests = $employeeRequests->where(['type' => $type, 'user_id', auth()->id()])->latest()->paginate(30);

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
        $employeeRequest->priority = $request->priority;
        $employeeRequest->employee_description = $request->employee_description;
        if ($request->hasFile('file')) {
            $employeeRequest->file = upload_file($request->file('file'), 'employee-requests');
        }
        $employeeRequest->status = 'pending';
        $employeeRequest->save();
        alert()->success('سفارش مورد نظر با موفقیت ثبت شد', 'ثبت سفارش');
        activity_log('employee-request-create', __METHOD__, [$request->all(), $employeeRequest]);
        return redirect()->route('employee-requests.index', ['type' => $employeeRequest->type]);

    }


    public function show($id)
    {
        //
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

    public function employeeAction()
    {

    }


    private function sendSmsToUser()
    {

    }

    private function sendSmsToManager()
    {

    }

    private function sendNotificationToUser()
    {

    }

    private function sendNotificationToManager()
    {

    }

    private function sendSmsForResult()
    {

    }

    private function sendNotificationForResult()
    {

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
