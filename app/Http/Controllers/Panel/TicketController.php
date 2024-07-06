<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index()
    {
        $this->authorize('tickets-list');

        if (auth()->user()->isAdmin()){
            $tickets = Ticket::latest()->paginate(30);
        }else{
            $tickets = Ticket::where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id())
                ->latest()->paginate(30);
        }

        return view('panel.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $this->authorize('tickets-create');

        return view('panel.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        $this->authorize('tickets-create');

        $ticket = Ticket::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver,
            'title' => $request->title,
            'code' => $this->generateCode(),
        ]);

        if ($request->file){
            $file_info = [
                'name' => $request->file('file')->getClientOriginalName(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'size' => $request->file('file')->getSize(),
            ];

            $file = upload_file($request->file, 'Messages');

            $file_info['path'] = $file;
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);

        // log
        activity_log('create-ticket', __METHOD__, [$request->all(), $ticket]);

        $message = 'تیکتی با عنوان "'.$ticket->title.'" به شما ارسال شده است';
        $url = route('tickets.edit', $ticket->id);

        Notification::send($ticket->receiver, new SendMessage($message, $url));

        return redirect()->route('tickets.edit', $ticket->id);
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('tickets-create');

        $ticket->messages()->whereNull('read_at')->where('user_id','!=',auth()->id())->update(['read_at' => now()]);

        return view('panel.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('tickets-create');

        $ticket->update(['status' => 'pending']);

        // prevent from send sequence notification
        $first_message = $ticket->messages()->orderBy('created_at', 'desc')->first();
        if ($first_message != null && $first_message->user_id != auth()->id()){
            $message = 'پاسخی برای تیکت "'.$ticket->title.'" ثبت شده است';
            $url = route('tickets.edit', $ticket->id);
            $receiver = auth()->id() == $ticket->sender_id ? $ticket->receiver : $ticket->sender;
            Notification::send($receiver, new SendMessage($message, $url));
        }
        // end prevent from send sequence notification

        if ($request->file){
            $file_info = [
                'name' => $request->file('file')->getClientOriginalName(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'size' => $request->file('file')->getSize(),
            ];

            $file = upload_file($request->file, 'Messages');

            $file_info['path'] = $file;
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);

        // log
        activity_log('edit-ticket', __METHOD__, [$request->all(), $ticket]);

        return back();
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('tickets-delete');

        foreach ($ticket->messages as $message){
            if ($message->file){
                unlink(public_path(json_decode($message->file)->path));
            }
        }

        // log
        activity_log('delete-ticket', __METHOD__, $ticket);

        $ticket->delete();
        return back();
    }

    public function changeStatus(Ticket $ticket)
    {
        if ($ticket->sender_id == auth()->id() || $ticket->receiver_id == auth()->id()){
            if ($ticket->status == 'closed'){
                $ticket->update(['status' => 'pending']);
            }else{
                $ticket->update(['status' => 'closed']);
            }

            // send notif
            $status = Ticket::STATUS[$ticket->status];
            $message = "وضعیت تیکت '$ticket->title' به '$status' تغییر یافت";
            $url = route('tickets.index');
            $receiver = auth()->id() == $ticket->sender_id ? $ticket->receiver : $ticket->sender;
            Notification::send($receiver, new SendMessage($message, $url));
            // end send notif

            // log
            activity_log('ticket-change-status', __METHOD__, $ticket);

            alert()->success('وضعیت تیکت با موفقیت تغییر یافت','تغییر وضعیت');
            return back();

        }else{
            abort(403);
        }
    }

    private function generateCode()
    {
        $last_ticket = Ticket::latest()->first();
        $newCode = $last_ticket ? $last_ticket->code++ : 1;

        if ($last_ticket) {
            while (Ticket::where('code', $newCode)->exists()) {
                $newCode++;
            }
            return $newCode;
        } else {
            return $newCode;
        }
    }
}
