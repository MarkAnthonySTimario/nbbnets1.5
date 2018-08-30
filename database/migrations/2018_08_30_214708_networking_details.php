<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NetworkingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networking_intent_dtls',function(Blueprint $t){
            $t->increments('id');
            $t->integer('intent_id');
            $t->string('donation_id',16);
            $t->string('component_cd',2);
            $t->string('blood_type',6);
            $t->date('released_at')->nullable();
            $t->string('released_by',30)->nullable();
            $t->date('returned_at')->nullable();
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
        Schema::drop('networking_intent_dtls');
    }
}
