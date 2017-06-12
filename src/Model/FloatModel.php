<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class FloatModel extends Model
{
    public $timestamps = false;
    protected $table = 'floats';
    protected static $unguarded = true;
}
