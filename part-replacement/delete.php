<?php
include_once "../config/database.php";
include_once "../classes/request.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);

if(isset($_POST['deleteId'])){
	$deleteId = $_POST['deleteId'];
	$request->deleteByUser($deleteId);
}

?>