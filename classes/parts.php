<?php
$errors = array();

class Part
{
    public $conn;
    public $partArray = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        $query = "SELECT * FROM part";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByFilter($filter){
        // $query = "SELECT * FROM part WHERE partNo LIKE '$filter%'";
        $query = "SELECT * FROM part WHERE stockRoomCode LIKE '$filter%'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

        // $this->partArray = $stmt->fetch(PDO::FETCH_ASSOC);
        // return $stmt;
    }

    function readOne($id){
        $query = "SELECT * FROM part WHERE partId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function searchPart($partNo){
        $query = "SELECT * FROM part WHERE partNo LIKE '%$partNo%' OR partDescription LIKE '%partNo%'";
        $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();

        return $stmt;
    }

    function addPart($partNo, $partDesc, $stockRoomCode){
        global $errors;
        $query = "INSERT INTO [part] (partNo, partDescription, stockRoomCode) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $partNo);
        $stmt->bindParam(2, $partDesc);
        $stmt->bindParam(3, $stockRoomCode);

        if($stmt->execute()){
            $_SESSION['message'] =  "New Part Successfully Added";
            header("location: parts.php");
        }
        else{
            array_push($errors, "There was a problem adding the new part. Please try again");
        }
    }

    function updatePart($partNo, $partDesc, $stockRoomCode, $partId){
        global $errors;
        $query = "UPDATE part SET partNo=?, partDescription=?, stockRoomCode=? WHERE partId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $partNo);
        $stmt->bindParam(2, $partDesc);
        $stmt->bindParam(3, $stockRoomCode);
        $stmt->bindParam(4, $partId);

        if($stmt->execute()){
            $_SESSION['message'] = "Part Updated";
            header("location: parts.php");
        }else{
            array_push($errors, "Update Failed. Please Try again.");
            header("location: parts.php");
        }
    }

    function deletePart($partId){
        $query = "DELETE FROM part WHERE partId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $partId);

        if($stmt->execute()){
            $_SESSION['message'] = "Part has been deleted";
            header("location: parts.php");
        }else{
            return false;
        }
    }

}
