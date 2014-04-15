<?php

namespace Jb\Bundle\PhumborBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * PhumborBundle configuration structure.
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jb_phumbor');

        $rootNode
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

        $this->addTransformationSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add the transformation section configuration structure
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode
     */
    private function addTransformationSection(ArrayNodeDefinition $rootNode)
    {
        // Resize validate function. Resize value are int >= 0 or the string "orig"
        $validateResize = function ($v) {
            return $v !== "orig" && !is_int($v);
        };

        $rootNode
            ->children()
                ->arrayNode('transformation')
                    ->prototype('array')
                        // Filter key is a prototype array. Remove it if empty array
                        ->validate()
                            ->always(function($val) {
                                if (empty($val['filters'])) {
                                    unset($val['filters']);
                                }

                                return $val;})
                        ->end()
                        ->children()
                            ->scalarNode('trim')
                            ->end()
                            ->arrayNode('crop')
                                ->children()
                                    ->integerNode('top_left_x')->isRequired()->end()
                                    ->integerNode('top_left_y')->isRequired()->end()
                                    ->integerNode('bottom_right_x')->isRequired()->end()
                                    ->integerNode('bottom_right_y')->isRequired()->end()
                                ->end()
                            ->end()
                            ->arrayNode('fit_in')
                                ->children()
                                    ->integerNode('width')->isRequired()->end()
                                    ->integerNode('height')->isRequired()->end()
                                ->end()
                            ->end()
                            ->arrayNode('resize')
                                ->children()
                                    ->scalarNode('width')
                                        ->isRequired()
                                        ->validate()
                                            ->ifTrue($validateResize)
                                            ->thenInvalid(
                                                'Invalid transformation.resize.width value "%s". '
                                                    . 'It must be an integer or the string "orig"'
                                            )
                                        ->end()
                                    ->end()
                                    ->scalarNode('height')
                                        ->isRequired()
                                        ->validate()
                                            ->ifTrue($validateResize)
                                            ->thenInvalid(
                                                'Invalid transformation.resize.height value "%s". '
                                                    . 'It must be an integer or the string "orig"'
                                            )
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('halign')->end()
                            ->scalarNode('valign')->end()
                            ->booleanNode('smart_crop')->end()
                            ->arrayNode('filters')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->isRequired()->end()
                                        ->arrayNode('arguments')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}