<?php
class Model_Apikey extends Zend_Db_Table_Abstract
{
    protected $_name = 'apikey';
    protected $_sequence = true;
    
    public function createKey($authid) {
    	$uuid = uniqid('', true);
    	$expieres = new DateTime("+1 month");
    	
    	$this->insert(array(
    			 "apikey" => $uuid,
    			 "authid" => $authid,
    			 "expires" => $expieres
    			));
    	return $uuid;
    }
}