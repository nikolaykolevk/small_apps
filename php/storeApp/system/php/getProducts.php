<?php

include ("productClass.php");
include ("dbConn.php");

if ($_GET["cat"]!=0) {
    $category = $_GET["cat"];
    $stmt = $conn->prepare("SELECT * FROM products WHERE category=:category");
    $stmt->bindParam(':category', $category);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
}

$products = [];

while (($result = $stmt->fetch())) {
    $product = new Product($result);
    array_push($products, $product);
}

echo json_encode($products);

?>