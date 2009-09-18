<?php
final class Uber_Object
{
    private $_readableAttributes = array();
    private $_writeableAttributes = array();
    private $_accessibleMethods = array();
    private $_wrappedObject = null;

    public function __construct($object, $readableAttributes = true, $writeableAttributes = array(), $accessibleMethods = array())
    {
        $this->_wrappedObject = $object;
        $this->_writeableAttributes = $writeableAttributes;
        $this->_readableAttributes = $readableAttributes;
        $this->_accessibleMethods = (array) $accessibleMethods;
    }

    public function __get($name)
    {
        if ($this->_readableAttributes === true || (is_array($this->_readableAttributes) && in_array($name, $this->_readableAttributes))) {
            return $this->_wrappedObject->$name;
        }
        throw new Uber_Object_Exception('Readable attributes:' . join(',', $this->_readableAttributes) . '. Tried to read attribute "' . $name . '"');
    }

    public function __set($name, $value)
    {
        if ($this->_writeableAttributes === true || (is_array($this->_writeableAttributes) && in_array($name, $this->_writeableAttributes))) {
            $this->_wrappedObject->$name = $value;
        } else {
            throw new Uber_Object_Exception('Writeable attributes:' . join(',', $this->_writeableAttributes) . '. Tried to overwrite attribute "' . $name . '" with value "' . var_export($value, true) . '"');
        }
    }

    public function __call($method, $arguments)
    {
        if (in_array($method, $this->_accessibleMethods) && is_callable(array($this->_wrappedObject , $method))) {
            return call_user_func_array(array($this->_wrappedObject , $method), $arguments);
        }
    }

    public function __toString()
    {
        return 'Uber_Object';
    }
}
?>