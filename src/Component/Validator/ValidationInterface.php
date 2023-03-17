<?php

namespace Goksagun\ApiBundle\Component\Validator;

use Symfony\Component\HttpFoundation\Request;

interface ValidationInterface
{
    public function run(Request $request = null, string $action = null): void;
}