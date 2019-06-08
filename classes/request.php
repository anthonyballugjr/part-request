<?php
date_default_timezone_set('Asia/Manila');

$today = date("Y-m-d");
$now = date("h:i:sA");
$dateStamp = date('Y-m-d H:i:s');
$errors = array();
$session_user = $_SESSION['user'][0]['uid'][0];

class Request{
    private $conn;
    public $requestId;
    public $lastStatId;
    public $requestTypeId;

    public $row = array();

    function __construct($db){
        $this->conn = $db;
    }

    function tatChart(){
        $query = "SELECT TOP (30) COUNT(convert(varchar, receivedAt, 101)) AS requestCount, convert(varchar, receivedAt, 101) AS dateT, AVG(DATEDIFF(hour, requestedAt, receivedAt)) as ave FROM request WHERE statusId=5 OR statusId=8 GROUP BY convert(varchar, receivedAt, 101) ORDER BY convert(varchar, receivedAt, 101) ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        return json_encode($data);

    }

    function readType(){
        $query = "SELECT * FROM requestType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function viewOne(){
        $query = "SELECT * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN requestType ON request.requestTypeId=requesttype.requestTypeId INNER JOIN status ON request.statusId=status.statusId WHERE requestId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->requestId);
        $stmt->execute();

        $this->row = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $this->row['requestTypeId'];
    }

