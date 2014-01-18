<?php

class Application extends xml_manager {
	
	var $Root, $inc_dir, $Host, $DB_server = '';
	
	function Application($Module) 
	{
		global $_SESSION;
		global $DO_LOGIN;
		
		$this->Root = getRoot();		// getRoot è una funzione di Engine
		$this->load($this->Root . $Module);
		
		$_SESSION['FF'];
		$_SESSION['FF']['FILTERS'];
		
		$this->Module 					= $Module;
		$this->inc_dir 					= $this->Root . 'inc';
		$this->conf_dir 				= $this->Root . 'conf';
		$this->Host 					= $_SERVER['HTTP_HOST'];
		$this->DB_server 				= str_replace(".", "", $this->Host);
		$this->Context 					= 'Borning';
		$this->CurrentTable				= $_REQUEST['table'];
		$this->ConfigObj				= $this->getNodeChildrensAsObj('//admin', array('nodevalueOnly'));
		$this->DOMObj					= $this->query('/config/admin')->item(0);
		$this->ErrorLogFile 			= $this->inc_dir . '/errorlog.txt';
		$this->ErrorLogCriticalWeight	= 500000;
		$this->BackupsCriticalNumber	= 30;
		$this->jsMessages				= array();
		$this->dump_dir					= $_SERVER['DOCUMENT_ROOT'] . $this->ConfigObj['adminpath'] . 'dumps/';
		
		/* Istanzo l'Object DB */
		$DB = $this->getNodeChildrensAsObj("//database/" . $this->DB_server, array('nodevalueOnly'));
		$this->DB_settings = array();
		$this->DB_settings['host'] 	= $DB['host'];
		$this->DB_settings['user'] 	= $DB['user'];
		$this->DB_settings['pwd'] 	= $DB['pwd'];
		$this->DB_settings['name'] 	= $DB['name'];
		$this->DB_settings['conn'] 	= false;
		
		/* Faccio i controlli sullo stato dell'applicazione */
		$this->checkUp();
		
	}
	
	function setGlobalJsVars()
	{
		global $Table;
		global $print;
		
		echo '<script>';
		switch($this->Context) {
			
			case 'AdminConfiguring':
				echo 'var table = "'.$Table->name.'";
				var RootDir = "'.$this->Root.'";';
				break;
			
			case 'Console':
				break;
				
			case 'Borning':
				break;
			
			default:
				echo 'var RootDir = "'.$this->Root.'";
				var table = "'.$Table->name.'";';
				break;
			
		} 
		echo 'var dictionary = {};';
		foreach ($print as $lemma => $text) {
			echo '
			dictionary.'.$lemma.' = "'.$text.'"; ';
		}
		echo '
		var multifieldSeparator = "'.$this->ConfigObj['multifieldseparator'].'";';
		echo '</script>
';
	}
		
	function print_settings_menu()
	{
		global $User,$print;
		if($User->Type > 1) {
			echo '<li><a href="#">Settings</a>'.
				'<ul class="SecondLevel">'.
				'<li><a href="#" onclick="FF.Console.show(\''.$this->Root.'\');">'.$print['show_console'].'</a></li>';
				if($this->Context != 'AdminConfiguring') {
					echo '<li><a href="conf/configurator.php?table='.$this->CurrentTable.'">'.$print['configure'].'</a></li>';
				}
				echo '</ul>'.
			'</li>';
		}
	}
	
	function getAvailableLanguages()
	{
		return $this->getDirFiles($this->Root . 'languages');
	}
	
	function setGlobalAppVars()
	{
		global $Table;
		
		echo '<script>';
		echo "FF.AppContext 	= '" . $this->Context . "';";
		if(isset($Table) && isset($Table->name))
			echo "FF.CurrentTable 	= '" . $Table->name . "';";
		echo "Dashboard.currentAppDir = '" . $this->Root . "';";
		echo '</script>
';
	}
	
	function getRestoreFile()
	{
		if($this->query('/config/backup')->length > 0)
			return $this->query('/config/backup')->item(0)->getAttribute('restored_from');
	}
	
