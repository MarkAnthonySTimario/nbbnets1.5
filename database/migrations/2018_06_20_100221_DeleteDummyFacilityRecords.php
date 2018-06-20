<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDummyFacilityRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(DB::raw("DELETE FROM r_facility WHERE facility_name like '%blood service facility%' "));
        DB::statement(DB::raw("DELETE FROM r_user WHERE facility_cd NOT IN (SELECT facility_cd FROM r_facility)"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
