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

<!--DASHBOARD TABLE-->
<?php 
$status = array(1,2,3,4,5,6);
$label = array('Replacement', 'Approved for Stock (AFS)', '2BIN', 'Quality Issue');
$i = 1;
$a = 0;
while($i<=4){ ?>
	<div class='row mb-1' style='border: solid black 1px; box-shadow: 0px 0px 0px 0px #000;'>
		<div class='col-2 bg-moog d-flex align-items-center'>
			<div class='mx-auto'>
				<h5 class='text-center text-white'><?php echo $label[$a]?></h5>
			</div>
		</div>
		<div class='col my-1'>
			<div class='row my-auto'>
				<?php foreach($status as $x){ ?>
					<div class='col text-center'>

						<a class="btn btn-lg btn-outline-primary text-center" href="tableView.php?st=<?php echo base64_encode($x);?>&rt=<?php echo base64_encode($i);?>">
							<h4 class='text-center my-2 mb-0'>
								<?php echo $request->countByStatusAndType($x,$i);?>
							</h4>
						</a>

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

