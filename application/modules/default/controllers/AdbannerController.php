<?php
class AdbannerController extends Zend_Controller_Action {
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
		$adbanner = $db->fetchAll('SELECT a.*, b.name AS adzone_name FROM adbanner AS a LEFT JOIN adzone AS b ON a.zoneid=b.id;');
		$this->view->adbanner = $adbanner;
		$this->view->messages = $this->_flashMessenger->getMessages();
	}
	public function addAction() {
		$sql = "SELECT * FROM adzone;";
		$db = Zend_Registry::get('db');
		$adzone = $db->fetchAll($sql);
		$this->view->adzone = $adzone;
		$this->view->messages = $this->_flashMessenger->getMessages();
	}
	public function saveaddAction() {
		if (!$this->_request->isPost()) {
			$this->_redirector->gotoSimple('index');
		}

		$name = $this->_getParam('name');
		if (empty($name)) {
			$this->_flashMessenger->addMessage('广告名不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$image = $this->_getParam('image');
		if (empty($image)) {
			$this->_flashMessenger->addMessage('广告图片不能为空');
			$this->_redirector->gotoSimple('add');
		}
		$url = $this->_getParam('url');
		if (empty($url) ) {
			$this->_flashMessenger->addMessage('广告目标链接不能为空');
			$this->_redirector->gotoSimple('add');
		}
        $zoneid = intval($this->_getParam('zoneid'));
		if (empty($zoneid) || !is_numeric($zoneid)) {
			$this->_flashMessenger->addMessage('广告位没有选择');
			$this->_redirector->gotoSimple('add');
		}
		$sql = " SELECT width, height FROM adzone WHERE id='". intval($zoneid)  . "';";
		$db = Zend_Registry::get('db');
		$arr = $db->fetchRow($sql);
		$width = $arr['width'];
		$height = $arr['height'];
		$tracid = $this->_getParam('tracid');
		$uptime = strtotime($this->_getParam('uptime'));
		$downtime = strtotime($this->_getParam('downtime'));
		$status = intval($this->_getParam('status'));
		$data = array(
			'name' => $name,
			'image' => $image,
			'url' => $url,
			'tracid' => $tracid,
			'zoneid' => $zoneid,
			'uptime' => $uptime,
			'downtime' => $downtime,
			'status' => $status,
			'width' => $width,
			'height' => $height,
		);
		$db = Zend_Registry::get('db');
		$db->insert('adbanner', $data);
		$this->_flashMessenger->addMessage('成功增加一条广告');
		$this->_redirector->gotoSimple('index');
	}
	public function editAction() {
	}
	public function saveeditAction() {
	}
	public function deleteAction() {
		$id = intval($this->_getParam('id'));
		if ($id ==0 ) {
			$this->_flashMessenger->addMessage('ID 非法');
			$this->_redirector->gotoSimple('index');
		} else {
			$n = $db->delete('adbanner', 'id = ' . $id);
			$this->_flashMessenger->addMessage('成功删除一条广告位');
			$this->_redirector->gotoSimple('index');
		}
	}
}

