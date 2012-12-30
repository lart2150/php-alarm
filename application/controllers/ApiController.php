<?php

class ApiController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
		echo 'moo';
    }

	public function doorEventAction()
    {
		$doorTable = new Model_Doors();
		$doorPort = (int) $this->_request->getParam('doorPort');
		
		$doorRowset = $doorTable->fetchAll(array('doorPort = ?' => $doorPort));
		$doorID = $doorRowset->current()->doorID;
		$eventType = (int) $this->_request->getParam('eventType');

		$data = array(
			'doorID'      => $doorID,
			'eventTypeID' => $eventType,
		);
		$table = new Model_Event();
		$table->insert($data);
		if ($eventType == 1) {
		    $eventTable = new Model_Event();
		    $alarmState = $eventTable->getArmedState();
		    if ($alarmState->eventKey != 'disarmed')
		    {
    			$eventTypeTable = new Model_Eventtype();
    			$event = $eventTypeTable->fetchAll(array('eventTypeID  = ?' => $eventType))->current();
    			
    			$mail = new Zend_Mail();
    			$mail->setBodyText($doorRowset->current()->doorName . ' - ' . $event->eventText);
    			$mail->setFrom('alarm@alarm.lart2150.com', 'Alarm System');
    			$mail->addTo('alarm@lart2150.com', 'Alarm');
    			$mail->setSubject('Alarm Event - ' . $doorRowset->current()->doorName);
    			$mail->send();
		    }
		}
		echo json_encode($data);
    }
}

