<?
class dm_generic_mysql {

	var $db_conn, $db_name, $debug;

	/**
   * Controlla se una stringa passata
   * neccessita l'addslashes.
   *
   * @param string $str
   * @return boolean
   */
	function strNeedSlash($str) {
		if (($sp = strpos($str,'\'')) !== false) {
			if ($sp<1 || $str[$sp-1] != '\\') {
				return true;
			} else {
				return $this->strNeedSlash(substr($str,$sp+1));
			}
		}
		return false;
	}
	
	function ObjToIndbObj( $obj )
	{
		$ret = array();
		foreach ($obj as $k => $v) {
			$ret["indb_" . $k] = $v;
		}
		return $ret;
	}
	
	/**
   * Pulisce una stringa ricevuta, passata per variabile,
   * con un addslashes se necessario.
   *
   * @param string $str
   */
	function cleanStr(&$str) {
		if ($this->strNeedSlash($str)) {
			$str = addslashes($str);
		}
	}

	/**
   * Restituisce una stringa passata
   * modificandola con un addslashes se necessario.
   *
   * @param string $str
   * @return string
   */
	function retCleanStr($str) {
		if ($this->strNeedSlash($str)) {
			$str = addslashes($str);
		}
		return $str;
	}

	/**
   * Questa funzione riceve un array associativo $_POST
   *
   * @param array $post
   * @return object
   */
	function makeInDbObject($post) {
		$TempClass = new stdClass ();
		foreach ($post as $key=>$value) {
			// Usare i CASE:
			if(strpos($key,'indb_')===0) {
				if (strpos($key,'indb_td_')===0) {
					// il values � una tringa data 18/01/2004
					// in mysql 'YYYY-MM-DD'
					if (substr($value,0,7)=='_V_NUL_') {
						$TempClass->{str_replace('indb_td_','',$key)} = "NULL";
					} else if (substr($value,0,7)=='_V_NOW_') {
						$TempClass->{str_replace('indb_td_','',$key)} = "NOW()";
					} else {
						$arr_data = $this->dte_parse($value, 'd/m/Y');
						$TempClass->{str_replace('indb_td_','',$key)} = "'".$arr_data['year'].'-'.$arr_data['mon'].'-'.$arr_data['mday']."'";
					}
				} else if (strpos($key,'indb_tdt_')===0) {
					// il values � una tringa data 23/12/2004 21:30:00
					// in mysql 'YYYY-MM-DD HH:MM:SS'
					$arr_data = $this->dte_parse($value, 'd/m/Y H:i:s');
					$TempClass->{str_replace('indb_td_','',$key)} = "'".$arr_data['year'].'-'.$arr_data['mon'].'-'.$arr_data['mday'].' '.$arr_data['hours'].':'.$arr_data['minutes'].':'.$arr_data['seconds']."'";
				} else {
					switch (substr($value,0,7)) {
						case '_V_NOW_': // NOW VALUE
						$TempClass->{str_replace('indb_','',$key)} = "NOW()";
						break;
						case '_V_NUL_': // NULL VALUE
						$TempClass->{str_replace('indb_','',$key)} = "NULL";
						break;
						case '_V_DEF_': // DEFAULT VAUE
						$TempClass->{str_replace('indb_','',$key)} = "DEFAULT";
						break;
						case '_V_CUS_': // CUSTOM VALUE _V_CUS_'gnappo'
						$TempClass->{str_replace('indb_','',$key)} = substr($value,7);
						break;
						default:
							$TempClass->{str_replace('indb_','',$key)} = "'".$this->retCleanStr($value)."'";
							break;
					}
				}
			}
		}
		return $TempClass;
	}

