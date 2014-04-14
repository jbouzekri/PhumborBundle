<?php

namespace Jb\Bundle\PhumborBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jb_phumbor');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('server')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('url')
                            ->defaultValue('http://localhost')
                        ->end()
                        ->scalarNode('secret')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}