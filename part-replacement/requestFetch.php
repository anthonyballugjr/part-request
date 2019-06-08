<?php 
include_once "../config/database.php";
include_once "../config/utilities.php";
include_once "../classes/utility.php";

$database = new Database();
$db = $database->getConnection();


if(isset($_POST['statusId'])){
	$label = $_POST['label'];
	$statusId = $_POST['statusId'];
	$me = new Utility($db);
	if($statusId == 0){
		$stmt = $me->readMyRequests();
		$count = $stmt->rowCount();
	}else{
		$stmt = $me->readMineByStatus($statusId);
		$count = $stmt->rowCount();
	}
}

if($count > 0){
	echo "<h5>$label ($count)</h5>";
	?>
	<table class="table table-sm table-hover table-bordered text-center dt" id="requestTable">
		<thead class="bg-dark text-white">
			<tr>
				<th scope="row">Request Id</th>
				<th scope="col">Reason</th>
				<th scope="col">Part Requested</th>
				<th scope="col">Status</th>
				<th scope="col">Created</th>
				<th scope="col">Last Activity</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				$stat = $row['statusId'];
				switch($stat){
					case 1://new
					$class = 'badge-primary';
					$icon = "<i class='fas fa-circle text-primary'></i>";
					break;
					case 2://for picking
					$class = 'badge-info';
					$icon = "<i class='fas fa-people-carry text-info'></i>";
					break;
					case 3://for delivery
					$class = 'badge-warning';
					$icon = "<i class='fas fa-truck text-warning'></i>";
					break;
					case 4://delivered
					$class = 'badge-dark';
					$icon = "<i class='fas fa-truck-loading text-dark'></i>";
					break;
					case 5://closed
					$class = 'badge-success';
					$icon = "<i class='fas fa-check text-success'></i>";
					break;
					case 6://on hold
					$class = 'badge-danger';
					$icon = "<i class='fas fa-stopwatch text-danger'></i>";
					break;
					case 7://cancelled
					$class = 'badge-danger';
					$icon = "<i class='fas fa-ban text-danger'></i>";
					break;
					case 8://archived
					$class = 'badge-dark';
					$icon = "<i class='fas fa-archive text-dark'></i>";
					default:
					$class = 'badge-dark';
					break;

				}

				//0-view, 1-edit, 2-cancel, 4-close/mark as received, 6-archive
				echo 
				"<tr>
				<td>{$requestId}</td>
				<td>{$requestType}</td>
				<td>{$partNo}</td>
				<td>$icon <badge class='badge $class'>{$statusName}</td>
				<td>".timeElapsed($row['requestedAt'])."</td>
				<td>".timeElapsed($row['lastUpdatedAt'])."<td>
				<div class='btn-group'>
				<button type='button' class='btn btn-primary btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fas fa-cog'></i> Select Action</button>
				<div class='dropdown-menu'>
				<a class='dropdown-item text-dark update' href='#' id={$requestId} updateType=0><i class='fas fa-eye'></i> View Details</a>";
				if($stat == 1 || $stat == 6){
					echo
					"<a class='dropdown-item text-dark update' id={$requestId} updateType=1 href='#'><i class='fas fa-edit'></i> Edit/Update Details</a>
					<div class='dropdown-divider'></div>
					<a class='dropdown-item text-dark update' id={$requestId} updateType=2 href='#'><i class='fas fa-ban'></i> Cancel Request</a>";
				}else if($stat == 4){
					echo
					"<a class='dropdown-item text-dark update' id={$requestId} updateType=4 href='#'><i class='fas fa-check'></i> Close Request</a>";
				}else if($stat == 5){
					echo
					"<div class='dropdown-divider'></div>
					<a class='dropdown-item text-dark update' id={$requestId} updateType=6 href='#'><i class='fas fa-archive'></i> Archive Request</a>";
				}else if($stat == 7){
					echo
					"<div class='dropdown-divider'></div>
					<a class='dropdown-item text-dark update' id={$requestId} updateType=5 href='#'><i class='fas fa-trash'></i> Delete Request</a>";
				}else if($stat == 8){

				}else{
					echo
					"<div class='dropdown-divider'></div>
					<a class='dropdown-item text-dark update' id={$requestId} updateType=2 href='#'><i class='fas fa-ban'></i> Cancel Request</a>";
				}
				echo
				"</div>
				</div>
				</td>
				</tr>";
			}?>
		</tbody>
	</table>


<?php }else{ ?>
	<h6>No Data to show</h6>
<?php } ?>

