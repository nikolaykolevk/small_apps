<?php
session_start();
include ("dbconn.php");

$productID = $_GET["product"];
$quantity = $_GET["q"];
$userID = $_SESSION["userID"];

$stmt = $conn->prepare("SELECT * FROM cart WHERE productID=:productID AND userID=:userID");
$stmt->bindParam(':userID', $userID);
$stmt->bindParam(':productID', $productID);
$stmt->execute();
if ($stmt->rowCount() == 1) {
    $order = $stmt->fetch();
    $stmt = $conn->prepare("UPDATE `cart` SET `quantity` = :quantity WHERE ID = :ID");
    $quantity = $order["quantity"] + $quantity;
    $stmt->bindParam(':ID', $order["ID"]);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO `cart` (`userID`, `productID`, `quantity`) VALUES (:userID, :productID, :quantity)");
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':productID', $productID);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
}



?>