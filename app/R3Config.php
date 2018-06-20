<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class R3Config extends Model
{
    protected $table = 'r3_config';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'facility_cd';
}
