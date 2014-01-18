<?php

class Table extends DatabaseManager {
	
	function Table($table) 
	{
		global $App;
		$this->DatabaseManager($App->DB_settings);
		
		/* ########## DEFAULT CONFIGURATION ########## */
		$this->name				= $table;
		$this->title			= $this->name;
		$this->visible			= 'true';
		$this->dataperpage		= 20;
		$this->orderfield		= 'false';
		$this->orderdir			= 'false';
		$this->description		= '';
		$this->query			= 'SELECT '.$this->getFieldSelectCustom().' FROM ' . $this->name;
		$this->editQuery		= 'SELECT * FROM ' . $this->name;
		$this->loadPath			= $App->ConfigObj['adminloadingpath'] . $this->name . "/";
		$this->tableXpath		= '/config/tables/table[@name = "'.$this->name.'"]';
		$this->CONF				= $App->getTableConfiguration($this->name);
		$this->DOM				= $App->getTableDOM($this->name);
		
		$this->applySettings();
	}
	
	function applySettings() 
	{
		global $App;
		$_SESSION['FF'][$this->name];
		$_SESSION['FF']['CurrentTable'] = $this->name;
		
		/* Se sono in modifica cambio la query secondo l'ID richiesto */
		if ($_REQUEST['action'] == 'EDIT')
			$this->editQuery .= " where ".$_REQUEST['editField']." = ".$_REQUEST['editId'];
		
		/* Se sono in fastedit, cambio la editquery */
		if($App->Context == 'FastEditing')
			$this->editQuery .= " where ".$_REQUEST['idField']." = '".$_REQUEST['id']."'";
		
		/* Setto la modalità di visualizzazione: ALL(Admin only) - PARTIAL */
		if(isset($_REQUEST['show']))
			$_SESSION['FF'][$this->name]['show'] = $_REQUEST['show'];
		
		/* Applico i settaggi del config.xml */
		$count = false;
		foreach($this->CONF['attributes'] as $Name => $Value) {
			$Name = strToLower($Name);
			$this->$Name = $Value;
			if ( $Name == "dataperpage")
				$count = true;
		}
		if ( !$count )
			$this->dataperpage = 20;
		$this->description = $App->getTableDescription($this->name);
			
			
		/* ##### FILTRI DATI ##### */
		
		// Inserimento nuovo filtro
		if($_REQUEST['action'] == "SetFilter") {
			$_SESSION['FF']['FILTERS'][$this->name] = array();
			foreach ($this->getFields() as $Name => $Type) {
				$newFilter = false;
				// Controllo il primo filtro
				if($_REQUEST[$Name . '_filterType'] != "--" && $_REQUEST[$Name . '_filterType'] != "" && $_REQUEST[$Name . '_filterValue'] != "") 
					$newFilter = $_REQUEST[$Name . '_filterType'] . "=>" . $_REQUEST[$Name . '_filterValue'];
				
				// Controllo l'operatore
				if($_REQUEST[$Name . '_option2'] != "--" && $_REQUEST[$Name . '_option2'] != "") 
					$newFilter .= '****'.$_REQUEST[$Name . '_option2'].'****';
				
				// Controllo il secondo filtro
				if($_REQUEST[$Name . '_filterType2'] != "--" && $_REQUEST[$Name . '_filterType2'] != "" && $_REQUEST[$Name . '_filterValue2'] != "") 
					$newFilter .= $_REQUEST[$Name . '_filterType2'] . "=>" . $_REQUEST[$Name . '_filterValue2'];
				
				if($newFilter) 
					$_SESSION['FF']['FILTERS'][$this->name][$Name] = $newFilter;
			}
			// Resetto la pagina corrente
			unset($_REQUEST['currentPage']);
			unset($_SESSION['FF'][$this->name]['currentPage']);
			
			_log("Impostato nuovo filtro");
			$App->jsMessage("Impostato nuovo filtro", "Normal");
		}
		
		// Cancellazione filtro tabella
		if($_REQUEST['action'] == "deleteFilter") {
			_log("Release filter for table " . $this->name . ": " . $_SESSION['FF']['FILTERS'][$this->name]);
			unset($_SESSION['FF']['FILTERS'][$this->name]);
		}
		
		// Gestione filtri attivi
		if(isset($_SESSION['FF']['FILTERS'][$this->name])) {
			// Ho un filtro per questa tabella.
			$totFilters = count($_SESSION['FF']['FILTERS'][$this->name]);
			if ( $totFilters > 0 ) {
				$this->query .= " where";
				$index = 0;
				foreach($_SESSION['FF']['FILTERS'][$this->name] as $field => $filterSetup) {
					$filters = explode("****", $filterSetup);
					if(count($filters) == 3)	// Ho due condizioni per lo stesso campo, apro la parentesi
						$this->query .= "(";
					foreach($filters as $filter) {
						if($filter == "and" || $filter == "or") {
							$this->query .= " ".$filter;
						} else {
							list ($Operand, $Value) = explode("=>", $filter);
							if($Operand != "" && $Operand != null) 	// Setto il filtro
								$this->setFilter($Operand, $Value, $field);
							
						}
					}
					if(count($filters) == 3)	// Ho due condizioni per lo stesso campo, chiudo la parentesi
						$this->query .= ")";
					$index++;
					if($index < $totFilters)
						$this->query .= " and";
				}
			}
		}
		/* ##### FINE FILTRI DATI ##### */
		
		/* ##### ORDINAMENTO DATI ##### */
		if($this->getSetting('orderfield')) {
			if($_REQUEST['orderfield']) {
				// Setto il nuovo ordinamento
				$orderfield = $_REQUEST['orderfield'];
				$orderdir = $this->getSetting('orderdir');
				if($orderdir == 'DESC') $orderdir = 'ASC';
				else $orderdir = 'DESC';
			} else {
				// Carico l'ordinamento salvato nel config
				$orderdir = $this->CONF['attributes']['orderdir'];
				$orderfield = $this->CONF['attributes']['orderfield'];
				if(!$orderdir) $orderdir = 'DESC';
			}
			$_SESSION['FF'][$this->name]['orderdir'] = $orderdir;
			$_SESSION['FF'][$this->name]['orderfield'] = $orderfield;
			$this->query .= " ORDER BY $orderfield $orderdir";
		}
		/* ##### FINE ORDINAMENTO DATI ##### */
		
		/* ##### PAGINAZIONE (limite) ##### */
		$this->dataperpage = intval($_REQUEST['dataperpage']);
		if($this->dataperpage = $this->getSetting('dataperpage')) {
			$_SESSION['FF'][$this->name]['dataperpage'] = intval($this->dataperpage); 
			if(intval($this->dataperpage) < intval($this->getCountData())) {
				if($_REQUEST['currentPage'] || $_SESSION['FF'][$this->name]['currentPage']) {
					$totPage = ceil($this->getCountData() / $this->dataperpage);
					$this->currentPage = ($_REQUEST['currentPage']) ? $_REQUEST['currentPage'] : $_SESSION['FF'][$this->name]['currentPage'];
					if($this->currentPage > $totPage) $this->currentPage = $totPage;
					
					$this->query .= " LIMIT ".(($this->currentPage-1) * $this->dataperpage).", ".$this->dataperpage;
					$_SESSION['FF'][$this->name]['currentPage'] = $this->currentPage;
				} else {
					$this->query .= " LIMIT 0, ".$this->dataperpage;
					$this->currentPage = $_SESSION['FF'][$this->name]['currentPage'] = 1;
				} 
			} else {
				// Cancello il paginatore
				unset($this->currentPage);
				unset($_SESSION['FF'][$this->name]['currentPage']);
			}
		}
		/* ##### FINE PAGINAZIONE ##### */
	}
	
