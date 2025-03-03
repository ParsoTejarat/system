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


    public $user_id;
    public $title;
    public $message;
    public $url;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }


    public function handle()
    {


        Notification::send($users, new SendMessage($message, $url, $title));



    }
}
