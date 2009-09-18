<?php
class Xmpl_Event extends Uber_Event
{
    //	custom hooks
    const HOOK_FIRST = 1;
    const HOOK_SECOND = 2;

    function __construct($oSubject, $eventHook, $aParams = array())
    {
        parent::__construct($oSubject, $eventHook, $aParams);
    }
}
?>