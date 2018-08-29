<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNetworkIntentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('networking_intent',function(Blueprint $t){
            $t->boolean('serve')->default(false)->after('by');
            $t->string('request_no',10)->nullable()->after('serve');
            $t->string('reserved_by',30)->after('request_no')->nullable();
            $t->datetime('reserved_dt')->after('reserved_by')->nullable();
            $t->string('released_by',30)->after('reserved_dt')->nullable();
            $t->datetime('released_dt')->after('released_by')->nullable();
            $t->string('verified_by',30)->after('released_dt')->nullable();
            $t->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('networking_intent',function(Blueprint $t){
            $t->dropColumn('serve');
            $t->dropColumn('request_no');
            $t->dropColumn('reserved_by');
            $t->dropColumn('reserved_dt');
            $t->dropColumn('released_by');
            $t->dropColumn('released_dt');
            $t->dropColumn('verified_by');
            $t->dropSoftDeletes();
        });
    }
}
