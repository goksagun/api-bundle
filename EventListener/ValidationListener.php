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
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(ContainerInterface $container, Reader $reader)
    {
        $this->container = $container;
        $this->reader = $reader;
    }

    public function onKernelController(ControllerEvent $event)
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

        $validateAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, Validate::class);

        if (null === $validateAnnotation) {
            try {
                $reflectionClass = new \ReflectionClass($class);
            } catch (\ReflectionException $e) {
                return;
            }

            $validateAnnotation = $this->reader->getClassAnnotation($reflectionClass, Validate::class);
        }

        if (null === $validateAnnotation) {
            return;
        }

        $validationClass = $validateAnnotation->getClass();

        $validation = $this->container->get($validationClass);

        if (!$validation instanceof AbstractValidation) {
            return;
        }

        $validation->run($request, $action);
    }
}