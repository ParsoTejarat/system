<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendMessage;


class TicketController extends Controller
{


    public function appSendNotification(Request $request)
    {
        dd($request->all());
        $message = 'تیکتی با عنوان "' . json_decode($ticket->content())->title . '" به شما ارسال شده است';
        $url = route('tickets.edit', json_decode($ticket->content())->id);
        Notification::send($ticket->receiver, new SendMessage($message, $url));
    }


}
