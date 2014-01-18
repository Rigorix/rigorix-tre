<?php

class Utils {
	
	function Utils() {}
	
	function dbDateToItalian($Date) 
	{
		$D = explode("-", $Date);
		return $D[2]."-".$D[1]."-".$D[0];
	}
	
	function addSlashes( $string ) {
		if(get_magic_quotes_gpc()) 
			$string = stripslashes($string);
		return $string;
	}
	
	function removeMagicQuotes ($postArray, $trim = false)
	{
	    if (get_magic_quotes_gpc() == 1)
	    {
	        $newArray = array();   
	       
	        foreach ($postArray as $key => $val)
	        {
	            if (is_array($val))
	            {
	                $newArray[$key] = removeMagicQuotes ($val, $trim);
	            }
	            else
	            {
	                if ($trim == true)
	                {
	                    $val = trim($val);
	                }
	                $newArray[$key] = stripslashes($val);
	            }
	        }   
	       
	        return $newArray;   
	    }
	    else
	    {
	        return $postArray;   
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
	
	function getMultipleValue($value)
	{
		global $App;
		return explode($App->ConfigObj['multifieldseparator'], $value);
	}
	
	function diff($old, $new){
        foreach($old as $oindex => $ovalue){
            $nkeys = array_keys($new, $ovalue);
            foreach($nkeys as $nindex){
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if($matrix[$oindex][$nindex] > $maxlen){
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }       
        }
        if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
        return array_merge(
            $this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
			$this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen))
		);
	} 
	
	function htmlDiff($old, $new){
		var_dump($old);
		var_dump($new);
        $diff = $this->diff(explode(' ', $old), explode(' ', $new));
        foreach($diff as $k){
            if(is_array($k))
                $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
                    (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
            else $ret .= $k . ' ';
        }
        return $ret;
	} 
	
	function serializeArray($array, $sep)
	{
		$res = '';
		foreach($array as $item) {
			if($res != '')
				$res .= $sep;
			$res .= $item;
		}
		return $res;
	}	
	
}

?>