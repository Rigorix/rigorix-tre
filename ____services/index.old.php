<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

session_start();
require_once("../dm/dm_generic_mysql.php");

$sql_debug = true;
$db_name = "rigorix_tre";
$db_conn = mysql_pconnect ("localhost", "rigorix_rigorix", "rigorix_tre_!!");
mysql_select_db ( $db_name );

$db = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );

$response;
$query;

if ( isset ($_REQUEST) ):

    $id_field_name = $db->getFirstFieldName ( get("getTable"));

    if ( has("getTableFields") ) {
        $fields_info = get_table_fields (get("getTableFields"));
        $response = new stdClass ();
        foreach ( $fields_info as $field ) {
            $response->{$field->name} = $field->type;
        }
        echo  json_encode( $response );
    }

    if ( has("getTable") ) {
        $query = "select * from " . get("getTable");

        if ( has("getFields") )
            $query = str_replace("*", get("getFields"), $query);

        if ( has("onlyIf") )
            $query .= " where " . get("onlyIf");

        if ( has("orderBy") )
            $query .= " order by " . get("orderBy");
        else
            $query .= " order by $id_field_name ";

        if ( has("orderType") )
            $query .= " " . get("orderType");
        else
            $query .= " desc ";

        if ( has("limit") )
            $query .= " limit " . get("limit");

        $response = $db->getArrayObjectQueryCustom( $query );
        echo  json_encode( $response );
    }

    if ( has("deleteEntryFrom") ) {
        $query = "delete from " . get ("deleteEntryFrom");

        if ( has("onlyIf") ) {
            // SAFE: not deleting anything if a condition is not given
            $query .= " where " . get("onlyIf");

//            $db->executeQuery( $query );
            echo '{ "status": "ok", "title": "Delete item", "body": "<h3 class=\"text-success\"><span class=\"glyphicon glyphicon-ok mrm\"></span> Item deleted successfully</h3>", "action": "deleteEntryFrom", "query": "'.$query.'" }';
        }
    }

    if ( has("editEntryFrom") ) {
        //
    }

endif;

function has ( $request_var ) {
    return array_key_exists($request_var, $_REQUEST);
}

function get ( $request_var ) {
    return $_REQUEST[$request_var];
}

function get_table_fields ($table) {
    $mysqli = new mysqli("localhost", "rigorix_rigorix", "rigorix_tre_!!", "rigorix_tre");
    $fields_info = false;
    if ( $result = $mysqli->query("select * from " . $table)) {
        $fields_info = $result->fetch_fields();
        $result->close();
    }
    $mysqli->close();

    return $fields_info;
}

?>