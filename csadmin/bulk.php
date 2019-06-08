<?php
include_once "../config/database.php";
include_once "../classes/request.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
if(isset($_POST['action'])){
	$action = $_POST['action'];
	echo $action;
}
?>