<?php

namespace Touki\SnippetsGenerator\Generator;

use Symfony\Component\Config\Definition\Processor;

abstract class AbstractGenerator
{
    protected $config;

    abstract protected function getConfigurator();

    abstract public function generate();

    public function setConfiguration(Array $config)
    {
        $processor = new Processor;
        $defaults = $this->getConfigurator();

        $processed = $processor->processConfiguration($defaults, $config);

        $this->config = $processed;
    }

    public function setConfig(Array $config)
    {
        return $this->setConfiguration($config);
    }

    public function getConfiguration()
    {
        return $this->config;
    }

    public function getConfig()
    {
        return $this->getConfiguration();
    }
}