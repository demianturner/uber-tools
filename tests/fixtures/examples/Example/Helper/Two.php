<?php
class Example_Helper_Two
{

    function setMixinConsumer(&$consumer)
    {
        $this->_consumer = $consumer;
    }

    public function two()
    {
        return 2;
    }
}
?>