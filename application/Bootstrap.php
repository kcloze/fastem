<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initConfig()
	{
		Zend_Registry::set('config', new Zend_Config($this->getOptions()));
	}

	protected function _initDatabases()
	{
		$this->bootstrap('db');
		$db = $this->getResource('db');
		Zend_Registry::set('db', $db);
	}
}
