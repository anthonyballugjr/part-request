<?php
include_once "../config/database.php";
include_once "../classes/message.php";
$database = new Database();
$db = $database->getConnection();
$message = new Message($db);


if(isset($_POST['messageId'])){
	$id = $_POST['messageId'];
	$data = $message->readOne($id);
}

?>

<div class="form-row">
	<div class="col-2">
		<h6><strong>From:</strong></h6>
	</div>
	<div class="col">
		<h6><?php echo $data['sender'];?></h6>
	</div>
</div>
<div class="form-row">
	<div class="col-2">
		<h6><strong>Subject:</strong></h6>
	</div>
	<div class="col">
		<h6><?php echo $data['subject'];?></h6>
	</div>
</div>
<div class="form-row">
	<div class="col-2">
		<h6><strong>Message:</strong></h6>
	</div>
	<div class="col">
		<h6><?php echo $data['message'];?></h6>
	</div>
</div>