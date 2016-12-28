<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Executor\CInitializer;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\InitException;
use Interpro\Scalar\Model\Bool;
use Interpro\Scalar\Model\Float;
use Interpro\Scalar\Model\Int;
use Interpro\Scalar\Model\String;
use Interpro\Scalar\Model\Text;

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

            if(!is_int($value))
            {
                throw new InitException('Scalar поле '.$own_name.' типа '.$own_type_name.' должно быть задано целым числом!');
            }

            $field = Int::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'string')
        {
            if($value === null)
            {
                $value = '';
            }

            if(!is_string($value))
            {
                throw new InitException('Scalar поле '.$own_name.' типа '.$own_type_name.' должно быть задано строкой!');
            }

            $field = String::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'text')
        {
            if($value === null)
            {
                $value = '';
            }

            if(!is_string($value))
            {
                throw new InitException('Scalar поле '.$own_name.' типа '.$own_type_name.' должно быть задано в текстовом виде!');
            }

            $field = Text::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'float')
        {
            if($value === null)
            {
                $value = 0;
            }

            if(!is_float($value) and !is_int($value))
            {
                throw new InitException('Scalar поле '.$own_name.' типа '.$own_type_name.' должно быть задано числом с пл. точкой!');
            }

            $field = Float::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        elseif($own_type_name === 'bool')
        {
            if($value === null)
            {
                $value = false;
            }

            if(!is_bool($value))
            {
                throw new InitException('Scalar поле '.$own_name.' типа '.$own_type_name.' должно быть задано булевым значением!');
            }

            $field = Bool::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        else
        {
            throw new InitException('Scalar не обрабатывает тип '.$type_name);
        }

        $field->value = $value;
        $field->save();
    }
}
