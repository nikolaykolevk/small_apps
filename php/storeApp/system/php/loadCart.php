<?php
session_start();
include ("productClass.php");
include ("dbConn.php");

$userID = $_SESSION["userID"];
$stmt = $conn->prepare("SELECT * FROM cart WHERE userID=:userID");
$stmt->bindParam(':userID', $userID);
$stmt->execute();

    $products = [];
while (($cartProduct = $stmt->fetch())) {
    $stmt2 = $conn->prepare("SELECT * FROM products where ID=:productID");
    $stmt2->bindParam(":productID", $cartProduct["productID"]);
    $stmt2->execute();


    while (($result = $stmt2->fetch())) {
        $product = new Product($result);
        $product->quantity = $cartProduct["quantity"];
        array_push($products, $product);
    }
}

echo json_encode($products);

?>