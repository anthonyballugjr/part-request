<?php
$pageTitle = "Request Logs";

include_once "../config/database.php";
include_once "../classes/logs.php";
include_once "../config/utilities.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

$database = new Database();
$db = $database->getConnection();

$logs = new Logs($db);

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

		<!--LOGS-->
		<div class="col mx-5">
			<a class="btn btn-primary" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Back to Dashboard</a>
			<hr>
			<div class="card card-dark border-dark text-info mx-auto" style="background:black;max-width: 80rem">

				<div class="card-header py-1 sticky-top bg-black" style="background: black;">
					Logs
					<div class="float-right py-1 px-1">
						<a onclick="window.location.reload();" href="#" class="text-white" data-placement="left"><i class="fas fa-sync"></i> </a>
					</div>
				</div>

				<div class="row" id="logsColumn" style="max-height:500px;">
					<ul class="pl-4" style="list-style: none">
						<?php $stmt = $logs->readAll();
						foreach($stmt as $row){
							extract($row);

							if($row['remarks'] == "edit-content"){
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId}'s details)</li>";
							}else if($row['remarks'] == "returned"){
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} returned RN-{$requestId} to <em>{$statusName}</em> status)</li>";
							}else if($row['action'] == "created"){
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId})</li>";
							}else if($row['statusId'] == 6){
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) ----- Remarks: {$remarks}</li>";
							}else if($row['statusId'] == 7){
								$created = $logs->getRow(1, $row['requestId']);
								$cancelled = $logs->getRow(7, $row['requestId']);
								$time1 = strtotime($created['actionAt']);
								$time2 = strtotime($cancelled['actionAt']);
								$diff = intval(($time2) - $time1)/60;
								$hours = intval($diff/60);
								$min = $diff%60;
								$tat = $hours.":".$min;

								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) ----- Remarks: {$remarks} ----- TAT [ $tat ] </li>";
							}else if($row['statusId'] == 4){
								if($row['remarks'] != null || $row['remarks'] != ""){
									echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>)  -----  Remarks: {$remarks}</li>";
								}else{
									echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>)</li>";
								}
							}else if($row['remarks'] != null || $row['remarks'] != ""){
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} added remarks to RN-{$requestId} with status <em>{$statusName}</em>) -----  Remarks: {$remarks}</li>";
							}else if($row['statusId'] == 5){
								$created = $logs->getRow(1, $row['requestId']);
								$cancelled = $logs->getRow(5, $row['requestId']);
								$time1 = strtotime($created['actionAt']);
								$time2 = strtotime($cancelled['actionAt']);
								$diff = intval(($time2) - $time1)/60;
								$hours = intval($diff/60);
								$min = $diff%60;
								$tat = $hours.":".$min;

								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) ----- TAT [ $tat ] </li>";
							}else{
								echo "<li>[{$logId}] --------------------- ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " --------------------- ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>)</li>";
							}

						}
						

						?>
					</ul>
				</div>


			</div>
		</div>
		<!--/LOGS-->

	</div>

</div>

<?php include_once "../includes/footer.php";?>

<script>
	


</script>







