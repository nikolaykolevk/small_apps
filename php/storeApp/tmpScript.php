<?php
session_start();
include ("dbConn.php");
include ("system/php/admin/adminLogs.php");

//$_SESSION["adminID"] = "43";

if (isset($_POST['submit'])) {
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    
    // Creating admin
    $password = md5($password);
    
    $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES(:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    adminLog(6);
    
}

$stmt = $conn->prepare("SELECT * FROM admins");
$stmt->execute();

while ($result = $stmt->fetch()) {
    $username = $result["username"];
    $email = $result["email"];
    $status = $result["status"];
    $date = $result["dateOfCreation"];
    $status = $status ? "Not blocked" : "Blocked";
    
    echo "<div class='my-1 border border-dark'> <b>Email</b>: <i class='text-light'>" . $email . "</i> - <b>username</b>:  <i class='text-light'>" . $username . "</i> - <b>Created</b>:  <i class='text-light'>" . $date . "</i> - <b>Status</b>:  <i class='text-light'>" . $status . "</i>";
    echo '<button class="btn btn-sm btn-dark mx-3" onclick="changeStatus(' . $result["ID"] . ',' . $result["status"] . ', 2' . ')"> Change Status </button> </div> <br>';
}
  



?>