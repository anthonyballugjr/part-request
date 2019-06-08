<?php
date_default_timezone_set('Asia/Manila');
$today = Date('Y-m-d');
$now = Date('Y-m-d h:i:s');
$errors = array();

class Message{
	private $conn;

	function __construct($db){
		$this->conn = $db;
	}

	function readAll(){
		$query = "SELECT TOP (10) * FROM message ORDER BY createdAt DESC";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		return $stmt;
	}

	function sendMessage(){
		global $sender, $subject, $message, $errors;

		$sender = $_POST['sender'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];

		$query = "INSERT INTO message(sender, subject, message) VALUES(?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $sender);
		$stmt->bindParam(2, $subject);
		$stmt->bindParam(3, $message);

		if($stmt->execute()){
			$access = $_SESSION['access'];
			switch($access){
				case 1:
				$$_SESSION['message'] = "Message Sent";
				header("location: ../csadmin/index.php");
				break;
				case 2:
				$_SESSION['message'] = "Message Sent";
				header("location: ../itadmin/index.php");
				break;
				case 3:
				$_SESSION['message'] = "Message Sent";
				header("location: ../part-replacement/index.php");
				break;
				default:
				$_SESSION['message'] = "Message Sent";
				header("location: /");
				break;
			}
			
		}else{
			return false;
		}
	}

	function readOne($id){
		$viewed = 1;
		$query = "SELECT * FROM message WHERE messageId=?;UPDATE message SET viewed=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->bindParam(2, $viewed);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		// print_r($data);
		return $data;

	}

}