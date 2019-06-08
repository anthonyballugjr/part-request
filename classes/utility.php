<?php
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:s');
$session_user = $_SESSION['user'][0]['uid'][0];

class Utility{
	private $conn;

	function __construct($db){
		$this->conn = $db;
	}

	//REQUESTS
	function readStatus($id){
		$query = "SELECT statusName FROM status WHERE statusId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function readType($id){
		$query = "SELECT requestType FROM requestType WHERE requestTypeId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function readMyRequests(){
		global $session_user;
		$code = 8;
		$query = "SELECT * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN status ON request.statusId=status.statusId INNER JOIN requestType ON requestType.requestTypeId=request.requestTypeId WHERE request.samaccount=? AND request.statusId!=? ORDER BY CONVERT(varchar, request.requestedAt, 23) DESC, CONVERT(varchar, request.requestedAt, 24) DESC";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1, $session_user);
		$stmt->bindParam(2, $code);
		$stmt->execute();
		return $stmt;
	}

	function readMineByStatus($stat){
		global $session_user;
		$query = "SELECT * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN status ON request.statusId=status.statusId INNER JOIN requestType ON requestType.requestTypeId=request.requestTypeId WHERE request.samaccount=? AND request.statusId=? ORDER BY CONVERT(varchar, request.requestedAt, 23) ASC, CONVERT(varchar, request.requestedAt, 24) ASC";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1, $session_user);
		$stmt->bindparam(2, $stat);
		$stmt->execute();
		return $stmt;
	}

	function countByStatus($status){
		if($status == "active"){
			$query = "SELECT * FROM request WHERE statusId <= 4";
			$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();
		}else{
			$query = "SELECT * FROM request WHERE statusId=?";
			$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->bindParam(1, $status);
			$stmt->execute();
		}
		
		$count = $stmt->rowCount();
		return $count;
	}

	function countAging(){
		$query = "SELECT * FROM request WHERE DATEDIFF(hour, requestedAt, GETDATE()) > 8 AND statusId!=5 AND statusId!=8";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		$count = $stmt->rowCount();
		return $count;
	}

	function readAging(){
		$query = "SELECT * FROM request INNER JOIN requestType ON request.requestTypeId=requestType.requestTypeId INNER JOIN status ON request.statusId=status.statusId WHERE DATEDIFF(hour, requestedAt, GETDATE()) > 8 AND request.statusId!=5 AND request.statusId!=8";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		return $stmt;
	}

	function getTAT($rid){
		$query = "SELECT CONCAT((DATEDIFF(minute, requestedAt, receivedAt)/60),':',(DATEDIFF(minute, requestedAt, receivedAt)%60)) AS tat FROM request WHERE requestId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $rid);
		$stmt->execute();

		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	function countMyRequests($code){
		$samaccount = $_SESSION['user'][0]['samaccountname'][0];
		$query = "SELECT * FROM request WHERE statusId=? AND samaccount=?";
		$stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1, $code);
		$stmt->bindParam(2, $samaccount);
		$stmt->execute();
		$count = $stmt->rowCount();
		return $count;
	}
	//.REQUESTS

}

?>