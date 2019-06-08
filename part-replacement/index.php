<?php

$pageTitle = "My Requests";
$navTitle = "Parts Replacement";
include_once "../includes/header.php";
include_once "../config/database.php";
include_once "../classes/utility.php";
include_once "../classes/request.php";
include_once "../config/utilities.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$util = new Utility($db);

if(!isSession()){
	header("location:/prq");
}

// print_r($_SESSION);

$statusFilter = array("Any" => 0, "New" => 1, "For Picking" => 2, "For Delivery" =>3, "Delivered" => 4, "Received/Closed" => 5, "On Hold" => 6, "Cancelled" => 7, "Archived" => 8);

if(isset($_POST['deleteAllCancelled'])){

}

if(isset($_POST['deleteAllReceived'])){

}

include_once "../includes/nav.php";

?>

<div class="container-fluid">

	<div class="row mt-3">
		<div class="col-7">
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
		</div>
	</div>
	
	<div class="row">
		<div class="col-7">
			<div class="row">
				<div class="col">

					<div class="card border-dark">

						<div class="card-body">
							<div class="container loader" id="loader" style="display: none;">
								<span>
									<img src="../res/hourglass.gif" class="img-fluid">
								</span>
							</div>

							<div class="form-row form-group bg-moog">

								<div class="col-sm form-inline">
									<label class="text-white ml-2 mr-3">Showing status:</label>
									<select class="form-control form-control-sm" id="reqStatus" onchange="getRequests()">
										<?php foreach($statusFilter as $label => $value){
											echo "<option value=$value>$label</option>";
										}?>
									</select>
								</div>
								<div class="col-sm-4 py-1">
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
										</div>
										<input type="search" class="form-control form-control-sm my-auto" placeholder="Search my requests..." id="searchTable">
									</div>
								</div>

								<div class="float-right d-flex">
									<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
										<div class="btn-group" role="group" aria-label="First group">
											<button type="button" class="btn border-left rounded-0 text-white"data-trigger="focus" id="pop" data-toggle="popover" title="IMPORTANT" data-content="IN CASE OF MISTAKES IN DETAILS; YOU MAY UPDATE/EDIT YOUR REQUEST/S AS LONG AS THE STATUS REMAINS 'NEW'; OTHERWISE, CANCEL YOUR REQUEST AND SUBMIT A NEW ONE"><i class="fas fa-info-circle"></i></button>
											<div class="dropdown border-left">
												<button class="btn btn-sm text-white dropdown-toggle" data-toggle="dropdown" type="button">
													<i class="fas fa-grip-horizontal"></i>
												</button>
												<div class="dropdown-menu">
													<a class="dropdown-item delete-all update" updateType=8 id=7 href="#">Delete All Cancelled Requests</a>
												</div>
											</div>
											<button type="button" class="btn border-left rounded-0 text-white" onclick="getRequests()" data-toggle="tooltip" title="Refresh Window"><i class="fas fa-sync"></i></button>
										</div>
									</div>
								</div>
							</div>

							<div class="row active-request">
								<div class="col" id="reqBody">
									
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!--ROW-->
			
			<!--/ROW-->

		</div>
		<!--/COL-->

		<div class="col" id="dynamic">

		</div>
		<!--/COL-->

	</div>
	<!--/ROW-->

</div>
<!--/CONTAINER-FLUID-->

<?php include_once "../includes/footer.php";?>

<script>
	$(document).ready(function() {
		$('.select2').select2();

		var statusId = 0;
		var label = 'Any';
		$.ajax({
			url: 'requestFetch.php',
			method: 'POST',
			data: {
				statusId: statusId,
				label: label
			},
			success: function(data){
				$('#reqBody').html(data);
				console.log(statusId);
			}
		});
	});

	function getRequests(){
		var statusId = $('#reqStatus').val();
		var label = $('#reqStatus option:selected').text();

		console.log(statusId, label);
		$.ajax({
			url: 'requestFetch.php',
			method: 'POST',
			beforeSend: function(){
				$('#loader').show();
			},
			data: {
				statusId: statusId,
				label: label
			},
			success: function(data){
				$('#reqBody').html(data);
			},
			complete: function(data){
				$('#loader').hide();
			}
		});
	}

	$('.delete-all').click(function(){
		var deleteId = $(this).attr('delete-id');
		console.log(deleteId);
		$.post('delete.php', {deleteId:deleteId}, function(data, status){
			console.log(data);
		})
	});

	$(document).on('click', '.update', function() {
		var requestId = $(this).attr("id");
		var updateType = $(this).attr("updateType");

		$.ajax({
			url: "clientUpdateModal.php",
			method: "POST",
			data: {
				requestId: requestId,
				updateType: updateType
			},
			success: function(data) {
				$('#dynamic').html(data);
				// $('#updateModal select').css('width', '100%');
				// $('.js-example-basic-single').select2();
				// $('#updateModal').modal('show');

			}
		});
	});

	$('#searchTable').on('keyup', function() {
		var searchTerm = $(this).val().toLowerCase();
		$('#requestTable tbody tr').each(function() {
			var lineStr = $(this).text().toLowerCase();
			if (lineStr.indexOf(searchTerm) === -1) {
				$(this).hide();
			} else {
				$(this).show();
			}
		});
	});

	jQuery(function () {
		jQuery('[data-toggle="popover"]').popover()
	});

	jQuery(function () {
		jQuery('[data-toggle="tooltip"]').tooltip()
	});

	setInterval('getRequests()', 1000*15);
</script>