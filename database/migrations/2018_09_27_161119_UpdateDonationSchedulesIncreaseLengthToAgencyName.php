<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDonationSchedulesIncreaseLengthToAgencyName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_schedules',function(Blueprint $t){
            $t->string('agency_name',100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_schedules',function(Blueprint $t){
            $t->string('agency_name',50)->change();
        });
    }
}
