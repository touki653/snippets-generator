# PHP Snippets Generator

## What is it ?

PHP Snippets Generator is a Phar archive which helps you to rapidly create common tasks like a full getter and setter class, a phar archive or insert a license at the begining of each file.

## Installation

## Features

### Generate:GetSet

Generates a full class of getter and setter

```sh
$ snippets-generator.phar generate:getset [--name="..."] [--access="..."] [-p|--prop="..."] [-p|--prop="..."] ...
```

```sh
$ snippets-generator.phar generate:getset
Name of your class: # Insert your class name
Access to your properties [protected]: # Pick the access you want (private, protected, public)

 > Listing your properties
 > Leave blank or say stop to stop

Name of a property: # Insert a property name or leave blank to stop adding

Do you confirm creation ? [y]: # Whether to be sure to create the file
```

Full example:

```sh
$ snippets-generator.phar generate:getset
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