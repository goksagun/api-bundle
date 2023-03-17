<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GoksagunApiBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}