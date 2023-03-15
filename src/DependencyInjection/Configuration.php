<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('api');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('validation')
                    ->children()
                        ->scalarNode('type')->defaultValue('annotation')->end()
                    ->end()
                ->end() // validation
            ->end()
        ;

        return $treeBuilder;
    }
}