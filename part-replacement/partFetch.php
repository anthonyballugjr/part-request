<?php
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/parts.php";
include_once "../classes/bin.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);

if(isset($_POST['filter'], $_POST['rtype'])){
	$rtype = $_POST['rtype'];
	$filter = $_POST['filter'];

	if($rtype == 3){
		$part = new Bin($db);
		$stmt = $part->readBin($filter);
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo "<option value=".$row['partId'].">".$row['partNo']."</option>";
		}
	}else{
		$part = new Part($db);
		$stmt = $part->readByFilter($filter);
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo
			"<option value=".$row['partId'].">".$row['partNo']."</option>";

		}
	}
	

}

?>