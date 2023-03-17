<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator\Annotation;

use Symfony\Contracts\Service\Attribute\Required;

/**
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Validate
{
    /**
     * The class name of the validation.
     *
     * @var string
     * @Required()
     */
    #[Required]
    public $class;

    /**
     * @param string|array $class
     */
    public function __construct($class)
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