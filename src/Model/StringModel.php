<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class StringModel extends Model
{
    public $timestamps = false;
    protected $table = 'strings';
    protected static $unguarded = true;
}
