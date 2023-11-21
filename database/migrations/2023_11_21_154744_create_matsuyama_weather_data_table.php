<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matsuyama_weather_data', function (Blueprint $table) {
            $table->id();
            $table->date('observation_date')->unique();
            $table->float('precipitation_total')->nullable();
            $table->float('precipitation_max_1h')->nullable();
            $table->float('precipitation_max_10min')->nullable();
            $table->float('temperature_avg');
            $table->float('temperature_max');
            $table->float('temperature_min');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matsuyama_weather_data');
    }
};
