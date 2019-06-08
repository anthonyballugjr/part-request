<?php
$errors = array();
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/bin.php";
include_once "../classes/logs.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$bin = new Bin($db);


// $filter = ["-","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
$filter = array("K" => "Components", "E" => "Hydraulics Boeing", "R" => "Hydarulics Airbus", "J" => "Highlift");

if(isset($_POST['updateRequestBtn'])){
	$partId = $_POST['partId'];
	if(empty($partId) || $partId="" || $partId=null){
		array_push($errors, "Select a Part No");
		echo "<script>alert('Please select a Part No.');window.location.href='./index.php'</script>";
	}else if($_POST['quantity'] <=0){
		echo "ladjflakfj";
	}else{
		$request->requestId = $_POST['requestId'];
		$request->updateRequest();
	}
	
}

if(isset($_POST['deleteAllBtn'])){
	$statusId = $_POST['statusId'];
	$request->deleteByUser($statusId);
}

if(isset($_POST['deleteRequestBtn'])){
	$requestId = $_POST['requestId'];
	$request->deleteOne($requestId);
}

if(isset($_POST['archiveBtn'])){
	$code = 8;
	$id = $_POST['requestId'];
	$request->updateStatus($code, null, $id, null, null);
}

if(isset($_POST['cancelRequestBtn'])){
	$code = 7;
	$id = $_POST['requestId'];
	$remarks = $_POST['remarks'];
	$request->updateStatus($code, null, $id, null, $remarks);
}

if(isset($_POST['closeRequestBtn'])){
	$code = 5;
	$person = $_SESSION['user'][0]['displayname'][0];
	$id = $_POST['requestId'];
	$request->updateStatus($code, $person, $id, null, null);
}

if(isset($_POST['updateType'], $_POST['requestId'])){
	$requestId = $_POST['requestId'];
	$updateType = $_POST['updateType'];
	$request->requestId = $requestId;
	$request->viewOne();
}?>

