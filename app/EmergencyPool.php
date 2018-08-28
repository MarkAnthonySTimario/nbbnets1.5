<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyPool extends Model
{
    protected $primaryKey= 'id';
    public $timestamps = false;    
    protected $table = "emergency_pool";
}
