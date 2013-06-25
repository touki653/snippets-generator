<?php

namespace Touki\SnippetsGenerator\Generator;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Base class for Generators
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class Generator
{
    /**
     * The user parameters entries
     *
     * @var array
     */
    protected $config = null;

    /**
     * Returns the default configuration
     * This function has to be overriden
     *
     * @return ConfigurationInterface The Generator configuration class
     */
    abstract protected function getConfigurator();

    /**
     * Where all business logic happens when configuration is set
     * This function has to be overriden
     *
     * @return mixed
     */
    abstract public function generate();

    /**
     * Set configuration
     * This is a proxy method, which calls the default configurator tree defined in generator configuration class
     *
     * @param array $config User configuration
     *
     * @return void
     */
    public function setConfiguration(array $config)
    {
        $processor = new Processor;
        $defaults = $this->getConfigurator();

        $processed = $processor->processConfiguration($defaults, array($config));

        $this->config = $processed;
    }

    /**
     * Alias for setConfiguration()
     *
     * @param array $config User configuration
     */
    public function setConfig(array $config)
    {
        return $this->setConfiguration($config);
    }

    /**
     * Returns the configuration
     *
     * @return array User configuration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Alias for getConfiguration()
     *
     * @return array User configuration
     */
    public function getConfig()
    {
        return $this->getConfiguration();
    }

    /**
     * Returns a specific configuration item
     *
     * @param string $key The item key
     *
     * @return mixed The value for key or null if undefined
     */
    public function getConfigItem($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }

    /**
     * Renders a twig file
     *
     * @param string $template    Template name
     * @param array  $parameters  Parameters for the Twig template
     * @param string $skeletonDir Where to find template
     *
     * @return string The rendered template
     */
    protected function render($template, $parameters, $skeletonDir = null)
    {
        $skeletonDir = $skeletonDir ?: $this->getSkeletonDir();

        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($skeletonDir), array(
            'debug'            => true,
            'cache'            => false,
            'strict_variables' => true,
            'autoescape'       => false,
        ));

        return $twig->render($template, $parameters);
    }

    /**
     * Renders a file
     *
     * @param string $template    Template name
     * @param string $target      Target file
     * @param array  $parameters  Parameters for the Twig template
     * @param string $skeletonDir Where to find template
     *
     * @return integer|boolean How much bytes have been written. False on failure
     */
    protected function renderFile($template, $target, $parameters, $skeletonDir = null)
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        return file_put_contents($target, $this->render($template, $parameters, $skeletonDir));
    }

    /**
     * Returns the default skeleton dir
     *
     * @return string Views location
     */
    protected function getSkeletonDir()
    {
        return __DIR__.'/../Resources/views';
    }
}
