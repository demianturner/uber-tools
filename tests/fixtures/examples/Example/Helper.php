<?php
class Example_Helper extends Uber_Lazy
{
    var $mixins = array('one' => 'Example_Helper_One' , 'two' => 'Example_Helper_Two' , 'three' => 'Example_Helper_Three');

    public function ___construct()
    {}

    function say_hi_from_parent()
    {
        return 'say hi from parent';
    }
}
?>