	/**
   * Inserisce nella tabella data l'oggetto passato
   *
   * @param string $table
   * @param object $obj
   * @return true or error+exit
   */
	function insertObject($table, $obj) {
		$fields='';
		$values='';
		$first=true;
		foreach (get_object_vars($obj) as $var=>$val) {
			if ($first) {
				$fields = $var;
				$values = $val;
				$first=false;
			} else {
				$fields .= ', '.$var;
				$values .= ', '.$val;
			}
		}
		$sSQL = "INSERT INTO $table ($fields) VALUES ($values)";
		//    echo $sSQL;
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			// bisognerebbe gestire il mysql_insert_id()
			return mysql_insert_id();
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore insertObject\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	/**
   * Effettua un update di una tabella con gli attributi dell'oggetto
   * la clausola WHERE viene specificata tramite name_key e value_key
   * ATTENZIONE value_key deve essere passato gia' slashed !!
   *
   * @param string $table
   * @param object $obj
   * @param array $where_key_val
   * @return true or error+exit
   */
	function updateObject($table, $obj, $where_key_val) {
		$sets='';
		$first=true;
		foreach (get_object_vars($obj) as $var=>$val) {
			if ($first) {
				$sets = "$var=$val";
				$first=false;
			} else {
				$sets .= ", $var=$val";
			}
		}
		$first_whe=true;
		$where = '';
		foreach ($where_key_val as $key_whe=>$val_whe) {
			if ($first_whe) {
				$where = "WHERE $key_whe='$val_whe'";
				$first_whe=false;
			} else {
				$where .= " AND $key_whe='$val_whe'";
			}
		}
		$sSQL = "UPDATE $table SET $sets $where";
		//    echo $sSQL;
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			// bisognerebbe gestire il mysql_insert_id()
			return true;
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore updateObject\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	/**
   * Restituisce un array di elementi di una data,
   * costruendoli passando una stringa data ed una stringa
   * che ne descrive il formato.
   *
   * @param string $PASSED
   * @param string $TXT_DATE_FORMAT
   * @return array
   */
	function dte_parse($PASSED,$TXT_DATE_FORMAT) {
		$lib_import_datearr=array();
		$lib_import_datearr['h'] = 2; // 01-12 - time - hours 12
		$lib_import_datearr['H'] = 2; // 00-23 - time - hours 24
		$lib_import_datearr['g'] = 0; // 1-12  - time - hours 12
		$lib_import_datearr['G'] = 0; // 0-23  - time - hours 24
		$lib_import_datearr['i'] = 2; // 00-59 - time - minutes
		$lib_import_datearr['k'] = 0; // 0-59  - time - minutes ** k - non standard code.
		$lib_import_datearr['s'] = 2; // 00-59 - time - seconds
		$lib_import_datearr['x'] = 0; // 0-59  - time - seconds ** x - non standard code.
		$lib_import_datearr['a'] = 2; // am/pm - time
		$lib_import_datearr['A'] = 2; // AM/PM - time
		$lib_import_datearr['j'] = 0; // 1-31  - date - day
		$lib_import_datearr['d'] = 2; // 01-31 - date - day
		$lib_import_datearr['n'] = 0; // 1-12  - date - month
		$lib_import_datearr['m'] = 2; // 01-12 - date - month
		$lib_import_datearr['y'] = 2; // 04    - date - year
		$lib_import_datearr['Y'] = 4; // 2004  - date - year
		$PASSED = trim($PASSED);
		$TXT_DATE_FORMAT = trim($TXT_DATE_FORMAT);
		$store_arr = array();
		$lastchar = "";
		$dte_frmt_lstchr = "";
		$dte_frmt_idx = 0;
		$bln_formatter = FALSE;
		$bln_twelve_hour_cycle = FALSE;
		for ($i=0;$i<strlen($PASSED);$i++) {
			$dte_frmt_lstchr=substr($TXT_DATE_FORMAT, $dte_frmt_idx,1);
			$dte_frmt_idx ++;
			if (array_key_exists($dte_frmt_lstchr, $lib_import_datearr)) {
				$bln_formatter = FALSE;
			} else {
				$bln_formatter = TRUE;
			}
			if ($bln_formatter) {
				$lastchar = substr($PASSED,$i,1);
				if ($lastchar!=$dte_frmt_lstchr) {
					$store_arr = FALSE;
					$i = strlen($PASSED)+1;
				}
			} else {
				switch ($lib_import_datearr[$dte_frmt_lstchr]) {
					case 0:
						$lastchar = substr($PASSED,$i,1);
						if ($i+1<strlen($PASSED)) {
							if (is_numeric(substr($PASSED,$i+1,1))) { $lastchar=$lastchar.substr($PASSED,$i+1,1); $i++; }
						}
						switch ($dte_frmt_lstchr) {
							case "j":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else {
									$store_arr['mday']=$lastchar;
								}
								break;
							case "n":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else {
									$store_arr['mon']=$lastchar;
								}
								break;
							case "k":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else {
									$store_arr['minutes']=$lastchar;
								}
								break;
							case "x":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else { $store_arr['seconds']=$lastchar; }
								break;
							case "g":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else {
									$store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= TRUE;
								}
								break;
							case "G":
								if (!is_numeric($lastchar)) {
									$store_arr = FALSE; $i = strlen($PASSED)+1;
								} else {
									$store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= FALSE;
								}
								break;
						}
						break;
					case 2:
						$lastchar = substr($PASSED,$i,2);
						if (strlen($lastchar)!=2) {
							$store_arr = FALSE;
							$i = strlen($PASSED)+1;
						} else {
							$i++;
							switch ($dte_frmt_lstchr) {
								case "A":
									if (strtoupper($lastchar)!="AM" && strtoupper($lastchar)!="PM") {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['ampm']=strtoupper($lastchar);
									}
									break;
								case "a":
									if (strtoupper($lastchar)!="AM" && strtoupper($lastchar)!="PM") {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['ampm']=strtoupper($lastchar);
									}
									break;
								case "H":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= FALSE;
									}
									break;
								case "h":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= TRUE;
									}
									break;
								case "i":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['minutes']=$lastchar;
									}
									break;
								case "s":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['seconds']=$lastchar;
									}
									break;
								case "d":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['mday']=$lastchar;
									}
									break;
								case "m":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['mon']=$lastchar;
									}
									break;
								case "y":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										if ($lastchar<70) {
											$lastchar="20".$lastchar;
										} else {
											$lastchar="19".$lastchar;
										}
										$store_arr['year']=$lastchar;
									}
									break;
							}
						}
						break;
					case 4:
						$lastchar = substr($PASSED,$i,4);
						if (strlen($lastchar)!=4) {
							$store_arr = FALSE;
							$i = strlen($PASSED)+1;
						} else {
							$i=$i+3;
							switch ($dte_frmt_lstchr) {
								case "Y":
									if (!is_numeric($lastchar)) {
										$store_arr = FALSE; $i = strlen($PASSED)+1;
									} else {
										$store_arr['year']=$lastchar;
									}
									break;
							}
						}
						break;
				} // END switch
			} // END else get the date value
		}
		if (isset($store_arr['hours'])) {
			if ($bln_twelve_hour_cycle) {
				if (isset($store_arr['ampm'])) {
					if ($store_arr['ampm']=="PM") {
						$store_arr['hours']=$store_arr['hours']+12;
					} else {
						if ($store_arr['hours']==12) {
							$store_arr['hours']=0;
						}
					}
				}
			}
		}
		if (isset($store_arr['ampm'])) {
			unset($store_arr['ampm']);
		}
		return $store_arr;
	}

	function getNumCount($table, $arrWhere='') {
		$first_whe=true;
		$where = '';
		if (is_array($arrWhere)) {
			foreach ($arrWhere as $key_whe=>$val_whe) {
				if ($first_whe) {
					$where = " WHERE $key_whe='$val_whe'";
					$first_whe=false;
				} else {
					$where .= " AND $key_whe='$val_whe'";
				}
			}
		}
		$sSQL = "SELECT COUNT(*) AS tot FROM $table".$where;
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			$res = mysql_fetch_assoc($stmt);
			mysql_free_result($stmt);
			return $res['tot'];
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore getNumCount\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

    function getFirstFieldName($table) {
        $res = @mysql_query("select * from $table limit 1");
        return mysql_field_name($res, 0);
    }

	function getSingleObjectQueryCustom($sSQL) {
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

	function getSingleArrayQueryCustom($sSQL) {
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			$res = mysql_fetch_array($stmt);
			mysql_free_result($stmt);
			return ($res);
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore getArrObjects\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}
	
	function getArrayObjectQueryCustom($sSQL) {
		$stmt = @mysql_query($sSQL);
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
			if ($this->debug>0){
				echo "<!--\nErrore getArrObjects\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	// Limit deve essere nel formato "0,10"
	function getArrObjects($table, $arrWhere='', $arrOrderBy='', $ascDesc='', $limit='') {
		$first_whe=true;
		$where = '';
		if (is_array($arrWhere)) {
			foreach ($arrWhere as $key_whe=>$val_whe) {
				if ($first_whe) {
					$where = " WHERE $key_whe='$val_whe'";
					$first_whe=false;
				} else {
					$where .= " AND $key_whe='$val_whe'";
				}
			}
		}
		$first_ord=true;
		$orderby='';
		if (is_array($arrOrderBy)) {
			foreach ($arrOrderBy as $key_order=>$val_order){
				if ($first_ord) {
					$orderby = " ORDER BY $val_order";
					$first_ord=false;
				} else {
					$orderby .= ", $val_order";
				}
			}
		}
		$strlimit = '';
		if (strlen($limit)>0) {
			$strlimit = ' LIMIT '.$limit;
		}
		$sSQL = "SELECT * FROM $table".$where.$orderby.$strlimit;
		//echo $sSQL;
		$stmt = @mysql_query($sSQL);
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
			if ($this->debug>0){
				echo "<!--\nErrore getArrObjects\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	/**
   * Restituisce
   *
   * @param unknown $table
   * @return unknown
   */
	function getEmptyObject($table) {
		$campi = mysql_list_fields($this->db_name, $table, $this->db_conn);
		$colonne = mysql_num_fields($campi);
		$objEmpty = new stdClass();
		if ($colonne>0) {
			for ($i = 0; $i < $colonne; $i++) {
				$row_name = mysql_field_name($campi, $i);
				$objEmpty->{$row_name} = '';
			}
			return $objEmpty;
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore getEmptyObject\ndbname:".$this->db_name."\ntabella:$table\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	function executeQuery($sSQL) {
		$stmt = @mysql_query($sSQL);
		if ($stmt) {
			// bisognerebbe gestire il mysql_insert_id()
			return mysql_insert_id();
		} else {
			if ($this->debug>0){
				echo "<!--\nErrore updateObject\n$sSQL\n".mysql_error()."\n-->";
			}
			exit();
		}
	}

	/**
   * Costruttore
   *
   * @param resource $db_conn
   * @param resource $db_name
   * @return dm_generic
   */
	function dm_generic_mysql($db_conn, $db_name, $debug=0) {
		$this->db_conn = $db_conn;
		$this->db_name = $db_name;
		$this->debug = $debug;
	}

} // End Class
?>