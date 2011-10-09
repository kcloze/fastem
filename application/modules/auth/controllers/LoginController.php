<?php
class Auth_LoginController extends Zend_Controller_Action
{
	private $_redirector = null;
	private $_flashMessenger = null;

	public function init() {
		$this->_helper->layout->disableLayout();
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}

	public function indexAction()
	{
		if ($this->_request->isPost()) {
			$username = $this->_request->getPost('username');
			if (empty($username)) {
				$this->_flashMessenger->addMessage('请输入用户名');
				$this->_redirector->gotoSimple('index');
			}
			$password = $this->_request->getPost('password');
			if (empty($password)) {
				$this->_flashMessenger->addMessage('请输入密码');
				$this->_redirector->gotoSimple('index');
			}
			$db = $this->_getParam('db');
			$adapter = new Zend_Auth_Adapter_DbTable(
				$db,
				'user',
				'username',
				'password',
				'MD5(CONCAT(?, salt))'
			);

			$adapter->setIdentity($username);
			$adapter->setCredential($password);

			$auth = Zend_Auth::getInstance();


			$result = $auth->authenticate($adapter);

			if ($result->isValid()) {
				$cookie = new Zend_Http_Cookie('fastem_inadmin', 'true_in_fastem', $_SERVER['SERVER_NAME'], time() + 7200, '/');
				$this->_flashMessenger->addMessage('您已经成功登陆');
				$this->_redirector->gotoSimple('index', 'index', 'index');
			} else {
				$this->_flashMessenger->addMessage('登陆失败');
			}
				$this->_redirector->gotoSimple('index');
		}
		$this->view->messages = $this->_flashMessenger->getMessages();
	}

}
