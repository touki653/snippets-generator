<?php

namespace Touki\SnippetsGenerator\Generator\GetSet;

use Touki\SnippetsGenerator\Generator\Generator;
use Touki\SnippetsGenerator\Configuration\GetSetConfiguration;
use Touki\SnippetsGenerator\Exception\BadMethodCallException;

class GetSetGenerator extends Generator
{
    private $template;

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

        $path = rtrim($config['path'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $name = $this->_camelize($config['name']);

        $filename = $path.$name.'.php';
        $file = new \SplFileObject($filename, 'w+');

        $this->_initTemplate($file);
        $this->_writeLine('<?php', 0, 2);
        $this->_writeLine(sprintf('class %s', $name));
        $this->_writeLine('{');

        foreach ($config['properties'] as $prop) {
            $this->_writeLine(sprintf("%s \$%s;", $config['access'], $prop), 1, 2);
        }

        $last = count($config['properties'])-1;
        $i = 0;
        foreach ($config['properties'] as $prop) {
            $camelized = $this->_camelize($prop);

            $this->_writeLine(sprintf("public function get%s()", $camelized), 1);
            $this->_writeLine('{', 1);
            $this->_writeLine(sprintf('return $this->%s;', $prop), 2);
            $this->_writeLine('}', 1, 2);

            $this->_writeLine(sprintf('public function set%s($%s)', $camelized, $prop), 1);
            $this->_writeLine('{', 1);
            $this->_writeLine(sprintf('$this->%s = $%s;', $prop, $prop), 2, 2);
            $this->_writeLine('return $this;', 2);

            if ($i != $last) {
                $this->_writeLine('}', 1, 2);
            } else {
                $this->_writeLine('}', 1);
            }

            $i++;
        }

        $this->_writeLine('}');

        return $filename;
    }

    private function _initTemplate($file)
    {
        $this->template = $file;
    }

    private function _writeLine($line = null, $indentation = 0, $lineOffset = 1)
    {
        $this->template->fwrite(sprintf(
            "%s%s%s",
            str_repeat(" ", $indentation * 4),
            $line,
            str_repeat(PHP_EOL, $lineOffset)
        ));
    }

    private function _camelize($string)
    {
        return str_replace(" ", "", ucwords(strtr($string, "_-", "  ")));
    }
}
