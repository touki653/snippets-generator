<?php

namespace Touki\SnippetsGenerator\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * GetSet Generator default configuration
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class GetSetConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('getset');

        $rootNode
            ->children()
                ->scalarNode('name')
                    ->isRequired()
                ->end()
                ->enumNode('access')
                    ->defaultValue('protected')
                    ->values(array('public', 'protected', 'private'))
                ->end()
                ->arrayNode('properties')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                    ->isRequired()
                ->end()
                ->scalarNode('path')
                    ->defaultValue('./')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
