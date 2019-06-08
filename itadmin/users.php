<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../classes/user.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

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

			<h3 align="center">USERS</h3>

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

			<div class="float-right mb-3">
				<button class="btn btn-sm btn-warning add-user" item=1><i class="fas fa-user-plus"></i> Add New User</button>
			</div>
			
			<table class="table table-bordered table-sm" id="dt">
				<thead class="bg-moog text-white">
					<tr class="text-center">
						<th scope="col">User</th>
						<th scope="col">Full Name</th>
						<th scope="col">Access</th>
						<th class="noex" scope='col'>Action</th>
					</tr>
					<tbody>
						<?php $stmt = $user->readAll();
						$access = array(1 => "CS Admin (WH)", 2 => "Sys Admin (IT)", 3 => "Picker", 4 => "Delivery");
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							extract($row);
							echo 
							"<tr class='text-center'>
							<td>{$samaccount}</td>
							<td>{$displayName}</td>
							<td>".$access[$row['accessId']]."</td>
							<td>
							<button class='btn btn-primary btn-sm edit-item' item=1 id={$userId}><i class='fas fa-edit edit-item'></i> Edit</button>
							<button class='btn btn-danger btn-sm delete-item' item=1 id={$userId}><i class='fas fa-trash delete-item'></i> Remove </button>
							</td>
							</tr>";
						}?>
					</tbody>
				</thead>
			</table> 

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
	$('.add-user').click(function(){
		$.get('addUser.php', function(data){
			$('#taskview').html(data);
		})
	});

	$('.delete-item').click(function(){
		var id = $(this).attr('id');
		var item = $(this).attr('item');
		title = 'Delete User';
		$.ajax({
			url: 'delete-item.php',
			method: 'POST',
			data: {
				id:id,
				item: item
			},
			success: function(data){
				$('#item-title').text(title);
				$('#item-body').html(data);
				$('#item-modal').modal('show');
			}
		});
	});

	$('.edit-item').click(function(){
		var item = $(this).attr('item');
		var id = $(this).attr('id');
		var title = 'Edit User';

		$.ajax({
			url: 'edit-item.php',
			method: 'POST',
			data: {
				item: item,
				id: id
			},
			success: function(data){
				$('#item-title').text(title);
				$('#item-body').html(data);
				$('#item-modal select').css('width', '100%');
				$('.select-2').select2();
				$('#item-modal').modal('show');
			}
		});
	});

	$(function(){
		$('#dt').DataTable({
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







