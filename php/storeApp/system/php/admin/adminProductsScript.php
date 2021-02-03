<?php
session_start();
include ("dbConn.php");
include ("adminLogs.php");

if (isset($_POST['submit'])) {

    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $tmpVar = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($tmpVar));
    $extensions = array(
        "jpeg",
        "jpg",
        "png"
    );

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "../../../images" . $file_name);
        echo "<h2 class='text-center text-light my-2'> Successfully uploaded!" . "</h2>";
    } else {
        print_r($errors);
    }

    $imgSrc = "/education/issue7/storeApp/images/" . $file_name;
    $name = $_POST["productName"];
    $category = $_POST["productCategory"];
    $price = $_POST["productPrice"];
    $description = $_POST["productDescription"];
    $rating = $_POST["productRating"];

    $stmt2 = $conn->prepare("INSERT INTO `products` (`category`, `name`, `price`, `imgSrc`, `description`, `rating`) VALUES (:category, :name, :price, :imgSrc, :description, :rating)");
    $stmt2->bindParam(':category', $category);
    $stmt2->bindParam(':name', $name);
    $stmt2->bindParam(':price', $price);
    $stmt2->bindParam(':imgSrc', $imgSrc);
    $stmt2->bindParam(':description', $description);
    $stmt2->bindParam(':rating', $rating);
    $stmt2->execute();

    adminLog(2);
}

if (isset($_POST['removeProductID'])) {

    $productID = $_POST['removeProductID'];
    $stmt = $conn->prepare("DELETE FROM `products` WHERE ID = :productID");
    $stmt->bindParam(':productID', $productID);
    $stmt->execute();

    adminLog(3);
}


$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();

while ($result = $stmt->fetch()) {
    $name = $result["name"];
    $category = $result["category"];
    $price = $result["price"];
    $description = $result["description"];
    $rating = $result["rating"];
    $imgSrc = $result["imgSrc"];
    
    echo "<div class='my-1 border border-dark py-3 px-2'> <b class='text-light'> category: " . $category . "</b> - <i><b>" . $name . "</b></i> - <b class='text-light'>" . $price . "$</b> - <i><b>" . $description . "</b> - <i><b class='text-light'> rating: " . $rating;
    echo "<img class='mx-2' width='200' src='" . $imgSrc . "'>";
    echo "<button class='mx-3 btn btn-dark btn-sm float-right' onclick='remove(" . $result["ID"] . ")'>Remove</button>" . "</b> </div> <br>";
}


?>