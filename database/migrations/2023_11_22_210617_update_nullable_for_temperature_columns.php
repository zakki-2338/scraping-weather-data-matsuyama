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
       Schema::table('matsuyama_weather_data', function (Blueprint $table) {
            $table->double('temperature_avg', 8, 2)->nullable()->change();
            $table->double('temperature_max', 8, 2)->nullable()->change();
            $table->double('temperature_min', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('matsuyama_weather_data', function (Blueprint $table) {
            $table->double('temperature_avg', 8, 2)->nullable(false)->change();
            $table->double('temperature_max', 8, 2)->nullable(false)->change();
            $table->double('temperature_min', 8, 2)->nullable(false)->change();
        });
    }
};
