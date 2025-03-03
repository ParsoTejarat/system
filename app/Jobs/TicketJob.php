<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }


    public function handle()
    {
        Log::info('Job دریافت شد:', ['data' => $this->userData]);
        $data = (object) $this->userData->data;
        Log::info('$data', ['data' =>$data]);
        $users = User::whereIn('id', [$data->user_id])->get();
        Log::info('$users', ['data' =>$users]);
        $title = $data->title;
        Log::info('$title', ['data' =>$title]);
        $message = $data->message;
        Log::info('$message', ['data' =>$message]);
        $url = route('tickets.index');

        Notification::send($users, new SendMessage($message, $url, $title));



    }
}
