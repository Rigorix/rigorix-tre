<?php

class utility {
	
	function utility()
	{
		
	}
	
	function checkFileName ($fileName, $dir) 
	{
		$i = 0;
		$suf = "";
		while(is_file($dir.$suf.$fileName)) {
			$i++;
			$suf = $i;
		}
		return $suf.$fileName;
	}
	
	function normalize_db_datetime ($date)
	{
		if ( $date !== false && $date != '' ) {
			list ($date, $time) = explode (" ", $date);
			$date = explode( "-", $date );
			return $date[2] . "/" . $date[1] . "/" . $date[0];
		} else 
			return false;
	}
	
	function get_regioni_list ()
	{
		global $db;
		return $db->getArrayObjectQueryCustom ("select * from provincia group by regione order by regione asc");
	}
	
	function get_province_by_region ( $k )
	{
		global $db;
		return $db->getArrayObjectQueryCustom ("select * from provincia where regione = '$k' order by nome asc");
	}
	
	function get_province_list ()
	{
		global $db;
		return $db->getArrayObjectQueryCustom ("select * from provincia order by nome asc");
	}
	
	function get_nazioni_list ()
	{
		global $db;
		return $db->getArrayObjectQueryCustom ("select * from nazione order by nome asc");
	}
	
	function parseStringDateToDb ( $date )
	{
		if ( $date !== false ) {
			list ( $d, $m, $y ) = explode ( "/", $date );
			return $y . "-" . $m . "-" . $d;
		} else 
			return $date;
	}
	
	function parseDbDateToString ( $date )
	{
		if ( $date !== false ) {
			list ( $y, $m, $d ) = explode ( "-", $date );
			return $d . "/" . $m . "/" . $y;
		} else 
			return $date;
	}
	
	function print_cal_date ( $date )
	{
		if ( $date !== false && $date != '' ) {
			$month = array ("GEN", "FEB", "MAR", "APR", "MAG", "GIU", "LUG", "AGO", "SET", "OTT", "NOV", "DIC");
			list ( $d, $m, $y ) = explode ( "/", $date );
			return '<div class="ui-widget ui-corner-all" style="width: 27px; padding: 1px 2px; background: #fff;"><strong style="text-align:center; display: block; color: red;font-size: 10px; line-height: 10px;">'.$d.'</strong><small style="font-size: 10px; line-height: 10px;text-align:center; display: block; ">'.$month[($m/1)-1].'</small><small style="text-align:center; display: block; font-size: 10px; line-height: 10px;">'.$y.'</small></div>';
		} else 
			return $date;
	}
	
	function print_json ( $obj )
	{
		return json_encode ( $obj );
	}
	
	function is_2_power ( $number )
	{
		$ret = false;
		while ( (($number / 2)%2 == 0 || $number == 2) && $number != 0 ) {
			$number /= 2;
			if ( $number == 1 ) {
				$ret = true;
				break;
			}
		}
		return $ret;
	}
  
  function retCorrTiroParata( $valInDb )
  {
    $res = '';
    if (strlen($valInDb)==1){
      $res = $valInDb-1;
    } else {
      $res = ($valInDb[0]-1).($valInDb[1]-1);
    }
    if ($res == '33' || $res == 33) $res = 3;
    if ($res == '22' || $res == 22) $res = 2;
    if ($res == '11' || $res == 11) $res = 1;
    if ($res == '00' || $res == 00) $res = 0;
      return $res;
  }
	
}

?>