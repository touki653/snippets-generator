<?php

namespace Touki\SnippetsGenerator\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Touki\SnippetsGenerator\Console\Helper\ImprovedDialogHelper;
use Touki\SnippetsGenerator\Console\Command\GetSetCommand;
use Touki\SnippetsGenerator\Console\Command\PharCommand;
use Touki\SnippetsGenerator\Console\Command\SelfCompileCommand;

class Application extends BaseApplication
{
    const VERSION = '1.0';

    public function __construct()
    {
        error_reporting(E_ALL);

        $commands = array(
            new GetSetCommand,
            new PharCommand,
            new SelfCompileCommand
        );

        parent::__construct('PHP Snippets Generator', self::VERSION);

        $this->addCommands($commands);
        $this->getHelperSet()->set(new ImprovedDialogHelper, 'impdialog');
    }
}