	function getFieldSelectCustom()
	{
		global $App;
		$q = '';
		foreach($this->getFields() as $f => $t) {
			if($t == 'string' || $t == 'blob') 
				$q .= "SUBSTRING($f, 1, ".$App->ConfigObj['stringtruncate'].") as $f, ";
			else 
				$q .= "$f, ";
		}
		$q = substr($q, 0, (strlen($q)-2));
		return $q;
	}
	
	function getCountData() 
	{
		return $this->getSingleArrayQueryCustom("select count(*) as tot from ".$this->name, 'tot');
	}
	
	function getPlugins()
	{
		global $App;
		if($App->query('//table[@name = "'.$this->name.'"]/plugins/plugin')->length > 0) 
			return $App->query('//table[@name = "'.$this->name.'"]/plugins/plugin');
		else
			return false;
	}
	
	function getPlugin($file)
	{
		global $App;
		return $App->query('.//plugin[@ref = "'.$file.'"]', $this->DOM)->item(0);
	}
	
	function getPager() 
	{
		if($this->currentPage) {
			// DO paginatore
			echo '<div class="pager tabStyle">';
				if(isset($_SESSION['FF']['FILTERS'][$this->name])) {
					$filterQuery = str_replace("SELECT *", "SELECT count(*) as tot", $this->query);
					$filterQuery = explode("ORDER BY", $filterQuery);
					$totDatas = $this->getSingleArrayQueryCustom($filterQuery[0], 'tot');
				} else 
					$totDatas = $this->getCountData();
				
				$totPage = ceil($totDatas / $this->dataperpage);
				if(($this->currentPage-1) > 1) 
					echo '<a href="content.php?table='.$this->name.'&currentPage=1"><<</a>';
				if($this->currentPage > 1) 
					echo ' &nbsp;<a href="content.php?table='.$this->name.'&currentPage='.($this->currentPage-1).'"><</a>';
				echo " &nbsp;Pagina <strong>".$this->currentPage."</strong> di ".(($totPage == 0) ? '1' : $totPage);
				if($this->currentPage < $totPage) 
					echo ' &nbsp;<a href="content.php?table='.$this->name.'&currentPage='.($this->currentPage+1).'">></a>';
				if(($this->currentPage+1) < $totPage) 
					echo ' &nbsp;<a href="content.php?table='.$this->name.'&currentPage='.$totPage.'">>></a>';
			
			echo '</div>';
		}
	}
	
