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

namespace Touki\SnippetsGenerator\Generator\CommentPackage;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Touki\SnippetsGenerator\Generator\Generator;
use Touki\SnippetsGenerator\Configuration\CommentPackageConfiguration;
use Touki\SnippetsGenerator\Exception\BadMethodCallException;

/**
 * Comment Package Generator
 * Generates a file level doc comment on each .php file
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CommentPackageGenerator extends Generator
{
    /**
     * Symfony Filesystem
     * @var Filesystem
     */
    protected $fs;

    /**
     * Symfony Finder
     * @var Finder
     */
    protected $finder;

    /**
     * {@inheritDoc}
     */
    public function getConfigurator()
    {
        return new CommentPackageConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function build(array $config = array())
    {
        $this->fs     = new Filesystem;
        $this->finder = new Finder;
    }

    /**
     * {@inheritDoc}
     */
    public function generate()
    {
         if (null === $config = $this->getConfig()) {
            throw new BadMethodCallException("Cannot generate comment-package, configuration has not been set");
        }

        $fs     = $this->fs;
        $finder = $this->finder;
        $finder
            ->files()
            ->in($config['path'])
            ->name('*.php')
        ;

        if (!empty($config['exclude'])) {
            $finder->exclude($config['exclude']);
        }

        $docblock = $this->getTemplate($config);

        foreach ($finder as $file) {
            $realpath = $file->getRealpath();
            $relative = $file->getRelativePathname();
            $this->removeDocComment($realpath, $file->getContents());

            $tokens = token_get_all($file->getContents());
            $nsLine = 0;

            foreach ($tokens as $token) {
                if ($token[0] == T_NAMESPACE) {
                    $nsLine = $token[2];
                    break;
                }
            }

            if (!$nsLine) {
                continue;
            }

            $newFile = new \SplFileObject($realpath.'~', 'w+');
            $nsLine -= 2; // Previous Line, not from line 0

            foreach (new \SplFileObject($realpath) as $line => $content) {
                if ($line == $nsLine) {
                    $newFile->fwrite($docblock);
                }

                $newFile->fwrite($content);
            }

            $fs->remove($realpath);
            $fs->rename($newFile->getRealpath(), $realpath);
        }
    }

    /**
     * Get the template for the comment package
     *
     * @return string Rendered template
     */
    private function getTemplate(array $config = array())
    {
        return $this->render(sprintf('CommentPackage/%s.php.twig', $config['template']), $config);
    }

    /**
     * Removes the doc comments
     *
     * @access private
     * @param  string  $realpath Fullpath to file
     * @param  string  $content  File content
     */
    private function removeDocComment($realpath, $content)
    {
        $tokens   = token_get_all($content);
        $comment  = null;
        $fromLine = 0;
        $fs       = $this->fs;

        foreach ($tokens as $token) {
            if ($token[0] == T_DOC_COMMENT) {
                $comment  = $token[1];
                $fromLine = $token[2];
            }

            if ($token[0] == T_NAMESPACE) {
                $nsLine = $token[2];
                break;
            }
        }

        if ($comment) {
            $lines   = explode("\n", $comment);
            $num     = count($lines);
            $toLine  = $fromLine + $num;
            $exclude = range($fromLine-1, $toLine-1);
            $newFile = new \SplFileObject($realpath.'~', 'w+');

            foreach (new \SplFileObject($realpath) as $line => $content) {
                if (!in_array($line, $exclude)) {
                    $newFile->fwrite($content);
                }
            }

            $fs->remove($realpath);
            $fs->rename($newFile->getRealpath(), $realpath);
        }
    }
}
