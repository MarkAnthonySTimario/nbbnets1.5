<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransfusionConfig extends Model
{
    protected $table = 'r_bts_facility_config';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'facility_cd';
}
