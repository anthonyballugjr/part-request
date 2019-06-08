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

if(isset($_POST['saveUserBtn'])){
	$name = $_POST['name'];
	$samaccount = $_POST['samaccount'];
	$accessId = $_POST['accessId'];
	$user->addUser($name, $samaccount, $accessId);
}

if(isset($_POST['savePartBtn'])){
	$partNo = $_POST['partNo'];
	$partDescription = $_POST['partDescription'];
	$stockRoomCode = $_POST['stockRoomCode'];
	$part->addPart($partNo, $partDescription, $stockRoomCode);
}

if(isset($_POST['saveBinBtn'])){
	$partNo = $_POST['partNo'];
	$location = $_POST['location'];
	$bin->addBinLocation($partNo, $location);
}


if(isset($_POST['item'])){
	$item = $_POST['item'];
	if($item == 1){
		$name = $_POST['name'];
		$samaccount = $_POST['samaccount'];
	}
}

switch($item){

//USER
	case 1: 
	$access = array(1=>"CS Admin (WH)", 2=>"System Admin (IT)", 3=> "Picker", 4=> "Delivery");
	?>

	<form method="POST" action="add-item.php">
		<div class="form-row form-group">
			<div class="col">
				<label>Name</label>
				<input name="name" type="text" class="form-control" value="<?php echo $name;?>" readonly>
			</div>
			<div class="col">
				<label>Account Name</label>
				<input name="samaccount" type="text" class="form-control" value="<?php echo $samaccount;?>" readonly>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col-6">
				<label>Access</label>
				<select name="accessId" class="form-control select-2">
					<?php
					foreach($access as $a => $value){
						echo "<option value=".$a." >".$value."</option>";
					}?>
				</select>
			</div>
		</div>
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="saveUserBtn" type="submit" class="btn btn-primary">Save</button>
		</div>
	</form>
	
	<?php
	break;

//PART
	case 2: ?>

	<form method="POST" action="add-item.php">
		<div class="form-row form-group">
			<div class="col">
				<label>Part No</label>
				<input name="partNo" type="text" class="form-control" placeholder="Enter Part No" autofocus>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col">
				<label>Part Description</label>
				<input name="partDescription" type="text" class="form-control" placeholder="Enter Description">
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col">
				<label>Stock Room (Code)</label>
				<input name="stockRoomCode" type="text" class="form-control" placeholder="Enter Stock Room Code">
			</div>
		</div>
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-primary" name="savePartBtn">Save</button>
		</div>
	</form>

	<?php
	break;

//BIN
	case 3: ?>

	<form method="POST" action="add-item.php">
		<div class="form-row form-group">
			<div class="col">
				<label>Part Number</label>
				<input name="partNo" type="text" class="form-control" placeholder="Enter Part No" autofocus>
			</div>
		</div>
		<div class="form-row form-group">
			<div class="col">
				<label>Bin Location</label>
				<input name="location" type="text" class="form-control" placeholder="Enter Location">
			</div>
		</div>
		<hr>
		<div class="form-group float-right">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button name="saveBinBtn" type="submit" class="btn btn-primary">Save</button>
		</div>
	</form>

	<?php
	break;

//DEFAULT
	default:
	echo "x";
	break;

}?>


