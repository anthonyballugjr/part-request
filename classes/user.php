<?php

class User{
    private $conn;
    public $term;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function login($auth){
        $query = "SELECT * FROM [user] WHERE samaccount=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $auth);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row > 0){
            $_SESSION['user'] = $row;
            $_SESSION['access'] = $row['accessId'];
            $_SESSION['message'] = "NICE!";

            if($_SESSION['access'] == 1){
                header("location: ./csadmin/index.php");
            }else if($_SESSION['access'] == 2){
                header("location: ./itadmin/index.php");
            }else{
                header("location: ./part-replacement/index.php");
            }
        }else{
            echo "NOT IN THE DATABASE";
        }
    }

    function readAll(){
        $query = "SELECT * FROM [user] ORDER BY accessId ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readByAccess($code){
        $query = "SELECT * FROM [user] WHERE accessId = ? ORDER BY displayName ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $code);
        $stmt->execute();
        return $stmt;
    }

    function readOne($id){
        $query = "SELECT * FROM [user] WHERE userId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function addUser($name, $samaccount, $accessId){
        global $errors;
        $query = "SELECT * FROM [user] WHERE samaccount=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $samaccount);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row == true){
            $_SESSION['message'] = "User already exists in the database.";
            header("location:users.php");
        }else{
            // $sql  = "INSERT INTO [user] SET samaccount=?, displayName=?, accessId=?";
            $sql = "INSERT INTO [user] (samaccount, displayName, accessId) VALUES(?, ?, ?)";
            $prep = $this->conn->prepare($sql);
            $prep->bindParam(1, $samaccount);
            $prep->bindParam(2, $name);
            $prep->bindParam(3, $accessId);

            if($prep->execute()){
                $_SESSION['message'] = "User added";
                header("location: users.php");
            }else{
                return false;
            }
        }
    }

    function updateUser($accessId, $userId){
        $query = "UPDATE [user] SET accessId=? WHERE userId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $accessId);
        $stmt->bindParam(2, $userId);

        if($stmt->execute()){
            $_SESSION['message'] = "User updated";
            header("location: users.php");
        }else{
            return false;
        }
    }

    function removeUser($userId){
        $query = "DELETE FROM [user] WHERE userId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);

        if($stmt->execute()){
            $_SESSION['message'] = "User has been removed";
            header("location: users.php");
        }else{
            return false;
        }
    }

}
