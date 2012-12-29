<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $this->layoutView = $view;
        $view->page = 'login';
    }

    public function indexAction()
    {
        $form = new Form_Login();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                if ($this->_process($form->getValues())) {
                    $this->_helper->redirector('index', 'index');
                }
            }
        }
        $this->view->form = $form;
    }
    protected function _process($values)
    {
        $adapter = Zend_Registry::get('auth');
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index');
    }
}

