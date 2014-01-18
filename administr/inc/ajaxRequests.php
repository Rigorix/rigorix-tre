<?
header('Content-Type: text/html; charset=utf-8'); 
require_once ("Engine.php");
require_once ($App->inc_dir . "/class.mysqldumper.php");

$App->Context = 'AjaxRequest';
$Table = new Table($_SESSION['FF']['CurrentTable']);

switch($_GET['action']) {
	
	case 'getDataContextMenu': 
		echo '<ul>';
		echo '<li><a href="edit.php?action=EDIT&table='.$_REQUEST['table'].'&editField='.$_REQUEST['fieldId'].'&editId='.$_REQUEST['rowId'].'">Edit</a></li>';
		echo '<li><a href="javascript:void();" onclick="FF.Contents.deleteRow(\''.$_REQUEST['rowId'].'\', \''.$_REQUEST['fieldId'].'\', \''.$_REQUEST['table'].'\');">Delete</a></li>';
		echo '</ul>';
		break;

	case 'removeDatabaseConnection':
		$conns = $App->query('//config/database')->item(0)->childNodes;
		$conns = $App->removeTextChilds($conns);
		$conns->item($_REQUEST['index']-1)->parentNode->removeChild($conns->item($_REQUEST['index']-1));
		$App->doBackup("Updating Datas before removing Database Connection");
		$App->dom->save($App->file);
		$App->load($App->file);
		echo "OK";
		break;
	
	case 'refreshDatas':
		
		$Datas = $Table->getDatas();
		foreach ($Datas as $Data) {
			$firstField = true;

			/* Ciclo tutti i campi */
			foreach($Table->getFields() as $Name => $Type) {
				$_FIELD = $Table->getFieldConfig($Name);
				if($firstField === true) {
					$idValue = $Data->$Name;
					?>
					<tr id="row_<?php echo $Data->$Name; ?>" title="<?=$idValue?>,<?=$Name?>" class="<?=(($i%2==0)?"row1":"row2")?>">
					<td width="15"><input class="rowSelector" type="checkbox" onClick="FF.Contents.checkRow(this, this.checked);"><input type="hidden" id="idField" value="<?php echo $Name; ?>"></td>
					<!-- td width="15"><a href="#" class="roundButton" onClick="window.location='edit.php?action=EDIT&table=<?php echo $Table->name; ?>&editField=<?php echo $Name; ?>&editId=<?php echo $idValue; ?>'">Edit</a></td>
					<td width="15"><a href="#" class="roundButton" onClick="FF.Contents.deleteRow('<?php echo $idValue; ?>', '<?php echo $Name; ?>', '<?=$Table->name?>');">Del</a></td -->
					<?
					$firstField = false;
				} 
				if($Table->isFieldVisible($Name)) {
					
					// Faccio vedere il dato!
					$Table->printFieldData($idValue, $Name, $Data);
					
				}
				
			}
			echo "</tr>";
		}
		
		break;
	
	case 'autocomplete': 
		error_reporting(E_ERROR);
		$results = $Table->getArrayObjectQueryCustom("select ".$_REQUEST['field_name']." from " . $Table->name . " where ".$_REQUEST['field_name']." like '%".$_REQUEST['w']."%' order by ".$_REQUEST['field_name']." asc");
		if(count($results) > 0) {
			$ret = 'var results = new Array(';
			foreach ($results as $res) {
				$ret .= '"' . $res->$_REQUEST['field_name'] . '",';
			}
			$ret = substr($ret, 0, -1);
			$ret .= ');';
			echo $ret;
		} else {
			echo 'var results = new Array();';
		}		
		break;
	
	case 'saveSessionVar':
		$_SESSION['FF'][$_GET['name']] = $_GET['value'];
		break;
		
	case 'getSessionVar':
		echo $_SESSION['FF'][$_GET['name']];
		break;
	
	case 'setRowCompressedView':
		if(isset($_SESSION['FF']['RowCompressedView']) && $_SESSION['FF']['RowCompressedView'] == true)
			$_SESSION['FF']['RowCompressedView'] = false;
		else 
			$_SESSION['FF']['RowCompressedView'] = true;
		break;
	
	case 'removeModule':
		$path = $_REQUEST['path'];
		$ModulesDom = new xml_manager();
		$ModulesDom->load(getRoot() . 'modules/modules.conf.xml');
		$Module = $ModulesDom->query('//module[@path = "'.$path.'"]')->item(0);
		$Module->parentNode->removeChild($Module);
		$ModulesDom->dom->save( $ModulesDom->file );
		echo "OK";
		break;
	
	case 'createModule':
		$_SESSION['FF']['newmodule']['newModuleName'] = $_REQUEST['name'];
		$_SESSION['FF']['newmodule']['newModuleDir'] = $_REQUEST['dir'];
		echo "OK";
		break;
	
	case 'setModuleProps':
		foreach ($_REQUEST as $k => $v) {
			$_SESSION['FF']['newmodule'][$k] = $v;
		}
		$_SESSION['FF']['newmodulestatus'] = true;
		echo "OK";
		break;
	
	case 'find':
		if(!isset($_REQUEST['truncate']))
			$truncate = 200;
		else
			$truncate = $_REQUEST['truncate'];
			
		$FinderTable = new Table($_REQUEST['table']);
		$Fields = $FinderTable->getFields();
		$FieldsName = array_keys($Fields);
		$query = "select ";
		foreach ($Fields as $field => $type) {
			if($type == 'string' || $type == 'blob') 
				$query .= "SUBSTRING($field, 1, $truncate) as $field, ";
			else 
				$query .= "$field, ";
		}
		$query = substr($query, 0, (strlen($query)-2));
		$query .= " from " . $FinderTable->name . " where ";
		foreach ($Fields as $field => $type) {
			$query .= $field . " like '%".$_REQUEST['w']."%' or ";
		}
		$query = substr($query, 0, (strlen($query)-4));
		$i = -1;
		foreach($FinderTable->getArrayObjectQueryCustom($query) as $Res) {
			$i=0;			
			?>
			<tr id="row_<?php echo $Res->$FieldsName[$i]; ?>" title="<?=$Res->$FieldsName[$i]?>,<?=$FieldsName[$i]?>" class="<?=(($i%2==0)?"row1":"row2")?>">
			<td width="15"><input style="visibility: hidden" class="rowSelector" type="checkbox" onClick="FF.Contents.checkRow(this, this.checked);"><input id="idField" type="hidden" value="<?php echo $FieldsName[$i]; ?>"></td>
			<?
			foreach ($Res as $Field) {
				if($FinderTable->isFieldVisible($FieldsName[$i])) {
					$truncate_str = '';
					echo $FinderTable->printFieldData('-', $FieldsName[$i], $Res);
				}
				$i++;
			}
			echo '</tr>';
		}
		if($i == -1)
			echo "<no_result>";
		break;
	
	case '_log':
		_logToPage($_GET['logText']);
	
	case 'deletePageLogger':
		$_SESSION['FF']['pageLogger'] = null;
		break;
	
	case 'setDataPerPage':
		$Attr = $App->query('/config/tables/table[@name = "'.$_REQUEST['table'].'"]');
		$Attr->item(0)->setAttribute('dataperpage', $_REQUEST['num']);
		$App->doBackup("Updating Datas per page for table " . $_REQUEST['table']);
		$App->dom->save($App->file);
		
		unset($_SESSION['FF'][$_GET['table']]["dataperpage"]);
		// DA CANELLARE?
		echo "CONFIG>TABLES>TABLE.name=".$_GET['table'].".dataperpage >>>> ".$_GET['num'];
		break;
	
	case 'refreshFastEdited':
		echo "<htmlcode>";
		$Table = new table($_REQUEST['table']);
		$query = "SELECT ".$_REQUEST['campoGet']." FROM ".$Table->name." WHERE ".$Table->getIdField($Table->name)." = " . $_REQUEST['id'];
		$res = $Table->getSingleObjectQueryCustom($query);
		$_FIELD = $Table->getFieldConfig($_GET['campoGet']);
		if($res !== false) 
			$Table->printFieldData($_REQUEST['id'], $_REQUEST['campoGet'], $res);
		else 
			echo "KO";
		echo "<htmlcode>";
		break;
	
	case 'saveFastEdit':
		if($_GET['loadingType_' . $_GET['field']] == 'BROWSE') {
			$value = $Table->uploadFile($name . '_filesystem');
		}
		$query = "update ".$_GET['table']." set ".$_GET['field']." = '".$_GET[$_GET['field']]."' where ".$_GET['idField']." = '".$_GET['idValue']."' ";
		$res = $Table->query($query);
		if($res !== false) echo "OK";
		return $res;
		break;
		
	case 'removeUploadedFile': 
		$dir = $_SERVER['DOCUMENT_ROOT'].$App->ConfigObj['adminloadingpath'].$Table->name."/";
		$res = $App->removeFile($_GET['fileName'], $dir);
		if($res) 
			echo "OK";
		else 
			echo "KO";
		break;
		
	case 'removeUser': 
		$UserNode = $App->getUserByName($_REQUEST['userName']);
		if($UserNode !== false) {
			$UserNode->parentNode->removeChild($UserNode);
			$App->dom->save( $App->file );
			echo $_REQUEST['userName'];
		} else
			echo "ERROR_DELETING";
		break;
	
	case 'getFilterConsole':
		?><htmlcode><form id="filterConsole" action="content.php?table=<?php echo $_REQUEST['table']; ?>&action=SetFilter" method="post">
			<h2 style="font-weight: normal">Imposta il filtro <input type="submit" value="Filtra!" class="bigButton"></h2><br />
		<table cellspacing="0" cellpadding="5"><?
		$i=0;	
		
		foreach($Table->getFields($_REQUEST['table']) as $name => $type) {
			$Field = $Table->getFieldConfig($name);
			$generalOperand = $fieldFilter1Operand = $fieldFilter2Operand = $fieldFilter1Value = $fieldFilter2Value = false;
			
			if($Table->isFieldVisible($name)) {
				
				if($_SESSION['FF']['FILTERS'][$Table->name][$name] && count($_SESSION['FF']['FILTERS'][$Table->name][$name]) > 0) {
					$filterList = explode("****", $_SESSION['FF']['FILTERS'][$Table->name][$name]);
					for($i=0; $i<count($filterList); $i++) {
						if(($filterList[$i] == 'and' || $filterList[$i] == 'or') && count($filterList) == 3)
							$generalOperand = $filterList[$i];
						else if ($fieldFilter1Operand === false) 
							list ($fieldFilter1Operand, $fieldFilter1Value) = explode("=>", $filterList[$i]);
						else 
							list ($fieldFilter2Operand, $fieldFilter2Value) = explode("=>", $filterList[$i]);
					}
				}

				echo '<tr bgcolor="' . (($i%2==0) ? '#f3f3f3' : '#ffffff') . '"><td><strong>'.$Field['attributes']['title'].'</strong></td><td>
					<select name="'.$name.'_filterType">
					<option value="--">--</option>
					<option value="eq" '.(($fieldFilter1Operand == "eq")?"selected":"").'>Uguale a</option>
					<option value="noteq" '.(($fieldFilter1Operand == "noteq")?"selected":"").'>Diverso da</option>';
					if(!$Table->isFieldVirtual($Field) && !$Table->isFieldCross($Field)) {
						echo '<option value="present" '.(($fieldFilter1Operand == "present")?"selected":"").'>Contiene</option>
						<option value="notpresent" '.(($fieldFilter1Operand == "notpresent")?"selected":"").'>Non contiene</option>';
					}
					if($type == 'int' || $type == 'real') {
						echo '<option value="higher" '.(($fieldFilter1Operand == "higher")?"selected":"").'>Maggiore di</option>
						<option value="lower" '.(($fieldFilter1Operand == "lower")?"selected":"").'>Minore di</option>';
					}
					echo '</select>
				</td><td>';
				if($Table->isFieldVirtual($Field) || $Table->isFieldCross($Field)) {
					$Table->printFieldInput($Field, $fieldFilter1Value, array('appendCustomName' => '_filterValue'));
				} else 
					echo '<input type="text" name="'.$name.'_filterValue" value="'.$fieldFilter1Value.'">';
				echo '</td><td style="background: #EDD977"><select name="'.$name.'_option2"><option value="--">--</option><option value="and" '.(($generalOperand == "and")?"selected":"").'>AND</option><option value="or" '.(($generalOperand == "or")?"selected":"").'>OR</option></select></td><td>
					<select name="'.$name.'_filterType2">
					<option value="--">--</option>
					<option value="eq" '.(($fieldFilter2Operand == "eq")?"selected":"").'>Uguale a</option>
					<option value="noteq" '.(($fieldFilter2Operand == "noteq")?"selected":"").'>Diverso da</option>';
					if(!$Table->isFieldVirtual($Field) && !$Table->isFieldCross($Field)) {
						echo '<option value="present" '.(($fieldFilter2Operand == "present")?"selected":"").'>Contiene</option>
						<option value="notpresent" '.(($fieldFilter2Operand == "notpresent")?"selected":"").'>Non contiene</option>';
					}
					if($type == 'int' || $type == 'real') {
						echo '<option value="higher" '.(($fieldFilter2Operand == "higher")?"selected":"").'>Maggiore di</option>
						<option value="lower" '.(($fieldFilter2Operand == "lower")?"selected":"").'>Minore di</option>';
					}
					echo '</select>
				</td><td>';
				if($Table->isFieldVirtual($Field) || $Table->isFieldCross($Field)) {
					$Table->printFieldInput($Field, $fieldFilter2Value, array('appendCustomName' => '_filterValue2'));
				} else 
					echo '<input type="text" name="'.$name.'_filterValue2" value="'.$fieldFilter2Value.'">';
				echo '</td></tr>';
				$i++;
			}
		}
		echo '</table><input type="submit" value="Filtra!" class="bigButton"></form><script>FF.UI.setAppInteractions({});</script><htmlcode>';
		break;
	
	case 'duplicateRows':
		$ids = explode(",", $_GET['ids']);
		$fields = $Table->getFields();
		foreach($ids as $id) {
			if($id != "") {
				$fieldsButId = implode(", ", $Table->getFieldsButId());
				$query = "insert into ".$Table->name." ($fieldsButId) select $fieldsButId from ".$Table->name." where ".$Table->getIdField()." = '".$id."'";
				$res = $Table->executeQuery($query);
				if ($res) 
					_log("Duplicate row in table ".$Table->name." ($query)");
				else 
					_log("Error duplicating row ($query)");
			}
		}
		break;
	
	case 'deleteMultipleRows': 
		$ids = explode(",", $_GET['ids']);
		$Table = new table($_GET['table']);
		foreach($ids as $id) {
			if($id != "") {
				$query = "DELETE FROM ".$Table->name." WHERE ".$Table->getIdField($Table->name)." = '" . $id . "'";
				$res = $Table->executeQuery($query);
			}
		}
		break;
		
	case 'makeExcelDump':
		$Table = new table($_GET['table']);
		$_FIELDS = $Table->getFields($Table->table);
		$excel = "<table><tr>";
		foreach($_FIELDS as $field => $type) {
			if($Table->isFieldVisible($field)) $excel .= "<td><strong>".strToUpper($field)."</strong></td>";
		}
		$excel .= "</tr>";
		$datas = $Table->query("SELECT * FROM ".$Table->table);
		for($i=0; $i<count($datas); $i++) {
			$excel .= "<tr>";
			foreach($_FIELDS as $field => $type) {
				if($Table->isFieldVisible($field)) $excel .= "<td>".$datas[$i][$field]."</td>";
			}
			$excel .= "</tr>";
		}
		
		$excel .= "</table>";
		$fileName = "Dump_".$Table->table."_".date("Y-m-d")."_".time().".xls";
		$fc = fopen($fileName, 'w') or die("can't open file");
		fwrite($fc, $excel);
		fclose($fc);
		if(is_file("Dump_".$Table->table."_".date("Y-m-d")."_".time().".xls")) {
			echo "OK <filename>".$fileName."<filename>";
			_log("EXCEL Dump: File creato correttamente ($fileName)");
		} else {
			_log("EXCEL Dump: Impossibile creare il file ($fileName)");
			echo "KO";
		}
		$nextWeek = time() + (7 * 24 * 60 * 60);
		$croneTime  = date('Y-m-d', $nextWeek);
		$Table->setCrone("ExcelDumpDelete", $Table->table, $croneTime, $fileName);		// Una settimana
		break;
	
	case 'makeSQLDump':
		_log('Request for SQL dump of: ' . $_REQUEST['table']);
		$dumper = new Mysqldumper($Table->DB_Obj, $Table->user, $Table->pwd, $Table->dbName); 
		$dumper->setSingleTable($_GET['table']);
		$DumpText = $dumper->createDump();
		$fileName = $App->dump_dir . "Dump_".$_GET['table']."_".date("Y-m-d")."_".time().".sql";
		$fc = fopen($fileName, 'w') or die("can't open file");
		fwrite($fc, $DumpText);
		fclose($fc);
		if(is_file($fileName)) {
			echo "OK <filename>Dump_".$_GET['table']."_".date("Y-m-d")."_".time().".sql<filename>";
			_log("SQL Dump: File creato correttamente ($fileName)");
		} else {
			_log("SQL Dump: Impossibile creare il file ($fileName)");
			echo "KO";
		}
		$nextWeek = time() + (7 * 24 * 60 * 60);
		$croneTime  = date('Y-m-d', $nextWeek);
		$App->setCrone($_GET['table'], "SQLDumpDelete", $croneTime, $fileName);		// Una settimana
		break;
	
	case 'makeCSVDump':
		$Table = new table($_GET['table']);
		$_FIELDS = $Table->getFields($Table->table);
		$csv = "";
		foreach($_FIELDS as $field => $type) {
			if($Table->isFieldVisible($field)) $csv .= strToUpper($field).",";
		}
		$csv .= "\n\r";
		$datas = $Table->query("SELECT * FROM ".$Table->table);
		for($i=0; $i<count($datas); $i++) {
			foreach($_FIELDS as $field => $type) {
				if($Table->isFieldVisible($field)) $csv .= $datas[$i][$field].",";
			}
			$csv .= "\n\r";
		}
		
		$fileName = "Dump_".$Table->table."_".date("Y-m-d")."_".time().".csv";
		$fc = fopen($fileName, 'w') or die("can't open file");
		fwrite($fc, $csv);
		fclose($fc);
		if(is_file("Dump_".$Table->table."_".date("Y-m-d")."_".time().".csv")) {
			echo "OK <filename>".$fileName."<filename>";
			_log("CSV Dump: File creato correttamente ($fileName)");
		} else {
			_log("CSV Dump: Impossibile creare il file ($fileName)");
			echo "KO";
		}
		$nextWeek = time() + (7 * 24 * 60 * 60);
		$croneTime  = date('Y-m-d', $nextWeek);
		$Table->setCrone("CSVDumpDelete", $Table->table, $croneTime, $fileName);		// Una settimana
		break;
	
	case 'getPlugins':
		echo '<tr><td><input type="button" value="remove" onclick="FF.Configurator.removePlugin(this, null);" /></td>
		<td><input type="text" name="plugin'.$_GET['index'].'_name" /></td>
		<td><select name="plugin'.$_GET['index'].'_ref">';
		$handle = opendir('../plugins');
		while (($file = readdir($handle)) !== false) {
			if($file != "." && $file != "..") {
				echo '<option value="'.$file.'">'.$file.'</option>';
			}
		}
		echo '</select></td></tr>';
		break;
	
	
	case 'saveViewportUI':
		//echo "___".$_GET['menuCol']."___" . $App->get("CONFIG>UI>VIEWPORTS>MENU.width");
		if($App->setAttribute("CONFIG>UI>VIEWPORTS>MENU.sx", 'sx', $_GET['menuCol']) !== false) {
			$App->saveBuiltXML(true);
			echo "OK";
		}
		else echo "KO";
		break;
	
	
	
	
	// CONFIGURATOR
	
	
	
	
	case 'removeVirtualField':
		$App->doBackup('CONF: Remove virtual settings for field ' . $_REQUEST['field']);
		$Virtual = $Table->getVirtualFieldConf($_REQUEST['field']);
		$Virtual->parentNode->removeChild($Virtual);
		$App->saveConfig();
		echo '<span style="color: green"><br /><br />Rimosso correttamente!</span>';
		break;
	
	case 'removeCrossField':
		$App->doBackup('CONF: Remove cross settings for field ' . $_REQUEST['field']);
		$Cross = $Table->getCrossFieldConf($_REQUEST['field']);
		$Cross->parentNode->removeChild($Cross);
		$App->saveConfig();
		echo '<span style="color: green"><br /><br />Rimosso correttamente!</span>';
		break;
		
	case 'deleteBackupFile':
		if (is_file($_GET['file'])) {
			unlink($_GET['file']);
			_log("Cancellato il file: " . $_GET['file']);
			echo "Succesfully deleted!";
		} else {
			_log("Impossibile cancellare il file: " . $_GET['file']);
			echo "Error deleting!";
		}
		break;
		
	case 'restoreBackup':
		if (is_file($_GET['file'])) {
			$App->doBackup('Secure point before restoration');
			
			unlink($App->getModuleDir() . "/config.xml");
			//if(rename($_GET['file'], $App->getModuleDir() . "/config.xml")) {
			if(copy($_GET['file'], $App->getModuleDir() . "/config.xml")) {
				// Tutto è andato a buon fine. Aggiorno il config scrivendo il file 
				// da cui ho effettuato il restore
				$App->load($App->file);
				$App->query('/config/backup')->item(0)->setAttribute('restored_from', $_GET['file']);
				$App->dom->save($App->file);
				echo "Configuration successfully restored!";
			} else
				echo "Error restoring config";
		} else 
			echo "KO-filenotfound";
		break;
	
	case 'viewConfigDiff': 
		$ff1 = $ff2 = '';
		if (is_file($_GET['file'])) 
			$ff1 = $_GET['file'];
		else
			echo "Error: backup file not found!!";
			
		$ff2 = $App->getConfigFilePath();
		
		if($ff1 != '' && $ff2 != '') {
			
			$file_array1 = file($ff1);
			$file_array2 = file($ff2);
			
			foreach ($file_array1 as $line_number => $line) {
				if(trim($line) != trim($file_array2[$line_number])) {
					echo '<div style="background: red; margin: 0 !important; padding: 0 !important"><pre>';
				} else 
					echo '<div style="line-height: 5px !important; height: 11px"><pre>';

				echo htmlspecialchars($line);
				echo '</pre></div>';
				//$file_array2[$line_number];
				
			}
			/*
			$fp = fopen($ff, 'r');
			$contents = '';
			while (!feof($fp)) {
			  $contents .= fread($fp, 8192);
			}
			fclose($fp);
			echo $contents;
			*/
		}
		break;
	
}


