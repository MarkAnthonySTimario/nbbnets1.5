<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    protected $table = 'don_r7';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'seqno';

}
