<?php
include_once "../config/database.php";
include_once "../config/ldap.php";

$database = new Database();
$db = $database->getConnection();

// echo $_SESSION['user'];
// echo $_SESSION['access'];

// foreach($_SESSION as $x){
// 	echo $x;
// }
?>

<h3 align="center">ADD USER</h3>

<div class="float-right">
	<a href="users.php" class="btn btn-sm btn-info add-item"><i class="fas fa-arrow-left"></i> Back to Users List</a>
</div>


<div class="row">
	<div class="col-4 mx-0">
		<input name="name" id="adname" type="text" class="form-control" placeholder="Search for a user" autofocus required>
	</div>
	<div class="col mx-0">
		<button name="searchBtn" id="searchBtn" class="btn btn-primary btn-sm search-user"><i class="fas fa-search"></i> Search</button>
	</div>
</div>


<div class="form-row form-group" >
	<div class="col" id="user-list"></div>
	
</div>


<script>
	$('.search-user').click(function(){
		var name = $('#adname').val();

		$.ajax({
			url: 'userFetch.php',
			method: 'POST',
			data: {name:name},
			success: function(data){
				$('.search-user').attr('disabled', true);
				$('#user-list').html(data);
				$('#adname').val('');
			}
		});
	});

	$(function(){
		$('#searchBtn').attr('disabled',true);

		$('#adname').keyup(function(){
			if($(this).val().length > 1){
				$('#searchBtn').attr('disabled', false);
			}
			else
			{
				$('#searchBtn').attr('disabled', true);        
			}
		})
	});

	$('#adname').keyup(function(e){
		if(e.keyCode === 13){
			$('#searchBtn').click();
		}
	});
</script>