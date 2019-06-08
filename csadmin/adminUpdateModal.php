<?php
$errors=array();
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/user.php";
include_once "../classes/utility.php";
include_once "../classes/logs.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$user = new User($db);
$utility = new Utility($db);
$logs = new Logs($db);

//for picking
if(isset($_POST['forPickingBtn'])){
	$code = 2;
	$person = $_POST['pickedBy'];
	$id = $_POST['requestId'];
	$type = $_POST['requestTypeId'];
	$request->updateStatus($code, $person, $id, $type, null);
}

//for delivery
if(isset($_POST['forDeliveryBtn'])){
	$code = 3;
	$person = $_POST['deliveredBy'];
	$id = $_POST['requestId'];
	$type = $_POST['requestTypeId'];
	$request->updateStatus($code, $person, $id, $type, null);
}

//delivered
if(isset($_POST['deliveredBtn'])){
	$code = 4;
	$id = $_POST['requestId'];
	$type = $_POST['requestTypeId'];
	$remarks = $_POST['remarks'];
	$request->updateStatus($code, null, $id, $type, $remarks);
}

//close/received
if(isset($_POST['closeRequestBtn'])){
	$code = 5;
	$id = $_POST['requestId'];
	$request->updateStatus($code, null, $id, null, null);
}

//onhold
if(isset($_POST['onHoldBtn'])){
	$code = 6;
	$remarks = $_POST['remarks'];
	$id = $_POST['requestId'];
	$type = $_POST['requestTypeId'];
	$request->updateStatus($code, null, $id, $type, $remarks);
}

if(isset($_POST['archiveBtn'])){
	$code = 8;
	$id = $_POST['requestId'];
	$request->updateStatus($code, null, $id, null, null);
}

//return
if(isset($_POST['returnToQueueBtn'])){
	$id = $_POST['requestId'];
	$last = $logs->getLastStatus($id);
	$code = 9;
	$person = $last['statusId'];
	$request->updateStatus($code, $person, $id, null, "returned");
}

//deliveryremarks
if(isset($_POST['deliveryRemarksBtn'])){
	$code = 10;
	$remarks = $_POST['remarks'];
	$id = $_POST['requestId'];
	$type = $_POST['requestTypeId'];
	$request->updateStatus($code, null , $id, $type, $remarks);
}

