<?php

class UserManager extends xml_manager {
	
	var $Module;
	
	function UserManager( $File ) 
	{
		global $_SESSION;
		
		// Leggo il file di configurazione dei moduli
		$this->xml_manager();
		$this->load( $File );
		
		// Controllo lo stato di login
		$this->checkLogin();
	} 
	
	function checkLogin()
	{
		if ($_REQUEST['action'] == 'logout') {
			$this->doLogout();
			header('Location: '.getRoot().'login.php?root=' . getRoot());
			exit;
		}
		if ($_REQUEST['action'] == 'resetModule') {
			unset($_SESSION['FF']['UserModule']);
			header('Location: '.getRoot().'login.php?root=' . getRoot());
			exit;
		}
		if($_SESSION['FF']['LOG_STATUS'] == true) {
			$this->Logged = true;
			if(is_string($_SESSION['FF']['User'])) {
				// Utente GOD
				$this->Name = 'God';
				$this->Type = 99;
				$this->Pwd	= $_SESSION['FF']['UserPwd'];
			} else {
				// Utente normale
				$this->Name = $_SESSION['FF']['User']['attributes']['name'];
				$this->Pwd 	= $_SESSION['FF']['User']['attributes']['pwd'];
				$this->Type	= $_SESSION['FF']['User']['attributes']['type'];
			}
			if(!isset($_SESSION['FF']['UserModule']))
				$this->doSelectModule();
			else {
				$this->Module = $_SESSION['FF']['UserModule'];
				$this->Conf = $this->getUserObjByModule($this->Name, $this->Module);
				$this->redirect();
			}
		} else {
			$_SESSION['FF']['LOG_STATUS'] = false;
			$this->doLogin();
		}
	}
	
	
	function checkModule()
	{
		$res = false;
		$Modules = $this->getAvailableModules();

		foreach ($Modules as $Module) {
			if($_SESSION['FF']['UserModule'] == $Module['attributes']['PATH'])
				$res = true;
		}

		if($res === true) {
			$this->Module = $_SESSION['FF']['UserModule'];
			$_SESSION['FF']['LoginChainComplete'] = true;
			if($_REQUEST['action'] == 'selectModule') {
				header('Location: index.php?status=checkModuleOk');
				exit;
			}
		} else {
			$this->doSelectModule();
		}
		
	}
		
	function doLogin()
	{
		if($_REQUEST['action'] == 'login') {
			
			// Postato da login.php la password, controllo
			
			$User = $this->getUserObj($_REQUEST['pwd']);
			if($User !== false) {
				// L'utente esiste, lo salvo in sessione e rifaccio il test del login
				$_SESSION['FF']['LOG_STATUS'] = true;
				$_SESSION['FF']['User'] = $User;
				if(is_string($User) && $User == 'GOD') 
					$_SESSION['FF']['UserPwd'] = md5($_REQUEST['pwd']);
				$this->checkLogin();
			} else {
				// L'utente non esiste, messaggio di errore
				header('Location: login.php?action=loginError&root=' . getRoot());
				exit;
			}
			
		} else if($_REQUEST['action'] != 'startLoginChain' && $_REQUEST['action'] != 'loginError') {
			
			$_SESSION['FF']['LOG_STATUS'] = false;
			unset($_SESSION['FF']['User']);
			header('Location: '.getRoot().'login.php?action=startLoginChain&root=' . getRoot());
			exit;
			
		}
	}
	
	function getUserObjByModule($user, $module)
	{
		$ModuleXml = $this->getXmlDom(getRoot() . 'modules/' . $module . '/config.xml');
		$res = $ModuleXml->query('/config/users/account[@name = "'.$user.'"]')->item(0);
		return $res;
	}
	
	function getTables()
	{
		return $this->Conf->getElementsByTagName('table');
	}
		
	function getUserObj($Pwd)
	{
		$res = false;
		
		$Modules = $this->getAvailableModules();
		
		foreach ($Modules as $Module) {
			
			$ModuleXml = $this->getXmlDom(getRoot() . 'modules/' . $Module->getAttribute('path') . '/config.xml');

			// Controllo tra gli utenti semplici
			$check = $ModuleXml->query('//account[@pwd = "'.$Pwd.'"]');
			if($check->length > 0) {
				$res = $this->getNodeObj($check->item(0));
			}
			
			// Controllo l'amministratore GOD
			if($res === false) {
				if($ModuleXml->query('//adminpwd')->item(0)->textContent == md5($Pwd))
					$res = 'GOD';
			}
		}
		return $res;
	}
	
	function getAvailableModules()
	{
		$ModuleDom = $this->getXmlDom(getRoot() . 'modules/modules.conf.xml');
		return $ModuleDom->query('//module');
	}
	
	function getUserModules()
	{
		$AvailModules = array();
		$ModulesDom = $this->getXmlDom(getRoot() . 'modules/modules.conf.xml');
		$Modules = $ModulesDom->query('//module');
		foreach ($Modules as $Module) {
			$ModuleDom = $this->getXmlDom(getRoot() . 'modules/' . $Module->getAttribute('path') . '/config.xml');
			
			// Controllo tra gli utenti semplici e l'amminstratore GOD
			if(
				$ModuleDom->query('//account[@pwd = "'.$this->Pwd.'"]')->length > 0 ||
				$ModuleDom->query('//adminpwd')->item(0)->textContent == $this->Pwd
			)
				array_push($AvailModules, $this->getNodeObj($Module));
		}
		array_unique($AvailModules);
		return $AvailModules;
	}
	
	function doSelectModule()
	{
		if($_REQUEST['action'] == 'selectModule' && isset($_REQUEST['module_path'])) {
			
			// Postato da login.php il modulo, controllo
			$_SESSION['FF']['UserModule'] = $_REQUEST['module_path'];
			$this->Module = $_REQUEST['module_path'];
			
			// Setto le impostazioni utente corrette per il modulo selezionato
			if(!is_string($_SESSION['FF']['User'])) {
				$UserNode = $this->getUserObjByModule($_SESSION['FF']['User']['attributes']['name'], $this->Module);
				$_SESSION['FF']['User'] = $this->getNodeObj($UserNode);
			}
			
			//header('Location: '.getRoot().'index.php?loginChain=complete&Module='. $_SESSION['FF']['UserModule']);
			exit;
			
		}
	}
	
	function isGOD()
	{
		if($this->Type == 99) 
			return true;
		else	
			return false;
	}
	
	function isAdmin()
	{
		if($this->Type == 99 || $this->Type == 1) 
			return true;
		else	
			return false;
	}
	
	function hasTablePermission($tableName)
	{
		$res = false;
		if($this->isAdmin()) 
			$res = true;
		else {
			foreach($this->Conf->getElementsByTagName('table') as $table) {
				if($table->getAttribute('name') == $tableName)
					$res = true;
			}
		}
		return $res;
	}
	
	function doLogout()
	{
		unset($_SESSION['FF']);
	}
	
	function redirect()
	{
		if(stripos($_SERVER['PHP_SELF'], 'login') !== false) {
			header('Location: '.getRoot().'index.php');
			exit;
		}
	}
	
}


?>