/* CONSOLE */

switch ($_GET['ConsoleCmd']) {
	
	case 'autocomplete':
		$dir = $_GET['path'];
		$word = $_GET['word'];
		$prev = $_GET['prevWord'];
		$ndir = "";
		if(strpos($word, '/') > -1) {
			$ndir = explode("/", $word);
			$word = $ndir[count($ndir)-1];
			unset($ndir[count($ndir)-1]);
			$ndir = implode("/", $ndir) . "/";
			$dir .= $ndir;
		}
		$h = opendir($dir);
		while ($file = readdir($h)) {
	        if (strstr($file, $word) == $file) {
	        	if(is_dir($dir . $file))
					echo $ndir . $file . "/";
				else 
					echo $ndir . $file;//substr($file, 0, -1);
				exit;
				//exit;
			}
	    }
	    closedir($h);
		break;
	
	case 'cd':
		if($_GET['path'] == 'docroot')
			echo $_SERVER['DOCUMENT_ROOT'];
		else if($_GET['path'] == 'moduleroot') {
			$m = explode("/", $App->Module);
			echo $_SERVER['DOCUMENT_ROOT'] . $App->ConfigObj['adminpath'] . 'modules/' . $m[1];
		} else if(is_dir($_GET['path']))
			echo "OK";
		else 
			echo "KO";
		break;
	
	case 'del':
		if(is_file($_GET['file'])) {
			$_SESSION['DELETE_CACHE'] = $_GET['file'];
			$_SESSION['DELETE_CACHE_TYPE'] = 'file';
			echo 'Do you really want to delete file: "' . $_GET['file'].'"';
		} else if(is_dir($_GET['file'])) {
			$_SESSION['DELETE_CACHE'] = $_GET['file'];
			$_SESSION['DELETE_CACHE_TYPE'] = 'dir';
			echo 'Do you really want to delete directory "' . $_GET['file'].'" and all its contents?';
		} else 
			echo "Can't find: " . $_GET['file'];
		break;
	
	case 'confirmdel':
		if(isset($_SESSION['DELETE_CACHE'])) {
			/*if($_SESSION['DELETE_CACHE_TYPE'] == 'dir' && rmdir($_SESSION['DELETE_CACHE'])) 
				echo 'Directory deleted successfully!';
			else if($_SESSION['DELETE_CACHE_TYPE'] == 'file' && unlink($_SESSION['DELETE_CACHE']))
				echo 'File deleted successfully!';
			else
				echo 'Error. Cannot delete ' . $_SESSION['DELETE_CACHE'] . ' - type: ' . $_SESSION['DELETE_CACHE_TYPE'];
			*/
			if($App->removeRessource($_SESSION['DELETE_CACHE'])) 
				echo $_SESSION['DELETE_CACHE_TYPE'] . ' deleted successfully!';
			else
				echo 'Error deleting';
		} else 
			echo 'Error deleting. Cache erased.';
		break;
	
	case 'mkdir':
		if(is_dir($_GET['dir'])) 
			echo "Failed to create new directory. (already exists)";
		else {
			if(mkdir($_GET['dir']))
				echo "Directory successfully created!";
			else
				echo "Failed to create new directory. (generic error)";
		}
		break;
	
	case 'mkfile':
		if(is_file($_GET['filepath'])) 
			echo "Failed to create new file. (already exists)";
		else {
			if(touch($_GET['filepath']))
				echo "File successfully created!";
			else
				echo "Failed to create new file. (generic error)";
		}
		break;
	
	case 'ls':
		$h = opendir($_GET['path']);
		$res = '<table cellpadding="0" cellspacing="0" class="ConsoleTable">';
		$res .= '<tr><th>NAME</th><th style="text-align: right">DETAILS</th></tr>';
	    while ($file = readdir($h)) {
	        if ($file != '.' && $file != '..') {
	            $res .= '<tr valign="top"><td>' . $file . '</td><td align="right">'.((is_dir($_GET['path'] . $file) ? '[dir]' : $Utils->byteConvert(filesize($_GET['path'].$file))))."</td></tr>";
			}
	    }
		$res .= '</table>';
		echo $res;
	    closedir($h);
		break;
	
	case 'php':
		eval($_GET['params']);
		break;
	
	case 'save':
		var_dump($_POST);
		break;
	
	case 'vi':
		if(is_file($_GET['file'])) {
			$fc = fopen($_GET['file'], 'r') or die("can't open file");
			while (!feof($fc))  {
				$contents .= fgets($fc);
			}
			if(getimagesize($_GET['file'])) {
				if(strpos($_GET['file'], $_SERVER['DOCUMENT_ROOT']) > -1)
					echo '<image src="'.str_replace($_SERVER['DOCUMENT_ROOT'], "", $_GET['file']).'">';
				else
					echo "Can't display the image! <br />You don't have the permission to view images outside the doc root (".$_SERVER['DOCUMENT_ROOT'].")";
			} else {
				echo '<div style="position: relative" id="'.$_GET['file'].'_editor"><form method="post" action="Console.php?action=save&file='.$_GET['file'].'" target="ConsoleActionFrame"><textarea name="code" style="width: 100%; height: 90%; font-size: 12px">';
				print_r($contents);
				echo '</textarea></form></div>';
			}
			
		} else 
			echo "Can't find file: " . $_GET['file'];
		break;
	
	case 'query':
		$res = $Table->query($_GET['params']);
		print_r($res);
		break;
		
	case 'log':
		print_r($_SESSION['FF']['logger']);
		break;
	
	case 'errorlog':
		$fc = fopen($App->ErrorLogFile, 'r') or die("can't open file");
		while (!feof($fc))  {
			$contents .= fgets($fc) . '<br>';
		}		
		
		//$cont = fread($fc, filesize($logFile));
		print_r($contents);
		break;
		
	case 'clearerrorlog':
		$fc = fopen($App->ErrorLogFile, 'w') or die("can't open file");
		fwrite($fc, '');
		fclose($fp);
		echo 'Errorlog successfully cleared!';
		break;
	
	case 'clearLog':
		$_SESSION['FF']['logger'] = "";
		echo "Log cleared";
		break;
		
	

}

















?>