<?php

namespace Interpro\Scalar\Model;

use Illuminate\Database\Eloquent\Model;

class TextModel extends Model
{
    public $timestamps = false;
    protected $table = 'texts';
    protected static $unguarded = true;
}