	function isRestorationFile($file)
	{
		if($this->getRestoreFile() == $file)
			return true;
		else
			return false;
	}
	
	function saveConfig($msg = '')
	{
		_log($msg);
		// Salvando una nuova configurazione, devo togliere il restored_from attribute
		if($this->query('/config/backup')->item(0)->hasAttribute('restored_from'))
			$this->query('/config/backup')->item(0)->removeAttribute('restored_from');
		
		$this->dom->save($this->file);
	}
	
	function doBackup($msg = '')
	{
		$this->query('/config/backup')->item(0)->nodeValue = $msg;
		list($modulesDir, $module, $file) = explode("/", $this->Module);
		$file = "BACKUP_".date("Y-m-d")."_".time().".xml";
		_log($msg . "<br />SAVE backup: " . $this->Root . $modulesDir . "/" . $module . "/" . $file);
		$this->dom->save( $this->Root . $modulesDir . "/" . $module . "/" . $file);
	}
	
	function getBackups()
	{
		$res = array();
		if(is_dir($this->getModuleDir())) {
			$handle = opendir($this->getModuleDir());
			while (($file = readdir($handle)) !== false) {
				if($file != "." && $file != ".." && strpos($file, "BACKUP") !== false) {
					array_push($res, $file);
				}
			}
			rsort($res);
			return $res;
		} else {
			_log("ERROR READING MODULE DIR (".$this->getModuleDir().")");
			echo "ERROR READING MODULE DIR (".$this->getModuleDir().")";
		}
	}
	
	function getModuleDir()
	{
		list($modulesDir, $module, $file) = explode("/", $this->Module);
		return $this->Root . $modulesDir . "/" . $module;
	}
	
	function getModuleRootDir()
	{
		list($modulesDir, $module, $file) = explode("/", $this->Module);
		return $this->Root . $modulesDir;
	}
	
	function requestIsValid($type)
	{
		switch($type) {
			case 'UpdateAdminPrefs':
				if($_REQUEST['title'] != '')
					return true;
				else {
					_log("App->requestIsValid => UpdateAdminPrefs request needs title and it's ''");
					return false;
				}
				break;
			default:
				_log("App->requestIsValid => Unknown type of request. ($type)");
				return false;
				break;
		}
	}
	
	function reloadConfiguration()
	{
		$this->Application($this->Module);
	}
	
	function getTablesNode() 
	{
		return $this->query('/config/tables')->item(0);
	}
	
	function getTables() 
	{
		return $this->query('/config/tables/table');
	}
	
	function addTable($table)
	{
		if($table != '' && $table != null) {
			$tables = $this->getTablesNode();
			$newtable = $this->dom->createElement('table');
			$newtable->setAttribute('name', $table);
			$newtable->setAttribute('title', $table);
			$tables->appendChild($newtable);
			
			$this->dom->save( $this->file );
		}
	}
	
	function removeTable($table)
	{
		$todelete = $this->getTable($table);
		if($todelete !== false) {
			$todelete->parentNode->removeChild($todelete);
			$this->dom->save( $this->file );
		}
	}
	
	function isTableVisible($table)
	{
		$Conf = $this->getTableConfiguration($table);
		if($Conf['attributes']['visible'] == 'true')
			return true;
		else
			return false;
	}
	
	function getTable($name)
	{
		$res = $this->query('/config/tables/table[@name = "'.$name.'"]');
		if($res->length > 0)
			return $res->item(0);
		else 
			return false;
	}
	
	function getTableDescription($Table) 
	{
		$res = $this->query('/config/tables/table[@name = "'.$Table.'"]/description')->item(0)->textContent;
		if($res != null)
			return $res;
		else 
			return '';
	}
	
	function hasTable($table)
	{
		$check = $this->query('/config/tables/table[@name = "'.$table.'"]');
		if($check->length > 0)
			return true;
		else
			return false;
	}
	
	function getTableDOM($Table)
	{
		return $this->query('/config/tables/table[@name = "'.$Table.'"]')->item(0);
	}
	
