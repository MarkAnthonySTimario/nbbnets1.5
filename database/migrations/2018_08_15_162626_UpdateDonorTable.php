<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDonorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("DELETE FROM donor WHERE bdate = '0000-00-00'");
        DB::statement("ALTER TABLE donor MODIFY COLUMN donor_photo MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE donor MODIFY COLUMN donor_photo BLOB");
    }
}
