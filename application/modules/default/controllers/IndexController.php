<?php 
class IndexController extends Zend_Controller_Action
{

	private $_sl = null;
	private $_redirector = null;

	public function init()
	{
		$this->_redirector = $this->_helper->getHelper('Redirector');
		if ($this->_hasParam('sl')) {
			$this->_sl = $this->_getParam('sl');
			$this->_forward('sload');
		}
	}

	public function indexAction()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
			var_dump($identity);
		} else {
			$this->_redirector->gotoSimple('index', 'index', 'login');
		}
	}
	public function sloadAction() 
	{
		if (null != $this->_sl) {
			if (strstr($this->_sl, ',')) {
				$ex = explode(',', $this->_sl);
			} else {
				$ex = array($this->_sl);
			}
			$adStr = 'function __g(v){return document.getElementById(v);}';
			$db = Zend_Registry::get('db');
			foreach ($ex as $s) {
				$zid = intval($s);
				if (!empty($zid)) {
					$rt = $db->fetchRow("SELECT * FROM adbanner WHERE zoneid=" . $db->quote($zid) . " AND   status='0';");
					if (is_array($rt)) {
						$adContent = "<a href=\"" . $rt['url'] . "\" ";
						if (!empty($rt['tracid'])) {
						    $adContent .= " onclick=\"javascript:_gaq.push(['_trackPageview','/fastem/" . $rt['tracid'] . ");\"";
						}
						$adContent .= ">";
						$adContent .= "<img src=\"" . $rt['image'] . "\" style=\"width:" . $rt['width'] . "px;height:" . $rt['height'] . "px;border:0px\"></a>";
						$adStr .= "__g('_sl_" . $rt['zoneid'] . "').innerHTML='". addslashes($adContent) . "';";
						unset($adContent);
					}
				}
			}
			$this->view->adstr = $adStr;
		}
	}

}


