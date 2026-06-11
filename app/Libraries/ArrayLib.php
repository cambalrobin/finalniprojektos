<?php
namespace App\Libraries;

use LDAP\Result;

class ArrayLib{
    public function __construct()
    {
        
    }

    public function setValueToKey($array){ 
        $result = [];
        foreach($array as $row){
            $result [$row] = $row;
        }
        return $result;
    }

}