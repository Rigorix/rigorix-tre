<?php
session_start();
require_once("../dm/dm_generic_mysql.php");
require_once("../dm/dm_utente.php");

if ( !isset ( $_SESSION["rigorix"]))
    $_SESSION["rigorix"] = array();

function register_section ( $section_name ) {
    $_SESSION["rigorix"]["section"] = $section_name;
	$_SESSION["rigorix"]["section_page"] = (isset($_REQUEST["section_page"]) ? $_REQUEST["section_page"] : $section_name . ".php");
}

$sql_debug = true;
$db_name = "rigorix_tre";
$db_conn = mysql_pconnect ("localhost", "rigorix_rigorix", "rigorix_tre_!!");
mysql_select_db ( $db_name );

$db = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );
//$dm_utente 		= new dm_utente( $db_conn, $db_name, $sql_debug );


function get_current_url () {
    global $HTTP_GET_VARS;

    return http_build_query($HTTP_GET_VARS);
}

function get_order_field_url ( $field_name ) {
    global $HTTP_GET_VARS;

    $uri = $HTTP_GET_VARS;
    if ( $uri["orderBy"] == $field_name )
        $uri["orderType"] = $uri["orderType"] == "desc" ? "asc" : "desc";
    else {
        $uri["orderType"] = "desc";
        $uri["orderBy"] = $field_name;
    }
    $uri = http_build_query($uri);
    return $_SERVER["PHP_SELF"] . "?" . $uri;
}

function get_pagination_url ( $start_page ) {
    global $HTTP_GET_VARS;

    $uri = $HTTP_GET_VARS;
    $uri["start_page"] = $start_page;
    $uri = http_build_query($uri);
    return $_SERVER["PHP_SELF"] . "?" . $uri;
}

function get_order_field_arrow ( $field_name ) {
    global $HTTP_GET_VARS;

    if ( $HTTP_GET_VARS["orderBy"] == $field_name )
        return $HTTP_GET_VARS["orderType"] == "desc" ? "<span class='glyphicon glyphicon-arrow-down'></span>" : "<span class='glyphicon glyphicon-arrow-up'></span>";
    else
        return "";
}

function get_from_service ( $url ) {
    $service = "http://tre.rigorix.com/services/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $service . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $file_content = curl_exec($ch);
    curl_close($ch);
    return $file_content;
}

function get_table_controller ( $table ) {
    $file_uri = "http://tre.rigorix.com/tool/controllers/$table.controller";

    $ch = curl_init($file_uri);
    curl_setopt($ch, CURLOPT_URL, $file_uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $file_content = curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ( $retcode > 400 ) ? false : json_decode($file_content);
}

function get_field_controller ( $field_name, $controller ) {
    $field_model = new stdClass();
    $field_model->visibility = "visible";
    $field_model->name = $field_name;
    $field_model->display_name = str_replace("_", " ", $field_name);
    $field_model->type = "db";
    $field_model->multivalues;
    $field_model->align = "left";
    $field_model->cssClass = "";

    if ( !$controller->fields->{$field_name} )
        $controller->fields->{$field_name};
    foreach ( $field_model as $k => $v ) {
        $controller->fields->{$field_name}->{$k} = $controller->fields->{$field_name}->{$k} ? $controller->fields->{$field_name}->{$k} : $v;
    }
    return $controller->fields->{$field_name};
}

function get_crossfield_value ( $value, $config ) {
    global $db;

    $query = "select {$config->label_field} from {$config->reference_table} where {$config->reference_field} = '$value'";
    $data = $db->getSingleObjectQueryCustom($query);
    return $data->{$config->label_field};
}
?>