	function setFilter($filterType, $filterValue, $field) 
	{
		if(strpos($this->query, "where") === false) 
			$this->query .= " where";
		switch($filterType) {
			case 'noteq':
				$this->query .= " $field != ".((is_numeric($filterValue)?$filterValue:"'".$filterValue."'"));
				break;
			case 'eq':
				$this->query .= " $field = ".((is_numeric($filterValue) ? $filterValue : "'".$filterValue."'"));
				break;
			case 'present':
				$this->query .= " $field like '%".$filterValue."%'";
				break;
			case 'notpresent':
				$this->query .= " $field not like '%".$filterValue."%'";
				break;
			case 'higher':
				$this->query .= " $field > $filterValue";
				break;
			case 'lower':
				$this->query .= " $field < $filterValue";
				break;
		}
	}
	
	function getSetting($setting) 
	{	
		global $App;
		if (isset($_GET[$setting])) 
			return $_GET[$setting];
		else if(isset($_SESSION['FF'][$this->name][$setting])) 
			return $_SESSION['FF'][$this->name][$setting]; 
		else if (array_key_exists($setting, $this->CONF['attributes'])) 
			return $this->CONF['attributes'][$setting];
		else 
			return false;
	}	
	
	function getFields() 
	{
		$Campi = array();
		for($i=0; $i < $this->getNumFields(); $i++) {
			$Campi[mysql_field_name($this->listFields(), $i)] = mysql_field_type($this->listFields(), $i);
		}
		return $Campi;
	}
	
	function getFieldsButId()
	{
		$Campi = array();
		for($i=0; $i < $this->getNumFields(); $i++) {
			if($i != 0)
				array_push($Campi, mysql_field_name($this->listFields(), $i));
		}
		return $Campi;
	}
	
