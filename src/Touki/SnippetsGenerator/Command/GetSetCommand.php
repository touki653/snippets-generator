<?php

namespace Touki\SnippetsGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Exception\InvalidArgumentException;
use Touki\SnippetsGenerator\Generator\GetSetGenerator;

class GetSetCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('generate:getset')
            ->setDescription('Generates a full class with getter and setter')
            ->setDefinition(array(
                new InputOption('name', '', InputOption::VALUE_REQUIRED, 'The name of the class to create'),
                new InputOption('access', '', InputOption::VALUE_REQUIRED, 'The access to your properties (public, protected, private)', 'protected'),
                new InputOption('prop', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Add a property to create'),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('name')) {
            throw new InvalidArgumentException("You must specify a name");
        }

        if (!is_array($input->getOption('prop')) || count($input->getOption('prop')) < 1) {
            throw new InvalidArgumentException("You must specify at least one property");
        }

        if (!in_array($input->getOption('access'), array('public', 'protected', 'private'))) {
            throw new InvalidArgumentException("Access should be whether public, protected or private");
        }

        $output->writeln(array(
            '',
            sprintf(" > Class      : <comment>%s</comment>", $input->getOption('name')),
            sprintf(" > Access     : <comment>%s</comment>", $input->getOption('access')),
            sprintf(" > Properties : <comment>%s</comment>", implode("</comment>, <comment>", $input->getOption('prop'))),
            ''
        ));

        if ($input->isInteractive()) {
            if (!$this->getHelperSet()->get('impdialog')->askConfirmation($output, "Do you confirm creation ?", true)) {
                return;
            }
        }

        $output->writeln(array(
            '',
            sprintf(' > Created file <comment>%s</comment>', $this->generate($input)),
            ''
        ));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('impdialog');

        $input->setOption('name', $dialog->ask($output, "Name of your property", $input->getOption('name')));

        $input->setOption('access', $dialog->ask(
            $output,
            "Access to your properties",
            $input->getOption('access') ?: 'protected',
            array('public', 'protected', 'private')
        ));

        $properties = $input->getOption('prop');
        if (empty($properties)) {
            $output->writeln(array(
                '',
                ' > Listing your properties',
                ' > <comment>Leave blank</comment> or say <comment>stop</comment> to stop',
                ''
            ));

            while (!in_array($prop = $dialog->ask($output, 'Name of a property'), array(null, 'stop'))) {
                $properties[] = $prop;
            }
        }
        $properties = array_unique($properties);

        $input->setOption('prop', $properties);
    }

    private function generate(InputInterface $input)
    {
        $config = array(
            'name' => $input->getOption('name'),
            'properties' => $input->getOption('prop'),
            'access' => $input->getOption('access'),
            'path' => getcwd()
        );

        $generator = new GetSetGenerator;
        $generator->setConfig($config);

        return $generator->generate();
    }
}
