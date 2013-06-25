<?php

namespace Touki\SnippetsGenerator\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Phar Generator default configuration
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PharConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('phar');

        $rootNode
            ->children()
                ->scalarNode('executable')
                    ->isRequired()
                ->end()
                ->scalarNode('path')
                    ->isRequired()
                ->end()
                ->arrayNode('exclude')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
