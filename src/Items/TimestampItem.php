<?php

namespace Interpro\Scalar\Items;

use Interpro\Core\Contracts\Taxonomy\Types\CType;
use Interpro\Extractor\Items\CItem;

class TimestampItem extends CItem
{
    private $offset;

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Types\CType $type
     * @param mixed $value
     * @param int $offset
     * @param bool $cap
     *
     * @return void
     */
    public function __construct(CType $type, $value, $offset = 0, $cap = false)
    {
        $this->offset = $offset;

        //$value = $value; //преобразовать, если надо

        parent::__construct($type, $value, $cap);
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getDate($format = 'd.m.Y')
    {
        return date($format, ($this->getValue() + ($this->offset * 3600)));
    }

}
