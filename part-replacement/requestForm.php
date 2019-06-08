<?php

$pageTitle = "Request Form";
$navTitle = "Parts Replacement";
$pageUser = "standard";
$errors = array();

date_default_timezone_set('Asia/Manila');

include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../classes/user.php";
include_once "../classes/request.php";
include_once "../classes/parts.php";
include_once "../includes/header.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$names = new User($db);
$part = new Part($db);

if(!isSession()){
	header("location:/prq");
}

$requestId;

if (isset($_POST['sendRequestBtn'])) {
	// foreach($_POST as $a){
	// 	echo $a."<br>";
	// }
	if(empty($_POST['requestTypeId'])){
		array_push($errors, 'You must select a reason for your request.');
	}
	else{
		$request = new Request($db);
		$request->sendRequest();
		$requestId = $request->requestId;
	}
	
}
include_once "../includes/nav.php";
?>
<!--Container-fluid-->
<div class="jumbotron">

	<div class="container-fluid" id="x">

		<div class="card border-primary mb-3" style="max-width: 60rem;margin:0 auto;">
			<div class="card-header bg-moog text-white"><h4>REQUEST FORM</h4></div>
			<div class="card-body">

				<?php echo displayError();?>

				<form method="POST" action="requestForm.php">
					<fieldset class="border">
						<legend class="border">Requestor's Information</legend>
						<div class="form-row form-group">
							<div class="col-sm">
								<label>Full Name</label>
								<input name="samaccount" type="hidden" class="form-control form-control-sm" value="<?php echo $_SESSION['user'][0]['uid'][0];?>" readonly>
								<input name="requestorName" type="text" class="form-control form-control-sm" value="<?php echo $_SESSION['user'][0]['cn'][0];?>" readonly>
							</div>
							<div class="col-sm">
								<label>Work Center</label>
								<!-- <input name="workCenter" type="text" class="form-control form-control-sm" value="<?php echo $_SESSION['user'][0]['department'][0];?>" readonly> -->
								<input name="workCenter" type="text" class="form-control form-control-sm" value="Information Technology" readonly>
							</div>
							<div class="col-sm">
								<label>Contact Number</label>
								<input type="text" name="contactNo" class="form-control form-control-sm" style="border: solid rgba(0,0,0,.4) 1px;" required autofocus>
							</div>
						</div>
					</fieldset>

					<fieldset class="border">
						<legend class="border">Part Request Information</legend>
						<div class="form-row form-group">
							<div class="col-6">
								<label>Reason for Request</label>
								<select class="form-control js-example-basic-single" id="reason4Request" name="requestTypeId" required>
									<option value="">--Select One--</option>
									<?php $stmt = $request->readType();
									while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
										echo "<option value=".$row['requestTypeId'].">".$row['requestType']."</option>";
									}?>
								</select>
							</div>
						</div>
						<div class="jumbotron-fluid" id="dynamicDiv">
							<div class="alert alert-dismissible alert-primary">
								<!-- <button type="button" class="close" data-dismiss="alert">&times;</button> -->
								<h4 class="alert-heading">Hey There!</h4>
								<p class="mb-0">Select a reason for your request</p>
							</div>
						</div>
					</fieldset>
					<div class="float-right">
						<button id="bottom" type="submit" class="btn btn-primary" name="sendRequestBtn">Submit</button>
					</div>
				</form>


			</div>
			<!--/Card-body-->
		</div>
	</div>
	<!--/Container-fluid-->

	<?php
	include_once "../includes/footer.php";
	?>

	<script>

		$(document).ready(function() {
			$('.js-example-basic-single').select2();
		});

		//Dynamic Form
		$(document).on('change', '#reason4Request', function() {
			var type = $(this).val();
			
			$.ajax({
				url: "dynamicForm.php",
				method: "POST",
				data: {
					type: type,r
				},
				success: function(data) {
					$('#dynamicDiv').html(data);
				}
			});
		});

	</script>

