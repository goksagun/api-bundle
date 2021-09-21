<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationHttpException extends HttpException
{
    protected $violations;

    /**
     * @param ConstraintViolationListInterface $violations The violation errors
     * @param string $message The internal exception message
     * @param \Exception $previous The previous exception
     * @param int $code The internal exception code
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        $message = 'Validation failed!',
        \Exception $previous = null,
        $code = 4022
    ) {
        parent::__construct(422, $message, $previous, [], $code);

        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }
}