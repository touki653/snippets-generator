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
