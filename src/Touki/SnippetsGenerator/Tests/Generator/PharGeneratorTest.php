<?php

namespace Touki\SnippetsGenerator\Tests\Generator;

use Touki\SnippetsGenerator\Generator\Phar\PharGenerator;

class PharGeneratorTest extends \PHPUnit_Framework_TestCase
{
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
        $generator = new PharGenerator;

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

        $generator = new PharGenerator;
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

        $generator = new PharGenerator;
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

        $generator = new PharGenerator;
        $generator->setConfig($config);

        $this->assertEquals($generator->getConfig(), $expects);

        $generator->generate();
    }
}