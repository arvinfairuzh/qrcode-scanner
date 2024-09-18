<?php

namespace App\Exports;

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

class QrCodeExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    public function collection()
    {
        return ModelsQrCode::all();
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->category,
            // Pass code to generate QR code
            $item->code, // Assuming you want to generate a QR code based on user ID
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Category',
            'QR Code',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($this->collection() as $index => $item) {
            $rowNumber = $index + 2; // Because headers are on the first row
            $code = $item->code;
            $filename = "{$item->category}-{$item->name}"; // Define the filename

            // Generate QR code and save it
            $this->generateQrCode($code, $filename);

            $imagePath = storage_path("app/public/qrcodes/{$filename}.png");

            if (file_exists($imagePath)) {
                $drawing = new Drawing();
                $drawing->setName('QR Code');
                $drawing->setDescription('QR Code');
                $drawing->setPath($imagePath);
                $drawing->setHeight(50); // Adjust image height as needed
                $drawing->setCoordinates('C' . $rowNumber); // Set image position

                $drawing->setWorksheet($sheet);
            }
        }
    }

    function generateQrCode($code, $filename)
    {
        $qrCode = QrCode::create($code)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $path = storage_path("app/public/qrcodes/{$filename}.png");
        $result->saveToFile($path);
    }
}
