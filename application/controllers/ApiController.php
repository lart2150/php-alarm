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

		
		$apikey = $this->_request->getParam('apikey');
		if ($apikey != $this->getInvokeArg('bootstrap')->getOption('apikey')) {
		    echo json_encode(array('error' => 'Bad key'));
		    return;
		}
		
		$data = array(
			'doorID'      => $doorID,
			'eventTypeID' => $eventType,
		);
		$table = new Model_Event();
		$eventid = (int) $table->insert($data);
		$data['eventID'] = $eventid;
		
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
    
    public function uploadVideoAction() {
        $apikey = $this->_request->getParam('apikey');
        if ($apikey != $this->getInvokeArg('bootstrap')->getOption('apikey')) {
            echo json_encode(array('error' => 'Bad key'));
            return;
        }
        $eventid = (int) $this->_request->getParam('eventid');
        /*echo '<html>
<body>
<form method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>';*/
        var_dump($_FILES);
        move_uploaded_file($_FILES['file']['tmp_name'], APPLICATION_PATH . '/../uploads/' . $eventid . '.webm');
    }
}

