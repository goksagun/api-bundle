<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator\Annotation;

/**
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Validate
{
    /**
     * @Required()
     */
    public $class;

    public function getClass(): string
    {
        return $this->class;
    }
}