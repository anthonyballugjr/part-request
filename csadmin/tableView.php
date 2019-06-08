<?php
// $pageTitle = "Requests";
date_default_timezone_set('Asia/Manila');

include_once "../config/database.php";
include_once "../config/utilities.php";
include_once "../classes/request.php";
include_once "../classes/parts.php";
include_once "../classes/utility.php";

$database = new Database();
$db = $database->getConnection();

if(isset($_GET['st'], $_GET['rt'])){
	$statusId = base64_decode($_GET['st']);
	$requestTypeId = base64_decode($_GET['rt']);
	$request = new Request($db);
	$utility = new Utility($db);
	$stmt = $request->readByStatusAndType($statusId, $requestTypeId);
	$count = $stmt->rowCount();
	$s = $utility->readStatus($statusId);
	$r = $utility->readType($requestTypeId);
	$pageTitle = $r['requestType']." - ".$s['statusName']." ($count)";
	
}

if(!isSession()){
	header("location:/prq");
}

include_once "../includes/header.php";
?>

<div class="jumbotron">
	<div class="navbar">
		<a class="btn btn-primary" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Back to Dashboard</a>
	</div>

	<div class="row my-3">
	</div>
	<?php echo displayError();?>
	<?php if (isset($_SESSION['message'])) : ?>
		<div class="alert alert-primary fade-show" role="alert">
			<?php 
			echo $_SESSION['message']; 
			unset($_SESSION['message']);
			?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif ?>
	<hr>
	<?php if($count > 0){?>

		<table class="table table-striped" id="reqTable">
			<h3><?php echo strtoupper($r['requestType'])." - ".strtoupper($s['statusName']);?></h3>
			<hr>
			<thead class="bg-moog text-white text-center">
				<tr>
					<?php switch($statusId){
					case 1://new
					echo 
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Workcenter</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">Action</th>';
					break;
					case 2://for picking
					echo
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">Assigned Picker</th>
					<th scope="col">Action</th>';
					break;
					case 3://for delivery
					echo
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">Picker</th>
					<th scope="col">Delivery Personnel Assigned</th>
					<th scope="col">Action</th>';
					break;
					case 4://delivered
					echo
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">Picker</th>
					<th scope="col">Delivered By</th>
					<th scope="col">Delivered</th>
					<th scope="col">Action</th>';
					break;
					case 5://received-closed
					echo
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">Picker</th>
					<th scope="col">Delivered By</th>
					<th scope="col">Delivered</th>
					<th scope="col">Closed by</th>
					<th scope="col">Closed</th>
					<th scope="col">Action</th>';
					break;
					case 6://on hold
					echo
					'<th scope="col">Request ID</th>
					<th scope="col">Requestor</th>
					<th scope="col">Workcenter</th>
					<th scope="col">Part Requested</th>
					<th scope="col">Created</th>
					<th scope="col">On Hold Remarks</th>
					<th scope="col">Action</th>';
					break;
				}?>
			</tr>
		</thead>
		<tbody class="text-center">
			<?php ;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				echo "<tr>";
				switch($statusId){
					case 1://new
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$workCenter}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>";
					break;
					case 2://for picking
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>
					<td scope='col'>{$pickedBy}</td>";
					break;
					case 3://for delivery
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>
					<td scope='col'>{$pickedBy}</td>
					<td scope='col'>{$deliveredBy}</td>";
					break;
					case 4://delivered
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>
					<td scope='col'>{$pickedBy}</td>
					<td scope='col'>{$deliveredBy}</td>
					<td scope='col'>".timeElapsed($row['deliveredAt'])."</td>";
					break;
					case 5://closed-received
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>
					<td scope='col'>{$pickedBy}</td>
					<td scope='col'>{$deliveredBy}</td>
					<td scope='col'>".timeElapsed($row['deliveredAt'])."</td>
					<td scope='col'>{$receivedBy}</td>
					<td scope='col'>".timeElapsed($row['receivedAt'])."</td>";
					break;
					case 6://On Hold
					echo
					"<th scope='row'>{$requestId}</th>
					<td scope='col'>{$requestorName}</td>
					<td scope='col'>{$workCenter}</td>
					<td scope='col'>{$partNo}</td>
					<td scope='col'>".timeElapsed($row['requestedAt'])."</td>
					<td scope='col'>{$lastRemarks}</td>";
					break;
					default:
					"<td scope='col' colspan='10'>Data here</td>";
					break;
				}?>
				<td colspan=2>
					<div class="container">
						<?php 
						$rid = base64_encode($row['requestId']);
						?>
						<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i>
								Select Action
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item update text-dark" id="<?php echo $row['requestId'];?>" updateType=10 href="#"><i class="fas fa-eye"></i> View Details</a>
								<?php
								switch($statusId){
									case 1:
									echo 
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=2><i class='fas fa-user-plus'></i> Assign Picker</a>
									<div class='dropdown-divider'></div>
									<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=6><i class='fas fa-stopwatch'></i> Put on hold</a>";
									break;
									case 2:
									echo
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=3><i class='fas fa-truck'></i> Assign Delivery Personnel</a>
									<a href='transcriptpdf.php?requestId=$rid' class='dropdown-item text-dark' target='_blank'><i class='fas fa-print'></i> Print Transcript</a>
									<div class='dropdown-divider'></div>
									<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=6><i class='fas fa-stopwatch'></i> Put on hold</a>";
									break;
									case 3:
									$with = "<i class='fas fa-flag mr-1'></i>";
									$without = "<i class='fas fa-comment mr-1'></i>";
									echo 
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=4><i class='fas fa-truck-loading'></i> Mark as <em>Delivered</em></a>
									<div class='dropdown-divider'></div>
									<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=6><i class='fas fa-stopwatch'></i> Put on hold</a>";

									break;
									case 4:
									echo 
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=5><i class='fas fa-check'></i> Close Request</a>";
									break;
									case 5:
									echo 
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=8><i class='fas fa-archive'></i> Move to Archives</a>";
									break;
									case 6:
									echo 
									"<a href='#' class='dropdown-item text-dark update' id={$requestId} updateType=9><i class='fas fa-undo'></i> Return to Queue</a>";
									break;
									default:
									echo "No action available";
									break;
								} ?>
								
							</div>
						</div>
						
					</div>
				</td>
			</tr>
		<?php	}?>
	</tbody>
