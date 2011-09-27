<?php
class Auth_LogoutController extends Zend_Controller_Action
{
    public function indexAction()
    {
            Zend_Auth::getInstance()->clearIdentity();
			$this->_helper->redirector->gotoSimple('index','index','index');
    }

}
