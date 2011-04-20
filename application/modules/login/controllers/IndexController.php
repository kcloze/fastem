<?php
class Login_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        var_dump($this->_request->getMethod());
        if ($this->_request->isPost()) {
            $db = $this->_getParam('db');
            $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'users',
                'username',
                'password',
                'MD5(CONCAT(?, password_salt))'
            );

            $adapter->setIdentity($this->_request->getPost('username'));
            $adapter->setCredential($this->_request->getPost('password'));

            $auth = Zend_Auth::getInstance();


            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $this->_redirect('/');
                return;
            } else {
                $this->view->tipmsg = "Login failed";
            }
        }
    }

}