	function getIdField()
	{
		return mysql_field_name($this->listFields(), 0);
	}
	
	function getCrossFieldConf($field)
	{
		global $App;
		$Node = $App->query('.//fields/field[@name = "'.$field.'"]/cross', $this->DOM);
		if($Node->length > 0)
			return $Node->item(0);
		else
			return null;
	}
	
	function removeVirtualFieldsData($Field)
	{
		global $App;
		// Rimuovo tutti i settaggi per i virtual fields
		if($App->query('.//virtualcross', $Field)->length > 0) {
			$vnode = $App->query('.//virtualcross', $Field)->item(0);
			while ($vnode->childNodes->length)
    			 $vnode->removeChild($vnode->firstChild);
		}		
	}
	
	function getVirtualFieldConf($field)
	{
		global $App;
		return $App->query($this->tableXpath . '/fields/field[@name = "'.$field.'"]/virtualcross/data');
	}
	
	function getFieldDBType($Field) 
	{
		if(is_string($Field))
			$Field = $this->getFieldConfig($Field);
		for($i=0; $i < $this->getNumFields(); $i++) {
			if(mysql_field_name($this->listFields(), $i) == $Field['attributes']['name'])
				return mysql_field_type($this->listFields(), $i);
		}
	}
	
	function getNumFields()
	{
		return mysql_num_fields($this->listFields());
	}
	
	function listFields()
	{
		return mysql_list_fields($this->DBName, $this->name, $this->Conn);
	}
	
	function isFieldVisible($Field) 
	{
		$FieldConf = $this->getFieldConfig($Field);		// Necessario il nome del field, non un oggetto
		if($_SESSION['FF'][$this->name]['show'] == 'all') 
			return true;
		else if($FieldConf['attributes']['visible'] == "false") 
			return false;
		else 
			return true;
	}
	
	function isVisible()
	{
		global $App;
		return $App->isTableVisible($this->name);
	}
	
	function getDatas()
	{
		return $this->getArrayObjectQueryCustom($this->query);
	}
	
	function getEditData()
	{
		return $this->getSingleArrayQueryCustom($this->editQuery);
	}
	
	function getFieldAttributes($Field) 
	{
		global $App;
		if(is_string($Field))
			$Field = $this->getFieldConfig($Field);
		return $App->getObjAttributes($Field);
	}
	
	function getDateFromKey($v)
	{
		switch ($v) {
			case 'NOW':
				return date('Y-m-d');
				break;
			default:
				if($v == '')
					return date('Y-m-d');
				else 
					return $v;		
		}
	}	
	
	function isFieldMultiple($Field) 
	{
		global $App;
		if(!is_string($Field))
			$Field = $Field['attributes']['name'];
		$Node = $this->getFieldConfig($Field);
		if($Node['attributes']['multiple'] == "true")
			return true;
		else
			return false;
	}
	
	function isFieldCross($Field) 
	{
		global $App;
		if(!is_string($Field))
			$Field = $Field['attributes']['name'];
		$CrossNode = $this->getCrossFieldConf($Field);
		if($CrossNode != null)
			return $CrossNode;
		else	
			return false;
	}
	
	function isFieldVirtual($Field) 
	{
		global $App;
		if(!is_string($Field))
			$Field = $Field['attributes']['name'];
		$VirtualNode = $this->getVirtualFieldConf($Field);

		if($VirtualNode != null && $VirtualNode->length > 0)
			return $VirtualNode;
		else	
			return false;
	}
	
	function isFieldFile($Field) 
	{
		global $App;
		if(!is_string($Field))
			$Field = $Field['attributes']['name'];
		$F = $this->getFieldConfig($Field);
		if($F['attributes']['type'] == 'filesystem')
			return true;
		else
			return false;
	}
	
