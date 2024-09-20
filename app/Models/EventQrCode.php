<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventQrCode extends Model
{
    use HasFactory;
    protected $table = 'event_qrcodes';
    public $guarded = [];
    protected $primaryKey = 'event_qrcode_id';

    public function qrcode(): HasOne
    {
        return $this->hasOne(QrCode::class, 'qrcode_id', 'qrcode_id');
    }
}
