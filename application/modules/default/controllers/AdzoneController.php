<?php
class AdzoneController extends Zend_Controller_Action {
	private $_redirector = null;
	private $_flashMessenger = null;
	public function init() {
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirector->gotoSimple('index', 'login', 'auth');
		}
	}
	public function indexAction() {
		$db = Zend_Registry::get('db');
		$adzone = $db->fetchAll('SELECT * FROM adzone;');
		$this->view->adzone = $adzone;
		$this->view->messages = $this->_flashMessenger->getMessages();
		$this->view->server_name = $_SERVER['SERVER_NAME'];
	}
	public function addAction() {
		$this->view->messages = $this->_flashMessenger->getMessages();
	}
	public function saveaddAction() {
		if (!$this->_request->isPost()) {
			$this->_redirector->gotoSimple('index');
		}

		$name = $this->_getParam('name');
		if (empty($name)) {
			$this->_flashMessenger->addMessage('广告位名不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$width = $this->_getParam('width');
		if (empty($width) || !is_numeric($width)) {
			$this->_flashMessenger->addMessage('广告位宽度不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$height = $this->_getParam('height');
		if (empty($height) || !is_numeric($height)) {
			$this->_flashMessenger->addMessage('广告位高度不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$description = $this->_getParam('description');
		$data = array(
			'name' => $name,
			'width' => $width,
			'height' => $height,
			'description' => $description
		);
		$db = Zend_Registry::get('db');
		$db->insert('adzone', $data);
		$this->_flashMessenger->addMessage('成功增加一条广告位');
		$this->_redirector->gotoSimple('index');
	}
	public function editAction() {
		$id = intval($this->_getParam('id'));
		if (empty($id)) {
			$this->_flashMessenger->addMessage('没有指定要编辑的广告位');
			$this->_redirector->gotoSimple('index');
		}
		$db = Zend_Registry::get('db');
		$adzone = $db->fetchRow('SELECT * FROM adzone WHERE id = ' . $id);
		if (false == $adzone) {
			$this->_flashMessenger->addMessage('找不到要编辑的广告位');
			$this->_redirector->gotoSimple('index');
		}
		$this->view->adzone = $adzone;
	}
	public function saveeditAction() {
	if (!$this->_request->isPost()) {
			$this->_redirector->gotoSimple('index');
		}
	    $id = $this->_getParam('id');
		if (empty($id)) {
			$this->_flashMessenger->addMessage('对不起，没有指定要编辑的广告位');
			$this->_redirector->gotoSimple('index');
		}

		$name = $this->_getParam('name');
		if (empty($name)) {
			$this->_flashMessenger->addMessage('广告位名不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$width = $this->_getParam('width');
		if (empty($width) || !is_numeric($width)) {
			$this->_flashMessenger->addMessage('广告位宽度不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$height = $this->_getParam('height');
		if (empty($height) || !is_numeric($height)) {
			$this->_flashMessenger->addMessage('广告位高度不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$description = $this->_getParam('description');
		$data = array(
			'name' => $name,
			'width' => $width,
			'height' => $height,
			'description' => $description
		);
		$db = Zend_Registry::get('db');
		$db->update('adzone', $data, 'id = ' . $id);
		$this->_flashMessenger->addMessage('成功保存编辑广告位');
		$this->_redirector->gotoSimple('index');
	}
	public function deleteAction() {
		$id = intval($this->_getParam('id'));
		if ($id ==0 ) {
			$this->_flashMessenger->addMessage('ID 非法');
			$this->_redirector->gotoSimple('index');
		} else {
			$db = Zend_Registry::get('db');
			$n = $db->delete('adzone', 'id = ' . $id);
			$this->_flashMessenger->addMessage('成功删除一条广告位');
			$this->_redirector->gotoSimple('index');
		}
	}
}

