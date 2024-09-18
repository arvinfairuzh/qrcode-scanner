<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id('qrcode_id');
            $table->string('code');
            $table->string('name');
            $table->string('category');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name');
            $table->date('event_date');
            $table->timestamps();
        });

        Schema::create('event_qrcodes', function (Blueprint $table) {
            $table->id('event_qrcode_id');
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('qrcode_id')->index();
            $table->boolean('scanned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_qrcodes');
    }
};
