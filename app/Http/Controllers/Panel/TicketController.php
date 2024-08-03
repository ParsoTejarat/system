<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index()
    {
        $this->authorize('tickets-list');

//        dd($tickets);
        if (auth()->user()->isAdmin()) {
            try {
                $ticketsData = $this->getAllTickets();

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }


        } else {
            try {
                $ticketsData = $this->getMyTickets();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }


        }


        return view('panel.tickets.index', compact(['ticketsData']));
    }

    public function create()
    {
        $this->authorize('tickets-create');
        $users = $this->getUsers(['user_id' => auth()->id(), 'company_name' => env('COMPANY_NAME')]);


        return view('panel.tickets.create', compact(['users']));
    }

    public function store(StoreTicketRequest $request)
    {
//        dd($request->All());
        $this->authorize('tickets-create');
//        dd($this->createTicket($request->all()));

//        $ticket = Ticket::create([
//            'sender_id' => auth()->id(),
//            'receiver_id' => $request->receiver,
//            'title' => $request->title,
//            'code' => $this->generateCode(),
//        ]);

        $data = [
            'sender_id' => auth()->id(),
            'company' => $request->company,
            'receiver_id' => $request->receiver,
            'title' => $request->title,
            'text' => $request->text,
            'file' => $request->file
        ];
        $ticket = $this->createTicket($data);


        // log
        activity_log('create-ticket', __METHOD__, [$request->all(), $ticket]);
//        $message = 'تیکتی با عنوان "' . json_decode($ticket->content())->title. '" به شما ارسال شده است';
//        $url = route('tickets.edit', json_decode($ticket->content())->id);
//        Notification::send($ticket->receiver, new SendMessage($message, $url));
        $content = json_decode($ticket->content(), true);
        return redirect()->route('tickets.edit', $content['id']);
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function edit($id)
    {
        $this->authorize('tickets-create');
        $ticket = $this->getMessages($id);
//        dd($ticket);
        return view('panel.tickets.edit', compact(['ticket']));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('tickets-create');

        $data = [
            'sender_id' => auth()->id(),
            'text' => $request->text,
            'ticket_id' => $id,
            'file' => $request->file,
        ];
        $ticket = $this->chatInTickets($data);
        return back();


    }

    public function destroy($id)
    {
        $this->authorize('tickets-delete');

        $ticket = $this->deleteTicket($id);
//        dd($ticket);

        return back();
    }

    public function changeStatus(Ticket $ticket)
    {
        if ($ticket->sender_id == auth()->id() || $ticket->receiver_id == auth()->id()) {
            if ($ticket->status == 'closed') {
                $ticket->update(['status' => 'pending']);
            } else {
                $ticket->update(['status' => 'closed']);
            }

            // send notif
            $status = Ticket::STATUS[$ticket->status];
            $message = "وضعیت تیکت '$ticket->title' به '$status' تغییر یافت";
            $url = route('tickets.index');
            $receiver = auth()->id() == $ticket->sender_id ? $ticket->receiver : $ticket->sender;
            Notification::send($receiver, new SendMessage($message, $url));
            // End-Send-Notification

            // log
            activity_log('ticket-change-status', __METHOD__, $ticket);

            alert()->success('وضعیت تیکت با موفقیت تغییر یافت', 'تغییر وضعیت');
            return back();

        } else {
            abort(403);
        }
    }


    private function getAllTickets()
    {
        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'get-all-tickets');

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function getMyTickets()
    {
        $data = ['user_id' => auth()->id()];

        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'get-my-tickets', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function createTicket($data)
    {

        try {
            $httpRequest = Http::timeout(30);
            if (isset($data['file'])) {
                $file = $data['file'];
                unset($data['file']);
                $httpRequest = $httpRequest->attach('file', file_get_contents($file), $file->getClientOriginalName());
            }

            $response = $httpRequest->post(env('API_BASE_URL') . 'create-ticket', $data);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Request failed', 'details' => $response->body()], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function getMessages($data)
    {
        $ticket_id = ['ticket_id' => $data];

        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'get-messages', $ticket_id);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }


    private function chatInTickets($data)
    {
        try {
            $httpRequest = Http::timeout(30);
            if (isset($data['file'])) {
                $file = $data['file'];
                unset($data['file']);
                $httpRequest = $httpRequest->attach('file', file_get_contents($file), $file->getClientOriginalName());
            }

            $response = $httpRequest->post(env('API_BASE_URL') . 'chat-in-tickets', $data);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Request failed', 'details' => $response->body()], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }

    }

    private function getUsers($data)
    {

        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'get-users', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }
    }


    private function deleteTicket($data)
    {
        $ticket_id = ['ticket_id' => $data];

        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'delete-ticket', $ticket_id);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
}
