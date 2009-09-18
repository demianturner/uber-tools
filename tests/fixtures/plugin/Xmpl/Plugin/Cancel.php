<?php
class Xmpl_Plugin_Cancel extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        // do something
        $e->cancel();
        if ($e->isCancelled()) {
            define('TEST_CONSTANT_EVENT_CANCELLED', true);
        }
    }
}
?>