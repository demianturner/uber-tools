<?php
abstract class Uber_Plugin_Abstract implements Uber_Event_Listener_Interface
{

    public function validate()
    {
        return true;
    }

    public function handleEvent(Uber_Event $e, $data = null)
    {}
}
?>