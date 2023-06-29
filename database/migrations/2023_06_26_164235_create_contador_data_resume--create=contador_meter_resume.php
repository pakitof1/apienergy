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
        Schema::create('contador_meter_resume', function (Blueprint $table) {
            $table->id();
            $table->date('datetime');
            $table->unsignedBigInteger('contador_id');
            $table->float('avgPower');
            $table->float('total_energy');
            $table->timestamps();

            $table->foreign('contador_id')->references('id')->on('contadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contador_meter_resume');

    }
};
