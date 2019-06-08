<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

$database = new Database();
$db = $database->getConnection();

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

	<div class="row px-0 mt-5">

		<?php include_once "sideNav.php";?>

		<!--LOGS-->
		<div class="col mx-5">
			<div class="card card-dark border-dark text-info" style="background:black;">

				<div class="card-header py-1" style="background: black;">
					<?php echo date('F d, Y');?>
					<div class="float-right py-1 px-1">
						<a onclick="getLogs();" href="#" class="text-white" data-toggle="tooltip" data-title="Refresh" data-placement="left"><i class="fas fa-sync"></i> </a>
					</div>
				</div>

				<div class="row" id="logsColumn" style="max-height: 600px;">
					
				</div>


			</div>
		</div>
		<!--/LOGS-->

	</div>

</div>

<?php include_once "../includes/footer.php";?>

<script>
	$(function(){
		getLogs();
	});

	setInterval('getLogs()', 1000*5);

	function getLogs(){
		$.get('logsFetch.php', function(data){
			$('#logsColumn').html(data);
		});
	}


</script>







