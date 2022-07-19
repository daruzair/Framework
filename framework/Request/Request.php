<?php
namespace framework\Request;
class Request
{
    public $values = array();
    public function __get( $key )
    {
        return $this->values[ $key ];
    }
    public function __set( $key, $value )
    {
        $this->values[ $key ] = $value;
    }
}