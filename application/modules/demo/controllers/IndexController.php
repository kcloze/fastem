<?php 
class Demo_IndexController extends Zend_Controller_Action
{
	public function init() {
		$this->_helper->layout->disableLayout();
	}

	public function indexAction()
	{
		$db = Zend_Registry::get('db');
		$adz = $db->fetchAll("SELECT * FROM adzone;");
		$this->view->adzone = $adz;
	}
}


