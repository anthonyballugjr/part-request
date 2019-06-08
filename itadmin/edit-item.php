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

if(isset($_POST['updateUserBtn'])){
	$accessId = $_POST['accessId'];
	$userId = $_POST['userId'];
	$user->updateUser($accessId, $userId);
}

if(isset($_POST['updatePartBtn'])){
	$partNo = $_POST['partNo'];
	$partDescription = $_POST['partDescription'];
	$stockRoomCode = $_POST['stockRoomCode'];
	$partId = $_POST['partId'];
	$part->updatePart($partNo, $partDescription, $stockRoomCode, $partId);
}

if(isset($_POST['updateBinBtn'])){
	$partNo = $_POST['partNo'];
	$location = $_POST['location'];
	$binId = $_POST['binId'];
	$bin->updateBin($partNo, $location, $binId);
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
	case 1: 
	$u = $user->readOne($userId);
	$access = array(1=>"CS Admin (WH)",2=>"Sys Admin (IT)", 3 => "Picker", 4 => "Delivery");
	?>

	<form method="POST" action="edit-item.php">
		<input name="userId" type="hidden" value="<?php echo $u['userId'];?>">
		<div class="form-row form-group">
			<div class="col">
				<label>Name</label>
				<input type="text" class="form-control" value="<?php echo $u['displayName'];?>" disabled>
			</div>
			<div class="col">
				<label>Account Name</label>
				<input type="text" class="form-control" value="<?php echo $u['samaccount'];?>" disabled>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col-6">
				<label>Access</label>
				<select name="accessId" class="form-control select-2">
					<?php
					foreach($access as $a => $value){
						if($u['accessId'] == $a){
							echo "<option value=".$a." selected>".$value."</option>";
						}else{
							echo "<option value=".$a.">".$value."</option>";
						}
					}?>
				</select>
			</div>
		</div>
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="updateUserBtn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</form>
	
	<?php
	break;

//PART
	case 2: 
	$p = $part->readOne($partId);
	?>

	<form method="POST" action="edit-item.php">
		<input name="partId" type="hidden" value="<?php echo $p['partId'];?>">
		<div class="form-row form-group">
			<div class="col">
				<label>Part No</label>
				<input name="partNo" type="text" class="form-control" value="<?php echo $p['partNo'];?>" autofocus required>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col">
				<label>Part Description</label>
				<input name="partDescription" type="text" class="form-control" value="<?php echo $p['partDescription'];?>" required>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col">
				<label>Stock Room</label>
				<input name="stockRoomCode" type="text" class="form-control" value="<?php echo $p['stockRoomCode'];?>" required>
			</div>
		</div>
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="updatePartBtn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</form>

	<?php
	break;

//BIN
	case 3:
	$b = $bin->readOne($binId);
	$filter = ["K" => "Components", "E" => "Hydraulics Boeing", "R" => "Hydarulics Airbus", "J" => "Highlift"];
	?>

	<form method="POST" action="edit-item.php">
		<input name="binId" type="hidden" value="<?php echo $b['binId'];?>">

		<div class="form-row form-group">
			<div class="col">
				<label>Part Number</label>
				<input name="partNo" type="text" class="form-control form-control-sm border" value="<?php echo $b['partNo'];?>">
			</div>
		</div>

		<div class="form-row form-group">
			<div class="col">
				<label>Location</label>
				<input name="location" type="text" class="form-control form-control-sm border" value="<?php echo $b['location'];?>">
			</div>
		</div>

		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="updateBinBtn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</form>

	<?php
	break;

//DEFAULT
	default:
	echo "x";
	break;

}?>

