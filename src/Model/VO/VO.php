<?php

namespace MPMA\ProIDConsumer\Model\VO;

abstract class VO implements \JSONSerializable
{
    private $_properties;

    abstract public function valid();
    public function __construct()
    {
        $this->_properties = [];

        foreach(get_object_vars($this) as $property => $void) {
            if ('_properties' == $property) continue;
            $cleanPropName = preg_replace('/[^a-z]/', '', strtolower($property));
            $this->_properties[$cleanPropName] = $property;
        }
    }
    public function jsonSerialize()
    {
        $properties = get_object_vars($this);
        unset($properties['_properties']);
        return $properties;
    }
    public function __toString()
    {
        return json_encode($this, JSON_UNESCAPED_SLASHES);
    }
    private function setter($attr, $value)
    {
        $cleanAttrName = strtolower($attr);
        if (! isset($this->_properties[$cleanAttrName])) {
            var_dump($this->_properties);
            throw new \Exception("No attribute {$attr} was found in class " . get_class($this), 2000);
        }

        $property = $this->_properties[$cleanAttrName];
        $this->$property = $value;
        return $this;
    }
    private function getter($attr)
    {
        $cleanAttrName = strtolower($attr);
        if (! isset($this->_properties[$cleanAttrName])) {
            var_dump($this->_properties);
            throw new \Exception("No attribute {$attr} was found in class " . get_class($this), 2000);
        }

        $property = $this->_properties[$cleanAttrName];
        return $this->$property;
    }
    public function __call($method, $value)
    {
        if ('set' == strtolower(mb_substr($method, 0, 3))) {
            $attr = preg_replace('/^set/', '', $method);
            return $this->setter($attr, $value[0]);
        }
        if ('get' == strtolower(mb_substr($method, 0, 3))) {
            $attr = preg_replace('/^get/', '', $method);
            return $this->getter($attr);
        }
        throw new \Exception('Call to undefined method: ' . get_class($this) . "::{$method}()", 3000);
    }
}