<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Component\Validator;

use Goksagun\ApiBundle\Component\Validator\Exception\ViolationHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractValidation implements ValidationInterface
{
    const DEFAULT_METHOD = '__invoke';
    const METHOD_PREFIX = 'validate';
    const METHOD_SUFFIX = 'Action';

    protected Request $request;

    public function __toString(): string
    {
        return get_class($this);
    }

    public function run(Request $request = null, string $action = null): void
    {
        $this->request = $request;

        $input = null;
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

            $input = $request->isMethod('GET') ? $request->query->all() : $request->request->all();
        }

        $method = $this->getMethod($action);

        $options = $this->$method($input);

        $constraint = new Assert\Collection($options);

        $this->validate($input, $constraint);
    }

    public function validate(array $input, Assert\Collection $constraint): void
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate($input, $constraint);

        if (count($violations)) {
            throw new ViolationHttpException($violations);
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