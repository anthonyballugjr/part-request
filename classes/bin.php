<?php
$errors = array();

class Bin{
	private $conn;

	function __construct($db){
		$this->conn = $db;
	}

	function readOne($id){
		$query = "SELECT * FROM bin WHERE binId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->execute();

		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	function searchBin($filter){
        $query = "SELECT * FROM bin WHERE location LIKE '%$filter%' OR partNo LIKE '%$filter%'";
        $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        return $stmt;
    }

	function addBinLocation($partNo, $location){
		$x = 1;
		$query = "INSERT INTO bin (partNo, location) VALUES (?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $partNo);
		$stmt->bindParam(2, $location);

		if($stmt->execute()){
			$_SESSION['message'] = "2Bin Location Added";
			header("location: bins.php");
		}else{
			header("location: bins.php");
			array_push($errors, "Bin Adding Failed. Please Try Again");
		}
	}

	function updateBin($partNo, $location, $id){
		$x = 1;
		$query = "UPDATE bin SET partNo=?, location=? WHERE binId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $partNo);
		$stmt->bindParam(2, $location);
		$stmt->bindParam(3, $id);

		if($stmt->execute()){
			$_SESSION['message'] = "Bin Location Updated";
			header("location: bins.php");
		}else{
			$_SESSION['message'] = "Bin Update Failed. Please Try Again";
			header("location: bins.php");
		}
	}

	function deleteBin($id){
		$query = "DELETE FROM bin WHERE binId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);

		if($stmt->execute()){
			$_SESSION['message'] = "Bin Location has been deleted";
			header("location: bins.php");
		}else{
			return false;
		}
	}

	function readBin($data){
		$query = "SELECT DISTINCT part.partId, bin.partNo FROM part INNER JOIN bin ON part.partNo=bin.partNo WHERE part.stockRoomCode LIKE '$data%'";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function readPartLocations($id){
		$query = "SELECT DISTINCT bin.location FROM part INNER JOIN bin ON part.partNo=bin.partNo WHERE part.partId=$id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

}

?>