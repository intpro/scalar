<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CDestructor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Scalar\Model\Bool;
use Interpro\Scalar\Model\Float;
use Interpro\Scalar\Model\Int;
use Interpro\Scalar\Model\String;
use Interpro\Scalar\Model\Text;

class Destructor implements CDestructor
{
    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     *
     * @return void
     */
    public function delete(ARef $ref)
    {
        $type      = $ref->getType();
        $type_name = $type->getName();
        $id        = $ref->getId();

        String::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        Int::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        Text::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        Float::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        Bool::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
    }
}
