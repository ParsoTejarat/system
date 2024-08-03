<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TicketController extends Controller
{


//    public function myTickets($id)
//    {
//        return DB::connection('moshrefi_db')->table('tickets')
//            ->where('sender_id', $id)
//            ->orWhere('receiver_id', $id)
//            ->latest()
//            ->paginate(30);
//    }
//
//    public function allTickets()
//    {
//        return DB::connection('moshrefi_db')->table('tickets')->latest()->paginate(30);
//            return response()->json(['message' => 'yes']);
//    }


}