	function getTableConfiguration($Table) 
	{
		return $this->getNodeObj($this->getTableDOM($Table));
	}
	
	function getTablePlugins($Table)
	{
		if($this->query('//table[@name = "'.$Table.'"]/plugins/plugin')->length > 0) 
			return $this->query('//table[@name = "'.$Table.'"]/plugins/plugin');
		else
			return false;
	}
	
	function getObjAttributes($Obj) 
	{
		$res = array();
		foreach ($Obj['attributes'] as $Name => $Attr) {
			$res[$Name] = $Attr;
		}
		return $res;
	}
	
	function getConfigObj()
	{
		return $this->ConfigObj;
	}
	
	function getNumConnections()
	{
		$res = 0;
		foreach($this->query('//database')->item(0)->childNodes as $child) {
			if($child->nodeName != '#text')
				$res++;
		}
		return $res;
	}
	
	function getConfigFilePath()
	{
		return $this->getModuleDir() . "/config.xml";
	}
	
	function getAdminMenu()
	{
		/*$Menu = $this->getNodeObj($this->query("//adminmenu")->item(0));
		return $Menu['childrens'];*/
		return $this->query('//menuheader');
	}
	
	function getDirFiles($Dir) 
	{
		$res = array();
		if (is_dir($Dir)) {
			if ($dh = opendir($Dir)) {
				while (($file = readdir($dh)) !== false) {
					if($file != "." && $file != "..") 
						array_push($res, $file);
				}
				closedir($dh);
				return $res;
			} else 
				return false;
		} else {
			return false;
		}
	}
	
	function uploadFile($field, $table)
	{
		if(isset($_FILES[$field]['name'])) {
			$uploadDir = $this->ConfigObj['adminloadingpath'];
			$dir = $_SERVER['DOCUMENT_ROOT'].$uploadDir.$table."/";
			if(!is_dir($dir)) {
				_log("[F = uploadFile]: ".$dir . " not found<br />Must create");
				if(mkdir($dir, 0777)) {
					_log("[F = uploadFile]: Directory created successfully!");
					if(chmod($dir, 0777))
						_log("[F = uploadFile]: chmoded 777 successfully!");
				} else {
					_log('[F = uploadFile]: Not able to create directory "'.$dir.'"');
					$_ERROR = 'Impossibile trovare e creare la directory';
				}
			}
			if(is_dir($dir)) {
			 	_log("[F = uploadFile]: " . $dir . " exists, proceed.");
				$fileName = str_replace(" ", "-", $_FILES[$field]['name']);
				_log('[F = uploadFile]: Uploading "' . $fileName . '"');
				$fileName = $this->checkFileName($fileName, $dir);
				$fileNameTemp = $_FILES[$field]['tmp_name'];
				if(move_uploaded_file($fileNameTemp, $dir.$fileName)) {
					_log("[F = uploadFile]: File successfully uploaded!");
					$_ERROR = false;
					return $fileName;
				} else {
					_log("[F = uploadFile]: Error moving uploading file to destination dir");
					$_ERROR = 'Impossibile copiare il file nella directory specificata';
				}
			} else {
				_log("[F = uploadFile]: Not possible to find or create the specified directory");
				$_ERROR = 'Impossibile trovare la directory specificata';
			}
			
		} else {
			_log("[F = uploadFile]: Unable to find the file to upload");
			$_ERROR = 'Impossibile trovare il file da caricare';
		}
		if($_ERROR) 
			return false;
	}
	
	function checkFileName($fileName, $dir) {
		$i = 0;
		$suf = "";
		while(is_file($dir.$suf.$fileName)) {
			$i++;
			$suf = $i;
		}
		return $suf.$fileName;
	}

	function removeFile($file, $dir) {
		if(unlink($dir.$file)) {
			_log("Successfully removed file: " . $dir . $file);
			return true;
		} else {
			_log("Error removing file: " . $dir . $file);
			return false;	
		}
	}
	
