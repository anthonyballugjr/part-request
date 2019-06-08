<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";

if(!isSession()){
	header("location:/prq");
}

// if(!isSysAdmin()){
// 	$code = base64_encode("401");
// 	$desc = base64_encode("Unauthorized");
// 	$message = base64_encode("You are not authorized to access this page");
// 	header("location:../error?code=$code&desc=$desc&message=$message");
// }

include_once "../includes/nav.php";
?>

<div class="container-fluid">

	<div class="row my-2">
		<div class="col">
			<?php echo displayError();?>
			<?php if(isset($_SESSION['message'])): ?>
				<div class="alert alert-dismissible alert-dark bg-moog py-3 my-3">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<p class="mb-0"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></a>.</p>
				</div>
			<?php endif?>
		</div>
	</div>

	<div class="row px-0 mt-5">

		
		<?php include_once "sideNav.php";?>

		<!--DYNAMIC TASK VIEW-->
		<div class="col-8 ml-5" id="dashContent">
			
		</div>
		<!--/DYNAMIC-->

		<div class="col">
			<div class="card border-dark">
				<div class="card-header bg-moog text-white">
					Recent Messages
					<p class="py-0 my-0"><a class="text-white" href='#'><small>View all</small></a></p>
				</div>

				<div class="card-body" id="messages-view">

				</div>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="viewMessage" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-moog text-white">
				<h5 class="modal-title" id="viewMessageTitle"><i class="fas fa-envelope-open-text"></i> </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="message-body">

			</div> 
			<div class="modal-footer">
				<button class="btn btn-danger" type="cance" data-dismiss="modal">Close</button>
			</div>     
		</div>
	</div>
</div>

<?php include_once "../includes/footer.php";?>

<script>
	$(function(){
		getDash();
		getMessages();
	});

	function getDash(){
		$.get('dashContent.php', function (data){
			$('#dashContent').html(data);
		});
	}

	function getMessages(){
		$.get('fetchMessages.php', function(data){
			$('#messages-view').html(data);
		});
	}

	setInterval('getDash()', 1000*10);
	setInterval('getMessages()', 1000*20);

</script>
