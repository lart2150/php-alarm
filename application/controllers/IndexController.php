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
        $events[0] = $eventTable->getEvents(4);
        $events[1] = $eventTable->getEvents(1);
        $events[2] = $eventTable->getEvents(2);
        $systemEvents = $eventTable->getEvents(null);

        //$door = $event->findParentRow('Model_Doors');
        $this->view->events = $events;
        $this->view->systemEvents = $systemEvents;
    }
}

