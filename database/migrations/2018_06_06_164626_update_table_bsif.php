<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableBsif extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsif', function (Blueprint $table) {                       
            DB::statement('ALTER TABLE bsif CHANGE `2.1.1` `2_1_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.1.2` `2_1_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.1.3` `2_1_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.1.4` `2_1_4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.2.1` `2_2_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.2.2` `2_2_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.2.3` `2_2_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.2.4` `2_2_4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2.12` `2_12` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3.1` `3_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3.1.1` `3_1_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3.2` `3_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3.3` `3_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4.1` `4_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4.1.1` `4_1_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4.2` `4_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4.3` `4_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4.4` `4_4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.1` `5_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.5` `5_5` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.6` `5_6` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.7` `5_7` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.8` `5_8` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5.9` `5_9` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.1` `6_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.2` `6_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.2.1` `6_2_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.2.2` `6_2_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.2.3` `6_2_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.2.4` `6_2_4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.3.1` `6_3_1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.3.2` `6_3_2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.3.3` `6_3_3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.7` `6_7` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6.8` `6_8` tinyint(1);');
            
            DB::statement('ALTER TABLE bsif ADD COLUMN `2_11` tinyint(1) NULL AFTER `2_2_4`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_7_1` tinyint(1) NULL AFTER `3_3`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_7_2` tinyint(1) NULL AFTER `3_7_1`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_8_1` tinyint(1) NULL AFTER `3_7_2`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_8_2` tinyint(1) NULL AFTER `3_8_1`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_8_3` tinyint(1) NULL AFTER `3_8_2`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_8_4` tinyint(1) NULL AFTER `3_8_3`');
            DB::statement('ALTER TABLE bsif ADD COLUMN `3_8_5` tinyint(1) NULL AFTER `3_8_4`');
            
            
            // $table->renameColumn('2.1.1', '2_1_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsif', function (Blueprint $table) {
            DB::statement('ALTER TABLE bsif CHANGE `2_1_1` `2.1.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_1_2` `2.1.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_1_3` `2.1.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_1_4` `2.1.4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_2_1` `2.2.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_2_2` `2.2.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_2_3` `2.2.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_2_4` `2.2.4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `2_12` `2.12` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3_1` `3.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3_1_1` `3.1.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3_2` `3.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `3_3` `3.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4_1` `4.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4_1_1` `4.1.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4_2` `4.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4_3` `4.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `4_4` `4.4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_1` `5.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_5` `5.5` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_6` `5.6` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_7` `5.7` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_8` `5.8` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `5_9` `5.9` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_1` `6.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_2` `6.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_2_1` `6.2.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_2_2` `6.2.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_2_3` `6.2.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_2_4` `6.2.4` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_3_1` `6.3.1` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_3_2` `6.3.2` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_3_3` `6.3.3` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_7` `6.7` tinyint(1);');
            DB::statement('ALTER TABLE bsif CHANGE `6_8` `6.8` tinyint(1);');
            
            DB::statement('ALTER TABLE bsif DROP COLUMN `2_11`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_7_1`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_7_2`');            
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_8_1`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_8_2`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_8_3`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_8_4`');
            DB::statement('ALTER TABLE bsif DROP COLUMN `3_8_5`');            
            
            // $table->renameColumn('2_1_1', '2.1.1');

        });
    }
}
