<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle;

use Goksagun\ApiBundle\Component\Validator\ValidationInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class GoksagunApiBundle extends AbstractBundle
{
    protected string $extensionAlias = 'api';

    public function configure(DefinitionConfigurator $definition): void
    {
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        $builder->registerForAutoconfiguration(ValidationInterface::class)
            ->setPublic(true)
            ->addTag('api.validator');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}