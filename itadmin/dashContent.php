<!--DASHBOARD TABLE-->
<?php 
date_default_timezone_set("Asia/Manila");
include_once "../config/database.php";
include_once "../classes/request.php";
include_once "../classes/utility.php";
include_once "../config/utilities.php";

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
$utility = new Utility($db);
?>


<div class="card border-dark">
	<div class="card-header bg-moog text-white">Requests</div>

	<div class="card-body">

		<div class="row py-2 mb-1 bg-primary text-center text-white">
			<div class="col">
				<div class="row">
					<div class="col-2"><h5>REQUEST TYPE</h5></a></div>
					<div class="col"><h5>NEW</h5></a></div>
					<div class="col"><h5>FOR PICKING</h5></div>
					<div class="col"><h5>FOR DELIVERY</h5></div>
					<div class="col"><h5>DELIVERED</h5></div>
					<div class="col"><h5>RECEIVED</h5></div>
					<div class="col"><h5>ON HOLD</h5></div>
				</div>
			</div>
		</div>

		<?php
		$status = array(1,2,3,4,5,6);
		$label = array('Replacement', 'Approved for Stock (AFS)', '2BIN', 'Quality Issue');
		$i = 1;
		$a = 0;
		while($i<=4){ ?>
			<div class='row mb-1' style='border: solid black 1px; box-shadow: 0px 0px 0px 0px #000;'>
				<div class='col-2 bg-moog d-flex align-items-center'>
					<div class='mx-auto'>
						<h6 class='text-center text-white'><?php echo $label[$a]?></h6>
					</div>
				</div>
				<div class='col my-1'>
					<div class='row my-auto'>
						<?php foreach($status as $x){ ?>
							<div class='col text-center'>

								<div class="text-center">
									<h3 class='text-center my-2 mb-0'>
										<?php echo $request->countByStatusAndType($x,$i);?>
									</h3>
								</div>

							</div>
						<?php } 
						$i++;
						$a++;
						?>
					</div>
				</div>
			</div>
		<?php } ?>
		<!--/DASHBOARD TABLE-->

		<div class="row bg-primary text-center text-white px-0">
			<div class="col">
				<div class="row">
					<div class='col-2 d-flex align-items-center'>
						<div class='mx-auto'>
							<h3 class='text-center my-1'>TOTAL</h3>
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
								<h3 class='my-1'>".$b."</h3>
								</div>";
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>