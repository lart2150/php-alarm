<?php
class Model_Eventtype extends Zend_Db_Table_Abstract
{
    protected $_name = 'eventtype';
    protected $_sequence = true;
    
    public function getEventTypeByEventKey($eventkey) {
        $select = $this->select()
            ->where('eventKey = ?', $eventkey);
    
        return $this->fetchAll($select)->current();
    }
}