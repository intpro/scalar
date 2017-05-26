<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class TimestampModel extends Model
{
    public $timestamps = false;
    protected static $unguarded = true;
}
