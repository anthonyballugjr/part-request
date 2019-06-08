<?php
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/bin.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$bin = new Bin($db);

// $filter = ["-","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
$filter = ["K" => "Components", "E" => "Hydraulics Boeing", "R" => "Hydarulics Airbus", "J" => "Highlift"];

if(isset($_POST['type'])){
	$requestTypeId = $_POST['type'];
	echo "<input type='hidden' value='$requestTypeId' id='rtype'>"; ?>

	<div class="container loader" id="loader" style="display: none;">
		<img src="../res/hourglass.gif" class="img-fluid">
	</div>
	<?php
//REPLACEMENT
	if($requestTypeId == 1 || $requestTypeId == 2){?>

		<div class="form-row form-group">
			<div class="col-sm-4">
				<label>Unit</label>
				<select class="form-control js-example-basic-single" id="partsFilter">
					<option value="x">--Select Unit--</option>
					<?php foreach($filter as $f => $key){
						echo "<option value=".$f.">".$key."</option>";
					}?>
				</select>
			</div>
			<div class="col-sm-4">
				<label>Part No</label>
				<select class="form-control js-example-basic-single" name="partId" id="partId" required>
					<option value=""></option>
				</select>
			</div>
		</div>

		<div class="jumbotron-fluid" id="dynamic_field">
			<div class="form-row form-group">
				<div class="col-sm-4">
					<label>Work Order</label>
					<input name="workOrder[]" type="text" class="form-control form-control-sm" style="border: solid rgba(0,0,0,.4) 1px;" id="workOrder" maxlength="9" minlength=8 placeholder="Work Order" required>
				</div>
				<div class="col-sm-2">
					<label>Quantity</label>
					<input name="quantity[]" type="number" class="form-control form-control-sm" style="border: solid rgba(0,0,0,.4) 1px;" id="quantity" min=1 placeholder="Quantity" required>
				</div>
				<div class="col-sm d-flex align-items-end">
					<a class="btn btn-primary btn-sm text-white" id="add-row" href="#bottom"><i class="fas fa-plus"></i> Add Work order</a>
				</div>
			</div>
		</div>

	<?php }
	else if($requestTypeId == 4){
		//AFS || QUALITY ISSUE?>
		
		<div class="form-row form-group">
			<div class="col-sm-4">
				<label>Unit</label>
				<select class="form-control js-example-basic-single" id="partsFilter" required>
					<option>--Select Unit--</option>
					<?php foreach($filter as $f => $key){
						echo "<option value=".$f.">".$key."</option>";
					}?>
				</select>
			</div>

			<div class="col-sm-4">
				<label>Part No</label>
				<select class="form-control js-example-basic-single" name="partId" id="partId" required>
					<option value=""></option>
				</select>
			</div>

			<div class="col-sm-2">
				<label>Quantity</label>
				<input type="number" class="form-control form-control-sm" name="quantity" style="border: solid rgba(0,0,0,0.4) 1px;" id="quantity" min=1 required>
			</div>
		</div>

	<?php }
	else if($requestTypeId == 3){?>

		<div class="form-row form-group">
			<div class="col-sm-4">
				<label>Unit</label>
				<select class="form-control js-example-basic-single" id="partsFilter" required>
					<option>--Select Unit--</option>
					<?php foreach($filter as $f => $key){
						echo "<option value=".$f.">".$key."</option>";
					}?>
				</select>
			</div>
			<div class="col-sm-4">
				<label>Part No</label>
				<select class="form-control js-example-basic-single" name="partId" id="partId" required>
					<option value=""></option>
				</select>
			</div>

			<div class="col-sm-2">
				<label>Quantity</label>
				<input type="number" class="form-control form-control-sm" name="quantity" style="border: solid rgba(0,0,0,0.4) 1px;" id="quantity" min=1 required>
			</div>
			<div class="col-sm">
				<label>Bin Location</label>
				<select class="form-control js-example-basic-single" name="binLocation" id="binLocation" required>
					<option value=""></option>
				</select>
			</div>
		</div>

	<?php }
	else{ ?>
		<div class="alert alert-dismissible alert-primary">
			<!-- <button type="button" class="close" data-dismiss="alert">&times;</button> -->
			<h4 class="alert-heading">Hey There!</h4>
			<p class="mb-0">Select a reason for your request</p>
		</div>

	<?php }
}

include_once "../includes/footer.php";
?>


<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});

	$(document).on('change', '#partsFilter', function() {
		var filter = $(this).val();
		var rtype = $('#rtype').val();

		console.log('RTYPE', rtype, 'FILTER', filter);

		$.ajax({
			url: "partFetch.php",
			method: "POST",
			data: {
				filter: filter,
				rtype: rtype
			},
			beforeSend: function(){
				$('#loader').show();
			},
			success: function(data) {
				$('#partId').html(data);
			},
			complete: function(data){
				$('#loader').hide();
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

	$(document).ready(function(){
		var i=1;

		$('#add-row').click(function(){
			i++;
			$('#dynamic_field').append('<div class="form-row form-group" id="row'+i+'"><div class="col-sm-4"><input type="text" class="form-control form-control-sm" name="workOrder[]" style="border: solid rgba(0,0,0,.4) 1px;" id="workOrder" placeholder="Work Order" maxlength="9" minlength=8 required></div><div class="col-sm-2"><input type="number" class="form-control form-control-sm" name="quantity[]" style="border: solid rgba(0,0,0,.4) 1px;" id="quantity" min=1 placeholder="Quantity" required></div><div class="col-sm d-flex align-items-end"><a class="btn btn-danger text-white btn_remove" id="'+i+'" href="#bottom"><i class="fas fa-times"></i> </a></div></div>');

		});

		$(document).on('click', '.btn_remove', function(){
			var button_id = $(this).attr("id"); 
			$('#row'+button_id+'').remove();
		});

	});
</script>