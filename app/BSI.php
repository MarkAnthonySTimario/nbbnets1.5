<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BSI extends Model
{
    protected $primaryKey= 'seqno';
    public $timestamps = false;
    protected $fillable = ['2_1_1', '2_1_2', '2_1_3', '2_1_4', '2_2_1', '2_2_2', '2_2_3', '2_2_4', '2_11', '2_12', '3_1', '3_1_1', '3_2', '3_3', '3_8_1', '3_8_2', '3_8_3', '3_8_4', '3_8_5', '4_1', '4_1_1', '4_2', '4_3', '4_4', '5_1', '5_5', '5_6', '5_7', '5_8', '5_9', '6_1', '6_2', '6_2_1', '6_2_2', '6_2_3', '6_2_4', '6_3_1', '6_3_2', '6_3_3', '6_7', '6_8','facility_cd','created_dt','created_by'];
    
    protected $table = "bsif";
}
