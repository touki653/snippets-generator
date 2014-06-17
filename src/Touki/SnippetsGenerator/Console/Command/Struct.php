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

namespace Touki\SnippetsGenerator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Twig_Environment, Twig_Loader_Filesystem, Twig_SimpleFilter;

/**
 * Struct command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Struct extends Command
{
    /**
     * Twig
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Constructor
     */
    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(sprintf("%s/../../Resources/views", __DIR__));
        $twig = new Twig_Environment($loader);
        $twig->addFilter(new Twig_SimpleFilter('ucfirst', 'ucfirst'));

        $this->twig = $twig;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this
            ->setName('generate:struct')
            ->setDescription("Generates a struct file")
            ->setDefinition([
                new InputOption('name', '', InputOption::VALUE_REQUIRED, 'Struct name'),
                new InputOption('properties', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Properties'),
                new InputOption('uses', 'u', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Uses'),
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->isInteractive()) {
            throw new \DomainException("Can only be ran on interactive mode");
        }

        $filename = sprintf("%s/%s.php", getcwd(), $input->getOption('name'));

        file_put_contents($filename, $this->twig->render('struct.php.twig', [
            'name' => $input->getOption('name'),
            'uses' => $input->getOption('uses'),
            'properties' => $input->getOption('properties')
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $notnull = function($answer) {
            if (!$answer) {
                throw new \InvalidArgumentException("Cannot be null");
            }

            return $answer;
        };

        $name = $dialog->askAndValidate($output, "<info>Name of your structure</info>: ", $notnull);
        $input->setOption('name', $name);

        $continue = true;
        $properties = [];
        $output->writeln([
            '',
            ' > Listing your properties',
            ' > <comment>Leave blank</comment> to stop',
            ''
        ]);
        $types = ["string", "integer", "float", "boolean", "resource"];
        $uses = [];

        while (true) {
            $name = $dialog->ask($output, "<info>Name a property</info>: ");

            if (null === $name) {
                break;
            }

            $pattern = "/_(.?+)/";
            $replace = function($match) {
                return strtoupper($match[1]);
            };
            $name = lcfirst(preg_replace_callback($pattern, $replace, $name));

            $type = $dialog->ask($output, "<info>Type/class of your property </info>[<comment>string</comment>]: ", 'string', $types);
            $hint = null;
            $use = null;

            if (!in_array($type, $types)) {
                $use = ltrim(str_replace("/", "\\", $type), '\\');

                $exp = explode("\\", $use);
                $hint = end($exp);
                $type = $hint;
            }

            if ('array' == strtolower($type)) {
                $hint = 'array';
                $type = $hint;
                $use = null;
            }
            
            if (null !== $use) {
                $uses[] = $use;
            }

            $properties[$name] = [
                'name' => $name,
                'type' => $type,
                'hint' => $hint
            ];

            $output->writeln('');
        }

        $input->setOption('uses', array_unique($uses));
        $input->setOption('properties', $properties);
    }
}
