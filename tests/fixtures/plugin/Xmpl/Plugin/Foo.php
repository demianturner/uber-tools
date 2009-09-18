<?php
class Xmpl_Plugin_Foo extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        if (! is_null($data)) {
            $txt = $data->get();
            $newData = strtoupper($txt);
            $data->set($newData);
        }
    }
}
?>