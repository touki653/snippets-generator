<?php

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
