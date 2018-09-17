<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSharedUnscreenedUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_unscreened_units',function(Blueprint $t){
            $t->increments('id');
            $t->string('facility_cd',5);
            $t->string('shared_facility_cd',5);
            $t->string('donation_id',19);
            $t->string('component_cd',2);
            $t->date('shared_dt');
            $t->string('shared_by',30);
            $t->string('approved_by',30);
            $t->string('registered_by',30)->nullable();
            $t->string('registered_approved_by',30)->nullable();
            $t->date('registered_dt')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shared_unscreened_units');
    }
}
