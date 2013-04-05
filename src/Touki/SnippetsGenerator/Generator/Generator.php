<?php

namespace Touki\SnippetsGenerator\Generator;

use Symfony\Component\Config\Definition\Processor;

abstract class Generator
{
    protected $config = null;

    abstract protected function getConfigurator();

    abstract public function generate();

    public function setConfiguration(Array $config)
    {
        $processor = new Processor;
        $defaults = $this->getConfigurator();

        $processed = $processor->processConfiguration($defaults, array($config));

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

    public function getConfigItem($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }
}
