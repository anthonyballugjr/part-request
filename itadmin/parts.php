<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

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
		

		<div class="col mx-5" id="taskview">

			<h3 align="center">PARTS</h3>

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
				<button class="btn btn-sm btn-warning add-item" item=2><i class="fas fa-plus"></i> Add new Part</button>
			</div>

			<div class="row">
				<div class="col-4 mx-0">
					<input name="partNo" id="partNo" type="text" class="form-control" placeholder="Search for a Part No." autofocus>
				</div>
				<div class="col mx-0">
					<button name="searchBtn" id="searchBtn" class="btn btn-primary btn-sm searchPart"><i class="fas fa-search"></i> Search</button>
				</div>
			</div>


			<div class="form-row form-group" >
				<div class="col mt-3" id="partsContent">
					
				</div>
			</div>

		</div>
		
	</div>

</div>

<?php include_once "../includes/footer.php";?>

<div class="modal fade" id="add-item-modal" role="dialog" data-backdrop="static" aria-labelledby="updateModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-moog text-white">
				<h5 class="modal-title" id="add-item-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="add-item-body">


			</div>
		</div>
	</div>
</div>

<script>

	$('.add-item').click(function(){
		var item = $(this).attr('item');
		var title = 'Add Part';

		$.ajax({
			url: 'add-item.php',
			method: 'POST',
			data: {item:item},
			success: function(data){
				$('#add-item-title').text(title);
				$('#add-item-body').html(data);
				$('#add-item-modal').modal('show');
			}
		})
	})

	$(function(){
		$('.searchPart').attr('disabled',true);

		$('#partNo').keyup(function(){
			if($(this).val().length > 2){
				$('.searchPart').attr('disabled', false);
			}
			else
			{
				$('.searchPart').attr('disabled', true);        
			}
		})
	});


	$('.searchPart').click(function(){
		var partNo = $('#partNo').val();
		console.log(partNo);

		$.ajax({
			method: 'POST',
			url: 'partFetch.php',
			data: {
				partNo: partNo
			},
			success: function(data){
				$('.searchPart').attr('disabled',true);
				$('#partsContent').html(data);
				$('#partNo').val("");
			}
		});
	});

	$('#partNo').keyup(function(e){
		if(e.keyCode === 13){
			$('#searchBtn').click();
		}
	});

</script>







