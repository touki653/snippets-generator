<?php

namespace Touki\SnippetsGenerator\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class PharConfiguration implements ConfigurationInterface
{
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
