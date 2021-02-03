<?php
session_start();
include ("dbConn.php");
include ("adminLogs.php");

$id = $_POST["id"];
$status = $_POST["status"];
$type = $_POST["type"];

switch ($type) {
    case 1:
        $stmt = $conn->prepare("SELECT * FROM orders WHERE ID=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        $orderNum = $result["User Order Number"];
        $userID = $result["userID"];

        $stmt = $conn->prepare("UPDATE `orders` SET `status`= :status WHERE `User Order Number`=:uon AND `userID` = :userID");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':uon', $orderNum);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();

        adminLog(1);
        break;
    case 2:
        $stmt = $conn->prepare("UPDATE `admins` SET `status`= :status WHERE `ID` = :adminID");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':adminID', $id);
        $stmt->execute();
        
        adminLog(4);
        break;
    
    case 3:
        $stmt = $conn->prepare("UPDATE `users` SET `status`= :status WHERE `ID` = :userID");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':userID', $id);
        $stmt->execute();
        
        adminLog(5);
        break;
        
}
?>
