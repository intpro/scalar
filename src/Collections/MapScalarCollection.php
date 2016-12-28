<?php

namespace Interpro\Scalar\Collections;

use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Types\CType;
use Interpro\Extractor\Contracts\Collections\MapCCollection;
use Interpro\Extractor\Contracts\Items\COwnItem;
use Interpro\Scalar\Creation\ScalarItemFactory;

class MapScalarCollection implements MapCCollection
{
    private $items = [];
    private $caps = [];
    private $factory;

    public function __construct(ScalarItemFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }

    private function getCap(CType $type)
    {
        $type_name = $type->getName();

        if($type_name === 'bool')
        {
            $this->caps[$type_name] = $this->factory->create($type, false, true);
        }
        elseif($type_name === 'string')
        {
            $this->caps[$type_name] = $this->factory->create($type, 'пусто', true);
        }
        elseif($type_name === 'int')
        {
            $this->caps[$type_name] = $this->factory->create($type, 0, true);
        }
        elseif($type_name === 'text')
        {
            $this->caps[$type_name] = $this->factory->create($type, 'пусто', true);
        }
        else
        {
            throw new \Exception('Тип '.$type_name.' не поддерживается)');
        }

        return $this->caps[$type_name];
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param string $field_name
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem
     */
    public function getItem(ARef $ref, $field_name)
    {
        $ownerType = $ref->getType();
        $type_name = $ownerType->getName();
        $key = $field_name.'_'.$ref->getId();

        if(!array_key_exists($type_name, $this->items))
        {
            $this->items[$type_name] = [];
        }

        if(!array_key_exists($key, $this->items[$type_name]))
        {
            $fieldType = $ownerType->getFieldType($field_name);

            return $this->getCap($fieldType);
        }

        return $this->items[$type_name][$key];
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param string $field_name
     * @param \Interpro\Extractor\Contracts\Items\COwnItem $item
     *
     * @return void
     */
    public function addItem(ARef $ref, $field_name, COwnItem $item)
    {
        $ownerType = $ref->getType();
        $type_name = $ownerType->getName();
        $key = $field_name.'_'.$ref->getId();

        if(!array_key_exists($type_name, $this->items))
        {
            $this->items[$type_name] = [];
        }

        $this->items[$type_name][$key] = $item;
    }

}
