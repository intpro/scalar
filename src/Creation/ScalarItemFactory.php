<?php

namespace Interpro\Scalar\Creation;

use Interpro\Core\Contracts\Taxonomy\Types\CType;
use Interpro\Extractor\Contracts\Creation\CItemFactory;
use Interpro\Extractor\Items\CItem;
use Interpro\Scalar\Exception\ScalarException;

class ScalarItemFactory implements CItemFactory
{
    private $cap_values;

    public function __construct()
    {
        $this->cap_values = ['int'=>0, 'string'=>'пусто', 'bool'=>false, 'text'=>'пусто', 'float'=>0];
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Types\CType $type
     * @param mixed $value
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem COwnItem
     */
    public function create(CType $type, $value)
    {
        $type_name = $type->getName();

        if($type_name === 'int')
        {
            $value = (int) $value;
        }
        elseif($type_name === 'string')
        {
            $value = (string) $value;
        }
        elseif($type_name === 'bool')
        {
            $value = (bool) $value;
        }
        elseif($type_name === 'float')
        {
            $value = (float) $value;
        }
        elseif($type_name === 'text')
        {
            $value = (string) $value;
        }

        $item = new CItem($type, $value, false);

        return $item;
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Types\CType $type
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem COwnItem
     */
    public function createCap(CType $type)
    {
        $type_name = $type->getName();

        if(!array_key_exists($type_name, $this->cap_values))
        {
            throw new ScalarException('Создание заглушки типа '.$type_name.' в пакете scalar не поддерживается!');
        }

        $value = $this->cap_values[$type_name];

        $item = new CItem($type, $value, true);

        return $item;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }

}
