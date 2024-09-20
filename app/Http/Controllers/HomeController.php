<?php

namespace App\Http\Controllers;

use App\Exports\QrCodeExport;
use App\Exports\ReportScannedExport;
use App\Models\Event;
use App\Models\EventQrCode;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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

    function report($event_id)
    {
        $data['event'] = Event::find($event_id);
        $data['data'] = EventQrCode::where(['event_id' => $event_id, 'scanned' => true])->get()->map(function ($item) {
            $qrCode = QrCode::where('qrcode_id', $item->qrcode_id)->first();
            return [
                'name' => $qrCode->name,
                'category' => $qrCode->category,
                'scanned' => $item->scanned ? 'HADIR' : 'TIDAK HADIR',
                'date' => $item->updated_at
            ];
        });

        return view('attendance', $data);
    }

    public function getAttendance()
    {
        $data = EventQrCode::select('event_qrcodes.*')->with('qrcode')
            ->where('scanned', 1)
            ->orderBy('event_qrcodes.updated_at', 'desc');
        return DataTables::of($data)
            ->editColumn('name', function ($row) {
                return $row->qrcode->name;
            })
            ->editColumn('category', function ($row) {
                return $row->qrcode->category;
            })
            ->editColumn('scanned', function ($row) {
                return $row->scanned ? 'Hadir' : 'Tidak Hadir';
            })
            ->editColumn('updated_at', function ($row) {
                return Carbon::parse($row->updated_at)->format('l, d F Y H:i:s');
            })
            ->make(true);
    }

    function eventAjax($event_id)
    {
        $event = Event::find($event_id);
        $event['event_date'] = Carbon::parse($event['event_date'])->format('l, d F Y');
        return response()->json($event);
    }

    public function exportQrCode()
    {
        return Excel::download(new QrCodeExport, 'qrcode.xlsx');
    }

    public function exportReportScanned($event_id)
    {
        return Excel::download(new ReportScannedExport($event_id), 'scanned.xlsx');
    }
}
