<?php
// include_once "header.php";
include_once "../config/database.php";
include_once "../classes/message.php";

$database = new Database();
$db = $database->getConnection();
$message = new Message($db);

if(isset($_POST['sendBtn'])){
 $message->sendMessage();
}

$id = $_SESSION['access'];
$access = array(1 =>"CS Admin", 2 =>"System Admin", 3 =>"Picker", 4 =>"Delivery", 5 =>"Customer");

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-moog" id="navBar">
  <a class="navbar-brand" href="./index.php"><i class="fas fa-cogs"></i> Parts Replacement Request v1.0</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

    <div class="navbar-nav">
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i> </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#messageAdmin"><i class="fas fa-envelope"></i> Message System Admin</a>
          <a class="dropdown-item" href="#" id="lockBtn"><i class="fas fa-lock"></i> Lock Screen</a>
          <div class="dropdown-divider"></div>
          <?php if($_SESSION['access'] == 2){
            echo "<a class='dropdown-item' target='_blank' href='../readme.html'><i class='fab fa-readme'></i> View Readme</a>";
          }?>
        </div>
      </div>

    </div>

    <div class="navbar-nav ml-auto" id="link-div">
      <?php if($_SESSION['access'] != 5) { ?>
        <!-- <a class="text-white nav-link"><i class="fas fa-user mr-1"></i> <?php echo $_SESSION['user'][0]['givenname'][0]." ($access[$id])"; ?></a> -->
        <a class="text-white nav-link"><i class="fas fa-user mr-1"></i> <?php echo $_SESSION['user']['samaccount']." ($access[$id])";?></a>
      <?php } else { ?>
        <a class="nav-item nav-link" href="requestForm.php" data-toggle="tooltip" data-placement="top" title="Request for Part"><i class="fas fa-tools"></i> Request for Part</a>
        <a class="nav-item nav-link" href="index.php"><i class="fas fa-comment-dots"></i> My Requests</a>
      <?php } ?>
    </div>
  </div>
</nav>

<div class="modal fade" id="lockScreen" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-moog text-white">
        <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-lock"></i> Screen Locked</h5>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <input id="samaccountcheck" type="hidden" value="<?php echo $_SESSION['user']['samaccount'];?>">
          <input id="samaccount" type="text" class="form-control custom-input-1" placeholder="Enter Account Name to Unlock">
        </div>

        <div align="center">
          <button name="unlockBtn" id="unlockBtn" class="btn btn-primary btn-block"><i class="fas fa-unlock"></i> Unlock</button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="messageAdmin" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-moog text-white">
        <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-paper-plane"></i> Send a Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form method="POST" action="/prq/includes/nav.php">

          <div class="form-row">
            <div class="col form-group">
              <label>Name</label>
              <input name="sender" type="text" class="form-control" value="<?php echo $_SESSION['user'][0]['displayname'][0];?>" readonly>
              <!-- <input name="sender" type="text" class="form-control" value="ADB" readonly> -->
            </div>
          </div>
          <div class="form-row">
            <div class="col form-group">
              <label>Subject</label>
              <input name="subject" type="text" class="form-control" placeholder="Indicate your subject e.g. 'Suggestion'" required>
            </div>
          </div>
          <div class="form-row">
            <div class="col form-group">
              <label>Message</label>
              <textarea name="message" type="text" class="form-control" placeholder="Your Message here" rows="10" required></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button name="sendBtn" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
          </div>

        </form>

      </div>      
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#navBar > div a[href="' + window.location.pathname.split("/")[3] + '"]').addClass('active');
  });

  $(function(){
    $('#unlockBtn').attr('disabled', true);

    $('#samaccount').keyup(function(){
      var auth = $('#samaccountcheck').val();
      console.log(auth);

      if($(this).val() !== auth){
        $('#unlockBtn').attr('disabled', true);
      }
      else
      {
        $('#unlockBtn').attr('disabled', false);        
      }
    })
  });

  $('#lockBtn').click(function(){
    localStorage.setItem('isLocked', true);
    $('#lockScreen').modal('show');
  });

  $('#unlockBtn').click(function(){
    localStorage.removeItem('isLocked');
    $('#lockScreen').modal('hide');
  });

  $(document).ready(function() {
    var check = localStorage.getItem('isLocked');
    console.log(check);

    if(check){
      $('#lockScreen').modal('show');
    }else{
      $('#lockScreen').modal('hide');
    }
  });
</script>