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

    public function __construct(ContainerInterface $container, Reader $reader, string $type = 'annotation')
    {
        $this->container = $container;
        $this->reader = $reader;
        $this->type = $type;
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

        if ('attribute' === $this->type) {
            $validateAnnotation = $reflectionMethod->getAttributes(Validate::class);
        } else {
            $validateAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, Validate::class);
        }

        if (null === $validateAnnotation) {
            try {
                $reflectionClass = new \ReflectionClass($class);
            } catch (\ReflectionException $e) {
                return;
            }

            if ('attribute' === $this->type) {
                $validateAnnotation = $reflectionClass->getAttributes(Validate::class);
            } else {
                $validateAnnotation = $this->reader->getClassAnnotation($reflectionClass, Validate::class);
            }
        }

        if (null === $validateAnnotation || [] === $validateAnnotation) {
            return;
        }

        if ('attribute' === $this->type) {
            $validationClass = $validateAnnotation[0]->getArguments()['class'] ?? $validateAnnotation[0]->getArguments()[0];
        } else {
            $validationClass = $validateAnnotation->getClass();
        }

        $validation = $this->container->get($validationClass);

        if (!$validation instanceof AbstractValidation) {
            return;
        }

        $validation->run($request, $action);
    }
}