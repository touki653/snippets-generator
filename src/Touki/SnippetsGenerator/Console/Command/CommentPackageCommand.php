<?php

namespace Touki\SnippetsGenerator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Touki\SnippetsGenerator\Exception\InvalidArgumentException;
use Touki\SnippetsGenerator\Generator\CommentPackage\CommentPackageGenerator;

/**
 * Comment Package Command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CommentPackageCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:comment-package')
            ->setDescription('Generates a file-level doc comment in each php files')
            ->setDefinition(array(
                new InputOption('dir',      '', InputOption::VALUE_REQUIRED, 'The directory to traverse'),
                new InputOption('template', '', InputOption::VALUE_REQUIRED, 'The template to use', 'default'),
                new InputOption('package',  '', InputOption::VALUE_REQUIRED, 'The package name', basename(getcwd())),
                new InputOption('ver',      '', InputOption::VALUE_REQUIRED, 'The package version', '1.0.0'),
                new InputOption('name',     '', InputOption::VALUE_REQUIRED, 'Your name'),
                new InputOption('email',    '', InputOption::VALUE_REQUIRED, 'Your email'),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('dir')) {
            throw new InvalidArgumentException("You must specify a directory");
        }

        $output->writeln(array(
            '',
            sprintf(" > Directory : <comment>%s</comment>", $input->getOption('dir')),
            sprintf(" > Template  : <comment>%s</comment>", $input->getOption('template')),
            sprintf(" > Package   : <comment>%s</comment>", $input->getOption('package')),
            sprintf(" > Version   : <comment>%s</comment>", $input->getOption('ver')),
            sprintf(" > Name      : <comment>%s</comment>", $input->getOption('name')),
            sprintf(" > Email     : <comment>%s</comment>", $input->getOption('email')),
            ''
        ));

        if ($input->isInteractive()) {
            if (!$this->getHelperSet()->get('impdialog')->askConfirmation($output, "Do you confirm creation ?", true)) {
                return;
            }
        }

        $output->writeln(array(
            '',
            sprintf(' > All comment were generated', $this->generate($input)),
            ''
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('impdialog');

        $input->setOption('dir', $dialog->ask(
            $output,
            "Which directory to traverse",
            $input->getOption('dir')
        ));

        $input->setOption('template', $dialog->ask(
            $output,
            "Which template to use",
            $input->getOption('template')
        ));

        $input->setOption('package', $dialog->ask(
            $output,
            "Package name",
            $input->getOption('package')
        ));

        $input->setOption('ver', $dialog->ask(
            $output,
            "Package version",
            $input->getOption('ver')
        ));

        $input->setOption('name', $dialog->ask(
            $output,
            "Your name",
            $input->getOption('name')
        ));

        $input->setOption('email', $dialog->ask(
            $output,
            "Your email",
            $input->getOption('email')
        ));
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
            "path"     => sprintf("%s/%s", getcwd(), $input->getOption('dir')),
            "template" => $input->getOption('template'),
            "package"  => $input->getOption('package'),
            "version"  => $input->getOption('ver'),
            "name"     => $input->getOption('name'),
            "email"    => $input->getOption('email')
        ));

        return $generator->generate();
    }
}
