<?php

namespace App\Http\Controllers;

use App\Models\EventQrCode;
use App\Models\QrCode;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    function scanning(Request $request)
    {
        $data = $request->all();
        $findQrCode = QrCode::where('code', $data['code'])->first();
        if (!$findQrCode) return 'QR Code tidak valid';

        $findEventQrCode = EventQrCode::where(['event_id' => $data['event_id'], 'qrcode_id' => $findQrCode['qrcode_id']])->first();
        if (!$findEventQrCode) return 'QR Code tidak terdaftar di acara ini';
        if ($findEventQrCode['scanned']) return 'QR Code sudah pernah didata';
        $findEventQrCode->scanned = true;
        $findEventQrCode->save();

        return response()->json([
            'status' => true,
            'name' => $findQrCode['name'],
        ]);
    }
}
