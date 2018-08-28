<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmergencyPoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("emergency_pool",function($s){
            $s->increments('id');
            $s->string("facility_cd",5);
            $s->string("blood_type",6);
            $s->string("component_cd",16);
            $s->string("pool",16);
            $s->unique(['facility_cd','component_cd','blood_type']);
            $s->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("emergency_pool");
    }
}
