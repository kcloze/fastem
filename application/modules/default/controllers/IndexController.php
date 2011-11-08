<?php 
class IndexController extends Zend_Controller_Action
{

	private $_sl = null;
	private $_redirector = null;
	private $_flashMessenger = null;

	public function init()
	{
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
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
			$this->view->messages = $this->_flashMessenger->getMessages();
		} else {
			$this->_redirector->gotoSimple('index', 'login', 'auth');
		}
	}
	public function sloadAction() 
	{
		$this->_helper->layout->disableLayout();

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
						switch ($rt['adtype']) {
						case 0:
							$adStr .= $this->_buildImageLink($rt);
							break;
						case 1:
							$adStr .= $this->_buildIframeLink($rt);
							break;
						}

					}
				}
			}
			$this->view->adstr = $adStr;
			$response = $this->getResponse();
			$expireTime = 6000;
			$response->setHeader('Content-Type', 'text/javascript;charset=UTF-8', TRUE);
			$response->setHeader('Pragma', 'public', TRUE);
			$response->setHeader('Cache-Control', 'max-age=' . $expireTime, TRUE);
            $response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $expireTime) . ' GMT', TRUE);

		} else {
			exit();
		}
	}

	private function _buildImageLink($d) {
		$adContent = "<a href=\"" . $d['url'] . "\" ";
		if (!empty($d['tracid'])) {
			$adContent .= " onclick=\"javascript:_gaq.push(['_trackEvent','" . $d['name'] . "', 'clicked', '" . $d['url'] . "']);\" ";
		}
		$adContent .= " target=\"_blank\">";
		$adContent .= "<img src=\"" . $d['image'] . "\" width=\"" . $d['width'] . "\" height=\"" . $d['height'] . "\" style=\"border:0px\"></a>";
		$adStr = "__g('_sl_" . $d['zoneid'] . "').innerHTML='". addslashes($adContent) . "';";
		unset($adContent);
		return $adStr;
	}

	private function _buildIframeLink($d) {
		$adContent = "<iframe src=\"" . $d['url'] . "\" width=\"" . $d['width'] . "\" height=\"" . $d['height'] . "\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" border=\"0\" frameborder=\"0\" style=\"border:none\"></iframe>";
        $adStr = "__g('_sl_" . $d['zoneid'] . "').innerHTML='". addslashes($adContent) . "';";
		unset($adContent);
		return $adStr;
	}

}


