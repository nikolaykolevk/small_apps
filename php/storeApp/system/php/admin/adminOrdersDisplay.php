<?php
include ("dbConn.php");

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY ID DESC");
$stmt->execute();

while ($result = $stmt->fetch()) {
    $userID = $result["userID"];
    $stmt2 = $conn->prepare("SELECT * FROM users WHERE id=:userID");
    $stmt2->bindParam(':userID', $userID);
    $stmt2->execute();
    $data = $stmt2->fetch();
    $email = $data["email"];

    $productID = $result["productID"];
    $stmt2 = $conn->prepare("SELECT * FROM products WHERE ID=:productID");
    $stmt2->bindParam(':productID', $productID);
    $stmt2->execute();
    $data = $stmt2->fetch();
    $productName = $data["name"];
    $productPrice = $data["price"];

    $status = $result["status"];
    $status = $status ? "Delivered" : "Not Delivered";

    echo "<div class='my-1 border border-dark'> <b>Email</b>: <i class='text-light'>" . $email . "</i> - <b>Product</b>:  <i class='text-light'>" . $productName . "</i> - <b>Price</b>:  <i class='text-light'>" . $productPrice . "$</i> - <b>Status</b>:  <i class='text-light'>" . $status . "</i> - <b>quantity</b>:  <i class='text-light'>" . $result["quantity"] . "</i> - <b>date</b>:  <i class='text-light'>" . $result["date"] . "</i>";
    echo '<button class="btn btn-sm btn-dark mx-3" onclick="changeStatus(' . $result["ID"] . ',' . $result["status"] . ', 1'.')"> Change Status </button> </div> <br>';
}

?>