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

namespace Touki\SnippetsGenerator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Exception\InvalidArgumentException;
use Touki\SnippetsGenerator\Generator\CommentPackage\CommentPackageGenerator;
use Touki\SnippetsGenerator\Console\Application;

/**
 * Self Comment Package Command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SelfCommentCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('self-comment')
            ->setDescription('Generates the comments for this application')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->isInteractive()) {
            if (!$this->getHelperSet()->get('impdialog')->askConfirmation($output, "Are you sure ?", true)) {
                return;
            }
        }

        $output->writeln(array(
            '',
            sprintf(' > All comments were generated', $this->generate($input)),
            ''
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * Generates the comments based on input options
     *
     * @param  InputInterface $input An Input instance
     * @return string         The generated filename
     */
    private function generate(InputInterface $input)
    {
        $generator = new CommentPackageGenerator;

        $generator->setConfig(array(
            "path"     => sprintf("%s/%s", getcwd(), "src"),
            "exclude"  => array('Fixtures'),
            "template" => "readme",
            "package"  => "Snippets Generator",
            "version"  => Application::VERSION,
            "name"     => "Touki",
            "email"    => "g.vincendon@vithemis.com"
        ));

        return $generator->generate();
    }

    /**
     * Returns template names
     *
     * @return array
     */
    private function getTemplates()
    {
        return array_map(
            function($file) {
                return basename($file, '.php.twig');
            },
            glob(__DIR__.'/../../Resources/views/CommentPackage/*.php.twig')
        );
    }
}
