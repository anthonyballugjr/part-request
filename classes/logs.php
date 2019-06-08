<?php
date_default_timezone_set('Asia/Manila');
$today = Date('Y-m-d');
$now = Date('Y-m-d h:i:s');


class Logs{
	private $conn;

	function __construct($db){
		$this->conn = $db;
	}

	function readAll(){
		$query = "SELECT TOP (100) * FROM logs INNER JOIN status ON logs.statusId=status.statusId ORDER BY CONVERT(varchar, logs.actionAt, 23) DESC, CONVERT(varchar, logs.actionAt, 24) DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$data[] = $row;
		}
		$data = array_reverse($data);
		return $data;
	}

	function readFew(){
		$query = "SELECT TOP (30) * FROM logs INNER JOIN status ON logs.statusId=status.statusId INNER JOIN request ON logs.requestId=request.requestId ORDER BY CONVERT(varchar, logs.actionAt, 23) DESC, CONVERT(varchar, logs.actionAt, 24) DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			array_push($data, $row);
		}
		$data = array_reverse($data);
		return $data;
	}

	function readNow(){
		global $today;
		$query = "SELECT * FROM logs INNER JOIN status ON logs.statusId=status.statusId WHERE CONVERT(varchar, actionAt, 23)=? ORDER BY CONVERT(varchar, logs.actionAt, 23) DESC, CONVERT(varchar, logs.actionAt, 24) DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $today);
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			array_push($data, $row);
		}
		$data = array_reverse($data);
		return $data;

	}

	function readByRequest($requestId){
		$query = "SELECT * FROM logs INNER JOIN status ON logs.statusId=status.statusId WHERE logs.requestId=? ORDER BY CONVERT(varchar, logs.actionAt, 23) ASC, CONVERT(varchar, logs.actionAt, 24) ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $requestId);
		$stmt->execute();
		return $stmt;
	}

	function getRow($statusId, $requestId){
		$query = "SELECT * FROM logs WHERE requestId=? AND statusId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $requestId);
		$stmt->bindParam(2, $statusId);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	function getLastStatus($id){
		$query = "SELECT statusId FROM logs WHERE requestId=? ORDER BY actionAt DESC OFFSET 1 ROWS";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->execute();
		$lastStatus = $stmt->fetch(PDO::FETCH_ASSOC);
		return $lastStatus;
	}


}
?>