<?php

/**
 * This file is a part of the Snippets Generator package
 *
 * For the full informations, please read the README file
 * distributed with this package
 *
 * @package Snippets Generator
 * @version 1.0.0
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\SnippetsGenerator\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Comment Package Generator default configuration
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CommentPackageConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('comment-package');

        $rootNode
            ->children()
                ->scalarNode('template')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('path')
                    ->defaultValue('./')
                ->end()
                ->scalarNode('package')
                    ->isRequired()
                ->end()
                ->scalarNode('version')
                    ->isRequired()
                ->end()
                ->scalarNode('name')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('email')
                    ->defaultValue(null)
                ->end()
                ->arrayNode('exclude')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
