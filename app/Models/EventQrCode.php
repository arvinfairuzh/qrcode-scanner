<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQrCode extends Model
{
    use HasFactory;
    protected $table = 'event_qrcodes';
    public $guarded = [];
    protected $primaryKey = 'event_qrcode_id';
}
