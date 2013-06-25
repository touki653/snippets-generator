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
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Exception\InvalidArgumentException;
use Touki\SnippetsGenerator\Generator\Phar\PharGenerator;

/**
 * Phar Command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PharCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:phar')
            ->setDescription('Generates a PHP Archive of a given executable file')
            ->setDefinition(array(
                new InputOption('file', '', InputOption::VALUE_REQUIRED, 'The path to the executable file'),
                new InputOption('exclude', '', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Exclude files/directories'),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('file')) {
            throw new InvalidArgumentException("You must specify an executable file");
        }

        $output->writeln(array(
            '',
            sprintf(" > Executable : <comment>%s</comment>", $input->getOption('file')),
            sprintf(" > Phar       : <comment>%s</comment>", str_replace('.php', '', $input->getOption('file')).'.phar'),
            sprintf(" > Excluded   : <comment>%s</comment>", implode("</comment>, <comment>", $input->getOption('exclude'))),
            ''
        ));

        if ($input->isInteractive()) {
            if (!$this->getHelperSet()->get('impdialog')->askConfirmation($output, "Do you confirm creation ?", true)) {
                return;
            }
        }

        $output->writeln(array(
            '',
            sprintf(' > Created archive <comment>%s</comment>', $this->generate($input)),
            ''
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('impdialog');

        $input->setOption('file', $dialog->ask($output, "Your existing executable filename (</info>e.g bin/create.php<info>)", $input->getOption('file')));

        $exclude = $input->getOption('exclude');
        if (empty($exclude)) {
            if ($dialog->askConfirmation($output, 'Do you want to exclude some files or directories ?')) {
                $output->writeln(array(
                    '',
                    ' > Listing your excluded files/directories',
                    ' > <comment>Leave blank</comment> or say <comment>stop</comment> to stop',
                    ''
                ));

                while (!in_array($prop = $dialog->ask($output, 'Exclude'), array(null, 'stop'))) {
                    $exclude[] = $prop;
                }
            }
        }
        $exclude = array_unique($exclude);
        $input->setOption('exclude', $exclude);
    }

    /**
     * Generates the phar command based on input options
     *
     * @param  InputInterface $input An Input instance
     * @return string         The generated filename
     */
    private function generate(InputInterface $input)
    {
        $generator = new PharGenerator;

        $generator->setConfig(array(
            'executable' => $input->getOption('file'),
            'exclude'    => $input->getOption('exclude'),
            'path'       => getcwd()
        ));

        return $generator->generate();
    }
}
