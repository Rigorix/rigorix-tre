<?php


class DatabaseManager {
	
	function DatabaseManager($DB_Obj) 
	{	
		$this->DB_Obj = $DB_Obj;
		
		$db_conn = mysql_pconnect($DB_Obj['host'], $DB_Obj['user'], $DB_Obj['pwd']);
		if(mysql_select_db($DB_Obj['name'])) {
			
			$this->Conn 		= $db_conn;
			$this->DBName		= $DB_Obj['name'];
			$this->listTables	= mysql_list_tables($this->DBName);
			
		}
	}
	
	function doConnection($db_conn) 
	{
		$this->db_conn = $db_conn;
	}
	
	function getTables() 
	{
		$Tables = array();
		while($Table =  mysql_fetch_array($this->listTables)) {
			array_push($Tables, $Table);
		}
		mysql_free_result($result);
		return $Tables;
	}
	
	function getTablesName() {
		$tables = $this->getTables();
		$tablesName = array();
		foreach ( $tables as $table ) {
			array_push($tablesName, $table[0]);
		}
		return $tablesName;
	}
	
	function executeQuery($query)
	{
		$result = @mysql_query($query);
		if ($result) {
			// bisognerebbe gestire il mysql_insert_id()
			return true;
		} else {
			return false;
		}
	}
	
	function query($Query)
	{
		$res = array();
		$result = mysql_query($Query, $this->Conn);
		//if ($result) {
			while($row = mysql_fetch_array($result)) {
				array_push($res, $row);
			}
			return $res;
		//} else 
		//	return false;
	}
	
	function getArrayObjectQueryCustom($Query) {
		$stmt = @mysql_query($Query);
		if ($stmt) {
			$iCounter = 0;
			$aGeneric = Array();
			while($res = mysql_fetch_object($stmt)) {				
				$aGeneric[$iCounter] = $res;
				$iCounter++;
			}
			mysql_free_result($stmt);
			return ($aGeneric);
		} else {
			echo "<!--\nErrore getArrObjects\n$Query\n".mysql_error()."\n-->";
			exit();
		}
	}
	
	function getSingleObjectQueryCustom($sSQL, $Field = null) {
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			$res = mysql_fetch_object($stmt);
			mysql_free_result($stmt);
			return ($res);
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore getArrObjects\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}
	
	function getSingleArrayQueryCustom($Query, $Field = null) 
	{
		$stmt = @mysql_query($Query);
		if ($stmt) {
			$res = mysql_fetch_array($stmt);
			mysql_free_result($stmt);
			if($Field != null)
				return $res[$Field];
			else
				return ($res);
		} else {
			echo "ERRORE: " . $Query;
			if ($this->debug>0){
				echo "<!--\nErrore getArrObjects\n$Query\n".mysql_error()."\n-->";
			}
			exit();
		}
	}
	
	function checkStringSlashes($Str) 
	{
		if (($sp = strpos($Str, '\'')) !== false) {
			if ($sp < 1 || $Str[$sp-1] != '\\') {
				return true;
			} else {
				return $this->checkStringSlashes(substr($Str, $sp+1));
			}
		}
		return false;
	}
	
}


?>