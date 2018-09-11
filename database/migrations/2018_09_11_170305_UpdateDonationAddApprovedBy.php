<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDonationAddApprovedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(DB::raw("UPDATE donation SET created_dt = now() WHERE created_dt like '0000%'"));
        Schema::table('donation',function(Blueprint $t){
            $t->string('approved_by',30)->after('donation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation',function(Blueprint $t){
            $t->dropColumn('approved_by');
        });
    }
}
