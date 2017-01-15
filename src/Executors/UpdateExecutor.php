<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CUpdateExecutor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Scalar\Exception\ScalarException;
use Interpro\Scalar\Model\Bool;
use Interpro\Scalar\Model\Float;
use Interpro\Scalar\Model\Int;
use Interpro\Scalar\Model\String;
use Interpro\Scalar\Model\Text;

class UpdateExecutor implements CUpdateExecutor
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
     * @param \Interpro\Core\Contracts\Taxonomy\Fields\OwnField $own
     * @param mixed $value
     *
     * @return void
     */
    public function update(ARef $ref, OwnField $own, $value)
    {
        $type          = $ref->getType();
        $type_name     = $type->getName();
        $id            = $ref->getId();
        $own_type_name = $own->getFieldTypeName();
        $own_name      = $own->getName();

        if($own_type_name === 'int')
        {
            $value = (int) $value;

            $field = Int::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'string')
        {
            $value = (string) $value;

            $field = String::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'text')
        {
            $value = (string) $value;

            $field = Text::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'float')
        {
            $value = (float) $value;

            $field = Float::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'bool')
        {
            $value = (bool) $value;

            $field = Bool::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        else
        {
            throw new ScalarException('Scalar не обрабатывает тип '.$type_name);
        }

        $field->value = $value;
        $field->save();
    }
}
