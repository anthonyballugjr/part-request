<?php
include_once "../config/database.php";
include_once "../classes/bin.php";

$database = new Database();
$db = $database->getConnection();


if(isset($_POST['id'])){

	$partId = $_POST['id'];
	$bin = new Bin($db);
	$stmt = $bin->readPartLocations($partId);

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "<option value=".$row['location'].">".$row['location']."</option>";
	}
}
?>