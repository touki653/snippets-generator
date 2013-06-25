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

namespace Touki\SnippetsGenerator\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Improved dialog helper
 * This class adds color to the interactive actions 
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ImprovedDialogHelper extends Helper
{
    /**
     * Get helper name
     *
     * @return string impdialog
     */
    public function getName()
    {
        return 'impdialog';
    }

    /**
     * Outputs a styled question and waits for user interaction
     *
     * @param  OutputInterface $output       Where to ask
     * @param  string          $question     The given question
     * @param  string          $default      The default value
     * @param  array           $autocomplete List of avaible values
     * @return mixed           What user answered
     */
    public function ask(OutputInterface $output, $question, $default = null, array $autocomplete = null)
    {
        if (!$default) {
            $question = sprintf("<info>%s</info>: ", $question);
        } else {
            $question = sprintf("<info>%s</info> [<comment>%s</comment>]: ", $question, $default);
        }

        return $this->getHelperSet()->get('dialog')->ask($output, $question, $default, $autocomplete);
    }

    /**
     * Outputs a styled question which only accepts boolean values
     *
     * @param  OutputInterface $output   Where to ask
     * @param  string          $question The given question
     * @param  boolean         $default  The default value
     * @return boolean         What user answered
     */
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
