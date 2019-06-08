<?php
include_once "../config/database.php";
include_once "../classes/request.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);


if(isset($_POST['status'])){
	$status = $_POST['status'];
	$stmt = $request->readByStatus($status);
	$count = $stmt->rowCount();
}

if($count > 0){
	$data = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$data[] = $row;
	}
	$data = array_reverse($data);
	?>
	<table class="table table-striped" id="dt">
		<thead class="bg-dark text-white">
			<tr>
				<th scope="col">Request Id</th>
				<th scope="col">Reason</th>
				<th scope="col">Part Requested</th>
				<th scope="col">Part Description</th>
				<th scope="col">Workorder</th>
				<th scope="col">Quantity</th>
				<th scope="col">Requestor/Contact Number</th>
				<th scope="col">Work Center</th>
				<th scope="col">Created at</th>
				<?php if($status == 2){
					echo "<th scope='col'>Assigned Picker</th>";
				}else{
					echo "<th scope='col'>Assigned Delivery Personnel</th>";
				}?>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $d){
				extract($d);
				echo
				"<tr>
				<th scope='row'>{$requestId}</th>
				<td>{$requestType}</td>
				<td>{$partNo}</td>
				<td>{$partDescription}</td>
				<td>";
				if($d['requestTypeId'] == 1 || $d['requestTypeId'] == 2){
					$workorders = explode(":", $d['workOrder']);
					echo "<ol type='A' class='px-0'>";
					foreach($workorders as $workorder){
						echo "<li>$workorder</li>";
					}
					echo "</ol>";
				}else{
					echo "N/A";
				}
				echo 
				"</td>
				<td>";
				if($d['requestTypeId'] == 1 || $d['requestTypeId'] == 2){
					$quantities = explode(":", $d['quantity']);
					echo "<ol type='A' class='px-0'>";
					foreach($quantities as $quantity){
						echo "<li>$quantity</li>";
					}
					echo "</ol>";
				}else{
					echo $d['quantity'];
				}
				echo
				"</td>
				<td>{$requestorName}/{$contactNo}</td>
				<td>{$workCenter}</td>
				<td>".date('m/d/y', strtotime($d['requestedAt']))." @ ".date('h:iA', strtotime($d['requestedAt']))."</td>";
				if($status == 2){
					echo "<td>{$pickedBy}</td>";
				}else{
					echo "<td>{$deliveredBy}</td>";
				}
				"</tr>";
			}?>
		</tbody>
	</table>


<?php } else{
	echo "<div class='alert alert-primary'><h3>No Data to Show</h3></div>";
} 
?>

<script>
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