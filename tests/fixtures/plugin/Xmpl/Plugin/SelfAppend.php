<?php
class Xmpl_Plugin_SelfAppend extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        $GLOBALS['UBER']['actions']['appended_names'] .= $e->getName();
    }
}
?>