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
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Generator\Phar\PharGenerator;

/**
 * Self Compile Command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SelfCompileCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('self-compile')
            ->setDescription('Generates the phar file for this application')
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
            sprintf(' > Created archive <comment>%s</comment>', $this->generate()),
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
     * Generates the phar command based on input options
     *
     * @return string The generated filename
     */
    private function generate()
    {
        $generator = new PharGenerator;

        $generator->setConfig(array(
            'executable' => 'bin/snippets-generator',
            'exclude'    => array('Tests'),
            'path'       => getcwd()
        ));

        return $generator->generate();
    }
}