    function readByStatusAndType($status, $type){
        $query = "SELECT * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN status ON request.statusId=status.statusId INNER JOIN requestType ON requestType.requestTypeId=request.requestTypeId WHERE request.statusId=? AND request.requestTypeId=? ORDER BY request.requestTypeId ASC, CONVERT(varchar, request.requestedAt, 23) ASC, CONVERT(varchar, request.requestedAt, 24) ASC";
        $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $type);
        $stmt->execute();
        return $stmt;
    }

    function readByStatus($status){
        $query = "SELECT TOP (15) * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN status ON request.statusId=status.statusId INNER JOIN requestType ON requestType.requestTypeId=request.requestTypeId WHERE request.statusId=? ORDER BY request.requestTypeId DESC, CONVERT(varchar, request.requestedAt, 24) DESC";
        $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->bindParam(1, $status);
        $stmt->execute();
        return $stmt;
    }

    function readAll(){
        $query = "SELECT * FROM request INNER JOIN part ON request.partId=part.partId INNER JOIN status ON request.statusId=status.statusId INNER JOIN requestType ON requestType.requestTypeId=request.requestTypeId ORDER BY request.requestTypeId ASC, CONVERT(varchar, request.requestedAt, 23), CONVERT(varchar, request.requestedAt, 24) ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function sendRequest(){
        global $partId, $requestorName, $samaccount, $workCenter, $contactNo, $workOrder, $requestTypeId, $quantity, $binLocation, $today, $now, $errors, $session_user, $dateStamp;

        $partId = $_POST['partId'];
        $requestorName = $_POST['requestorName'];
        $samaccount = $_POST['samaccount'];
        $workCenter = $_POST['workCenter'];
        $contactNo = $_POST['contactNo'];
        $requestTypeId = $_POST['requestTypeId'];
        $quantity = $_POST['quantity'];

        if($requestTypeId == 1 || $requestTypeId == 2){
            $workOrder = implode(':',$_POST['workOrder']);
            $quantity = implode(':',$_POST['quantity']);
        }
        if($requestTypeId == 3){
            $binLocation = $_POST['binLocation'];
        }

        if(empty($contactNo)){
            array_push($errors, 'Contact number must not be empty');
        }else if(is_null($requestTypeId) || empty($requestTypeId)){
            array_push($errors, 'Please select a reason for request');
        }else if(empty($partId)){
            array_push($errors, 'Please select a Part No');
        }else if(($requestTypeId == 1 || $requestTypeId == 2) && empty($workOrder)){
            array_push($errors,'Please enter Work Order');
        }else if($quantity <= 0){
            array_push($errors, 'Quantity must not be zero or less than zero');
        }else{

            $query = "INSERT INTO request (samaccount, requestorName, contactNo, partId, workCenter,  workOrder, requestTypeId, quantity, binLocation, updatedBy, lastUpdatedAt) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $samaccount);
            $stmt->bindParam(2, $requestorName);
            $stmt->bindParam(3, $contactNo);
            $stmt->bindParam(4, $partId);
            $stmt->bindParam(5, $workCenter);
            $stmt->bindParam(6, $workOrder);
            $stmt->bindParam(7, $requestTypeId);
            $stmt->bindParam(8, $quantity);
            $stmt->bindParam(9, $binLocation);
            $stmt->bindParam(10, $session_user);
            $stmt->bindParam(11, $dateStamp);

            if ($stmt->execute()) {
                $id = $this->conn->lastInsertId();

                // $_SESSION['message'] = "Request Successfully Submitted.  <a class='alert-link' href='../updateTranscript.php?requestId=$id' target='_blank'> View Request</a>";
                $_SESSION['message'] = "Request Successfully Submitted.";
                header("location: ./");
            } else {
                return false;
            }
        }
    }

    function updateStatus($code, $person, $id, $type, $remarks){
        global $dateStamp, $errors, $session_user;
        $t = base64_encode($type);

        //UPDATE TO "FOR PICKING"
        if($code ==  2){
            $query = "UPDATE request SET pickedBy=?, statusId=?, assignedPickerAt=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $person);
            $stmt->bindParam(2, $code);
            $stmt->bindParam(3, $dateStamp);
            $stmt->bindParam(4, $session_user);
            $stmt->bindParam(5, $remarks);
            $stmt->bindParam(6, $dateStamp);
            $stmt->bindParam(7, $id);

            if($stmt->execute()){
                $stat = base64_encode(1);
                $rid = base64_encode($id);

                $_SESSION['message']="Picker Assigned. "." "."<a href='transcriptpdf.php?requestId=$rid' target='_blank' class='alert-link'>Print Transcript</a>";
                header("location:tableView.php?st=$stat&rt=$t");
            }else{
                return false;
                echo "Oooops! Something went wrong, Please Try Again";
            }

        //UPDATE TO "FOR DELIVERY"
        }else if($code == 3){
            $query = "UPDATE request SET deliveredBy=?, statusId=?, assignedDeliveryAt=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $person);
            $stmt->bindParam(2, $code);
            $stmt->bindParam(3, $dateStamp);
            $stmt->bindParam(4, $session_user);
            $stmt->bindParam(5, $remarks);
            $stmt->bindParam(6, $dateStamp);
            $stmt->bindParam(7, $id);

            if($stmt->execute()){
                $stat = base64_encode(2);
                $_SESSION['message']="Request Updated";
                header("location:tableView.php?st=$stat&rt=$t");
            }else{
                return false;
                echo "Oooops! Something went wrong, Please Try Again";
            }

        //UPDATE TO "DELIVERED"
        }else if($code == 4){
            $query = "UPDATE request SET statusId=?, deliveredAt=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $code);
            $stmt->bindParam(2, $dateStamp);
            $stmt->bindParam(3, $session_user);
            $stmt->bindParam(4, $remarks);
            $stmt->bindParam(5, $dateStamp);
            $stmt->bindParam(6, $id);

            if($stmt->execute()){
                $stat = base64_encode(3);

                $_SESSION['message']="Request Updated";
                header("location:tableView.php?st=$stat&rt=$t");
            }else{
                return false;
                echo "Oooops! Something went wrong, Please Try Again";
            }

        //UPDATE TO RECEIVED/CLOSED
        }else if($code == 5){
            $query = "UPDATE request SET receivedBy=?, statusId=?, receivedAt=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $session_user);
            $stmt->bindParam(2, $code);
            $stmt->bindParam(3, $dateStamp);
            $stmt->bindParam(4, $session_user);
            $stmt->bindParam(5, $remarks);
            $stmt->bindParam(6, $dateStamp);
            $stmt->bindParam(7, $id);

            if($stmt->execute()){
                $_SESSION['message']="Request Closed.";
                header("location:./");
            }else{
                return false;
            }

        //UPDATE TO ON-HOLD
        }else if($code == 6){
            $query = "UPDATE request SET statusId=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $code);
            $stmt->bindParam(2, $session_user);
            $stmt->bindParam(3, $remarks);
            $stmt->bindParam(4, $dateStamp);
            $stmt->bindParam(5, $id);

            if($stmt->execute()){
                $stat = base64_encode(1);
                $_SESSION['message']="Request moved to 'On Hold'";
                header("location:tableView.php?st=$stat&rt=$t");
            }else{
                return false;
            }

        //UPDATE TO CANCELLED
        }else if($code == 7){
            $query = "UPDATE request SET statusId=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $code);
            $stmt->bindParam(2, $session_user);
            $stmt->bindParam(3, $remarks);
            $stmt->bindParam(4, $dateStamp);
            $stmt->bindParam(5, $id);

            if($stmt->execute()){
                $_SESSION['message']="Request Cancelled.";
                header("location:./");
            }else{
                return false;
            }

        //ARCHIVE
        }else if($code == 8){
            $query = "UPDATE request SET statusId=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $code);
            $stmt->bindParam(2, $session_user);
            $stmt->bindParam(3, $remarks);
            $stmt->bindParam(4, $dateStamp);
            $stmt->bindParam(5, $id);

            if($stmt->execute()){
                $stat = base64_encode(3);
                $_SESSION['message'] = "Request moved to Archives";
                header("location:./");
            }else{
                return false;
            }

        //RETURN TO QUEUE
        }else if($code == 9){
            $query = "UPDATE request SET statusId=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $person);
            $stmt->bindParam(2, $session_user);
            $stmt->bindParam(3, $remarks);
            $stmt->bindParam(4, $dateStamp);
            $stmt->bindparam(5, $id);

            if($stmt->execute()){
                $_SESSION['message']= "Request $id returned to Requests Queue.";
                header("location:./");
            }else{
                return false;
            }

        //DELIVERY REMARKS
        }else if($code == 10){
            $query = "UPDATE request SET updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $session_user);
            $stmt->bindParam(2, $remarks);
            $stmt->bindParam(3, $dateStamp);
            $stmt->bindParam(4, $id);

            if($stmt->execute()){
                $stat = base64_encode(3);

                $_SESSION['message'] = "Remarks Added.";
                header("location:./");
            }else{
                return false;
            }
        }else{
            array_push($errors, "Status Code Invalid!");
        }
    }

    function updateRequest(){
        global $partId, $quantity, $binLocation, $workorder, $requestTypeId, $errors, $session_user, $dateStamp;
        $remarks = "edit-content";

        $requestTypeId = $_POST['requestTypeId'];
        $partId = $_POST['partId'];
        $quantity = $_POST['quantity'];

        if($requestTypeId == 1 || $requestTypeId == 2){
            $workorder = implode(':',$_POST['workorder']);
            $quantity = implode(':',$_POST['quantity']);
        }
        if($requestTypeId == 3){
            $binLocation = $_POST['binLocation'];
        }

        if($quantity <= 0){
            array_push($errors, "Minimum quantity is 1");
        }else{

            $query = "UPDATE request SET partId=?, quantity=?, binLocation=?, workorder=?, updatedBy=?, lastRemarks=?, lastUpdatedAt=? WHERE requestId=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $partId);
            $stmt->bindParam(2, $quantity);
            $stmt->bindParam(3, $binLocation);
            $stmt->bindParam(4, $workorder);
            $stmt->bindParam(5, $session_user);
            $stmt->bindParam(6, $remarks);
            $stmt->bindParam(7, $dateStamp);
            $stmt->bindParam(8, $this->requestId);

            if($stmt->execute()){
                $_SESSION['message'] = "Request Updated";
                header('location:./');
            }else{
                return false;
            }
        }
    }

    function countByStatusAndType($status, $type){
        $query = "SELECT * FROM request WHERE statusId=? AND requestTypeId=?";
        $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->bindParam(1,$status);
        $stmt->bindParam(2,$type);
        $stmt->execute();

        $count = $stmt->rowCount();
        return $count;
    }

    function deleteByUser($statusId){
        global $session_user;
        $query = "DELETE FROM request WHERE samaccount=? AND statusId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $session_user);
        $stmt->bindParam(2, $statusId);

        switch($statusId){
            case 7:
            $req = "Cancelled";
            break;
            case 5:
            $req = "Received";
            break;
            default:
            $req = "Cancelled";
            break;
        }

        if($stmt->execute()){
            $_SESSION['message'] = "All your $req Requests has been deleted";
            header("location: ./index.php");
        }else{
            return false;
        }
    }

    function deleteAll($statusId){
        $query = "DELETE FROM request WHERE statusId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $statusId);

        switch($statusId){
            case 7:
            $req = "Cancelled";
            break;
            case 5:
            $req = "Received";
            break;
            default:
            $req = "Cancelled";
            break;
        }
        if($stmt->execute()){
            $_SESSION['message'] = "All $req has been deleted";
            header("location: ./index.php");
        }else{
            return true;
        }
    }

    function deleteOne($id){
        $query = "DELETE FROM request WHERE requestId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()){
            $_SESSION['message'] = "Request has been deleted";
            header("location: ./index.php");
        }else{
            return true;
        }
    }

    function archiveByUser($statusId){
        $stat = 8;
        $old = 5;
        $query = "UPDATE SET statusId=? WHERE statusId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $stat);
        $stmt->bindParam(2, $old);
        if($stmt->execute()){
            $_SESSION['message'] = "Request moved to Archives";
            header("location:./");
        }else{
            return false;
        }

    }

}
