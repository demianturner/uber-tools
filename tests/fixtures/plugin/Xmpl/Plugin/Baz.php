<?php
class Xmpl_Plugin_Baz extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        $aParams = $e->getParameters();
        $GLOBALS['UBER']['actions'] = array('test1' => $aParams['test_value']);
    }
}
?>