<?php
session_start();
include ("productClass.php");
include ("dbConn.php");

$userID = $_SESSION["userID"];
$stmt = $conn->prepare("SELECT * FROM users WHERE ID=:userID");
$stmt->bindParam(':userID', $userID);
$stmt->execute();
$result = $stmt->fetch();
$ordersCount = $result["Orders Count"];
$ordersCount++;

$stmt = $conn->prepare("UPDATE users SET `Orders Count` = :ordersCount WHERE ID = :userID");
$stmt->bindParam(':userID', $userID);
$stmt->bindParam(':ordersCount', $ordersCount);
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM cart WHERE userID=:userID");
$stmt->bindParam(':userID', $userID);
$stmt->execute();

while($result = $stmt->fetch()) {
    $productID = $result["productID"];
    $quantity = $result["quantity"];
    
    $stmt2 = $conn->prepare("INSERT INTO `orders` (`userID`, `productID`, `User Order Number`, `quantity`) VALUES (:userID, :productID, :uon, :quantity)");
    $stmt2->bindParam(':userID', $userID);
    $stmt2->bindParam(':productID', $productID);
    $stmt2->bindParam(':uon', $ordersCount);
    $stmt2->bindParam(':quantity', $quantity);
    $stmt2->execute();
}

$stmt = $conn->prepare("DELETE FROM `cart` WHERE userID = :userID;");
$stmt->bindParam(':userID', $userID);
$stmt->execute();


    
    
?>
