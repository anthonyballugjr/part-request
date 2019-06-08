<?php
include_once "../config/database.php";
include_once "../classes/logs.php";

$database = new Database();
$db = $database->getConnection();

$logs = new Logs($db);
?>	

<div class="card card-dark border-dark text-info" style="background:black;">

	<div class="card-header py-1 sticky-top bg-black" style="background: black;">
		Showing last 30 logs
		<div class="float-right py-0 px-1">
			<a href="logs.php" class="text-white" data-toggle="tooltip" data-title="View All Logs" data-placement="left"><i class="fas fa-list"></i> </a>
		</div>
	</div>

	<ul class="pl-4">
		<?php $stmt = $logs->readFew();
		foreach($stmt as $row){
			extract($row);
			if($row['remarks'] == "edit-content"){
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId}'s details)</li>";
			}else if($row['action'] == "created"){
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId})</li>";
			}else if($row['remarks'] == "returned"){
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} returned RN-{$requestId} to <em>{$statusName}</em> status)</li>";
			}else if($row['statusId'] == 6){
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) Remarks: {$remarks}</li>";
			}else if($row['statusId'] == 7){
				$created = $logs->getRow(1, $row['requestId']);
				$cancelled = $logs->getRow(7, $row['requestId']);
				$time1 = strtotime($created['actionAt']);
				$time2 = strtotime($cancelled['actionAt']);
				$diff = intval(($time2) - $time1)/60;
				$hours = intval($diff/60);
				$min = $diff%60;
				$tat = $hours.":".$min;

				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) Remarks: {$remarks} TAT [ $tat ] </li>";
			}else if($row['statusId'] == 4){
				if($row['remarks'] != null || $row['remarks'] != ""){
					echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) Remarks: {$remarks}</li>";
				}else{
					echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>)</li>";
				}
			}else if($row['remarks'] != null || $row['remarks'] != ""){
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} added remarks to RN-{$requestId} with status <em>{$statusName}</em>) Remarks: {$remarks}</li>";
			}else if($row['statusId'] == 5){
				$created = $logs->getRow(1, $row['requestId']);
				$cancelled = $logs->getRow(5, $row['requestId']);
				$time1 = strtotime($created['actionAt']);
				$time2 = strtotime($cancelled['actionAt']);
				$diff = intval(($time2) - $time1)/60;
				$hours = intval($diff/60);
				$min = $diff%60;
				$tat = $hours.":".$min;

				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>) ----- TAT [ $tat ] </li>";
			}else{
				echo "<li>[{$logId}] ".date('m/d/y - h:i:sA', strtotime($row['actionAt'])). " ({$actionBy} {$action} RN-{$requestId} to <em>{$statusName}</em>)</li>";
			}

		}?>
	</ul>
</div>

<?php
include_once "../includes/footer.php";
?>

<script>
	jQuery(function () {
		jQuery('[data-toggle="tooltip"]').tooltip()
	});
</script>