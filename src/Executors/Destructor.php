<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CDestructor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Scalar\Model\BoolModel;
use Interpro\Scalar\Model\FloatModel;
use Interpro\Scalar\Model\IntModel;
use Interpro\Scalar\Model\StringModel;
use Interpro\Scalar\Model\TextModel;
use Interpro\Scalar\Model\TimestampModel;

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

        StringModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        IntModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        TextModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        FloatModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        BoolModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
        TimestampModel::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
    }
}
