<?php
$servername = "localhost";
$dbUser = "root";
$dbPwd = "";
$dbName = "education";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $dbUser, $dbPwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>