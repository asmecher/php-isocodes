<?php

namespace Sokil\IsoCodes;

abstract class Database implements \Iterator, \Countable
{
    const DATABASE_PATH = '/../../../databases';
    
    protected $_iso;
    
    protected $_index = array();
    
    protected $_values = array();
    
    protected $_entryTagName;
    
    protected $_entryClassName = '\Sokil\IsoCodes\Database\Entry';
    
    private $_pointer = 0;
    
    public function __construct()
    {
        $this->_load(__DIR__ . self::DATABASE_PATH . '/' . $this->_iso . '.xml');
    }
    
    public function getIso()
    {
        return $this->_iso;
    }
    
    private function _load($xmlFile)
    {
        $xml = new \DOMDocument();
        $xml->load($xmlFile);
        
        $list = $xml->getElementsByTagName($this->_entryTagName);
        
        /* @var $entry \DOMElement */
        foreach($list as $item) {
            
            $entry = new $this->_entryClassName($this);
            
            $this->_values[] = $entry;
            
            /* @var $entry \DOMAttr */
            foreach($item->attributes as $attribute) {
                $entry->{$attribute->name} = $attribute->value;
                $this->_index[$attribute->name][$attribute->value] = $entry;
            }
        }
        
    }
    
    public function toArray()
    {
        return $this->_values;
    }
    
    public function current()
    {
        return $this->_values[$this->_pointer];
    }
    
    public function key()
    {
        return $this->_pointer;
    }
    
    public function next()
    {
        $this->_pointer++;
    }
    
    public function rewind()
    {
        $this->_pointer = 0;
    }
    
    public function valid()
    {
        return isset($this->_values[$this->_pointer]);
    }
    
    public function count()
    {
        return count($this->_values);
    }
}
