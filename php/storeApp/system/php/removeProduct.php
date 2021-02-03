<?php
session_start();
include ("dbConn.php");

$productID = $_GET["q"];
$userID = $_SESSION["userID"];
$stmt = $conn->prepare("DELETE FROM `cart` WHERE userID = :userID AND productID = :productID");
$stmt->bindParam(':userID', $userID);
$stmt->bindParam(':productID', $productID);
$stmt->execute();


?>