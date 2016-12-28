<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Ref\ARef;

use Interpro\Core\Contracts\Executor\OwnSynchronizer as OwnSynchronizerInterface;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\SyncException;
use Interpro\Scalar\Model\Bool;
use Interpro\Scalar\Model\Float;
use Interpro\Scalar\Model\Int;
use Interpro\Scalar\Model\String;
use Interpro\Scalar\Model\Text;

class Synchronizer implements OwnSynchronizerInterface
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
     *
     * @return void
     */
    public function sync(ARef $ref, OwnField $own)
    {
        $ownerType = $ref->getType();
        $owner_type_name = $ownerType->getName();
        $own_name = $own->getName();
        $id = $ref->getId();
        $field_type_name = $own->getFieldTypeName();

        if($field_type_name === 'int')
        {
            $model = Int::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'string')
        {
            $model = String::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'text')
        {
            $model = Text::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'float')
        {
            $model = Float::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'bool')
        {
            $model = Bool::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        else
        {
            throw new SyncException('Scalar синхронизатор не обрабатывает тип '.$field_type_name);
        }

        if(!$model)
        {
            if($field_type_name === 'int')
            {
                Int::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => 0]);
            }
            elseif($field_type_name === 'string')
            {
                String::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => '']);
            }
            elseif($field_type_name === 'text')
            {
                Text::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => '']);
            }
            elseif($field_type_name === 'float')
            {
                Float::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => 0]);
            }
            elseif($field_type_name === 'bool')
            {
                Bool::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => false]);
            }
        }
    }

}
