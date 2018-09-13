<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateComponentAddAliqouteSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("component",function(Blueprint $t){
            DB::statement(DB::raw("DELETE FROM component WHERE expiration_dt like '0000%'"));
            $t->string('donation_id',19)->change();
            $t->string('source_donation_id',16)->after('donation_id')->nullable();
            $t->string('aliqoute_by',30)->after('source_donation_id')->nullable();
            $t->dateTime('aliqoute_dt')->after('aliqoute_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("component",function(Blueprint $t){
            $t->string('donation_id',16)->change();
            $t->dropColumn('source_donation_id');
            $t->dropColumn('aliqoute_by');
            $t->dropColumn('aliqoute_dt');
        });
    }
}
