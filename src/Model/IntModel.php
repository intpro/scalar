<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class IntModel extends Model
{
    public $timestamps = false;
    protected $table = 'ints';
    protected static $unguarded = true;
}
