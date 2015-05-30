<?php
require 'config.php';

function cleanUpDB(){
    global $_DBHANDLE;
    if( $_DBHANDLE != false )
        mysql_close($_DBHANDLE);
    $_DBHANDLE = false;
}

function sqlQuery($sql){
	$result = mysql_query($sql) or die(mysql_error());
	return $result;
}

function countNumOfRows($result){
	return mysql_num_rows($result);
}

function fetchArray($result){
	return mysql_fetch_assoc($result);
}
?>