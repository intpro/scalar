<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class BoolModel extends Model
{
    public $timestamps = false;
    protected $table = 'bools';
    protected static $unguarded = true;
}
