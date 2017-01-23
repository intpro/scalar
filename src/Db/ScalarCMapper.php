<?php

namespace Interpro\Scalar\Db;

use Illuminate\Support\Facades\DB;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Types\AType;
use Interpro\Core\Taxonomy\Enum\TypeRank;
use Interpro\Extractor\Contracts\Db\CMapper;
use Interpro\Extractor\Contracts\Selection\SelectionUnit;
use Interpro\Scalar\Collections\MapScalarCollection;
use Interpro\Scalar\Creation\ScalarItemFactory;
use Interpro\Extractor\Contracts\Selection\Tuner;

class ScalarCMapper implements CMapper
{
    private $factory;
    private $tables = [];
    private $units = [];
    private $tuner;

    public function __construct(ScalarItemFactory $factory, Tuner $tuner)
    {
        $this->factory = $factory;
        $this->tables = [
            'int' => 'ints',
            'timestamps' => 'timestamps',
            'string' => 'strings',
            'text' => 'texts',
            'float' => 'floats',
            'bool' => 'bools'
        ];
        $this->tuner = $tuner;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->units = [];
    }

    private function addResultToCollection($type_name, AType $ownerType, MapScalarCollection $collection, array $result)
    {
        foreach($result as $item_array)
        {
            $field_name = $item_array['name'];

            if($ownerType->fieldExist($field_name) and $ownerType->getFieldType($field_name)->getName() === $type_name)
            {
                $fieldType = $ownerType->getFieldType($field_name);

                $item = $this->factory->create($fieldType, $item_array['value']);

                $ref = new \Interpro\Core\Ref\ARef($ownerType, $item_array['entity_id']);

                $collection->addItem($ref, $field_name, $item);
            }
        }
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param bool $asUnitMember
     *
     * @return \Interpro\Extractor\Contracts\Collections\MapCCollection
     */
    public function getByRef(ARef $ref, $asUnitMember = false)
    {
        $ownerType = $ref->getType();
        $owner_name = $ownerType->getName();
        $rank = $ownerType->getRank();

        if($rank === TypeRank::GROUP and $asUnitMember)
        {
            $selectionUnit = $this->tuner->getSelection($owner_name, 'group');

            return $this->select($selectionUnit);
        }

        $owner_id = $ref->getId();

        $key = $owner_name.'_'.$owner_id;

        if(array_key_exists($key, $this->units))
        {
            return $this->units[$key];
        }

        $collection = new MapScalarCollection($this->factory);
        $this->units[$key] = $collection;

        foreach($this->tables as $type_name => $table)
        {
            $query = DB::table($table);
            $query->where($table.'.entity_name', '=', $owner_name);
            $query->where($table.'.entity_id', '=', $owner_id);

            $result = $query->get(['entity_name', 'entity_id', 'name', 'value']);

            $this->addResultToCollection($type_name, $ownerType, $collection, $result);
        }

        return $collection;
    }

    /**
     * @param \Interpro\Extractor\Contracts\Selection\SelectionUnit $selectionUnit
     *
     * @return \Interpro\Extractor\Contracts\Collections\MapCCollection
     */
    public function select(SelectionUnit $selectionUnit)
    {
        $ownerType = $selectionUnit->getType();

        $unit_number = $selectionUnit->getNumber();
        $key = 'unit_'.$unit_number;

        if(array_key_exists($key, $this->units))
        {
            return $this->units[$key];
        }

        $collection = new MapScalarCollection($this->factory);
        $this->units[$key] = $collection;

        foreach($this->tables as $type_name => $table)
        {
            $query = DB::table($table);
            $query->where($table.'.entity_name', '=', $selectionUnit->getTypeName());
            if($selectionUnit->closeToIdSet())
            {
                $query->whereIn($table.'.entity_id', $selectionUnit->getIdSet());
            }

            $result = $query->get(['entity_name', 'entity_id', 'name', 'value']);

            $this->addResultToCollection($type_name, $ownerType, $collection, $result);
        }

        return $collection;
    }
}
