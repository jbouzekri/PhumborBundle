<?php

namespace Jb\Bundle\PhumborBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\HttpKernel\Kernel;

/**
 * PhumborBundle configuration structure.
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (Kernel::VERSION_ID >= 40200) {
            $treeBuilder = new TreeBuilder('jb_phumbor');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('jb_phumbor');
        }
        
        $rootNode
            ->children()
                ->arrayNode('server')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('url')
                            ->defaultValue('%env(THUMBOR_URL)%')
                        ->end()
                        ->scalarNode('secret')
                            ->defaultValue('%env(THUMBOR_SECURITY_KEY)%')
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->addTransformationSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add the transformation section configuration structure
     */
    private function addTransformationSection(ArrayNodeDefinition $rootNode)
    {
        // Resize validate function. Resize value are int >= 0 or the string "orig"
        $validateResize = function ($v) {
            return $v !== "orig" && !is_int($v);
        };

        $rootNode
            ->children()
                ->arrayNode('transformations')
                    ->useAttributeAsKey('id')
                    ->arrayPrototype()
                        ->performNoDeepMerging()
                        // Filter key is a prototype array. Remove it if empty array
                        ->validate()
                            ->always(function ($val) {
                                if (empty($val['filters'])) {
                                    unset($val['filters']);
                                }

                                return $val;
                            })
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
                            ->arrayNode('full_fit_in')
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
                                                'Invalid transformation.resize.width value %s. '
                                                . 'It must be an integer or the string "orig"'
                                            )
                                        ->end()
                                    ->end()
                                    ->scalarNode('height')
                                        ->isRequired()
                                        ->validate()
                                            ->ifTrue($validateResize)
                                            ->thenInvalid(
                                                'Invalid transformation.resize.height value %s. '
                                                . 'It must be an integer or the string "orig"'
                                            )
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('halign')
                                ->validate()
                                    ->ifNotInArray(array('left', 'center', 'right'))
                                    ->thenInvalid(
                                        'Invalid transformation.halign value %s. '
                                        . 'It must be one of the following : left, center, right.'
                                    )
                                ->end()
                            ->end()
                            ->scalarNode('valign')
                                ->validate()
                                    ->ifNotInArray(array('top', 'middle', 'bottom'))
                                    ->thenInvalid(
                                        'Invalid transformation.valign value %s. '
                                        . 'It must be one of the following : top, middle, bottom.'
                                    )
                                ->end()
                            ->end()
                            ->booleanNode('smart_crop')->end()
                            ->booleanNode('metadata_only')->end()
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
