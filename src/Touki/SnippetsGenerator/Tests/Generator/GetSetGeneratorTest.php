<?php

namespace Touki\SnippetsGenerator\Tests\Generator;

use Touki\SnippetsGenerator\Generator\GetSetGenerator;

class GetSetGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Touki\SnippetsGenerator\Exception\BadMethodCallException
     * @expectedExceptionMessage Cannot generate getset, configuration has not been set
     */
    public function testNoConfiguration()
    {
        $generator = new GetSetGenerator;

        $generator->generate();
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "name" at path "getset" must be configured.
     */
    public function testConfigurationEmpty()
    {
        $generator = new GetSetGenerator;

        $generator->setConfig(array());
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "name" at path "getset" must be configured.
     */
    public function testConfigurationNoName()
    {
        $generator = new GetSetGenerator;

        $generator->setConfig(array('getset'=> array(
            'access' => 'public',
            'properties' => array('bar', 'baz')
        )));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "properties" at path "getset" must be configured.
     */
    public function testConfigurationNoProperties()
    {
        $generator = new GetSetGenerator;

        $generator->setConfig(array('getset'=> array(
            'name' => 'foo',
            'access' => 'public'
        )));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Invalid type for path "getset.properties". Expected array, but got string
     */
    public function testConfigurationNoArrayProperties()
    {
        $generator = new GetSetGenerator;

        $generator->setConfig(array('getset'=> array(
            'name' => 'foo',
            'properties' => 'bar'
        )));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The value "foobaz" is not allowed for path "getset.access". Permissible values: "public", "protected", "private"
     */
    public function testConfigurationInvalidEnum()
    {
        $generator = new GetSetGenerator;

        $generator->setConfig(array('getset'=> array(
            'name' => 'foo',
            'properties' => array('bar', 'baz'),
            'access' => 'foobaz'
        )));
    }

    public function testConfigurationValidWithDefaultValues()
    {
        $generator = new GetSetGenerator;

        $config = array(
            'getset'=> array(
                'name' => 'foo',
                'properties' => array('bar', 'baz')
            )
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
        $generator = new GetSetGenerator;

        $config = array(
            'getset'=> array(
                'name' => 'foo',
                'properties' => array('bar', 'baz'),
                'access' => 'public'
            )
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
        $generator = new GetSetGenerator;

        $config = array(
            'getset'=> array(
                'name' => 'foo',
                'properties' => array('foo_baz', 'bar'),
                'access' => 'protected',
                'path' => __DIR__.'/../Fixtures/'
            )
        );

        $generator->setConfig($config);
        $file = $generator->generate();

        $this->assertFileEquals($file, __DIR__.'/../Fixtures/DefaultFoo.php');

        @unlink($file);
    }
}
