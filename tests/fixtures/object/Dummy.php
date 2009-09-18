<?php
class Dummy
{
    public $var1 = 1;
    public $var2 = 2;
    public $var3 = 3;

    public function call1()
    {
        return __METHOD__;
    }

    public function call2()
    {
        return __METHOD__;
    }

    public function setVar1($value)
    {
        $this->var1 = $value;
    }

    public function getVar2()
    {
        return $this->var2;
    }

    public function getVar3()
    {
        return $this->var3;
    }
}
?>