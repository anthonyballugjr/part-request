<?php
$pageTitle = "Dashboard/views";
include_once "../config/database.php";
include_once "../config/utilities.php";
include_once "../classes/request.php";
include_once "../includes/header.php";
include_once "../classes/logs.php";
include_once "../classes/utility.php";
include_once "../includes/nav.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$utility = new Utility($db);

if(!isSession()){
	header("location:/prq");
}

$label = array(7=>"Cancelled", 8=>"Archived", 99=>"Aging");

if(isset($_GET['view'])){
	$view = base64_decode($_GET['view']);
	if($view == 99){
		$v = $_GET['view'];
		$stmt = $utility->readAging();
		$count = $stmt->rowCount();
	}else{
		$v = $_GET['view'];
		$stmt = $request->readByStatus($view);
		$count = $stmt->rowCount();
	}
}?>

<div class="row px-0 mt-5">
	<div class="com mx-5">
		<a class="btn btn-primary" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Back to Dashboard</a>
	</div>
</div>

<?php if($count > 0){ ?>
	<div class="row my-3 mx-3">


		<div class="col-7">
			<?php echo "<h4>$label[$view] Requests</h4>";?>
			<table class="table table-sm text-center">
				<thead class="bg-moog text-white">
					<tr>
						<th>Request Id</th>
						<th>Reason</th>
						<?php if($view == 99){
							echo 
							"<th>Status</th>";

						}?>
						<th>Requestor</th>
						<th>Created</th>
						<?php if($view == 99){
							echo "<th>Last Activity</th>";
						}?>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);

						$id = base64_encode($row['requestId']);
						echo
						"<tr>
						<td>{$requestId}</td>
						<td>{$requestType}</td>";
						if($view == 99){
							$stat = $row['statusId'];
							switch($stat){
								case 1:
								$class = 'badge-primary';
								$icon = "<i class='fas fa-circle text-primary'></i>";
								break;
								case 2:
								$class = 'badge-info';
								$icon = "<i class='fas fa-people-carry text-info'></i>";
								break;
								case 3:
								$class = 'badge-warning';
								$icon = "<i class='fas fa-truck text-warning'></i>";
								break;
								case 4:
								$class = 'badge-dark';
								$icon = "<i class='fas fa-truck-loading text-dark'></i>";
								break;
								$class = 'badge-dark';
								break;

							}
							echo 
							"<td>$icon <badge class='badge $class'>{$statusName}</td>";
						}
						echo "<td>{$requestorName}</td>";
						if($view == 99){
							echo 
							"<td class='text-danger font-weight-bold'>".timeElapsed($row['requestedAt'])."</td>
							<td>".timeElapsed($row['lastUpdatedAt'])."</td>";
						}else{
							echo "<td>".timeElapsed($row['requestedAt'])."</td>";
						}
						echo "<td><a class='btn btn-primary btn-sm view' href='view.php?view=$v&requestId=$id'><i class='fas fa-eye'></i> Details</i></a></td>
						</tr>";
					}?>
				</tbody>
			</table>
		</div>

		<div class="col py-2 rounded border" id="dynamic">
			<?php if(isset($_GET['requestId'])){
				$id = base64_decode($_GET['requestId']);
				$request->requestId = $id;
				$request->viewOne()?>

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

							<table class="table table-hover table-sm table-bordered">
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
							<ol>
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
						$created = $logs->getRow(1, $request->row['requestId']);
						if($view == 7){
							$cancelled = $logs->getRow(7, $request->row['requestId']);

						}else{
							$cancelled = $logs->getRow(5, $request->row['requestId']);

						}
						$time1 = strtotime($created['actionAt']);
						$time2 = strtotime($cancelled['actionAt']);
						$diff = intval(($time2) - $time1)/60;
						$hours = intval($diff/60);
						$min = $diff%60;
						$tat = $hours.":".$min;
						if($view != 99){
							echo "<label class='font-weight-bold'>TAT (HR:MIN)</label>
							<p>$tat</p>";
						}else{
							echo "<label class='font-weight-bold'>TAT (HR:MIN)</label>
							<p>N/A</p>";
						}


						?>

					</div>
				</div>
				<hr>
				<div class="float-right">
					<a href="#" onclick="closeWin()" class="btn btn-danger">Close Window</a>
				</div>
			<?php } ?>
		</div>

	</div>

<?php }else{
	echo 
	"<div class='row mx-3 my-3'>
	<div class='col'>
	<div class='alert alert-danger'><h3 align='center'>No data to show</h3></div>
	</div>
	</div>";
}
include_once "../includes/footer.php";
?>

<script>
	function closeWin(){
		$('#dynamic').remove();
	}
</script>

