<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Blood;

class UpdateComponentAddDateOfCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("component",function(Blueprint $t){
            $t->date('collection_dt')->after('location')->nullable();
        });

        $bloods = Blood::with('donation_min.mbd')
                    ->whereExpirationDt(date('Y-m-d H:i:s'))
                    ->get();

        foreach($bloods as $blood){
            if($blood->donation_min->sched_id != 'Walk-in'){
                $collection_dt = $blood->donation_min->mbd->donation_dt;
            }else{
                $collection_dt = $blood->donation_min->created_dt;
            }
            DB::statement(DB::raw("UPDATE component SET collection_dt = '".$collection_dt."' WHERE donation_id = '".$blood->donation_id."' AND component_cd = '".$blood->component_cd."'"));
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("component",function(Blueprint $t){
            $t->dropColumn('collection_dt');
        });
    }
}
