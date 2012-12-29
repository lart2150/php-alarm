<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   protected function _initAutoload()
   {
       $autoLoader = Zend_Loader_Autoloader::getInstance();
       $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH,
            'namespace'     => '',
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'form/',
                    'namespace' => 'Form_'
                ),
                'models' => array(
                    'path' => 'models/',
                    'namespace' => 'Model_'
                ),
                'viewhelper' => array(
                    'path' => 'views/helpers/',
                    'namespace' => 'Zend_View_Helper_'
                )
            ),
       ));

       return $autoLoader;
   }
   
    protected function _initACL()
    {
        $acl = new Zend_Acl();
        $roleGuest = new Zend_Acl_Role('guest');
        $acl->addRole($roleGuest);
        $acl->addRole(new Zend_Acl_Role('view'), $roleGuest);
        $acl->addRole(new Zend_Acl_Role('arm'), 'view');
        $acl->addRole(new Zend_Acl_Role('disarm'), 'arm');
        $acl->addRole(new Zend_Acl_Role('administrator'));
        
        $acl->allow('view', null, array('view'));
        $acl->allow('arm', null, array('arm'));
        $acl->allow('disarm', null, array('disarm'));
        $acl->allow('administrator');
        
        Zend_Registry::set('acl', $acl);
    }
    protected function _initAuthDaapter()
    {
        $db = $this->getPluginResource('db')->getDbAdapter();
        $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'auth',
                'username',
                'password',
                'MD5(?) AND active = 1');
        Zend_Registry::set('auth', $adapter);
    }
}

