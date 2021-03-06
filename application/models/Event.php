<?php
class Model_Event extends Zend_Db_Table_Abstract
{
	protected $_name = 'event';
    protected $_sequence = true;
    protected $_referenceMap    = array(
        'door' => array(
            'columns'           => 'doorID',
            'refTableClass'     => 'Model_Doors',
            'refColumns'        => 'doorID'
        ),
        'eventtype' => array(
            'columns'           => 'eventTypeID',
            'refTableClass'     => 'Model_Eventtype',
            'refColumns'        => 'eventTypeID'
        )
    );

	public function getEvents($doorid, $count = 16, $offset = 0) {

		$select = $this->select()
			->from($this->_name)
			->setIntegrityCheck(false)
			->joinLeft('doors', 'event.doorID = doors.doorID', array('doorPort', 'doorName'))
			->joinLeft('eventtype', 'event.eventTypeID = eventtype.eventTypeID', array('eventText'))
			->joinLeft('auth', 'event.userID = auth.id', array('real_name'))
			->order(array('event.eventTimestamp DESC', 'eventID DESC'))
			->limit($count, $offset);
		if ($doorid=== null) {
		    $select->where('event.doorID IS NULL');
		} elseif (is_array($doorid)) {
		    $select->where('event.doorID in (?)', $doorid);
		} else {
		    $select->where('event.doorID = ?', $doorid);
		}
		return $this->fetchAll($select);
	}
	
	public function getArmedState() {
	    $select = $this->select()
    	    ->from($this->_name)
    	    ->setIntegrityCheck(false)
    	    ->joinLeft('eventtype', 'event.eventTypeID = eventtype.eventTypeID', array('eventText', 'eventKey'))
    	    ->joinLeft('auth', 'event.userID = auth.id', array('real_name'))
    	    ->where("eventtype.eventKey = 'disarmed' or eventtype.eventKey = 'armed'")
    	    ->order(array('event.eventTimestamp DESC', 'eventID DESC'))
    	    ->limit(1);
	    
	    return $this->fetchAll($select)->current();
	}
	
	public function hasVideo($eventid) {
	    return file_exists(APPLICATION_PATH . '/../uploads/' . $eventid . '.webm');
	}
}
