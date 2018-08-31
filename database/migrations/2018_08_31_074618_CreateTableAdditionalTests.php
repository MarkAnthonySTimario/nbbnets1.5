<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdditionalTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionaltest',function(Blueprint $t){
            $t->increments('id');
            $t->string('donation_id',16);
            $t->char('antibody',1)->nullable();
            $t->string('antibody_by',16)->nullable();
            $t->string('antibody_verifier',16)->nullable();
            $t->char('nat',1)->nullable();
            $t->string('nat_by',16)->nullable();
            $t->string('nat_verifier',16)->nullable();
            $t->char('zika',1)->nullable();
            $t->string('zika_by',16)->nullable();
            $t->string('zika_verifier',16)->nullable();
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
        Schema::drop('additionaltest');
    }
}
