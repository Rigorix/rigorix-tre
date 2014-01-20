<?php
session_start();
$path = "../../";

if ( isset ($_REQUEST["sysCommand"]) )
    switch ($_REQUEST["sysCommand"]) {

        case "ls":
            $h = opendir($path);
            $res = '<table cellpadding="0" cellspacing="0" class="ConsoleTable">';
            $res .= '<tr><th style="text-align: left">NAME</th><th style="text-align: right">DETAILS</th></tr>';
            while ($file = readdir($h)) {
                if ($file != '.' && $file != '..') {
                    $res .= '<tr valign="top"><td>' . $file . '&nbsp;&nbsp;</td><td align="right">'.((is_dir($path . $file) ? '[dir]' : byteConvert(filesize($path.$file))))."</td></tr>";
                }
            }
            $res .= '</table>';
            echo $res;
            closedir($h);
            break;

        case "cd":
            if ( isset ($_REQUEST["sysParameters"]) ) {
                switch ( $_REQUEST["sysParameters"]) {
                    case "..":
                        $_SESSION["jsterminal"]["path"] .= "../";
                        header('Content-type: application/json');
                        echo "{ path: 'diocane' }";
                        break;
                }
            }
            break;

        default:
            echo "Unknown command, sorry about this!";
            break;

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


?>