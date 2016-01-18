<?php
require_once('config.php');

$con = mysql_connect($serveur,$user,$password);

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($base, $con);
mysql_query("SET NAMES 'utf8'");

$path=$_SERVER['DOCUMENT_ROOT'];

//session_start();

require_once('./functions.php');