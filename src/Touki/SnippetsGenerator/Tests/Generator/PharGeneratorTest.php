<?php

namespace Touki\SnippetsGenerator\Tests\Generator;

use Touki\SnippetsGenerator\Generator\Phar\PharGenerator;

class PharGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $generator;

    public function setUp()
    {
        $this->generator = new PharGenerator;
    }

    /**
     * @expectedException Touki\SnippetsGenerator\Exception\BadMethodCallException
     * @expectedExceptionMessage Cannot generate phar, configuration has not been set
     */
    public function testNoConfiguration()
    {
        $generator = new PharGenerator;

        $generator->generate();
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "executable" at path "phar" must be configured
     */
    public function testConfigurationEmpty()
    {
        $generator = $this->generator;

        $generator->setConfig(array());
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "executable" at path "phar" must be configured
     */
    public function testConfigurationNoExecutable()
    {
        $config = array(
            'path' => getcwd()
        );

        $generator = $this->generator;
        $generator->setConfig($config);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "path" at path "phar" must be configured
     */
    public function testConfigurationNoPath()
    {
        $config = array(
            'executable' => 'foo'
        );

        $generator = $this->generator;
        $generator->setConfig($config);
    }

    public function testConfigurationByDefault()
    {
        $config = array(
            'executable' => 'foo',
            'path' => getcwd()
        );
        $expects = array(
            'executable' => 'foo',
            'path' => getcwd(),
            'exclude' => array()
        );

        $generator = $this->generator;
        $generator->setConfig($config);

        $this->assertEquals($generator->getConfig(), $expects);
    }
}
