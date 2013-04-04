<?php

namespace Touki\SnippetsGenerator\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class ImprovedDialogHelper extends Helper
{
    public function getName()
    {
        return 'impdialog';
    }

    public function ask(OutputInterface $output, $question, $default = null, array $autocomplete = null)
    {
        if (!$default) {
            $question = sprintf("<info>%s</info>: ", $question);
        } else {
            $question = sprintf("<info>%s</info> [<comment>%s</comment>]: ", $question, $default);
        }

        return $this->getHelperSet()->get('dialog')->ask($output, $question, $default, $autocomplete);
    }

    public function askConfirmation(OutputInterface $output, $question, $default = true)
    {
        $answer = 'z';
        while ($answer && !in_array(strtolower($answer[0]), array('y', 'n'))) {
            $answer = $this->ask($output, $question, ($default) ? 'y' : 'n');
        }

        if (false === $default) {
            return $answer && 'y' == strtolower($answer[0]);
        }

        return !$answer || 'y' == strtolower($answer[0]);
    }
}
