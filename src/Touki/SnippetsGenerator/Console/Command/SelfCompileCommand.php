<?php

namespace Touki\SnippetsGenerator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Generator\Phar\PharGenerator;

class SelfCompileCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('self-compile')
            ->setDescription('Generates the phar file for this application')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->isInteractive()) {
            if (!$this->getHelperSet()->get('impdialog')->askConfirmation($output, "Are you sure ?", true)) {
                return;
            }
        }

        $output->writeln(array(
            '',
            sprintf(' > Created archive <comment>%s</comment>', $this->generate($input)),
            ''
        ));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

    private function generate(InputInterface $input)
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
