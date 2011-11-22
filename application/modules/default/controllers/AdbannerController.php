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
		$qFilter = $this->_request->getQuery('filter');
		if (!empty($qFilter)) {
			$sCode = array(
				'declined' => -2,
				'pending' => -1,
				'normal' => 0,
				'expired' => 1
			);
			if (in_array($qFilter, $sCode)) {
				$status = $sCode[$qFilter];
			}
		}
		$db = Zend_Registry::get('db');
		$sql = 'SELECT a.*, b.name AS adzone_name FROM adbanner AS a LEFT JOIN adzone AS b ON a.zoneid=b.id ';
		if (isset($status)) {
			$sql .= " WHERE a.status = $status; ";
		} else {
			$sql .= ";";
		}
		$adbanner = $db->fetchAll($sql);
		$this->view->adbanner = $adbanner;
		$this->view->messages = $this->_flashMessenger->getMessages();
		$this->view->qFilter = $qFilter;
		$this->view->urlArr = array('action'=>'index', 'controller'=>'adbanner', 'module'=>'default');
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
		$adtype = intval($this->_getParam('adtype'));
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
			'adtype' => $adtype
		);
		$db = Zend_Registry::get('db');
		$db->insert('adbanner', $data);
		$this->_flashMessenger->addMessage('成功增加一条广告');
		$this->_redirector->gotoSimple('index');
	}
	public function editAction() {
		$id = intval($this->_getParam('id')); 
		if (empty($id)) {
			$this->_flashMessenger->addMessage('没有提供id');
			$this->_redirector->gotoSimple('index');
		}
		$db = Zend_Registry::get('db');
		$adbanner = $db->fetchRow("SELECT * FROM adbanner WHERE id = '" . $id . "';");
		if (false == $adbanner) {
			$this->_flashMessenger->addMessage('找不到要编辑的广告');
			$this->_redirector->gotoSimple('index');
		}
		$adzone = $db->fetchAll("SELECT * FROM adzone;");

		$this->view->adbanner = $adbanner;
		$this->view->adzone = $adzone;
	}
	public function saveeditAction() {

		if (!$this->_request->isPost()) {
			$this->_redirector->gotoSimple('index');
		}

		$id = intval($this->_getParam('id')); 
		if (empty($id)) {
			$this->_flashMessenger->addMessage('没有提供id');
			$this->_redirector->gotoSimple('index');
		}
		$name = $this->_getParam('name');
		if (empty($name)) {
			$this->_flashMessenger->addMessage('广告名不能为空');
			$this->_redirector->gotoSimple('index');
		}
		$image = $this->_getParam('image');
		if (empty($image)) {
			$this->_flashMessenger->addMessage('广告图片不能为空');
			$this->_redirector->gotoSimple('index');
		}
		$url = $this->_getParam('url');
		if (empty($url) ) {
			$this->_flashMessenger->addMessage('广告目标链接不能为空');
			$this->_redirector->gotoSimple('index');
		}
        $zoneid = intval($this->_getParam('zoneid'));
		if (empty($zoneid) || !is_numeric($zoneid)) {
			$this->_flashMessenger->addMessage('广告位没有选择');
			$this->_redirector->gotoSimple('index');
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
		$adtype = intval($this->_getParam('adtype'));
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
			'adtype' => $adtype
		);
		$db = Zend_Registry::get('db');
		$db->update('adbanner', $data, 'id = ' . $id);
		$this->_flashMessenger->addMessage('编辑广告成功');
		$this->_redirector->gotoSimple('index');
	}
	public function deleteAction() {
		$id = intval($this->_getParam('id'));
		if ($id ==0 ) {
			$this->_flashMessenger->addMessage('ID 非法');
			$this->_redirector->gotoSimple('index');
		} else {
			$db = Zend_Registry::get('db');
			$n = $db->delete('adbanner', 'id = ' . $id);
			$this->_flashMessenger->addMessage('成功删除一条广告位');
			$this->_redirector->gotoSimple('index');
		}
	}
}

