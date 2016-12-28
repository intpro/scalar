<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class Int extends Model
{
    public $timestamps = false;
    protected static $unguarded = true;
}
