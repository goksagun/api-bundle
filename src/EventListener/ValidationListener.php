<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Goksagun\ApiBundle\Component\Validator\AbstractValidation;
use Goksagun\ApiBundle\Component\Validator\Annotation\Validate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ValidationListener
{
    protected ContainerInterface $container;

    protected Reader $reader;

    protected string $type;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        // else returned $controllerInstance
        if (is_array($controller)) {
            $class = $controller[0];
            $action = $controller[1];
        } else {
            $class = $controller;
            $action = AbstractValidation::DEFAULT_METHOD;
        }

        try {
            $reflectionMethod = new \ReflectionMethod($class, $action);
        } catch (\ReflectionException $e) {
            return;
        }

        $reflectionAttributes = $reflectionMethod->getAttributes(Validate::class);

        if ([] === $reflectionAttributes) {
            try {
                $reflectionClass = new \ReflectionClass($class);
            } catch (\ReflectionException $e) {
                return;
            }

            $reflectionAttributes = $reflectionClass->getAttributes(Validate::class);
        }

        if ([] === $reflectionAttributes) {
            return;
        }

        $validationClass = $reflectionAttributes[0]->getArguments()['class'] ?? $reflectionAttributes[0]->getArguments()[0];

        $validation = $this->container->get($validationClass);

        if (!$validation instanceof AbstractValidation) {
            return;
        }

        $validation->run($request, $action);
    }
}