<?php

class Foo
{
    protected $foo_baz;

    protected $bar;

    public function getFooBaz()
    {
        return $this->foo_baz;
    }

    public function setFooBaz($foo_baz)
    {
        $this->foo_baz = $foo_baz;

        return $this;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }
}
