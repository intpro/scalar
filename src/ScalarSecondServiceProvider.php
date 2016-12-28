<?php

namespace Interpro\Scalar;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Interpro\Core\Contracts\Mediator\DestructMediator;
use Interpro\Core\Contracts\Mediator\InitMediator;
use Interpro\Core\Contracts\Mediator\SyncMediator;
use Interpro\Core\Contracts\Mediator\UpdateMediator;
use Interpro\Extractor\Contracts\Creation\CItemBuilder;
use Interpro\Extractor\Contracts\Db\JoinMediator;
use Interpro\Extractor\Contracts\Db\MappersMediator;
use Interpro\Extractor\Contracts\Selection\Tuner;
use Interpro\Scalar\Creation\ScalarItemFactory;
use Interpro\Scalar\Db\ScalarCMapper;
use Interpro\Scalar\Db\ScalarJoiner;
use Interpro\Scalar\Executors\Destructor;
use Interpro\Scalar\Executors\Initializer;
use Interpro\Scalar\Executors\Synchronizer;
use Interpro\Scalar\Executors\UpdateExecutor;

class ScalarSecondServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(Dispatcher $dispatcher,
                         MappersMediator $mappersMediator,
                         JoinMediator $joinMediator,
                         CItemBuilder $cItemBuilder,
                         InitMediator $initMediator,
                         SyncMediator $syncMediator,
                         UpdateMediator $updateMediator,
                         DestructMediator $destructMediator,
                         Tuner $tuner)
    {
        Log::info('Загрузка ScalarSecondServiceProvider');

        //Фабрике нужен медиатор мапперов и строитель item'ов простых типов, QS мапперу нужна фабрика
        $factory = new ScalarItemFactory();
        $mapper = new ScalarCMapper($factory, $tuner);
        $cItemBuilder->addFactory($factory);

        $mappersMediator->registerCMapper($mapper);

        //joiner нужен для объединения в запросах,
        //при использовании сортировок и фильтров, здесь через поле скалярного типа
        $joiner = new ScalarJoiner();
        $joinMediator->registerJoiner($joiner);

        $initializer = new Initializer();
        $initMediator->registerCInitializer($initializer);

        $synchronizer = new Synchronizer();
        $syncMediator->registerOwnSynchronizer($synchronizer);

        $updateExecutor = new UpdateExecutor();
        $updateMediator->registerCUpdateExecutor($updateExecutor);

        $destructor = new Destructor();
        $destructMediator->registerCDestructor($destructor);
    }

    /**
     * @return void
     */
    public function register()
    {
        Log::info('Регистрация ScalarSecondServiceProvider');

        $config = [
            'bool' => 'Булев тип',
            'int' => 'Целочисленный тип',
            'float' => 'Вещественный тип',
            'string' => 'Строка',
            'text' => 'Текст'
        ];

        $typeRegistrator = App::make('Interpro\Core\Contracts\Taxonomy\TypeRegistrator');

        $configInterpreter = new ConfigInterpreter();

        $manifests = $configInterpreter->interpretConfig($config);

        foreach($manifests as $manifest)
        {
            $typeRegistrator->registerType($manifest);
        }
    }

}
