<?php
include ("dbConn.php");
$adminID = $_SESSION["adminID"];

$stmt = $conn->prepare("SELECT * FROM adminlogs ORDER BY ID DESC LIMIT 20;");
$stmt->execute();

while ($result = $stmt->fetch()) {
    $msg = $result["message"];
    $date = $result["date"];
    
    $logAdminID = $result["adminID"];
    $stmt2 = $conn->prepare("SELECT * FROM admins WHERE ID=:adminID");
    $stmt2->bindParam(':adminID', $logAdminID);
    $stmt2->execute();
    $logAdmin = $stmt2->fetch();
    $adminName = $logAdmin["username"];
    
    echo "<div class='my-1 border border-dark'> <b class='text-light'>".$adminName . "</b> - <i><b>" . $msg . "</b></i> - <b class='text-light'>" . $date."</b> </div> <br>";
}
?>