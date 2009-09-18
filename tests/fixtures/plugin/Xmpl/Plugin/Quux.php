<?php
class Xmpl_Plugin_Quux extends Uber_Plugin_Abstract
{

    public function handleEvent(Uber_Event $e, $data = null)
    {
        $aParams = $e->getParameters();
        $GLOBALS['UBER']['actions'] = array('test2' => $aParams['test_value']);
    }
}
?>