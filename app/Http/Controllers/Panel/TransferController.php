<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use PDF as PDF;

class TransferController extends Controller
{

    public function index()
    {
        $this->authorize('transfer-list');


        $transfers = Transfer::query();


        if ($code = request()->query('code')) {
            $transfers->where('code', 'like', '%' . $code . '%');
        }


        if (!auth()->user()->isCEO() && !auth()->user()->isAdmin() && !auth()->user()->isItManager()) {
            $transfers->where('user_id', auth()->id());
        }


        $transfers = $transfers->latest()->paginate(30);


        return view('panel.transfers.index', compact('transfers'));
    }


    public function create()
    {
        $this->authorize('transfer-create');
        return view('panel.transfers.create');
    }


    public function store(Request $request)
    {
        $this->authorize('transfer-create');

        $transfer = new Transfer();
        $transfer->create([
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'user_id' => auth()->id(),
            'code' => $this->generateCode(),
        ]);
        activity_log('transfer-create', __METHOD__, [$request->all(), $transfer]);

        alert()->success('مشخصات ارسالی ثبت شد.', 'موفقیت آمیز');
        return redirect(route('transfers.index'));
    }


    public function show($id)
    {
        //
    }


    public function edit(Transfer $transfer)
    {
        $this->authorize('transfer-edit');
        return view('panel.transfers.edit', compact('transfer'));

    }


    public function update(Request $request, Transfer $transfer)
    {
        $this->authorize('transfer-edit');

        $transfer->update([
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'zip_code' => $request->zip_code,
            'address' => $request->address,

        ]);
        activity_log('transfer-edit', __METHOD__, [$request->all(), $transfer]);

        alert()->success('مشخصات ارسالی ویرایش شد.', 'موفقیت آمیز');
        return redirect(route('transfers.index'));
    }


    public function destroy(Transfer $transfer)
    {
        $this->authorize('transfer-delete');

        $transfer->delete();
        activity_log('transfer-delete', __METHOD__, [$transfer]);
        return back();
    }

    public function generateCode()
    {
        $code = '888' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

        while (Transfer::where('code', $code)->lockForUpdate()->exists()) {
            $code = '888' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function downloadReceipt($id)
    {
        $transfer = Transfer::whereId($id)->first();
        activity_log('transfer-download', __METHOD__, [$transfer]);

        $pdf = PDF::loadView('panel.pdf.transfer', ['transfer' => $transfer], [], [
            'format' => 'A5',
            'orientation' => 'L',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 0,
        ]);
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="مشخصات_پستی.pdf"');

    }
}
