<?php
date_default_timezone_set('Asia/Manila');
//dev
// $auth = getenv("USERNAME");
$auth = "riemann";

//prod
// $getenv = $_SERVER['AUTH_USER'];
// $split = explode("\\", $getenv);
// $auth = $split[1];
?>

<!DOCTYPE html>
<html>

<head>
	<title>Parts Replacement</title>
	<meta name="author" content="Anthony D. Ballug Jr.">
	<meta name="description" content="MOOG Baguio Part Replacement System">
	<script src="assets/jquery/3.3.1/jquery-3.3.1.min.js"></script>
	<script src="assets/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="assets/popper/1.14.7/popper.min.js"></script>
	<link rel="stylesheet" href="assets/bootstrap/4.3.1/css/bootstrap.css" type="text/css">
</head>

<body>

	<style>
		.lds-hourglass {
			display: inline-block;
			position: relative;
			width: 64px;
			height: 64px;
		}

		.lds-hourglass:after {
			content: " ";
			display: block;
			border-radius: 50%;
			width: 0;
			height: 0;
			margin: 6px;
			box-sizing: border-box;
			border: 26px solid #fff;
			border-color: #98012e transparent #98012e transparent;
			animation: lds-hourglass 1.2s infinite;
		}

		@keyframes lds-hourglass {
			0% {
				transform: rotate(0);
				animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
			}
			50% {
				transform: rotate(900deg);
				animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
			}
			100% {
				transform: rotate(1800deg);
			}
		}
	</style>

	<!--Container-fluid-->
	<div class="jumbotron bg-dark">

		<div class="container-fluid py-5 mt-5" id="x">

			<input id="auth" type="hidden" class="form-control" value="<?php echo $auth;?>">

			<div class="container mx-auto">
				<img class="mx-auto d-block" src="res/prrp.png">
				<h4 style="color: #98021e;" align="center">Parts Replacement Request</h4>
			</div>

			<div class="mx-auto py-3">
				<h2 align="center">Authenticating...</h2>
				<h3 align="center">
					<?php echo $auth;?>
				</h3>
				<div class="lds-hourglass mx-auto d-block"></div>
			</div>

		</div>
	</div>

	<script>
		//PROD/LIVE
		$(document).ready(function() {
			var auth = $('#auth').val();

			console.log(auth);
			setTimeout(function() {
				window.location.href = 'authenticate.php?auth=' + auth;
			}, 2000);
		});

		//MAINTENANCE
		// $(function(){
		// 	var code = btoa(503);
		// 	var description = btoa('Service Unavailable');
		// 	var message = btoa('We are sorry. The system is currently down for maintenance');

		// 	window.location.href = 'error?code=' + code + '&desc=' + description + '&message=' + message; 
		// });
	</script>

</body>

</html>