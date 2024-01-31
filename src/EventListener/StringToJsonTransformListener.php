<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StringToJsonTransformListener
{
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getContentTypeFormat() !== 'json' || !$content = $request->getContent()) {
            return;
        }

        $data = \json_decode($content, true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid json body: '.\json_last_error_msg());
        }

        $request->request->replace($data ?? []);
    }
}