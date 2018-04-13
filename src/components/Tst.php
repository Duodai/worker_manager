<?php
declare(strict_types=1);


namespace duodai\worman\components;

use DI\ContainerBuilder;
use function DI\create;
use Psr\Log\LoggerInterface;

class Tst
{
    public function tst()
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);
        $builder->addDefinitions([
            LoggerInterface::class => create(DummyLogger::class)
        ]);
        $container = $builder->build();
        $logger = $container->get(LoggerInterface::class);
        echo '<pre>'; var_dump($logger); echo '</pre>'; exit; //TODO Remove debug
    }

}