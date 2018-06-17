<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonorLog extends Model
{
    protected $table = 'donor_log';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
