<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../classes/bin.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

$database = new Database();
$db = $database->getConnection();

$binLocations = new Bin($db);

if(!isSession()){
	header("location:/prq");
}

// if(!isSysAdmin()){
// 	$code = base64_encode("401");
// 	$desc = base64_encode("Unauthorized");
// 	$message = base64_encode("You are not authorized to access this page");
// 	header("location:../error?code=$code&desc=$desc&message=$message");
// }

?>

<div class="container-fluid">

	<div class="row px-0 mt-5">

		<?php include_once "sideNav.php";?>

		<!--DYNAMIC TASK VIEW-->
		<div class="col mx-5" id="taskview">

			<h3 align="center">BIN LOCATIONS</h3>

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

			<div class="float-right">
				<button class="btn btn-sm btn-warning add-item" item=3><i class="fas fa-plus"></i> Add New 2BIN location</button>
			</div>

			<div class="row">
				<div class="col-4 mx-0">
					<input name="filter" id="filter" type="text" class="form-control" placeholder="Search 2BIN (Enter either Location or Part No.)" autofocus>
				</div>
				<div class="col mx-0">
					<button name="searchBtn" id="searchBtn" class="btn btn-primary btn-sm searchBin"><i class="fas fa-search"></i> Search</button>
				</div>
			</div>

			<div class="form-row form-group" >
				<div class="col mt-3" id="binsContent">
					
				</div>
			</div>

		</div>
		<!--/DYNAMIC-->
	</div>

</div>

<div class="modal fade" id="item-modal" role="dialog" data-backdrop="static" aria-labelledby="updateModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-moog text-white">
				<h5 class="modal-title" id="item-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="item-body">


			</div>
		</div>
	</div>
</div>


<?php include_once "../includes/footer.php";?>

<script>

	$(function(){
		$('.searchBin').attr('disabled',true);

		$('#filter').keyup(function(){
			if($(this).val().length > 3){
				$('.searchBin').attr('disabled', false);
			}
			else
			{
				$('.searchBin').attr('disabled', true);        
			}
		})
	});


	$('.searchBin').click(function(){
		var filter = $('#filter').val();
		console.log(location);

		$.ajax({
			method: 'POST',
			url: 'binFetch.php',
			data: {
				filter: filter
			},
			success: function(data){
				$('.searchPart').attr('disabled',true);
				$('#binsContent').html(data);
				$('#filter').val("");
				$('.searchBin').attr('disabled', true);
			}
		});
	});

	$('#filter').keyup(function(e){
		if(e.keyCode === 13){
			$('.searchBin').click();
		}
	});

	$('.add-item').click(function(){
		var item = $(this).attr('item');
		var title = 'Add Bin Location';

		$.ajax({
			url: 'add-item.php',
			method: 'POST',
			data: {item:item},
			success: function(data){
				$('#item-title').text(title);
				$('#item-body').html(data);
				$('#item-modal').modal('show');
			}
		})
	})
</script>







