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
        $users = User::whereIn('id',[$this->userData->user_id])->get();
        $title = $this->userData->title;
        $message = $this->userData->message;
        $url = route('tickets.index');

        Notification::send($users, new SendMessage($message, $url,$title));

    }
}
