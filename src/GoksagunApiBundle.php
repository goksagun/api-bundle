<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class GoksagunApiBundle extends AbstractBundle
{
    const VALIDATION_TYPE_ANNOTATION = 'annotation';
    const VALIDATION_TYPE_ATTRIBUTE = 'attribute';

    protected string $extensionAlias = 'api';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('validation')
                    ->children()
                       ->scalarNode('type')->defaultValue('annotation')->end()
                    ->end()
                ->end() // validation
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.xml');

        $container->services()
            ->get('api.listener.validation_listener')
            ->arg(2, $config['validation']['type'] ?? self::VALIDATION_TYPE_ANNOTATION)
        ;
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}