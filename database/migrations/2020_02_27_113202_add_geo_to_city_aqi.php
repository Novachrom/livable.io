<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeoToCityAqi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('city_aqi', function (Blueprint $table) {
            $table->double('longitude');
            $table->double('latitude');
            $table->boolean('is_geo')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('city_aqi', function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude', 'is_geo']);
        });
    }
}
