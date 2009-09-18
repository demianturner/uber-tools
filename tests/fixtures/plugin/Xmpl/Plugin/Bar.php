<?php
class Xmpl_Plugin_Bar extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        if (! is_null($data)) {
            $txt = $data->get();
            $newData = strrev($txt);
            $data->set($newData);
        }
    }
}
?>