	function showVirtualField($value, $field)
	{
		global $App;
		if(!is_string($field))
			$field = $field['attributes']['name'];
		$Node = $App->query($this->tableXpath . '/fields/field[@name = "'.$field.'"]/virtualcross/data[@value = "'.$value.'"]');
		if($Node->length > 0)
			return $Node->item(0)->getAttribute('label');
		else {
			_log("TABLE ".$this->name.": Error retrieving virtual value for field '".$field."' with value: $value!");
			_log(' \'-> Error query: ' . $this->tableXpath . '/fields/field[@name = "'.$field.'"]/virtualcross/data[@value = "'.$value.'"]');
			return "Error retrieving virtual value";
		}
	}
	
	function showCrossField($value, $field)
	{
		global $App;
		$res;
		
		if(!is_string($field))
			$field = $field['attributes']['name'];
		$Cross = $this->getCrossFieldConf($field);
		$FieldConf = $this->getFieldConfig($field);
		if($FieldConf['attributes']['multiple'] == 'true' && stripos($value, $App->ConfigObj['multifieldseparator']) > -1) {
			$value = explode($App->ConfigObj['multifieldseparator'], $value);
			foreach ($value as $v) {
				$res .= $this->getSingleArrayQueryCustom("SELECT * FROM ".$Cross->getAttribute('table')." WHERE ".$Cross->getAttribute('ref')." = '".$v."'", $Cross->getAttribute('title'));
				$res .= ", ";
			}
		} else 
			$res = $this->getSingleArrayQueryCustom("SELECT * FROM ".$Cross->getAttribute('table')." WHERE ".$Cross->getAttribute('ref')." = '".$value."'", $Cross->getAttribute('title'));
		return $res;
	}
	
	function getFieldByName($name)
	{
		global $App;
		if($App->query('.//fields/field[@name = "'.$name.'"]', $this->DOM)->length > 0)
			return $App->query('.//fields/field[@name = "'.$name.'"]', $this->DOM)->item(0);
		else {
			// Creo il campo field
			if($App->query('.//fields/', $this->DOM)->length == 0) {
				// Non c'è il nodo <fields>, lo aggiungo.
				$fields = $App->dom->createElement('fields');
				$this->DOM->appendChild($fields);
				$App->dom->save( $App->file );
			}
			$newField = $App->dom->createElement('field');
			$newField->setAttribute('name', $name);
			$App->query('.//fields', $this->DOM)->item(0)->appendChild($newField);
			$App->dom->save( $App->file );
			return $newField;
		}
	}
	
	function getFieldConfig($fieldname) 
	{
		global $App;
		$Field = $App->query($this->tableXpath . '/fields/field[@name = "'.$fieldname.'"]')->item(0);
		if($Field != null)
			$res = $App->getNodeObj($App->query($this->tableXpath . '/fields/field[@name = "'.$fieldname.'"]')->item(0));
		else {
			$res['attributes'];
			$res['attributes']['title'] 	= $fieldname;
			$res['attributes']['name']	 	= $fieldname;
			$res['attributes']['multiple'] 	= 'false';
			$res['attributes']['visible'] 	= 'true';
			$res['attributes']['type'] 		= 'db';
			$res['attributes']['orderfield']= '';
			$res['attributes']['orderdir']	= '';
			$res['attributes']['adminfield']= 'false';
			$res['childrens'] 				= array();
			$res['nodename']				= $fieldname;
		}
		if($res['attributes']['title'] == '')
			$res['attributes']['title'] = $res['attributes']['name'];
		return $res;
	}	
	
	function getDescription()
	{
		global $App;
		$res = $App->query($this->tableXpath . '/description');
		if($res->length > 0)
			return $res->item(0)->textContent;
		else 
			return '';
	}
	
	function hasDescription()
	{
		global $App;
		$res = $App->query($this->tableXpath . '/description');
		if($res->length > 0)
			return $res->item(0)->textContent;
		else 
			return false;
	}
	
	function hasCrossField($field)
	{
		if($this->getCrossFieldConf($field) != null)
			return true;
		else 
			return false;
	}
	
	function getFieldDefault($Field) 
	{
		global $App;
		
		if(!is_string($Field)) 		// Field name
			$Field = $Field['attributes']['name'];
		$Node = $App->query('.//fields/field[@name = "'.$Field.'"]/default', $this->DOM);
		if($Node->length > 0)
			return $Node->item(0)->textContent;
		else
			return "";
	}	
	
