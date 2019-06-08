<?php
$errors = array();

date_default_timezone_set('Asia/Manila');

include_once "config/database.php";
include_once "config/ldap.php";
include_once "classes/user.php";

$database = new Database();
$db = $database->getConnection();

//PROD
if(isset($_GET['auth'])){
	$auth =  $_GET['auth'];

	$ldap = new LDAP($db);
	$ldap->LDAPconnect();
	$ldap->authenticate($auth);
}

?>


