<?php
class Model_Apikey extends Zend_Db_Table_Abstract
{
    protected $_name = 'apikey';
    protected $_sequence = true;
    
    public function createKey($authid) {
    	$uuid = uniqid('', true);
    	$expieres = new DateTime("+1 month");
    	
    	$data = array(
    			 "apikey" => $uuid,
    			 "username" => $authid,
    			 "expires" => $expieres->format('Y-m-d H:i:s'),
    			);
        $this->insert($data);
    	return $uuid;
    }
}
