<?php
include ("productClass.php");
include("dbconn.php");

$id = $_GET["id"];
$stmt = $conn->prepare("SELECT * FROM products WHERE ID=:id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$result = $stmt->fetch();
$product = new Product($result);

echo json_encode($product);
?>