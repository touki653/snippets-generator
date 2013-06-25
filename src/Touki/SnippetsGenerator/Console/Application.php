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

namespace Touki\SnippetsGenerator\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Touki\SnippetsGenerator\Console\Helper\ImprovedDialogHelper;
use Touki\SnippetsGenerator\Console\Command\GetSetCommand;
use Touki\SnippetsGenerator\Console\Command\PharCommand;
use Touki\SnippetsGenerator\Console\Command\SelfCompileCommand;
use Touki\SnippetsGenerator\Console\Command\CommentPackageCommand;
use Touki\SnippetsGenerator\Console\Command\SelfCommentCommand;

/**
 * Improved application which initialize all commands
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Application extends BaseApplication
{
    const VERSION = '1.0.0';

    /**
     * Initialize snippets generator
     */
    public function __construct()
    {
        error_reporting(E_ALL);

        $commands = array(
            new GetSetCommand,
            new PharCommand,
            new SelfCompileCommand,
            new CommentPackageCommand,
            new SelfCommentCommand,
        );

        parent::__construct('PHP Snippets Generator', self::VERSION);

        $this->addCommands($commands);
        $this->getHelperSet()->set(new ImprovedDialogHelper, 'impdialog');
    }
}
