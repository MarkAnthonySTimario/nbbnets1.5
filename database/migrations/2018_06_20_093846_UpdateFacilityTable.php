<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(DB::raw("UPDATE r_facility SET created_dt = now() WHERE created_dt LIKE '0000%' OR created_dt LIKE '%-00-%' OR created_dt LIKE '%-00 %'"));
        DB::statement(DB::raw("ALTER TABLE `r_facility` ADD `nat` BOOLEAN NOT NULL DEFAULT FALSE AFTER `standard_bu_duration`"));
        DB::statement(DB::raw("ALTER TABLE `r_facility` ADD `antibody` BOOLEAN NOT NULL DEFAULT FALSE AFTER `nat`"));
        DB::statement(DB::raw("ALTER TABLE `r_facility` ADD `zika` BOOLEAN NOT NULL DEFAULT FALSE AFTER `antibody`"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(DB::raw('ALTER TABLE `r_facility` DROP COLUMN IF EXISTS nat'));
        DB::statement(DB::raw('ALTER TABLE `r_facility` DROP COLUMN IF EXISTS antibody'));
        DB::statement(DB::raw('ALTER TABLE `r_facility` DROP COLUMN IF EXISTS zika'));
    }
}
