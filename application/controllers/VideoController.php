<?php

class VideoController extends Zend_Controller_Action
{

    public function init()
    {
        $this->ident = Zend_Auth::getInstance()->getIdentity();
        $acl = Zend_Registry::get('acl');
        if (!$this->ident || !$acl->isAllowed($this->ident->role, null, 'arm'))
        {
            $this->_helper->redirector('index', 'auth');
        }
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function indexAction() {
        $eventid = (int) $this->_request->getParam('eventid');
        $file = APPLICATION_PATH . '/../uploads/' . $eventid . '.webm';
        $handle = @fopen($file, "r");
        header('Content-Type: video/webm');
        header('Content-Length: ' . filesize($file));
        while (($buffer = fgets($handle, 4096)) !== false) {
            echo $buffer;
        }
    }
}