//MODAL
if(isset($_POST['id'])){
	$requestId = $_POST['id'];
	$updateType = $_POST['updateType'];
	$request->requestId = $_POST['id'];
	$request->viewOne(); ?>

	<div class="row">
		<div class="col-7">
			<fieldset class="border">
				<legend class="border">Part Request Information</legend>
				<div class="form-group form-row">
					<div class="col-sm-2">
						<label>Request ID</label>
						<input name="requestId" type="text" value="<?php echo $request->row['requestId'];?>" class="form-control form-control-sm" readonly>
					</div>
					<div class="col-sm">
						<label>Reason for Request</label>
						<input type="text" value="<?php echo $request->row['requestType'];?>" class="form-control form-control-sm" disabled>
					</div>
					<div class="col-sm-2">
						<input name="requestTypeId" type="text" value="<?php echo $request->row['requestTypeId'];?>" class="form-control form-control-sm" hidden>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-sm">
						<label>Part Requested (Part No/Description)</label>
						<div class="input-group">
							<input type="text" class="form-control form-control-sm" value="<?php echo $request->row['partNo']; ?>" disabled>
							<input type="text" class="form-control form-control-sm" value="<?php echo $request->row['partDescription']; ?>" disabled>
						</div>
					</div>
				</div>

				<?php
				$requestTypeId = $request->row['requestTypeId'];
				switch($requestTypeId){

					case 1:
					$workorders = explode(':', $request->row['workOrder']);
					$quantities = explode(':', $request->row['quantity']);
					?>

					<table class="table table-hover table-bordered table-sm">
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

					<?php
					break;

					case 3:?>

					<div class="form-group form-row">
						<div class="col-sm-3">
							<label>Quantity</label>
							<input type="text" class="form-control form-control-sm" value="<?php echo $request->row['quantity'];?>" disabled>
						</div>
						<div class="col">
							<label>Bin Location</label>
							<input type="text" class="form-control form-control-sm" value="<?php echo $request->row['binLocation'];?>" disabled>
						</div>
					</div>

					<?php
					break;

					default:?>

					<div class="form-group form-row">
						<div class="col-sm-3">
							<label>Quantity</label>
							<input type="text" class="form-control form-control-sm" value="<?php echo $request->row['quantity'];?>" disabled>
						</div>
					</div>

					<?php
					break;
				}?>
			</fieldset>
		</div>

		<div class="col">
			<fieldset class="border">
				<legend class="border">Activity Stream</legend>
				<div class="container-fluid" id="logsColumn">
					<ol start="1" class="px-0 mx-0">
						<?php
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
									echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>) => <strong>Remarks:</strong> {$remarks}</li>";
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

								echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>) => <strong>TAT</strong>($tat)</li>";
							}else{
								echo "<li>".date('m/d/y @ h:i:sA', strtotime($row['actionAt'])).": ({$action} by {$actionBy} to <strong>{$statusName}</strong>)</li>";
							}
						}?>
					</ol>
				</div>
			</fieldset>
		</div>
	</div>
	<hr>

	<?php
			//FOR PICKING
	if($updateType == 2){
		?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center">Assign a Picker <i class="fas fa-user-plus"></i></h3>
			<div class="form-group form-row">
				<div class="col-sm-4 mx-auto">
					<select name="pickedBy" class="form-control form-control js-example-basic-single">
						<?php $stmt = $user->readByAccess(3);
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							extract($row);
							echo "<option value='{$displayName}'>{$displayName}</option>";
						}?>
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="forPickingBtn">Save changes</button>
			</div>
		</form>

	<?php } 

	//FOR DELIVERY
	else if($updateType == 3){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center">Assign Delivery Personnel <i class="fas fa-truck"></i></h3>
			<div class="form-group form-row">
				<div class="col-sm-4">
					<label>Assigned Picker</label>
					<input type="text" value="<?php echo $request->row['pickedBy'];?>" class="form-control form-control-sm custom-input-1" disabled>
				</div>
				<div class="col-sm-4">
					<label>Choose Delivery Personnel</label>
					<select name="deliveredBy" class="form-control js-example-basic-single">
						<?php $stmt = $user->readByAccess(4);
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							extract($row);
							echo "<option value='{$displayName}'>{$displayName}</option>";
						} ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="forDeliveryBtn">Save changes</button>
			</div>
		</form>

	<?php }

	//DELIVERED
	else if($updateType == 4){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center">Mark as "Delivered" <i class="fas fa-truck-loading"></i></h3>
			<div class="form-group form-row">
				<div class="col-sm-4">
					<label>Assigned Picker</label>
					<input type="text" value="<?php echo $request->row['pickedBy'];?>" class="form-control form-control-sm custom-input-1" disabled>
				</div>
				<div class="col-sm-4">
					<input type="hidden" value="<?php echo $request->row['requestTypeId'];?>">
					<label>Delivered by</label>
					<input type="text" value="<?php echo $request->row['deliveredBy'];?>" class="form-control form-control-sm" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<label>Remarks (Optional)</label>
				<textarea name="remarks" class="form-control border-dark" placeholder="Your remarks here"></textarea>
			</div>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="deliveredBtn">Mark as Delivered</button>
			</div>
		</form>

	<?php }
	//CLOSE/RECEIVED
	else if($updateType == 5){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center">Close Request <i class="fas fa-check"></i></h3>
			<h5 class="text-danger text-center">Request will be marked as <em>Received</em></h5>
			<hr>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Picked By</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['pickedBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>At</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo date('F d, Y - h:iA',strtotime($request->row['assignedPickerAt']));?>" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Delivered By</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['deliveredBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>At</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo date('F d, Y - h:iA',strtotime($request->row['deliveredAt']));?>" disabled>
				</div>
			</div>

			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="closeRequestBtn">Close Request</button>
			</div>
		</form>

	<?php }

	//ON HOLD
	else if($updateType == 6){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center">Put on hold <i class="fas fa-stopwatch"></i></h3>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Remarks</label>
					<textarea name="remarks" class="form-control" placeholder="Indicate details or reasons why the request is being put on hold." style="border: solid rgba(0,0,0,.4) 1px;" autofocus required></textarea>
				</div>
			</div>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="onHoldBtn">Put On Hold</button>
			</div>
		</form>

	<?php }
	//DELIVERY REMARKS
	else if($updateType == 4.1){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<input type="hidden" name="requestTypeId" value="<?php echo $request->row['requestTypeId'];?>">
			<h3 class="text-danger text-center"> Add Delivery Remarks <i class="fas fa-comment-alt"></i></h3>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Assigned Picker</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['pickedBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>Assigned Delivery Personnel</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['pickedBy'];?>" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Remarks</label>
					<textarea name="remarks" placeholder="Your remarks here..." class="form-control" style="border: solid rgba(0,0,0,.4) 1px;" autofocus required><?php echo $request->row['lastRemarks'];?></textarea>
				</div>
			</div>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="deliveryRemarksBtn">Save Remarks</button>
			</div>
		</form>

	<?php }
	//ARCHIVE
	else if($updateType == 8) {?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<h3 class="text-danger text-center"> Archive <i class="fas fa-archive"></i></h3>
			<h5 class="text-danger text-center">Move this request to Archives?</h5>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="archiveBtn">Move to Archives</button>
			</div>
		</form>

	<?php }

	//RETURN TO QUEUE
	else if($updateType == 9){?>

		<form method="POST" action="adminUpdateModal.php">
			<input type="hidden" name="requestId" value="<?php echo $request->row['requestId'];?>">
			<h3 class="text-danger text-center"> Return to Queue <i class="fas fa-undo"></i></h3>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
				<button type="submit" class="btn btn-primary" name="returnToQueueBtn">Return to Queue</button>
			</div>
		</form>

	<?php }
	
	//VIEWING
	else{
		if($request->row['statusId'] == 5){?>
			<h3 class="text-danger text-center">Received <i class="fas fa-clipboard-check"></i></h3>
			<hr>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Picked By</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['pickedBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>At</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo date('F d, Y - h:iA',strtotime($request->row['assignedPickerAt']));?>" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Delivered By</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['deliveredBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>At</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo date('F d, Y - h:iA',strtotime($request->row['deliveredAt']));?>" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-sm">
					<label>Closed By</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $request->row['receivedBy'];?>" disabled>
				</div>
				<div class="col-sm">
					<label>At</label>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo date('F d, Y - h:iA',strtotime($request->row['receivedAt']));?>" disabled>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-sm"></div>
				<div class="col-sm">
					<label>TAT (Hours: Minutes)</label>

					<?php
					$a = $request->row['receivedAt'];
					$b = $request->row['requestedAt'];
					$rid = $request->row['requestId'];
					$tat = $utility->getTAT($rid);
					?>
					<input type="text" class="form-control form-control-sm custom-input-1" value="<?php echo $tat['tat'];?>" disabled>
				</div>
			</div>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
			</div>
			<?php 
		}else{
			echo '<h3 class="text-danger text-center">Request Details <i class="fas fa-info-circle"></i></h3>
			<hr>
			<div class="form-group float-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss Window</button>
			</div>';
		}
	}
}
	//.MODAL
?>

<script>

	function openTranscript(){
		window.open('transcriptPDF.php');
	}
</script>

<?php include_once "../includes/footer.php";?>