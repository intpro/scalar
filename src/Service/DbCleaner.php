<?php

namespace Interpro\Scalar\Service;

use Interpro\Core\Contracts\Taxonomy\Taxonomy;
use Interpro\Core\Taxonomy\Enum\TypeRank;
use Interpro\Scalar\Model\Bool;
use Interpro\Scalar\Model\Float;
use Interpro\Scalar\Model\Int;
use Interpro\Scalar\Model\String;
use Interpro\Scalar\Model\Text;
use Interpro\Scalar\Model\Timestamp;
use Interpro\Service\Contracts\Cleaner as CleanerInterface;
use Interpro\Service\Enum\Artefact;

class DbCleaner implements CleanerInterface
{
    private $taxonomy;
    private $consoleOutput;

    public function __construct(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
        $this->consoleOutput = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    /**
     * @param callable $action
     *
     * @return bool
     */
    private function strategy(callable $action)
    {
        $report = false;

        $tables = [
            'string' => String::class,
            'text' => Text::class,
            'bool' => Bool::class,
            'float' => Float::class,
            'int' => Int::class,
            'timestamp' => Timestamp::class];

        foreach($tables as $type_name => $modelClass)
        {
            $wehave = $modelClass::all();

            foreach($wehave as $model)
            {
                $entity_name = $model->entity_name;
                $name        = $model->name;

                if(!$this->taxonomy->exist($entity_name))
                {
                    $action(1, $type_name, $model);
                    $report = true;
                }
                else
                {
                    $ownerType = $this->taxonomy->getType($entity_name);

                    if($ownerType->getRank() === TypeRank::OWN)
                    {
                        $action(2, $type_name, $model);
                        $report = true;
                    }
                    elseif(!$ownerType->ownExist($name))
                    {
                        $action(3, $type_name, $model);
                        $report = true;
                    }
                    elseif($ownerType->getFieldType($name) !== $this->taxonomy->getType($type_name))
                    {
                        $action(4, $type_name, $model);
                        $report = true;
                    }
                }
            }
        }

        return $report;
    }

    /**
     * @return bool
     */
    public function inspect()
    {
        $action = function($flag, $class_name, $model)
        {
            $entity_name = $model->entity_name;
            $entity_id   = $model->entity_id;
            $name        = $model->name;

            if($flag === 1)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): обнаружена запись для типа хозяина'.$entity_name.' не найденого в таксономии.';
            }
            elseif($flag === 2)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): обнаружена запись для типа хозяина'.$entity_name.' не соответствующего ранга.';
            }
            elseif($flag === 3)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): обнаружена запись несуществующего поля '.$name.' для хозяина '.$entity_name.'.';
            }
            elseif($flag === 4)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): обнаружена запись несуществующего поля '.$name.' для хозяина '.$entity_name.'.';
            }
            else
            {
                return;
            }

            $this->consoleOutput->writeln($message);
        };

        $report = $this->strategy($action);

        return $report;
    }

    /**
     * @return void
     */
    public function clean()
    {
        $action = function($flag, $class_name, $model)
        {
            $entity_name = $model->entity_name;
            $entity_id   = $model->entity_id;
            $name        = $model->name;

            $model->delete();

            if($flag === 1)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): удалена запись для типа хозяина'.$entity_name.' не найденого в таксономии.';
            }
            elseif($flag === 2)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): удалена запись для типа хозяина'.$entity_name.' не соответствующего ранга.';
            }
            elseif($flag === 3)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): удалена запись несуществующего поля '.$name.' для хозяина '.$entity_name.'.';
            }
            elseif($flag === 4)
            {
                $message = 'Scalar '.$class_name.'('.$entity_id.'): удалена запись несуществующего поля '.$name.' для хозяина '.$entity_name.'.';
            }
            else
            {
                return;
            }

            $this->consoleOutput->writeln($message);
        };

        $this->strategy($action);
    }

    /**
     * @return string
     */
    public function getArtefact()
    {
        return Artefact::DB_ROW;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'scalar';
    }
}