<div class="card border-dark" id="dynamicCard"><!--CARD-->
	<input type="hidden" class="form-control form-control-sm" value="<?php echo $request->row['requestTypeId'];?>" id="rtype">
	
	<div class="card-header bg-moog text-white navbar">
		<?php if($updateType == 0){
			echo "<h5>Request Details</h5>";
		} else if($updateType == 1){
			echo 
			"<h5>Update Request</h5>
			<div class='ml-auto'>
			<a href='#' class='text-light' data-trigger='focus' id='pop' data-toggle='popover' title='IMPORTANT' data-content='TO PREVENT DATA LOSS OR CONFLICT; YOU CAN ONLY UPDATE COMMON FIELDS AMONG REQUESTS SINCE PER TYPE OF REQUEST USES UNIQUE FIELDS. SHOULD YOU WISH TO EDIT THE TYPE/REASON FOR YOUR REQUEST, CANCEL THE EXISTING REQUEST AND FILE A NEW ONE.'><i class='fas fa-info-circle'></i>
			</a>
			</div>";
		}else if($updateType == 2){
			echo "<h5>Cancel Request</h5>";
		}else if($updateType == 4){
			echo "<h5>Close Request</h5>";
		}else if($updateType == 5){
			echo "<h5>Delete Request</h5>";
		}else{
			echo "<h5>Delete Requests</h5>";
		} ?>
	</div>
	<div class="card-body">
		<div class="container loader" id="loader" style="display:none;">
			<img src="../res/loader.gif" width="100px" height="100px">
		</div>
		<?php if($updateType == 0){
			?>

			<div class="row">
				<div class="col">
					<label class="font-weight-bold">Request Id</label>
					<p><?php echo $request->row['requestId'];?></p>
				</div>
				<div class="col">
					<label class="font-weight-bold">Reason</label>
					<p><?php echo $request->row['requestType'];?></p>
				</div>
				<div class="col">
					<label class="font-weight-bold">Status</label>
					<p class="$class"><?php echo $request->row['statusName'];?></p>
				</div>
				<div class="col">
					<label class="font-weight-bold">Created</label>
					<p><?php echo date('F d, Y', strtotime($request->row['requestedAt']));?></p>
				</div>
				<div class="col">
					<label class="font-weight-bold">By</label>
					<p><?php echo $request->row['requestorName'];?></p>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col">
					<label class="font-weight-bold">Part No</label>
					<p><?php echo $request->row['partNo'];?></p>
				</div>
				<div class="col-5">
					<label class="font-weight-bold">Part Description</label>
					<p><?php echo $request->row['partDescription'];?></p>
				</div>
				<div class="col">
					<?php $type = $request->row['requestTypeId'];
					
					if($type == 1 || $type == 2){

						$workorders = explode(":", $request->row['workOrder']);
						$quantities = explode(":", $request->row['quantity']);
						?>

						<table class="table table-hover table-sm">
							<thead class="thead-dark">
								<tr>
									<th>Work Order</th>
									<th>Quantity</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($workorders as $key => $workorder){
									$quantity = $quantities[$key];
									echo 
									"<tr>
									<td>".$workorder."</td>
									<td>".$quantity."</td>
									</tr>";
								} ?>
							</tbody>
						</table>
					<?php }else{
						echo "<label class='font-weight-bold'>Quantity</label>
						<p>".$request->row['quantity']."</p>";
					}?>
				</div>
				<?php if($request->row['binLocation'] !== null){
					echo "<div class='col'>
					<label class='font-weight-bold'>Bin Location</label>
					<p>".$request->row['binLocation']."</p>
					</div>";
				}?>
			</div>
			<hr>

			<div class="row">
				<div class="col-12">
					<label class="font-weight-bold">Activity Stream</label>
					<div class="container-fluid" id="logsColumn">
						<ol class="px-0">
							<?php $logs = new Logs($db);
							$stmt = $logs->readByRequest($request->row['requestId']);

							while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								extract($row);

								if($row['remarks'] == "edit-content"){
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": (<strong>Contents</strong> {$action} by {$actionBy})</li>";
								}else if($row['remarks'] == "returned"){
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": (returned by {$actionBy} to <strong>{$statusName}</strong> status)</li>";
								}else if($row['action'] == "created"){
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": (<strong>{$action}</strong> by {$actionBy})</li>";
								}else if($row['statusId'] == 6){
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>) => <strong>Remarks:</strong> {$remarks}</li>";
								}else if($row['statusId'] == 7){
									$created = $logs->getRow(1, $row['requestId']);
									$cancelled = $logs->getRow(7, $row['requestId']);
									$time1 = strtotime($created['actionAt']);
									$time2 = strtotime($cancelled['actionAt']);
									$diff = intval(($time2) - $time1)/60;
									$hours = intval($diff/60);
									$min = $diff%60;
									$tat = $hours.":".$min;

									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>) => <strong>Remarks:</strong> {$remarks}</li>";
								}else if($row['statusId'] == 4){
									if($row['remarks'] != null || $row['remarks'] != ""){
										echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>)</li>";
									}else{
										echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>)</li>";
									}
								}else if($row['remarks'] != null || $row['remarks'] != ""){
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$actionBy} added <strong>{$statusName}</strong> remarks => {$remarks})</li>";
								}else if($row['statusId'] == 5){
									$created = $logs->getRow(1, $row['requestId']);
									$cancelled = $logs->getRow(5, $row['requestId']);
									$time1 = strtotime($created['actionAt']);
									$time2 = strtotime($cancelled['actionAt']);
									$diff = intval(($time2) - $time1)/60;
									$hours = intval($diff/60);
									$min = $diff%60;
									$tat = $hours.":".$min;

									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>)</li>";
								}else{
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>)</li>";
								}
							}?>
						</ol>
					</div>
				</div>
				<div class="col">
					<?php
					if($request->row['statusId'] == 5 || $request->row['statusId'] == 7 || $request->row['statusId'] == 8){
						$created = $logs->getRow(1, $request->row['requestId']);
						if($request->row['statusId'] == 5 || $request->row['statusId'] == 8){
							$cancelled = $logs->getRow(5, $request->row['requestId']);
						}else{
							$cancelled = $logs->getRow(7, $request->row['requestId']);
						}
						$time1 = strtotime($created['actionAt']);
						$time2 = strtotime($cancelled['actionAt']);
						$diff = intval(($time2) - $time1)/60;
						$hours = intval($diff/60);
						$min = $diff%60;
						$tat = $hours.":".$min;
						echo
						"<label class='font-weight-bold'>TAT (HR:MIN)</label>
						<p>$tat</p>";
					}else{
						echo
						"<label class='font-weight-bold'>TAT (HR:MIN)</label>
						<p>N/A</p>";
					}?>
				</div>
			</div>
			<hr>
			<div class="float-right">
				<a href="#" onclick="closeWin()" class="btn btn-danger">Close Window</a>
			</div>

		<?php }else if($updateType == 1){

		//EDIT/UPDATE ?>
		<fieldset class="border">
			<legend class="border">Part Request Information</legend>

			<form method="POST" action="clientUpdateModal.php"><!--FORM-->
				<div class="form-row form-group">
					<div class="col-3">
						<label>Request Id</label>
						<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['requestId'];?>" name="requestId" readonly>
					</div>
					<div class="col">
						<label>Reason for Request</label>
						<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['requestType'];?>" disabled>
					</div>
				</div>

				<div class="form-row form-group">
					<div class="col">
						<label>Part Requested</label>
						<div class="form-row form-group">
							<div class="col-4">
								<select class="form-control js-example-basic-single" id="partsFilter">
									<option>--Filter--</option>
									<?php foreach($filter as $f => $key){
										if($f == $request->row['stockRoomCode'][0]){
											echo "<option value=".$f." selected>".$key."</option>";
										}else{
											echo "<option value=".$f.">".$key."</option>";
										}
									} ?>
								</select>
							</div>
							<div class="col">
								<select class="form-control js-example-basic-single" id="partId" name="partId">
									<option value="<?php echo $request->row['partId'];?>"><?php echo $request->row['partNo'];?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>" readonly>

				<?php if($request->row['requestTypeId'] == 1 || $request->row['requestTypeId'] == 2){
						//REPLACEMENT
					echo 
					"<div class='form-group form-row'>
					<div class='col'>
					<label>Workorder / Quantity</label>";

					$workorders = explode(':', $request->row['workOrder']);
					$quantities = explode(':', $request->row['quantity']);

					foreach($workorders as $key => $workorder){
						$quantity = $quantities[$key];
						?>

						<div class="input-group">
							<input type="text" name="workorder[]" value="<?php echo $workorder;?>" class="form-control form-control-sm">
							<input type="text" name="quantity[]" value="<?php echo $quantity;?>" class="form-control form-control-sm">
						</div>

					<?php }
					echo 
					"</div>
					</div>";
				}else if($request->row['requestTypeId'] == 3){
					//2 BIN ?>

					<div class="form-group form-row">
						<div class="col-3">
							<label>Quantity</label>
							<input type="number" value="<?php echo $request->row['quantity'];?>" class="form-control form-control-sm custom-input-1" min=1 name="quantity">
						</div>
						<div class="col">
							<label>Bin Location</label>
							<select class="form-control js-example-basic-single" name="binLocation" id="binLocation" required>
								<?php $stmt = $bin->readPartLocations($request->row['partId']);
								while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
									extract($row);
									if($row['location'] == $request->row['binLocation']){
										echo "<option value='{$location}' selected>{$location}</option>";
									}else{
										echo "<option value='{$location}'>{$location}</option>";
									}
								}?>
							</select>
						</div>
					</div>

				<?php }else {?>

					<div class="form-group form-row">
						<div class="col-3">
							<label>Quantity</label>
							<input type="number" value="<?php echo $request->row['quantity'];?>" class="form-control form-control-sm custom-input-1" name="quantity" min=1 required>
						</div>
					</div>

				<?php } ?>

				<div class="float-right">
					<a href="#" onclick="closeWin()" class="btn btn-danger">Cancel</a>
					<button class="btn btn-primary" name="updateRequestBtn" type="submit">Save</button>
				</div>
			</form><!--/FORM-->

		</fieldset>

	<?php }
	else if($updateType == 2){

		//CANCEL?>
		<div class="container text-center">
			<hr>
			<h3>Are you sure you want to cancel the selected request?</h3>
			<hr>
			<div class="float-center">
				<form method="POST" action="clientUpdateModal.php"><!--FORM-->
					<input type="hidden" value="<?php echo $requestId;?>" name="requestId">
					<label>Remarks</label>
					<textarea name="remarks" class="form-control mb-3" placeholder="Indicate details or reason why the request is being cancelled" autofocus></textarea>
					<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
					<button type="submit" class="btn btn-primary" name="cancelRequestBtn">Yes</button>
				</form><!--/FORM-->
			</div>
		</div>

	<?php }	
	else if($updateType == 4){
		
	//CLOSE/RECEIVED?>
	<div class="container text-center">	
		<hr>		
		<h5>Close this request?</h5>
		<h6>(This Request will be marked as "Received")</h6>
		<hr>
		<div class="float-center">
			<form method="POST" action="clientUpdateModal.php"><!--FORM-->
				<input type="hidden" value="<?php echo $requestId;?>" name="requestId">
				<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
				<button type="submit" class="btn btn-primary" name="closeRequestBtn">Yes</button>
			</form><!--/FORM-->
		</div>
	</div>

<?php } else if($updateType == 5){
	//DELETE ONE?>

	<div class="container text-center">
		<hr>
		<h3>Delete the selected request?</h3>
		<hr>
		<div class="float-center">
			<form method="POST" action="clientUpdateModal.php"><!--FORM-->
				<input type="hidden" value="<?php echo $requestId;?>" name="requestId">
				<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
				<button type="submit" class="btn btn-primary" name="deleteRequestBtn">Yes</button>
			</form><!--/FORM-->
		</div>
	</div>


<?php } else if($updateType == 6){
	//ARCHIVE?>

	<div class="container text-center">
		<hr>
		<h3>Archive the selected request?</h3>
		<hr>
		<div class="float-center">
			<form method="POST" action="clientUpdateModal.php"><!--FORM-->
				<input type="hidden" value="<?php echo $requestId;?>" name="requestId">
				<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
				<button type="submit" class="btn btn-primary" name="archiveBtn">Yes</button>
			</form><!--/FORM-->
		</div>
	</div>

<?php }

