<?php
class Auth_IndexController extends Zend_Controller_Action
{
	public function init() {
		$this->_helper->layout->disableLayout();
	}

	public function indexAction()
	{
		if ($this->_request->isPost()) {
			$db = $this->_getParam('db');
			$adapter = new Zend_Auth_Adapter_DbTable(
				$db,
				'user',
				'username',
				'password',
				'MD5(CONCAT(?, salt))'
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