</table>
<?php } else{
	echo "<h1 align='center'>No Data to Show</h1>";
}?>
</div>

<?php
include_once "../includes/footer.php"
;?>

<div class="modal fade" id="updateModal" role="dialog" aria-labelledby="updateModalLabel">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bg-moog text-white">
				<h5 class="modal-title" id="updateModalLabel">Update Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="update-body">


			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '.update', function() {
		var id = $(this).attr("id");
		var updateType = $(this).attr("updateType");

		$.ajax({
			url: "adminUpdateModal.php",
			method: "POST",
			data: {
				id: id,
				updateType: updateType
			},
			success: function(data) {
				$('#update-body').html(data);
				$('#updateModal select').css('width', '100%');
				$('.js-example-basic-single').select2();
				$('#updateModal').modal('show');

			}
		});
	});

	$(document).on('click', '.update_delivery', function() {
		var deliveryId = $(this).attr("id");

		$.ajax({
			url: "adminUpdateModal.php",
			method: "POST",
			data: {
				deliveryId: deliveryId,
			},
			success: function(data) {
				$('#update-body').html(data);
				$('#updateModal select').css('width', '100%');
				$('.js-example-basic-single').select2();
				$('#updateModal').modal('show');

			}
		});
	});

	$(function(){
		$('#reqTable').DataTable({
			"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
			dom: 'lfrtip',
			language: {
				oPaginate: {
					sNext: '<i class="fas fa-forward"></i>',
					sPrevious: '<i class="fas fa-backward"></i>',
					sFirst: '<i class="fas fa-step-backward"></i>',
					sLast: '<i class="fas fa-step-forward"></i>'
				}
			},
			pagingType: 'full_numbers',
		});
	});
</script>



