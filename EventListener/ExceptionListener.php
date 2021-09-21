<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\EventListener;

use Goksagun\ApiBundle\Component\Validator\Exception\ViolationHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());

            $data = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];

            if ($exception instanceof ViolationHttpException) {
                /** @var ConstraintViolation $violation */
                foreach ($exception->getViolations() as $violation) {
                    $data['errors'][] = [
                        'code' => $violation->getCode(),
                        'message' => $violation->getMessage(),
                        'path' => $violation->getPropertyPath(),
                    ];
                }
            }

            $response->setData($data);
        } else {
            // for development error debug trace
            return;
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}