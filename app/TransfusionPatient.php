<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransfusionPatient extends Model
{
    protected $table = 'bts_patient';
    public $incrementing = false;
    public $timestamps = false;
}
