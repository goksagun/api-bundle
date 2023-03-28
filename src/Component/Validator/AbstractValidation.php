<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator;

use Goksagun\ApiBundle\Component\Validator\Exception\ViolationHttpException;
use Goksagun\ApiBundle\Utils\ArrayUtils;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractValidation implements ValidationInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const DEFAULT_METHOD = '__invoke';
    const METHOD_PREFIX = 'validate';
    const METHOD_SUFFIX = 'Action';

    protected Request $request;

    protected Scope $scope = Scope::REQUEST;

    public function __toString(): string
    {
        return get_class($this);
    }

    public function run(Request $request = null, string $action = null): void
    {
        $this->request = $request;

        if (null !== $request) {
            if ($field = $request->query->get('field')) {
                if (!is_array($field)) {
                    $this->convertStringToArrayForFieldKeyword();
                }
            }

            if ($embed = $request->query->get('embed')) {
                if (!is_array($embed)) {
                    $this->convertStringToArray('embed');
                }
            }

            if ($sort = $request->query->get('sort')) {
                if (!is_array($sort)) {
                    $this->convertStringToArray('sort');
                }
            }
        }

        $method = $this->getMethod($action);

        $this->logger->debug(sprintf('Matched method is "%s".', $method));

        $options = $this->$method();

        $this->logger->debug('Options:', $options);

        if (!in_array($this->scope, Scope::cases())) {
            throw new \InvalidArgumentException(sprintf('The scope "%s" is invalid!', $this->scope->value));
        }

        $this->logger->debug(sprintf('The scope matched as "%s"', $this->scope->value));

        $inputKeys = array_keys($options);

        $input = match ($this->scope) {
            Scope::QUERY => $request->query->all(),
            Scope::REQUEST => $this->request->request->all(),
            Scope::HEADERS => $request->headers->all(),
            Scope::ATTRIBUTES => ArrayUtils::only($this->request->attributes->all(), $inputKeys),
        };

        $this->logger->debug('Input:', $input);

        $constraint = new Assert\Collection($options);

        $this->validate($input, $constraint);
    }

    public function validate(array $input, Assert\Collection $constraint): void
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate($input, $constraint);

        if (count($violations)) {
            throw new ViolationHttpException($violations, $this->scope);
        }
    }

    public function convertStringToArray($key): void
    {
        $this->request->query->set($key, array_unique($this->parseStringAsArray($this->request->query->get($key))));
    }

    public function convertStringToArrayForFieldKeyword(): void
    {
        foreach ($field = $this->request->query->get('field') as $key => $value) {
            $field[$key] = array_unique($this->parseStringAsArray($field[$key]));
        }

        $this->request->query->set('field', $field);
    }

    private function getMethod(?string $action): string
    {
        if (self::DEFAULT_METHOD === $action) {
            return $action;
        }

        $method = self::METHOD_PREFIX . ucfirst($action);

        if (!method_exists($this, $method)) {
            $method = $method . self::METHOD_SUFFIX;
            if (!method_exists($this, $method)) {
                throw new \RuntimeException(sprintf('The "%s" method is missing in class "%s".', $method, $this));
            }
        }

        return $method;
    }

    private function parseStringAsArray(?string $value): array
    {
        if (is_null($value)) {
            return [];
        }

        return preg_split('/ ?[,|] ?/', $value);
    }
}