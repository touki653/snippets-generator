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

namespace Touki\SnippetsGenerator\Generator\GetSet;

use Touki\SnippetsGenerator\Generator\Generator;
use Touki\SnippetsGenerator\Configuration\GetSetConfiguration;
use Touki\SnippetsGenerator\Exception\BadMethodCallException;

/**
 * Get Set Generator
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class GetSetGenerator extends Generator
{
    /**
     * {@inheritDoc}
     */
    protected function getConfigurator()
    {
        return new GetSetConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function generate()
    {
        if (null === $config = $this->getConfig()) {
            throw new BadMethodCallException("Cannot generate getset, configuration has not been set");
        }

        $path     = rtrim($config['path'], DIRECTORY_SEPARATOR);
        $filename = sprintf("%s/%s.php", $path, $config['name']);
        $this->renderFile('GetSet/class.php.twig', $filename, $config);

        return $filename;
    }
}
