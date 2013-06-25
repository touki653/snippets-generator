# PHP Snippets Generator

## What is it ?

PHP Snippets Generator is a Phar archive which helps you to rapidly create common tasks like a full getter and setter class, a phar archive or insert a license at the begining of each file.

## Installation

## Features

### Generate:GetSet

Generates a full class of getter and setter

```sh
$ php snippets-generator.phar generate:getset [--name="..."] [--access="..."] [-p|--prop="..."] [-p|--prop="..."] ...
```

```sh
$ php snippets-generator.phar generate:getset
Name of your class: # Insert your class name
Access to your properties [protected]: # Pick the access you want 

 > Listing your properties
 > Leave blank or say stop to stop

Name of a property: # Insert a property name or leave blank to stop adding

Do you confirm creation ? [y]: # Whether to be sure to create the file
```

Full example:

```sh
$ php snippets-generator.phar generate:getset
Name of your class: Foo
Access to your properties [protected]: protected

 > Listing your properties
 > Leave blank or say stop to stop

Name of a property: bar
Name of a property: baz
Name of a property:

 > Class      : Foo
 > Access     : protected
 > Properties : bar, baz

Do you confirm creation ? [y]: y

 > Created file /path/to/cwd/Foo.php

```

Will generate a `Foo.php` file with:

```php
<?php

/**
 * Foo
 */
class Foo
{
    /**
     * Bar
     * @var [type]
     */
    protected $bar;

    /**
     * Baz
     * @var [type]
     */
    protected $baz;

    /**
     * Get Bar
     *
     * @return [type]
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * Set Bar
     *
     * @param  [type] $bar A new bar
     * @return Foo
     */
    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * Get Baz
     *
     * @return [type]
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * Set Baz
     *
     * @param  [type] $baz A new baz
     * @return Foo
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;

        return $this;
    }
}
?>
```

### Generate:Phar

Generates a phar archive of a project

```sh
$ php snippets-generator.phar generate:phar [--file="..."] [-e|--exclude="..."]
```

``̀sh
$ php snippets-generator.phar generate:phar
Your existing executable filename (e.g bin/create.php): # Executable filename
Do you want to exclude some files or directories ? [y]: # Whether to add excluded files/dirs

# If picked yes
 > Listing your excluded files/directories
 > Leave blank or say stop to stop

Exclude: # Insert a filename or a dirname

Do you confirm creation ? [y]: # Whether to be sure to create the file
```

Full example

```sh
$ php snippets-generator.phar generate:phar
Your existing executable filename (e.g bin/create.php): bin/foo
Do you want to exclude some files or directories ? [y]: 

 > Listing your excluded files/directories
 > Leave blank or say stop to stop

Exclude: Tests
Exclude: composer.json
Exclude:

 > Executable : bin/foo
 > Phar       : bin/foo.phar
 > Excluded   : Tests, composer.json

Do you confirm creation ? [y]: 

 > Created archive bin/foo.phar

```

You should be able to run

```sh
$ php bin/foo.phar
```

### Generate:Comment-Package

Generates a file-level doc comment in each php files of a given directory

```sh
$ php snippets-generator.phar generate:comment-package [--dir="..."] [--template="..."] [--package="..."] [--ver="..."] [--name="..."] [--email="..."] [-e|--exclude="..."] [--templates]
```

#### Templates

All examples are given for values:

* package = Foo
* ver     = 1.0.0
* name    = Foobar
* email   = mail@gmail.com

**MIT**

```
/**
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package    Foo
 * @version    1.0.0
 * @author     Foobar <mail@gmail.com>
 * @copyright  2013 Foobar.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 */
```

**README**

```
/**
 * This file is a part of the Foo package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package Foo
 * @version 1.0.0
 * @author  Foobar <mail@gmail.com>
 */
```

**LICENSE**

```
/*
 * This file is part of the Foo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Foo
 * @version    1.0.0
 * @author     Foobar <mail@gmail.com>
 * @copyright  2013 Foobar.
 */
```