else if($updateType == 8 || $updateType == 9){
	if($updateType == 8) $req="Cancelled";
	else $req ="Received";
	//DELETE ALL CANCELLED?>
	<div class="container text-center">
		<hr>
		<h3>Delete all my <strong><?php echo $req;?></strong> Requests?</h3>
		<hr>
		<div class="float-center">
			<form method="POST" action="clientUpdateModal.php"><!--FORM-->
				<input type="hidden" value="<?php echo $requestId;?>" name="statusId">
				<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
				<button type="submit" class="btn btn-primary" name="deleteAllBtn">Yes</button>
			</form><!--/FORM-->
		</div>
	</div>


<?php }else  {

//DELETE ONE ?>
<div class="container text-center">
	<hr>
	<h3>Delete the selected request?</h3>
	<hr>
	<div class="float-center">
		<form method="POST" action="clientUpdateModal.php"><!--FORM-->
			<input type="hidden" value="<?php echo $requestId;?>" name="requestId">
			<a class="btn btn-danger" href="#" onclick="closeWin()">No</a>
			<button type="submit" class="btn btn-primary" name="deleteRequestBtn">Yes</button>
		</form><!--/FORM-->
	</div>
</div>

<?php } ?>
</div>

</div><!--/CARD-->

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});

	jQuery(function () {
		jQuery('[data-toggle="popover"]').popover()
	});

	jQuery(function () {
		jQuery('[data-toggle="tooltip"]').tooltip()
	});

	$(document).on('change', '#partsFilter', function() {
		var filter = $(this).val();
		var rtype = $('#rtype').val();

		$.ajax({
			url: "partFetch.php",
			method: "POST",
			data: {
				filter: filter,
				rtype: rtype
			},
			success: function(data) {
				$('#partId').html(data);
			}
		});
	});

	$(document).on('change', '#partId', function(){
		var rtype = $('#rtype').val();
		var id = $(this).val();

		if(rtype == 3){
			$.ajax({
				url: 'binFetch.php',
				method: 'POST',
				data: { id: id },
				beforeSend: function(){
					$('#loader.show');
				},
				success: function(data){
					$('#binLocation').html(data);
					console.log(data);
				},
				complete: function(data){
					$('#loader').hide();
				}
			});
		}
	});

	function closeWin(){
		$('#dynamicCard').remove();
	}
</script>


