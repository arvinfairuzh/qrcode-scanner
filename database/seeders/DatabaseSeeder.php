<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventQrCode;
use App\Models\QrCode as ModelsQrCode;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $event = Event::create([
        //     'event_name' => 'KPU',
        //     'event_date' => '2024-09-19',
        // ]);

        $data = [
            // ['name' => 'Pimpinan KPU Jatim', 'amount' => 8],
            // ['name' => 'Sekretariat KPU Jatim', 'amount' => 120],
            // ['name' => 'Pimpinan Bawaslu Jatim', 'amount' => 8],
            // ['name' => 'Sekretariat Bawaslu Jatim', 'amount' => 12],
            // ['name' => 'Paslon', 'amount' => 6],
            // ['name' => 'Tim Pendukun', 'amount' => 300],
            // ['name' => 'Keamanan', 'amount' => 50],
            ['name' => 'Media', 'amount' => 30],
            // ['name' => 'Stakeholder', 'amount' => 70],
            // ['name' => 'KPU RI', 'amount' => 10],
            // ['name' => 'Sekretariat KPU Jatim All Access', 'amount' => 10],
            // ['name' => 'Etnika Production All Access', 'amount' => 20],
        ];

        foreach ($data as $i => $item) {
            for ($i = 1; $i <= $item['amount']; $i++) {
                $qrcode = ModelsQrCode::create([
                    'code' => Str::random(),
                    'name' => $item['name'] . ' ' . $i,
                    'category' => $item['name'],
                ]);

                $filename = "{$qrcode['category']}-{$qrcode['name']}"; // Define the filename
                // Generate QR code and save it
                $this->generateQrCode($qrcode['code'], $filename);

                $imagePath = storage_path("app/public/qrcodes/{$filename}.png");
                // EventQrCode::create([
                //     'event_id' => 1,
                //     'qrcode_id' => $qrcode['qrcode_id'],
                // ]);
            }
        }
    }

    function generateQrCode($code, $filename)
    {
        // dd($code, $filename);
        $qrCode = QrCode::create($code)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $path = storage_path("app/public/qrcodes/{$filename}.png");
        $result->saveToFile($path);
    }
}
