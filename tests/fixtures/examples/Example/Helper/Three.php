<?php
class Example_Helper_Three
{

    function setMixinConsumer(&$consumer)
    {
        $this->_consumer = $consumer;
    }

    public function three()
    {
        return 3;
    }

    public function say_hi_from_parent()
    {
        return $this->_consumer->say_hi_from_parent();
    }
}
?>