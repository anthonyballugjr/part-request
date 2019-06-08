<?php
include_once "../config/database.php";
include_once "../classes/message.php";

$database = new Database();
$db = $database->getConnection();
$message = new Message($db);

$stmt = $message->readAll();
$count = $stmt->rowCount();

if($count > 0){
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);?>
		<div class="row border-bottom">
			<div class="col-1">
				<?php if($row['viewed'] == 0){
					echo "<h3><i class='fas fa-envelope'></i></h3>";
				}else{
					echo "<h3><i class='fas fa-envelope-open'></i></h3>";
				}?>
			</div>
			<div class="col ml-2">
				<a class="text-dark view-message" href='#' id="<?php echo $row['messageId'];?>">
					<p class="my-0"><strong>From:</strong> <?php echo $row['sender'];?></p>
					<p class="my-0"><strong>Subject:</strong> <?php echo $row['subject'];?></p>
					<small class="text-muted"> <?php echo date('F m, Y - h:iA', strtotime($row['createdAt']));?></small> 
				</a>
			</div>
		</div>
	<?php }
}else{
	echo "<h6>No messages to show</h6>";
}?>


<script>
	$('.view-message').click(function(){
		var messageId = $(this).attr('id');

		$.ajax({
			method: 'POST',
			url: 'viewMessage.php',
			data: {messageId: messageId},
			success: function(data){
				$('#viewMessageTitle').text('Message #'+ messageId);
				$('#message-body').html(data);
				$('#viewMessage').modal('show');
			}
		});
	});
</script>