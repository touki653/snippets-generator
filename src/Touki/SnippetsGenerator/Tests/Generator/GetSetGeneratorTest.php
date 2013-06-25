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

namespace Touki\SnippetsGenerator\Tests\Generator;

use Touki\SnippetsGenerator\Generator\GetSet\GetSetGenerator;

class GetSetGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $generator;

    public function setUp()
    {
        $this->generator = new GetSetGenerator;
    }

    /**
     * @expectedException Touki\SnippetsGenerator\Exception\BadMethodCallException
     * @expectedExceptionMessage Cannot generate getset, configuration has not been set
     */
    public function testNoConfiguration()
    {
        $generator = $this->generator;

        $generator->generate();
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "name" at path "getset" must be configured.
     */
    public function testConfigurationEmpty()
    {
        $generator = $this->generator;

        $generator->setConfig(array());
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "name" at path "getset" must be configured.
     */
    public function testConfigurationNoName()
    {
        $generator = $this->generator;

        $generator->setConfig(array(
                'access' => 'public',
                'properties' => array('bar', 'baz')
        ));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "properties" at path "getset" must be configured.
     */
    public function testConfigurationNoProperties()
    {
        $generator = $this->generator;

        $generator->setConfig(array(
            'name' => 'foo',
            'access' => 'public'
        ));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Invalid type for path "getset.properties". Expected array, but got string
     */
    public function testConfigurationNoArrayProperties()
    {
        $generator = $this->generator;

        $generator->setConfig(array(
            'name' => 'foo',
            'properties' => 'bar'
        ));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The value "foobaz" is not allowed for path "getset.access". Permissible values: "public", "protected", "private"
     */
    public function testConfigurationInvalidEnum()
    {
        $generator = $this->generator;

        $generator->setConfig(array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'foobaz'
        ));
    }

    public function testConfigurationValidWithDefaultValues()
    {
        $generator = $this->generator;

        $config = array(
            'name' => 'foo',
            'properties' => array('bar', 'baz')
        );
        $expect = array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'protected',
            'path' => './'
        );

        $generator->setConfig($config);

        $this->assertEquals($generator->getConfig(), $expect);
    }

    public function testConfigurationValidWithGivenValues()
    {
        $generator = $this->generator;

        $config = array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'public'
        );
        $expect = array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'public',
            'path' => './'
        );

        $generator->setConfig($config);

        $this->assertEquals($generator->getConfig(), $expect);
    }

    public function testGenerateGetset()
    {
        $generator = $this->generator;

        $config = array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'protected',
            'path' => __DIR__.'/../Fixtures/'
        );

        $generator->setConfig($config);
        $file = $generator->generate();

        $this->assertFileEquals($file, __DIR__.'/../Fixtures/DefaultFoo.php');

        @unlink($file);
    }
}
