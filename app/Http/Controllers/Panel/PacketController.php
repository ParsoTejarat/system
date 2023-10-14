<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePacketRequest;
use App\Http\Requests\UpdatePacketRequest;
use App\Models\Invoice;
use App\Models\Packet;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    public function index()
    {
        $this->authorize('packets-list');

        $packets = Packet::latest()->paginate(30);
        return view('panel.packets.index', compact('packets'));
    }

    public function create()
    {
        $this->authorize('packets-create');

        $invoices = Invoice::doesntHave('packet')->latest()->get(['id','buyer_name']);
        return view('panel.packets.create', compact('invoices'));
    }

    public function store(StorePacketRequest $request)
    {
        $this->authorize('packets-create');

        Packet::create([
            'invoice_id' => $request->invoice,
            'receiver' => $request->receiver,
            'address' => $request->address,
            'sent_type' => $request->sent_type,
            'send_tracking_code' => $request->send_tracking_code,
            'receive_tracking_code' => $request->receive_tracking_code,
            'packet_status' => $request->packet_status,
            'invoice_status' => $request->invoice_status,
        ]);

        alert()->success('بسته مورد نظر با موفقیت ایجاد شد','ایجاد بسته');
        return redirect()->route('packets.index');
    }

    public function show(Packet $packet)
    {
        //
    }

    public function edit(Packet $packet)
    {
        $this->authorize('packets-edit');

        $invoices = Invoice::doesntHave('packet')->latest()->get(['id','buyer_name']);
        return view('panel.packets.edit', compact('invoices', 'packet'));
    }

    public function update(UpdatePacketRequest $request, Packet $packet)
    {
        $this->authorize('packets-edit');

        $packet->update([
            'invoice_id' => $request->invoice,
            'receiver' => $request->receiver,
            'address' => $request->address,
            'sent_type' => $request->sent_type,
            'send_tracking_code' => $request->send_tracking_code,
            'receive_tracking_code' => $request->receive_tracking_code,
            'packet_status' => $request->packet_status,
            'invoice_status' => $request->invoice_status,
        ]);

        alert()->success('بسته مورد نظر با موفقیت ویرایش شد','ویرایش بسته');
        return redirect()->route('packets.index');
    }

    public function destroy(Packet $packet)
    {
        $this->authorize('packets-delete');

        $packet->delete();
        return back();
    }
}
