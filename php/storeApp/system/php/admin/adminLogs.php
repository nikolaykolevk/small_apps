<?php

function adminLog($action)
{
    $adminID = $_SESSION["adminID"];
    include ("dbConn.php");

    switch ($action) {
        case 1:
            $orderID = $_POST["id"];
            $status = $_POST["status"];

            $stmt = $conn->prepare("SELECT * FROM orders WHERE ID=:orderID");
            $stmt->bindParam(':orderID', $orderID);
            $stmt->execute();
            $result = $stmt->fetch();
            $orderNum = $result["User Order Number"];
            $userID = $result["userID"];

            $stmt = $conn->prepare("SELECT * FROM users WHERE ID=:userID");
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            $result = $stmt->fetch();
            $userEmail = $result["email"];

            $status = $status ? "DELIVERED" : "NOT DELIVERED";

            $msg = "Changed status of order number " . $orderNum . " for user: " . $userEmail . " to " . $status;

            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            break;
            
        case 2:
            $msg = "Added product: ".$_POST["productName"]." Price: ".$_POST["productPrice"];
            
            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            break;
            
        case 3:
            $msg = "removed product with ID: ".$_POST["removeProductID"];
            
            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            
            break;
            
        case 4:
            $stmt = $conn->prepare("SELECT * FROM admins WHERE ID=:adminID");
            $stmt->bindParam(':adminID', $_POST["id"]);
            $stmt->execute();
            $result = $stmt->fetch();
            $username = $result["username"];
            $status = $_POST["status"] ? "Not blocked" : "Blocked";
            
            $msg = "Changed status of admin : ".$username." to ". $status;
            
            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            
            break;
            
        case 5:
            $stmt = $conn->prepare("SELECT * FROM users WHERE ID=:userID");
            $stmt->bindParam(':userID', $_POST["id"]);
            $stmt->execute();
            $result = $stmt->fetch();
            $username = $result["username"];
            $status = $_POST["status"] ? "Not blocked" : "Blocked";
            
            $msg = "Changed status of user : ".$username." to ". $status;
            
            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            
            break;
            
        case 6:
            $msg = "Created new Admin: ".$_POST["username"];
            
            $stmt2 = $conn->prepare("INSERT INTO `adminLogs` (`adminID`, `action`, `message`) VALUES (:adminID, :action, :msg)");
            $stmt2->bindParam(':adminID', $adminID);
            $stmt2->bindParam(':action', $action);
            $stmt2->bindParam(':msg', $msg);
            $stmt2->execute();
            
            break;
            
    }
}
?>
