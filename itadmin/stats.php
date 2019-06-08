<?php
$pageTitle = "System Admin";
$navTitle = "System Admin";
include_once "../config/database.php";
include_once "../config/ldap.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

// if(!isSysAdmin()){
// 	$code = base64_encode("401");
// 	$desc = base64_encode("Unauthorized");
// 	$message = base64_encode("You are not authorized to access this page");
// 	header("location:../error?code=$code&desc=$desc&message=$message");
// }

if(!isSession()){
	header("location:/prq");
}

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

		<!--DYNAMIC TASK VIEW-->
		<div class="col mx-5" id="taskview">
			<div class="mx-auto text-center">
				<marquee scrollamount="20">
					<img src="../res/undercon.jpg" width="40%">
				</marquee>
			</div>
		</div>
		<!--/DYNAMIC-->
	</div>

</div>

<?php include_once "../includes/footer.php";?>







