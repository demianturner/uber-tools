<?php
class Xmpl_Context
{
    private $_data = 'this is some text';

    function get()
    {
        return $this->_data;
    }

    function set($data)
    {
        $this->_data = $data;
    }
}
?>