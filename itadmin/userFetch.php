<?php
include_once "../config/database.php";
include_once "../config/ldap.php";

$database = new Database();
$db = $database->getConnection();

$ldap = new LDAP($db);
$ldap->LDAPconnect();

// echo $_SESSION['user'];
// echo $_SESSION['access'];

// foreach($_SESSION as $x){
// 	echo $x;
// }

if(isset($_POST['name'])){
	$name = $_POST['name'];
}

$data = $ldap->searchUser($name);
if($data['count'] > 0){?>

	<div class='alert alert-danger fade-show mt-3' role='alert'><?php echo $data['count'];?> Search Results
		<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>


	<table class="table table-sm mt-3" id="dt">
		<thead class="bg-moog text-white text-center">
			<tr>
				<th scope="col">No.</th>
				<th scope="col">Full Name</th>
				<th scope="col">Account Name</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody class="text-center">
			<?php for($i=0;$i<$data['count'];$i++){
				echo "<tr>
				<td>".($i + 1)."</td>
				<td>".$data[$i]['displayname'][0]."</td>
				<td>".$data[$i]['samaccountname'][0]."</td>
				<td>
				<button class='btn btn-primary add-item' item=1 name='".$data[$i]['displayname'][0]."' samaccount='".$data[$i]['samaccountname'][0]."'><i class='fas fa-user-plus'></i> Add</button>
				</td>
				</tr>";
			}?>
		</tbody>
	</table>

<?php }else{

	echo "<div class='alert alert-danger fade-show mt-3' role='alert'>".$data['count']." Search Results
	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
	<span aria-hidden='true'>&times;</span>
	</button></div>";
}?>


<div class="modal fade" id="add-item-modal" role="dialog" data-backdrop="static" aria-labelledby="updateModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-moog text-white">
				<h5 class="modal-title" id="add-item-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="add-item-body">


			</div>
		</div>
	</div>
</div>

<script>
	$('.add-item').click(function(){
		var item = $(this).attr('item');
		var name = $(this).attr('name');
		var samaccount = $(this).attr('samaccount');
		var title = 'Add User';

		console.log(item, name, samaccount);

		$.ajax({
			url: 'add-item.php',
			method: 'POST',
			data: {
				item: item,
				name:name,
				samaccount:samaccount
			},
			success: function(data){
				$('#add-item-title').text(title);
				$('#add-item-body').html(data);
				$('#add-item-modal select').css('width', '100%');
				$('.select-2').select2();
				$('#add-item-modal').modal('show');
			}
		})

	});
</script>