<?php

namespace App\Exports;

use App\Models\EventQrCode;
use App\Models\QrCode as ModelsQrCode;
use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportScannedExport implements FromCollection, WithMapping, WithHeadings
{
    protected $event_id;
    public function __construct($event_id)
    {
        $this->event_id = $event_id;
    }

    public function collection()
    {
        return EventQrCode::where(['event_id' => $this->event_id])->get();
    }

    public function map($item): array
    {
        $qrCode = ModelsQrCode::where('qrcode_id', $item->qrcode_id)->first();

        return [
            $qrCode->name,
            $item->scanned ? 'HADIR' : 'TIDAK HADIR',
            $item->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kehadiran',
            'Waktu',
        ];
    }
}
