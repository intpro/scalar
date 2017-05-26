<?php

namespace Interpro\Scalar\Executors;

use Interpro\Core\Contracts\Ref\ARef;

use Interpro\Core\Contracts\Executor\OwnSynchronizer as OwnSynchronizerInterface;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\SyncException;
use Interpro\Scalar\Model\BoolModel;
use Interpro\Scalar\Model\FloatModel;
use Interpro\Scalar\Model\IntModel;
use Interpro\Scalar\Model\StringModel;
use Interpro\Scalar\Model\TextModel;
use Interpro\Scalar\Model\TimestampModel;

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
            $model = IntModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'timestamp')
        {
            $model = TimestampModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'string')
        {
            $model = StringModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'text')
        {
            $model = TextModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'float')
        {
            $model = FloatModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        elseif($field_type_name === 'bool')
        {
            $model = BoolModel::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();
        }
        else
        {
            throw new SyncException('Scalar синхронизатор не обрабатывает тип '.$field_type_name);
        }

        if(!$model)
        {
            if($field_type_name === 'int')
            {
                IntModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => 0]);
            }
            elseif($field_type_name === 'timestamp')
            {
                TimestampModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => time()]);
            }
            elseif($field_type_name === 'string')
            {
                StringModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => '']);
            }
            elseif($field_type_name === 'text')
            {
                TextModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => '']);
            }
            elseif($field_type_name === 'float')
            {
                FloatModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => 0]);
            }
            elseif($field_type_name === 'bool')
            {
                BoolModel::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => false]);
            }
        }
    }

}
