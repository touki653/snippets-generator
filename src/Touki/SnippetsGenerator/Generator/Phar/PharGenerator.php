<?php

/**
 * This file is a part of the Snippets Generator package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package Snippets Generator
 * @version 1.0.0
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\SnippetsGenerator\Generator\Phar;

use Touki\SnippetsGenerator\Generator\Generator;
use Touki\SnippetsGenerator\Configuration\PharConfiguration;
use Touki\SnippetsGenerator\Exception\BadMethodCallException;
use Touki\SnippetsGenerator\Exception\InvalidArgumentException;
use Symfony\Component\Finder\Finder;

class PharGenerator extends Generator
{
    /**
     * {@inheritDoc}
     */
    protected function getConfigurator()
    {
        return new PharConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function generate()
    {
        if (null === $config = $this->getConfig()) {
            throw new BadMethodCallException("Cannot generate phar, configuration has not been set");
        }

        $executable = $config['executable'];

        if (!file_exists($executable)) {
            throw new InvalidArgumentException(sprintf("File '%s' could not be found", $executable));
        }

        $pharFile = str_replace('.php', '', $executable).'.phar';
        $pharFilename = basename($pharFile);

        if (file_exists($pharFile)) {
            @unlink($pharFile);
        }

        $finder = new Finder;
        $finder
            ->files()
            ->in($config['path'])
        ;

        if (!empty($config['exclude'])) {
            $finder->exclude($config['exclude']);
        }

        $phar = new \Phar($pharFile);
        $phar->setSignatureAlgorithm(\PHAR::SHA1);
        $phar->startBuffering();

        foreach ($finder as $file) {
            $phar->addFromString($file->getRelativePathname(), $file->getContents());
        }

        $phar->addFromString($executable, $this->getExecutable($executable));
        $phar->setStub($this->getStub($pharFilename, $executable));
        $phar->stopBuffering();

        chmod($pharFile, 0777);

        return $pharFile;
    }

    private function getExecutable($executable)
    {
        $exec = file_get_contents($executable);

        return preg_replace('/'.preg_quote('#!/usr/bin/env php', '/').'\s*/is', '', $exec);
    }

    private function getStub($pharFile, $executable)
    {
        return <<<STUB
#!/usr/bin/env php
<?php
Phar::mapPhar('$pharFile');
require 'phar://$pharFile/$executable';
__HALT_COMPILER();
STUB;
    }
}
