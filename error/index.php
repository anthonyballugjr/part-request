<?php
$error = $_GET;
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo base64_decode($_GET['code']);?></title>


	<link rel="stylesheet" type="text/css" href="../assets/custom/css/404.css" />
	<link rel="stylesheet" type="text/css" href="../assets/fontawesome-free/css/all.css" />
	<!-- <link rel="stylesheet" type="text/css" href="../assets/bootstrap/4.3.1/css/bootstrap.css"/> -->

</head>

<body>
	<style>
		#back{
			padding: 7px;
			color:white;
			text-decoration: none;
			font-size: 1rem;
			background-color: #751b26 !important;
			border-radius: 8px;
		}
	</style>

	<div class="container">

		<div id="rocket"></div>

		<hgroup>
			<h1><?php echo base64_decode($error['code']);?></h1>
			<h1><?php echo base64_decode($error['desc']);?></h1>
			<h2><?php echo base64_decode($error['message']);?></h2>
			<div style="margin-top: 20px;">
				<a href="/prq" id="back"><i class="fas fa-home"></i> Back to Home</a>
			</div>
		</hgroup>
	</div>


</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</html>

<script>
	$(function(){

		function animSteam(){

			$('<span>',{
				className:'steam'+Math.floor(Math.random()*2 + 1),
				css:{
					marginLeft	: -10 + Math.floor(Math.random()*20)
				}
			}).appendTo('#rocket').animate({
				left:'-=58',
				bottom:'-=100'
			}, 120,function(){

				$(this).remove();
				setTimeout(animSteam,10);
			});
		}

		function moveRocket(){
			$('#rocket').animate({'left':'+=100'},5000).delay(1000)
			.animate({'left':'-=100'},5000,function(){
				setTimeout(moveRocket,1000);
			});
		}

		moveRocket();
		animSteam();
	});
</script>

