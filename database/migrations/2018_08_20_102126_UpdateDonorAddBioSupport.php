<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDonorAddBioSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE donor ADD COLUMN rfinger BLOB AFTER facility_cd");
        DB::statement("ALTER TABLE donor ADD COLUMN lfinger BLOB AFTER facility_cd");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE donor DROP COLUMN rfinger");
        DB::statement("ALTER TABLE donor DROP COLUMN lfinger");
    }
}
