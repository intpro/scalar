<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CInitializer;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\InitException;
use Interpro\Scalar\Model\BoolModel;
use Interpro\Scalar\Model\FloatModel;
use Interpro\Scalar\Model\IntModel;
use Interpro\Scalar\Model\StringModel;
use Interpro\Scalar\Model\TextModel;
use Interpro\Scalar\Model\TimestampModel;

class Initializer implements CInitializer
{
    public function __construct()
    {
    }

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
    public function init(ARef $ref, OwnField $own, $value = null)
    {
        $type          = $ref->getType();
        $type_name     = $type->getName();
        $id            = $ref->getId();
        $own_type_name = $own->getFieldTypeName();
        $own_name      = $own->getName();

        if($own_type_name === 'int')
        {
            if($value === null)
            {
                $value = 0;
            }
            else
            {
                $value = (int) $value;
            }

            $field = IntModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'timestamp')
        {
            if($value === null)
            {
                $value = time();
            }
            else
            {
                $value = strtotime(((string) $value));
            }

            $field = TimestampModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'string')
        {
            if($value === null)
            {
                $value = '';
            }
            else
            {
                $value = (string) $value;
            }

            $field = StringModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'text')
        {
            if($value === null)
            {
                $value = '';
            }
            else
            {
                $value = (string) $value;
            }

            $field = TextModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'float')
        {
            if($value === null)
            {
                $value = 0;
            }
            else
            {
                $value = (float) $value;
            }

            $field = FloatModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'bool')
        {
            if($value === null)
            {
                $value = false;
            }
            else
            {
                $value = (bool) $value;
            }

            $field = BoolModel::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        else
        {
            throw new InitException('Scalar не обрабатывает тип '.$type_name);
        }

        $field->value = $value;
        $field->save();
    }
}
