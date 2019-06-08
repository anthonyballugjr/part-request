<?php
$pageTitle = "Requests";
include_once "../includes/header.php";
include_once "../includes/nav.php";

if(!isSession()){
	header("location:/prq");
}
?>

<div class="container-fluid">

	<div class="row mt-5">
		<div class="col">
			<img src="../res/hourglass.gif" width="2%">
			<i class="text-muted fas"> Refresh time interval: 30s</i>
			<div class="card border-dark">
				<div class="card-header bg-moog text-white">
					<div class="row">
						<div class="col-sm-4 form-inline">
							<label class="mr-4">Showing Status:</label>
							<select class="form-control ml-5 select2" id="status">
								<option value=2>For Picking</option>
								<option value=3>For Delivery</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card-body" id="card-body">

				</div>
			</div>
		</div>
	</div>
</div>


<?php 
include_once "../includes/footer.php"; 
?>

<script>
	$(function(){
		getRequests();
		$('.select2').select2();
	});

	function getRequests(){
		var status = $('#status').val();

		$.ajax({
			url: 'requestFetch.php',
			method: 'POST',
			data: {status: status},
			success: function(data){
				$('#card-body').html(data);
			}
		});
	}

	$('#status').change(function(){
		getRequests();
	});

	setInterval('getRequests()', 1000*30);
</script>