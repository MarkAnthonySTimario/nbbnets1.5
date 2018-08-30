<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFacility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('r_facility',function(Blueprint $t){
            $t->boolean('supports_platelet_apheresis')->default(false)->after('zika');
        });

        Schema::table('r_component',function(Blueprint $t){
            $t->boolean('is_special')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('r_facility',function(Blueprint $t){
            $t->dropColumn('supports_platelet_apheresis');
        });
        Schema::table('r_component',function(Blueprint $t){
            $t->dropColumn('is_special');
        });
    }
}