	function removeRessource( $_target ) { 
    
	    if( is_file($_target) ) { 		//file? 
	        if( is_writable($_target) ) { 
	            if( @unlink($_target) ) { 
	                return true; 
	            } 
	        } 
	        return false; 
	    } 
	    if( is_dir($_target) ) { 		//dir? 
	        if( is_writeable($_target) ) { 
	            foreach( new DirectoryIterator($_target) as $_res ) { 
	                if( $_res->isDot() ) { 
	                    unset($_res); 
	                    continue; 
	                }	                    
	                if( $_res->isFile() ) { 
	                    $this->removeRessource( $_res->getPathName() ); 
	                } elseif( $_res->isDir() ) { 
	                    $this->removeRessource( $_res->getRealPath() ); 
	                } 
	                unset($_res); 
	            } 
	            if( @rmdir($_target) ) { 
	                return true; 
	            } 
	        } 
	        return false; 
	    } 
	} 
	
	function getUsers()
	{
		return $this->query('//users/account');
	}
	
	function getUserByName($username)
	{
		if($this->userExists($username))
			return $this->query('//users/account[@name="'.$username.'"]')->item(0);
		else
			return false;
	}
	
	function getUserTables($username)
	{
		global $User;
		$tables = null;
		if($this->userExists($username)) {
			$user = $this->getUserByName($username);
			$tables = $this->query('.//table', $user);
		} else if($User->isAdmin($username))
			$tables = $this->getTables();
		if($tables->length > 0) 
			return $tables;
		else 
			return false;
	}
	
	function getUserTablesArray($username)
	{
		$tables = $this->getUserTables($username);
		if($tables !== false) {
			$res = array();
			foreach($tables as $table) {
				array_push($res, $table->getAttribute('name'));
			}
			return $res;
		}
	}
	
	function getTotUsers()
	{
		return $this->query('//users/account')->length;
	}
	
	function getPluginDir() 
	{
		return $App->Root . 'plugins/';
	}
	
	function userExists($username)
	{
		$user = $this->query('//users/account[@name = "'.$username.'"]');
		if($user->length > 0)
			return true;
		else
			return false;
	}
	
	function addUser($name, $type, $pwd)
	{
		$users = $this->query('/config/users');
		$newUser = $this->dom->createElement('account');
		$newUser->setAttribute('name', $name);
		$newUser->setAttribute('type', $type);
		$newUser->setAttribute('pwd', $pwd);
		$newUser->appendChild($this->dom->createElement('tables'));
		
		$users->item(0)->appendChild($newUser);
		$this->dom->save( $this->file );
	}
	
	function addUserTable($username, $table)
	{
		if($table != '' && $table != null) {
			$user = $this->getUserByName($username);
			$tables = $user->getElementsByTagName('tables');//$this->query('//tables', $user);
			$newtable = $this->dom->createElement('table');
			$newtable->setAttribute('name', $table);
			$tables->item(0)->appendChild($newtable);
			
			$this->dom->save( $this->file );
		}
	}
	
	function byteConvert($bytes)
	{
		$symbol = array('Bytes', 'Kb', 'Mb', 'Giga', 'Tb', 'PiB', 'EiB', 'ZiB', 'YiB');
		
		$exp = 0;
		$converted_value = 0;
		if( $bytes > 0 ) {
			$exp = floor( log($bytes)/log(1024) );
			$converted_value = ( $bytes/pow(1024,floor($exp)) );
		}		
		return sprintf( '%.2f '.$symbol[$exp], $converted_value );
	}
	
	function checkUp()
	{
		global $User;
		
		// Controllo la grandezza del file di log
		if($User->isAdmin()) 
			if(filesize($this->ErrorLogFile) > $this->ErrorLogCriticalWeight)
				$this->jsMessage('Errorlog file is heavy ('.$this->byteConvert(filesize($this->ErrorLogFile)).')', 'Error');
		
		// Controllo quanti backup ci sono
		if($User->isAdmin()) 
			if(count($this->getBackups()) > $this->BackupsCriticalNumber)
				$this->jsMessage('There are '.count($this->getBackups()).' backups!', 'Error');
	}
	
