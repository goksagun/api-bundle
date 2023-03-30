<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Goksagun\ApiBundle\Component\Validator\ValidationInterface;
use Goksagun\ApiBundle\EventListener\ExceptionListener;
use Goksagun\ApiBundle\EventListener\StringToJsonTransformListener;
use Goksagun\ApiBundle\EventListener\TrashedListener;
use Goksagun\ApiBundle\EventListener\ValidationListener;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(ValidationInterface::class)
        ->tag('api.validator');

    $services
        ->set(StringToJsonTransformListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.controller', 'method' => 'onKernelController']);

    $services
        ->set(ValidationListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.controller', 'method' => 'onKernelController'])
        ->args([service('service_container')])
    ;

    $services
        ->set(ExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException']);

    $services
        ->set(TrashedListener::class)
        ->tag('doctrine.event_listener', ['event' => 'preFlush', 'method' => 'preFlushe']);
};
