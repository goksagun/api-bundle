<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator\Exception;

use Goksagun\ApiBundle\Component\Validator\Scope;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationHttpException extends HttpException
{
    protected ConstraintViolationListInterface $violations;

    protected Scope $scope;

    /**
     * @param ConstraintViolationListInterface $violations The violation errors
     * @param Scope $scope The scope of the request
     * @param string $message The internal exception message
     * @param \Exception|null $previous The previous exception
     * @param int $code The internal exception code
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        Scope $scope,
        string $message = 'Validation failed!',
        \Exception $previous = null,
        int $code = 4022,
    ) {
        parent::__construct(422, $message, $previous, [], $code);

        $this->violations = $violations;
        $this->scope = $scope;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }

    public function getScope(): string
    {
        return $this->scope->value;
    }
}