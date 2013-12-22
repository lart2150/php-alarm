<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->ident = Zend_Auth::getInstance()->getIdentity();
        $acl = Zend_Registry::get('acl');
        if (!$this->ident || !$acl->isAllowed($this->ident->role, null, 'view'))
        {
            $this->_helper->redirector('index', 'auth');
        }
        
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $this->layoutView = $view;
        $view->page = 'home';
    }

    public function indexAction()
    {
        $eventTable = new Model_Event();
        $events = $eventTable->getEvents(array(4, 2, 1));
        $systemEvents = $eventTable->getEvents(null);

        //$door = $event->findParentRow('Model_Doors');
        $this->view->events = $events;
        $this->view->systemEvents = $systemEvents;
        $this->view->alarmState = $eventTable->getArmedState();
        $this->view->eventTable = $eventTable;
    }
    
    public function armAction()
    {
        $this->layoutView->page = 'arm';
        $eventTable = new Model_Event();
        $alarmState = $eventTable->getArmedState();
        $this->view->alarmState = $alarmState;
        
        $acl = Zend_Registry::get('acl');
        $allowdToArm = $acl->isAllowed($this->ident->role, null, 'arm');
        $allowdToDisarm = $acl->isAllowed($this->ident->role, null, 'disarm');
        
        $this->view->allowdToArm = $allowdToArm;
        $this->view->allowdToDisarm = $allowdToDisarm;
        
        if (!$this->view->allowdToArm && !$this->view->allowdToDisarm)
        {
            $this->_helper->redirector('index', 'index');
        }
        
        $submitLabel = 'disarm';
        if ($alarmState->eventKey == 'disarmed') {
            $submitLabel = 'arm';
        }
        $form = new Form_Armdisarm(array('submitLablel' => $submitLabel));
        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())
                    && $acl->isAllowed($this->ident->role, null, $submitLabel)) {
                $eventTypeTable = new Model_Eventtype();
                $newState = $alarmState->eventKey == 'disarmed' ? 'armed' : 'disarmed';
                $eventType = $eventTypeTable->getEventTypeByEventKey($newState);
                
                $data = array(
                        'userID'      => $this->ident->id,
                        'eventTypeID' => $eventType->eventTypeID,
                        'eventNote'   => $form->getValue('reason')
                );
                $eventTable->insert($data);
                $this->_helper->redirector('index', 'index');
            }
        }
        
        
        $this->view->form = $form;
    }
}

