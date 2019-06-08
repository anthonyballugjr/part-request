<?php
$pageTitle = "Dashboard";

date_default_timezone_set("Asia/Manila");
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/utility.php";
include_once "../includes/header.php";
include_once "../config/utilities.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$utility = new Utility($db);

if(!isSession()){
	header("location:/prq");
}

// if(!isCSAdmin()){
// 	$code = base64_encode("401");
// 	$desc = base64_encode("Unauthorized");
// 	$message = base64_encode("You are not authorized to access this page");
// 	header("location:../error?code=$code&desc=$desc&message=$message");
// }

include_once "../includes/nav.php";

?>

<div class="container-fluid" id="dashBody">
	
	<div class="form-row mt-2">
		<div class="col-sm-2 ml-auto">
			<div class="ml-auto mx-2">
				<h6 class="text-dark text-right my-1"><?php echo date('F d, Y');?></h6>
				<h6 id="clock" class="text-right"></h6>
			</div>
		</div>
	</div>	

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
	
	<div class="container-fluid" id="x-fluid">
		<div class="row bg-moog">

			<div class="d-flex">
				<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
					<div class="btn-group" role="group" aria-label="First group">
						<button type="button" class="btn border-right rounded-0 text-white" data-trigger="focus" id="pop" data-toggle="popover" title="IMPORTANT" data-content="IF YOU FIND ANY BUGS, PLEASE MESSAGE THE SYSTEM ADMINISTRATOR."><i class="fas fa-info-circle"></i></button>
						<div class="btn-group border-right">
							<button class="btn btn-sm text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button">
								<i class="fas fa-grip-horizontal"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item bulk-action" id="delete-cancelled" href="#">Delete All Cancelled Requests</a>
								<a class="dropdown-item bulk-action" id="archive-closed" href="#">Archive <strong>Closed</strong> requests that are 30+ days old</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item bulk-action" id="delete-archive" href="#">Delete Archives that are 6+ months Old</a>
							</div>
						</div>
						<button type="button" class="btn border-right rounded-0 text-white" onclick="getDash()" data-toggle="tooltip" title="Refresh Window"><i class="fas fa-sync"></i></button>
						<a onclick="printPage()" class="nav-link text-white dash-icon-btn" data-toggle="tooltip" data-placement="top" title="Print Window" href="#"><i class="fas fa-print dash-icon-btn"></i> </a>
					</div>
				</div>
			</div>
		</div>

		<!--LABELS-->
		<div class="row py-2 mb-1 bg-primary text-center text-white">
			<div class="col-9">
				<div class="row">
					<div class="col-2"><h5>REQUEST TYPE</h5></div>
					<div class="col"><a class="text-white"><h5>NEW</h5></a></div>
					<div class="col"><a class="text-white"><h5>FOR PICKING</h5></a></div>
					<div class="col"><a class="text-white"><h5>FOR DELIVERY</h5></a></div>
					<div class="col"><a class="text-white"><h5>DELIVERED</h5></a></div>
					<div class="col"><a class="text-white"><h5>RECEIVED</h5></a></div>
					<div class="col"><a class="text-white"><h5>ON HOLD</h5></a></div>
				</div>
			</div>
			<div class="col">
				<div class="row">
					<div class="col"><a href="logs.php" class="text-white"><h5>LOGS</h5></a></div>
				</div>
			</div>
		</div><!--/LABELS-->


		<!--DASHBOARD-->
		<div class="row">

			<!--DASHBOARD TABLE CONTAINER-->
			<div class="col-9" id="dashContent">
				<h3 align="center">Fetching Data...</h3>
				<img class="mx-auto d-block" align="center" src="../res/hourglass.gif" width="50">

			</div><!--/DASHBOARD TABLE CONTAINER-->


			<!--LOGS CONTAINER-->
			<div class="col" id="logsColumn">


			</div><!--/LOGS-->

		</div>
		<!--/DASHBOARD-->

		<!--TOTAL-->
		<div class="row bg-primary text-center text-white px-0">
			<div class="col-9">
				<div class="row">
					<div class='col-2 d-flex align-items-center'>
						<div class='mx-auto'>
							<h5 class='text-center my-1'>TOTAL</h5>
						</div>
					</div>
					<div class="col">
						<div class="row my-auto">
							<?php 
							$stat = array(1,2,3,4,5,6);
							foreach($stat as $a){
								$b = $utility->countByStatus($a);
								echo 
								"<div class='col text-center'>
								<h5 class='my-1'>".$b."</h5>
								</div>";
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/TOTAL-->

		<div class="row mt-2">

			<div class="col-6 px-0">
				<div class="card border-dark">
					<div class="card-header bg-moog text-white d-flex align-items-center">
						Average TAT per Day
						<div class="row ml-auto">
							<button class="btn btn-link text-white" onclick="showTATChart()"><i class="fas fa-sync"></i></button>
						</div>

					</div>
					<div class="card-body" id="tatChart">
						<canvas class="chart-canvas" id="overallChart"></canvas>
					</div>
				</div>
			</div>

			<div class="col-2">
				<div class="card border-dark">
					<div class="card-header bg-moog text-white d-flex align-items-center">
						Cancelled Requests
					</div>
					<div class="card-body">
						<a class="text-dark" href="view.php?view=<?php echo base64_encode(7);?>">
							<h3><?php echo $utility->countByStatus(7);?></h3>
						</a>
					</div>
				</div>
			</div>
			<div class="col-2">
				<div class="card border-dark">
					<div class="card-header bg-moog text-white d-flex align-items-center">
						Archived Requests
					</div>
					<div class="card-body">
						<a class="text-dark" href="view.php?view=<?php echo base64_encode(8);?>">
							<h3><?php echo $utility->countByStatus(8);?></h3>
						</a>
					</div>
				</div>
			</div>
			<div class="col-2">
				<div class="card border-dark">
					<div class="card-header bg-moog text-white d-flex align-items-center">
						Aging Requests (8+ hours old)
					</div>
					<div class="card-body">
						<a class="text-dark" href="view.php?view=<?php echo base64_encode(99);?>">
							<h3><?php echo $utility->countAging();?></h3>
						</a>
					</div>
				</div>
			</div>

		</div>

	</div>
	<!--/x-fluid-->

</div><!--/CONTAINER-->



<?php
include_once "../includes/footer.php";
?>

<script>
	$(function (){
		startTime();
		getDash();
		getLogs();
		showTATChart();
	});

		// $('.bulk-action').click(function(){
		// 	var action = $(this).attr('id');

		// 	$.ajax({
		// 		url: 'bulk.php',
		// 		method: 'POST',
		// 		success: function(data){

		// 		}
		// 	});
		// });

		function showTATChart(){
			$(document).ready(function(){
				$.ajax({
					url: "dataOverview.php",
					method: "GET",
					success: function(data) {
						var datajson = JSON.parse(data);
						console.log(data);
						var dateT = [];
						var ave = [];
						var reqCount = [];
						var type = [];

						for(var i in datajson) {
							dateT.push(datajson[i].dateT);
							ave.push(datajson[i].ave);
							reqCount.push(datajson[i].requestCount)
						}
						console.log(dateT, ave);
						var typex = ["Replacement", "AFS", "2BIN", "Quality Issue"];

						var chartdata = {

							labels: dateT,
							datasets : 
							[
							{
								label: 'Total Request Count',
								backgroundColor: 'rgba(152, 1, 46, 0.5)',
								hoverBackgroundColor: 'rgba(152, 1, 46, 0.7)',
								borderColor: 'rgba(152, 1, 46, 0.7)',
								data: reqCount,
								yAxisID: 'A',
								type: 'bar'
							},
							{
								label: 'Average TAT',
								backgroundColor: 'rgba(0, 0, 0, 0.7)',
								borderColor: 'rgba(0, 0, 0, 0.7)',
								hoverBackgroundColor: 'rgba(0, 0, 0, 1)',
								hoverBorderColor: 'rgba(200, 200, 200, 1)',
								fill: false,
								lineTension: 0.4,
								pointStyle: 'rectRounded',
								data: ave,
								yAxisID: 'B',
								type: 'line'
							}
							]
						};

						var ctx = $("#overallChart");

						var lineGraph = new Chart(ctx, {
							data: chartdata,
							type: 'bar',
							options: {
								title: {
									display: true,
									text: 'Average TAT/Day'
								},
								scales: {
									yAxes: [{
										id: 'A',
										position: 'left',
										scaleLabel: {
											display: true,
											labelString: 'Request Count'
										},
										ticks: {
											beginAtZero: true,
											fixedStepSize: 10
										},
									},
									{
										id: 'B',
										position: 'right',
										scaleLabel: {
											display: true,
											labelString: 'Time in Hours'
										},
										ticks: {
											beginAtZero: true,
											fixedStepSize: 3
										}
									}],
									xAxes: [{
										scaleLabel: {
											display: true,
											labelString: 'Date'
										},
									}]
								}
							}
						});
					},
					error: function(data) {
						console.log(data);
					}
				});
			});
		}

		//REAL TIME CLOCK ON DASHBOARD
		function startTime() {

			var today = new Date();
			var hr = today.getHours();
			var min = today.getMinutes();
			var sec = today.getSeconds();
			ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
			hr = (hr == 0) ? 12 : hr;
			hr = (hr > 12) ? hr - 12 : hr;
			hr = checkTime(hr);
			min = checkTime(min);
			sec = checkTime(sec);
			document.getElementById("clock").innerHTML = hr + " : " + min + " : " + sec + " " + ap;
			var time = setTimeout(function() {
				startTime()
			}, 500);
		}

		function checkTime(i) {
			if (i < 10) {
				i = "0" + i;
			}
			return i;
		}

		function getDash(){
			$.get('dashContent.php', function (data){
				$('#dashContent').html(data);
			});
		}

		function getLogs(){
			$.get('logsFetch.php', function (data){
				$('#logsColumn').html(data);
			});
		}

		setInterval('getDash()', 1000*10);
		setInterval('getLogs()', 1000*10);

		function printPage(){
			var css = '@page { size: landscape; }',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');

			style.type = 'text/css';
			style.media = 'print';

			if (style.styleSheet){
				style.styleSheet.cssText = css;
			} else {
				style.appendChild(document.createTextNode(css));
			}

			head.appendChild(style);

			window.print();
		}

		jQuery(function () {
			jQuery('[data-toggle="popover"]').popover()
		});

	</script>






