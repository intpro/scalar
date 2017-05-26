<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CUpdateExecutor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Scalar\Exception\ScalarException;
use Interpro\Scalar\Model\BoolModel;
use Interpro\Scalar\Model\FloatModel;
use Interpro\Scalar\Model\IntModel;
use Interpro\Scalar\Model\StringModel;
use Interpro\Scalar\Model\TextModel;
use Interpro\Scalar\Model\TimestampModel;

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

            $field = IntModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'timestamp')
        {
            $value = strtotime(((string) $value));

            $field = TimestampModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'string')
        {
            $value = (string) $value;

            $field = StringModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'text')
        {
            $value = (string) $value;

            $field = TextModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'float')
        {
            $value = (float) $value;

            $field = FloatModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'bool')
        {
            $value = (bool) $value;

            $field = BoolModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        else
        {
            throw new ScalarException('Scalar не обрабатывает тип '.$type_name);
        }

        $field->value = $value;
        $field->save();
    }
}
