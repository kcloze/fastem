<?php 
class IndexController extends Zend_Controller_Action
{

    private $_redirector = null;

    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
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
}


