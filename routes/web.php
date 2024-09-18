<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('scanner');
// });
Route::get('/scanner', function () {
    return view('scanner');
});
Route::get('/generate-qr', function () {
    return QrCode::size(300)->generate('0Ssc0YV1PciB37cc');
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::post('/scanning', [App\Http\Controllers\ScanController::class, 'scanning']);
Route::get('/event/{event_id}', [App\Http\Controllers\HomeController::class, 'event']);
Route::get('/event/{event_id}/report', [App\Http\Controllers\HomeController::class, 'report']);
Route::get('/event/{event_id}/ajax', [App\Http\Controllers\HomeController::class, 'eventAjax']);
Route::get('/event/{event_id}/qrcode', [App\Http\Controllers\HomeController::class, 'exportUsers']);
Route::get('/event/{event_id}/scanned', [App\Http\Controllers\HomeController::class, 'exportReportScanned']);