	function jsMessage($msg, $type)
	{
		array_push($this->jsMessages, array(
			'msg' 	=> $msg,
			'type'	=> $type
		));
	}
	
	function checkJsMessages()
	{
		if(count($this->jsMessages) > 0) {
			// Ci sono messaggi da mostrare via JS
			echo '<script>';
			foreach ($this->jsMessages as $report) {
				echo "FF.report('".$report['msg']."', '".$report['type']."');";
			}
			echo '</script>';
		}		
	}
	
	/*
	 * Crones
	 */
	function hasTableCrone($table, $crone) 
	{
		_log("has crone: " . $crone . "?");
		$table = $this->getTable($table);
		
		if ($this->query('.//crone[@name = "'.$crone.'"]', $table)->length > 0) 
			return true;
		else
			return false;
	}
	
	function setCrone($table, $croneName, $timedFor, $params) {
		
		$this->doBackup("Setting new crone ($croneName) for $table");
		$table = $this->getTable($table);
		
		// Se non ci sono crones aggiungo il container
		if($this->query('.//crones', $table)->length == 0) 
			$table->appendChild($this->dom->createElement('crones'));
		
		if(!$this->hasTableCrone($table, $croneName)) {
			_log("Creo il nodo crone");
			// Creo il crone node
			$newCrone = $this->dom->createElement('crone');
			$newCrone->setAttribute('id', $this->query('.//crones', $table)->length);
			$newCrone->setAttribute('name', $croneName);
			$newCrone->setAttribute('timedFor', $timedFor);
			$newCrone->setAttribute('params', $params);
			$this->query('.//crones', $table)->item(0)->appendChild($newCrone);
		} else 
			_log('hasTableCrone == true');
		
		_log("- 2 --");
		
		$this->dom->save( $this->file );
		
		_log('Setted new crone for table "'.$table.'", id: '.$newCrone->getAttribute('id').', name: '.$newCrone->getAttribute('name').', timed for: '.$newCrone->getAttribute('timedFor').', params: '.$newCrone->getAttribute('params').'');
		echo "SETCRONE";
	}
	
	function deleteCrone($croneId, $table) {
		/*
		global $_CONF;
		_log("Remove Crone (CONFIG>TABLES>TABLE.name=".$table.">CRONES>CRONE.id=".$croneId.".node)");
		$_CONF->remove("CONFIG>TABLES>TABLE.name=".$table.">CRONES>CRONE.id=".$croneId.".node");
		$_CONF->saveBuiltXML();
		*/
	}
	
	function checkCrones() {
		/*
		global $_CONF;
		$crones = $_CONF->get("TABLE>CRONES.childNodes", $this->CONF);
		if($crones !== false) {
			foreach($crones as $crone) {
				$croneTime = $_CONF->get("CRONE.timedfor", $crone);
				if(date("Y-m-d") >= $croneTime) {
					$croneName = $_CONF->get("CRONE.name", $crone);
					$croneId = $_CONF->get("CRONE.id", $crone);
					$croneParams = $_CONF->get("CRONE.params", $crone);
					$sep = $_CONF->get("CONFIG>ADMIN>MULTIFIELDSEPARATOR.nodeValue");
					$croneParams = explode($sep, $croneParams);
					_log("Run crone '$croneName' for ".$this->table);
					$return = runCrone($croneName, $croneParams);
					if ($return['status'] == "OK") {	// Crone andato a buon fine
						$this->deleteCrone($croneId, $this->table);
						_log("Executed CRONE (".$return['title']." - ".$return['status'].")");
						_logToPage("Eseguito CRONE (".$return['title']." - ".$return['status'].")");
					} else {
						$this->deleteCrone($croneId, $this->table);
						_log("CRONE execution error. Returned ".$return['status'].", (".$return['title']." - ".$return['status'].")");
						_logToPage("CRONE execution Failed! (".$return['title']." - ".$return['status']." - ".$return['error_detail'].")");
					}
				}
			}
		}*/
	}
}


?>