	function printFieldData($idValue, $Name, $Data)
	{
		global $App;
		global $Utils;
		global $print;
		$Data->$Name = utf8_encode($Data->$Name);
		
		if($_SESSION['FF']['RowCompressedView'] == true)
			$divClass = 'datasBox Compressed';
		else 
			$divClass = 'datasBox';
		
		$fieldRowId = $this->name.'>'.$idValue.'>'.$Name;	
		if($this->isFieldCross($Name)) {
			echo '<td class="crossField"><div flex="2" class="'.$divClass.'" id="'.$fieldRowId.'" name="'.$Name.'">';
			echo $this->showCrossField($Data->$Name, $Name);
			echo "</div></td>";
		}
		else if($this->isFieldVirtual($Name)) {
			echo '<td class="vcrossField"><div flex="2" class="'.$divClass.'" id="'.$fieldRowId.'" name="'.$Name.'">';
			echo $this->showVirtualField($Data->$Name, $Name);
			echo "</div></td>";
		} else if($this->isFieldFile($Name)) {
			if($Data->$Name == '')
				$Data->$Name = '&nbsp';
			echo '<td class="imageField_'.$Name.'"><div flex="1" class="'.$divClass.'" id="'.$fieldRowId.'" title="File">'.$Data->$Name.'</div></td>';
		} else {
			echo '<td class="clmn_type_'.$this->getFieldDBType($Name).'">';
			echo '<div flex="'.$this->getFieldFlexByType ($this->getFieldDBType($Name)).'" class="'.$divClass.'" id="'.$fieldRowId.'" name="'.$Name.'" title="">';
			if($Data->$Name == '')
				$Data->$Name = '&nbsp;';
			switch($this->getFieldDBType($Name)) {
				case "blob": 
					echo substr(strip_tags($Data->$Name), 0, $App->ConfigObj['stringtruncate']);
					if(strlen($Data->$Name) > $App->ConfigObj['stringtruncate'])
						echo "...";
					if(strlen($Data->$Name) == 0)
						echo "&nbsp;";
					break;
				/*
				case "string":
					break;
				case "real":
					break;
				case "int":
					break;
				*/
				case "date":
					$date = $Utils->dbDateToItalian($Data->$Name);
					$dateArr = explode('-', $date);
					echo '<table class="dataWrapper calendar"><tr valign="middle"><td><span class="day">'.$dateArr[0].'</span></td><td><span class="month">'.$print['calendar_months'][$dateArr[1]].'</span><br /><span class="year">'.$dateArr[2].'</span></td></tr></table>';
					//echo '<div class="dataWrapper calendar"><span class="day">'.$dateArr[0].'</span><span class="month">'.$print['calendar_months'][$dateArr[1]].'</span><br /><span class="year">'.$dateArr[2].'</span></div>';
					break;
					
				default: 
					echo substr($Data->$Name, 0, $App->ConfigObj['stringtruncate']);
					if(strlen($Data->$Name) > $App->ConfigObj['stringtruncate'])
						echo "...";
					break;
			}
			
			echo '</div>';
			echo "</td>";
		}
	}
	
	function getFieldFlexByType ( $type )
	{
		switch($type) {
			case "blob": 
				return 4;
				break;
			case "string":
				return 3;
				break;
			case "real":
				return 3;
				break;
			case "int":
				return 1;
				break;
			case "date":
				return 1;
				break;
			default: 
				return 2;
				break;
		}
	}
	
