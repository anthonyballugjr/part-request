<?php
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../classes/user.php";
include_once "../classes/parts.php";
include_once "../classes/bin.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$part = new Part($db);
$bin = new Bin($db);


// $ldap = new LDAP($db);
// $ldap->LDAPconnect();

if(isset($_POST['deleteUserBtn'])){
	$userId = $_POST['userId'];
	$user->removeUser($userId);
}

if(isset($_POST['deletePartBtn'])){
	$partId = $_POST['partId'];
	$part->deletePart($partId);
}

if(isset($_POST['deleteBinBtn'])){
	$binId = $_POST['binId'];
	$bin->deleteBin($binId);
}


if(isset($_POST['item'])){
	$item = $_POST['item'];

	if($item == 1){
		$userId = $_POST['id'];
	}else if($item == 2){
		$partId = $_POST['id'];
	}else{
		$binId = $_POST['id'];
	}
}

switch($item){

//USER
	case 1:?>

	<form method="POST" action="delete-item.php">
		<input name="userId" type="hidden" value="<?php echo $userId;?>">
		<div class="form-row form-group">
			<div class="col text-center">
				<h3>Are you sure you want to remove the selected User?</h3>
			</div>
		</div>
	
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="deleteUserBtn" type="submit" class="btn btn-primary">Yes</button>
		</div>
	</form>
	
	<?php
	break;

//PART
	case 2:?>

	<form method="POST" action="delete-item.php">
		<input name="partId" type="hidden" value="<?php echo $partId;?>">
		<div class="form-row form-group">
			<div class="col text-center">
				<h3>Are you sure you want to delete the selected Part?</h3>
			</div>
		</div>
	
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="deletePartBtn" type="submit" class="btn btn-primary">Yes</button>
		</div>
	</form>

	<?php
	break;

//BIN
	case 3:?>

	<form method="POST" action="delete-item.php">
		<input name="binId" type="hidden" value="<?php echo $binId;?>">
		<div class="form-row form-group">
			<div class="col text-center">
				<h3>Are you sure you want to delete the selected 2BIN Location?</h3>
			</div>
		</div>
	
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="deleteBinBtn" type="submit" class="btn btn-primary">Yes</button>
		</div>
	</form>

	<?php
	break;

//DEFAULT
	default:
	echo "x";
	break;

}?>

<script>
	// $(document).ready(function() {
	// 	$('.select-2').select2();
	// });

</script>
