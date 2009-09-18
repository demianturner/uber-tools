<?php
class Example_Helper_One
{

    function setMixinConsumer(&$consumer)
    {
        $this->_consumer = $consumer;
    }

    public function one()
    {
        return 1;
    }
}
?>