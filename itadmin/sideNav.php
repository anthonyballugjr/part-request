<div class="col-1">
	<div class="btn-group-vertical" id="vert">
		<div class="btn-group">
			<a href="index.php" class="btn btn-lg btn-outline-danger task" id=1>
				<h4>Home</h4>
				<h1 class="text-center"><i class="fas fa-home"></i></h1>
			</a>
		</div>
		<div class="btn-group">
			<a href="users.php" class="btn btn-lg btn-outline-danger task" id=1>
				<h4>Users</h4>
				<h1 class="text-center"><i class="fas fa-user"></i></h1>
			</a>
		</div>
		<div class="btn-group">
			<a href="parts.php" class="btn btn-lg btn-outline-danger task" id=2>
				<h4>Parts</h4>
				<h1 class="text-center"><i class="fas fa-wrench"></i></h1>
			</a>
		</div>
		<div class="btn-group">
			<a href="bins.php" class="btn btn-lg btn-outline-danger task" id=3>
				<h4>2 Bin</h4>
				<h1 class="text-center"><i class="fas fa-dumpster"></i></h1>
			</a>
		</div>
		<div class="btn-group">
			<a href="stats.php" class="btn btn-lg btn-outline-danger task" id=4>
				<h4>Stats</h4>
				<h1 class="text-center"><i class="fas fa-chart-line"></i></h1>
			</a>
		</div>
		<div class="btn-group">
			<a href="logs.php" class="btn btn-lg btn-outline-danger task" id=4>
				<h4>Logs</h4>
				<h1 class="text-center"><i class="fas fa-bars"></i></h1>
			</a>
		</div>
	</div>
</div>


<script>
	$(function(){
		$('#vert div a[href="' + window.location.pathname.split("/")[3] + '"]').addClass('active');
	});
</script>