	function printFieldInput($Field, $Value, $Options = array())
	{
		global $App;
		
		if(!is_string($Field))
			$Field = $Field['attributes']['name'];
		$FieldAttr = $this->getFieldAttributes($Field);
		
		// Se esiste l'opzione, aggiunge una stringa al nome del campo (usato ad es. su filtri)
		if(array_key_exists('appendCustomName', $Options))
			$FieldAttr['name'] .= $Options['appendCustomName'];
		
		// ### Cross ###
		if($this->isFieldCross($Field)) { 	
			$Cross = $this->getCrossFieldConf($Field);
			$CrossValues = $this->query("SELECT * FROM " . $Cross->getAttribute('table'));
			
			// ### Cross, multifield ###
			if($this->isFieldMultiple($Field)) { 
				echo '<select name="'.$FieldAttr['name'].'" id="'.$FieldAttr['name'].'" multiple="multiple" class="FF_combobox" separator="'.$App->ConfigObj['multifieldseparator'].'">';
						$multiValues = explode($App->ConfigObj['multifieldseparator'], $Value);
						foreach($CrossValues as $row => $value) {
							 echo '<option value="'.$value[$Cross->getAttribute('ref')].'" '.((in_array($value[$Cross->getAttribute('ref')], $multiValues)) ? 'selected="selected"' : '').'>' . $value[$Cross->getAttribute('title')] . '</option>';
						}
				echo '</select>';
				
			} else {	
				
				// ### Cross, not multifield ###
				echo '<select class="FF_combobox" name="'.$FieldAttr['name'].'">
					<option value="--">Seleziona..</option>';
					foreach($CrossValues as $row => $value) {
						echo '<option value="'.$value[$Cross->getAttribute('ref')].'" '.(($Value == $value[$Cross->getAttribute('ref')]) ? "selected" : "").'>' . $value[$Cross->getAttribute('title')] . '</option>';
					}
				echo '</select>';
			}
		
		} else if ($this->isFieldVirtual($Field)) { 
			$virtualCrosses = $this->getVirtualFieldConf($Field);
			
			// ### VIRTUAL FIELD ###
			if($this->isFieldMultiple($Field)) { 
				
				// ### VIRTUAL, multifield ###
				echo '<select class="FF_combobox" class="FF_combobox" id="'.$FieldAttr['name'].'_multivalue" multiple="multiple">';
						$multiValues = explode($App->ConfigObj['multifieldseparator'], $Value);
						foreach($virtualCrosses as $virtualcross) {
							 echo '<option value="'.$virtualcross->getAttribute('value').'" '.((in_array($virtualcross->getAttribute('value'), $multiValues)) ? 'selected="selected"' : '').'>' . $virtualcross->getAttribute('label') . '</option>';
						}
				echo '</select>';				
				
			} else {
				
				// ### VIRTUAL, non multifield ###
				
				echo '<select class="FF_combobox" name="'.$FieldAttr['name'].'"><option value="--">Seleziona..</option>';
				foreach($virtualCrosses as $virtualcross) {
					echo '<option value="'.$virtualcross->getAttribute('value').'" '.(($Value == $virtualcross->getAttribute('value')) ? "selected" : "").'>' . $virtualcross->getAttribute('label') . '</option>';
				}
				echo '</select>';
			}
		
		} else if ($this->isFieldFile($Field)) { 
			
			// ### FILESYSTEM - LOAD ###
			echo '<table><tr valign=top>
				<td>';
				$Img = $App->ConfigObj['adminloadingpath'].$this->name."/".$Value;
				if(is_file($_SERVER['DOCUMENT_ROOT'].$Img)) 
					echo '<a href="'.$Img.'" target="_NEW" title="Vedi immagine"><img id="previewer_'.$FieldAttr['name'].'" src="'.$Img.'" width="150" /></a>';
				else 
					echo '<img id="previewer_'.$FieldAttr['name'].'" src="i/imageNotFound.gif" width="150" />';
				echo '</td>
				<td>Nome del file: <strong id="filename_'.$FieldAttr['name'].'">'.(($Value == '') ? '--' : $Value).'</strong>	| &nbsp;
				<input type="button" id="btnRemove_'.$FieldAttr['name'].'" onclick="FF.Contents.removeUploadedFile(\''.$Value.'\', \''.$this->name.'\', \''.$FieldAttr['name'].'\');" value="CANCELLA"><br /><br />
					Seleziona da directory:<br>
				<input type="hidden" name="loadingType_'.$FieldAttr['name'].'" value="LOAD" />
				<input type="hidden" name="remove_'.$FieldAttr['name'].'" id="remove_'.$FieldAttr['name'].'" value="" />';
				if($App->getDirFiles($_SERVER['DOCUMENT_ROOT'].$App->ConfigObj['adminloadingpath'].$this->name) !== false) {
					echo '<select id="fileSelect_'.$FieldAttr['name'].'" name="'.$FieldAttr['name'].'" onchange="FF.Contents.selectImage(\''.$FieldAttr['name'].'\',  \''.$App->ConfigObj['adminloadingpath'].$this->name.'\\'.$Value.'\' + this.options[this.selectedIndex].value, \'SELECT\');">
						<option value="">--</option>';
						foreach ($App->getDirFiles($_SERVER['DOCUMENT_ROOT'].$App->ConfigObj['adminloadingpath'].$this->name) as $File) {
							if($File == $Value)
								echo '<option value="'.$File.'" selected>'.$File.'</option>';
							else 
								echo '<option value="'.$File.'">'.$File.'</option>';
						}
					echo '</select>';
				} else 
					echo 'Non riesco a trovare o ad aprire la directory<br />('.$_SERVER['DOCUMENT_ROOT'].$App->ConfigObj['adminloadingpath'].$this->name.')';
				echo '<br /><br />';
				if($App->Context != 'FastEditing') {
					echo 'Carica da disco:<br />
					<input type="file" name="'.$FieldAttr['name'].'_filesystem" value="none" onchange="FF.Contents.selectImage(\''.$FieldAttr['name'].'\', this.value, \'BROWSE\');">';
				}
				echo '</td>
				</tr>
			</table>';
		
		} else {	// ### DEFAULT ###
			switch($this->getFieldDBType($Field)) {
				case "string": 
					if(strpos($Value, "'") !== false) $Value = str_replace("'", '"', $Value);
					echo '<div class="hiddenOverflow">
					<input '.(($FieldAttr['autocomplete'] == 'true') ? 'datasource="inc/ajaxRequests.php"' : '').' class="FF_textfield" restriction="'.$FieldAttr['restriction'].'" type="text" id="'.$FieldAttr['name'].'" name="'.$FieldAttr['name'].'" value=\''.$Value.'\' style="width:300px">
					</div>';
					break;
				case "real": 
					echo '<div class="hiddenOverflow">
					<input class="FF_textfield" restriction="numbers,'.$FieldAttr['restriction'].'" type="text" id="'.$FieldAttr['name'].'" name="'.$FieldAttr['name'].'" value="'.$Value.'" style="width:100px">
					</div>';
					break;
				case "int": 
					echo '<div><input class="FF_textfield" restriction="numbers,'.$FieldAttr['restriction'].'" type="text" id="'.$FieldAttr['name'].'" name="'.$FieldAttr['name'].'" value="'.$Value.'" style="width:100px"></div>';
					break;
				case "date":
					$Value = $this->getDateFromKey($Value);
					echo '<input class="datepicker" class="FF_textfield" type="text" value="'.$Value.'" name="'.$FieldAttr['name'].'" />';
					break;
				case "blob": 
					echo '<div class="hiddenOverflow">
					<textarea id="'.$FieldAttr['name'].'_RTA" style="width: 100%; height: 100px;" row="5" name="'.$FieldAttr['name'].'" class="FF_textarea '.$FieldAttr['name'].'">'.$Value.'</textarea>
					</div>';
					break;
				default:
					if(strpos($Value, "'") !== false) $Value = str_replace("'", '"', $Value);
					echo '<div class="hiddenOverflow">
					<input class="FF_textfield" restriction="'.$FieldAttr['restriction'].'" type="text" id="'.$FieldAttr['name'].'" name="'.$FieldAttr['name'].'" value=\''.$Value.'\' style="width:300px">
					</div>';
					break;
			}
				
		}
			
	}
	
}

?>