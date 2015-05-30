<?php
$_DB = $_REQUEST['db'];
$_DBHOST = 'localhost';
$_DBUSER = 'dataVisual';
$_DBPASS = 'dataVisual';
$_DBHANDLE = mysql_connect($_DBHOST, $_DBUSER, $_DBPASS);
mysql_query("SET NAMES utf8");

if (!isset($_DBHANDLE)){
    $_DBHANDLE = false;
}
if( !$_DBHANDLE ){
	die('Could not connect: ' . mysql_error());
}  
mysql_select_db($_DB, $_DBHANDLE) or die('Could not select database.');
?>