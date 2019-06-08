<?php
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/bin.php";

$database = new Database();
$db = $database->getConnection();

if(isset($_POST['filter'])){
	$filter = $_POST['filter'];
	$bin = new Bin($db);
	$stmt = $bin->searchBin($filter);
	$count = $stmt->rowCount();
}

if($count > 0){ ?>
	<div class="alert alert-dismissible alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?php echo $count;?></strong> Search Results Found.
	</div>

	<table class="table table-hover table-sm" id="binTable">
		<thead class="bg-moog text-white text-center">
			<tr>
				<th scope="col">2Bin Id</th>
				<th scope="col">Part Number</th>
				<th scope="col">Location</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody class="text-center">
			<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				echo 
				"<tr>
				<td>{$binId}</td>
				<td>{$partNo}</td>
				<td>{$location}</td>
				<td>
				<button class='btn btn-primary btn-sm edit-item' item=3 id={$binId}><i class='fas fa-edit'></i> Edit</button>
				<button class='btn btn-danger btn-sm delete-item' item=3 id={$binId}><i class='fas fa-trash'></i> Delete</button>
				</td>
				</tr>";
			}?>
		</tbody>
	</table>

<?php } else{?>
	<div class="alert alert-dismissible alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?php echo $count;?></strong> Search Results Found.
	</div>
<?php } ?>


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

<script>
	$('.edit-item').click(function(){
		var id = $(this).attr('id');
		var item = $(this).attr('item');
		var title = 'Edit Bin Location';

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
				$('.js-example-basic-single').select2();
				$('#item-modal').modal('show');
			}
		})
	});

	$('.delete-item').click(function(){
		var item = $(this).attr('item');
		var id = $(this).attr('id');
		var title = 'Delete Bin Location';
		$.ajax({
			url: 'delete-item.php',
			method: 'POST',
			data: {
				item: item,
				id: id
			},
			success: function(data){
				$('#item-title').text(title);
				$('#item-body').html(data);
				$('#item-modal').modal('show');
			}
		});
	});
</script>