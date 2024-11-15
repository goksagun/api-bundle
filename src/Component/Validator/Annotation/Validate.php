<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator\Annotation;

use Symfony\Contracts\Service\Attribute\Required;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Validate
{
    /**
     * The class name of the validation.
     *
     * @var string
     */
    #[Required]
    public $class;

    public function __construct(string|array $class)
    {
        if (is_array($class)) {
            $this->class = reset($class);
        } else {
            $this->class = $class;
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }
}