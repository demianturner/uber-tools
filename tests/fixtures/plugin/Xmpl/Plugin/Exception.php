<?php
class Xmpl_Plugin_Exception extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        throw new Uber_Plugin_Exception('catch me');
    }
}
?>