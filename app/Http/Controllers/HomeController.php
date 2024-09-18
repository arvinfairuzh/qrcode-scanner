<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventQrCode;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    function index()
    {
        $data['events'] = Event::where('event_date', '>=', Carbon::now())->get();

        return view('home', $data);
    }

    function event($event_id)
    {
        $data['event'] = Event::find($event_id);

        return view('event', $data);
    }

    function eventAjax($event_id)
    {
        $event = Event::find($event_id);
        $event['event_date'] = Carbon::parse($event['event_date'])->format('l, d F Y');
        return response()->json($event);
    }
}
