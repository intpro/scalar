<?php

namespace Interpro\Scalar\Db;

use Illuminate\Support\Facades\DB;
use Interpro\Core\Contracts\Taxonomy\Fields\Field;
use Interpro\Extractor\Contracts\Db\Joiner;
use Interpro\Extractor\Db\QueryBuilder;
use Interpro\Scalar\Exception\ScalarException;

class ScalarJoiner implements Joiner
{
    private $table_names;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->table_names  = [
            'int'    => 'ints',
            'timestamp'    => 'timestamps',
            'string' => 'strings',
            'text'   => 'texts',
            'float'  => 'floats',
            'bool'   => 'bools'
        ];
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Fields\Field $field
     * @param array $join_array
     *
     * @return \Interpro\Extractor\Db\QueryBuilder
     */
    public function joinByField(Field $field, $join_array)
    {
        $fieldType = $field->getFieldType();
        $type_name = $fieldType->getName();
        $field_name = $field->getName();

        $table_name = '';

        if(array_key_exists($type_name, $this->table_names))
        {
            $table_name = $this->table_names[$type_name];
        }
        else
        {
            throw new ScalarException('Нет соответствия таблицы для типа '.$type_name.'!');
        }

        $join_q = new QueryBuilder(DB::table($table_name));

        $join_q->select([$table_name.'.entity_name', $table_name.'.entity_id', $table_name.'.value as '.$join_array['full_field_names'][0]]);//Законцовка - в массиве только одно поле x_..x_id
        $join_q->whereRaw($table_name.'.name = "'.$field_name.'"');

        return $join_q;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }
}
