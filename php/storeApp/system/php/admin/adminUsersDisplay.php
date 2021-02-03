<?php
include ("dbConn.php");

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();

while ($result = $stmt->fetch()) {
    $username = $result["username"];
    $email = $result["email"];
    $status = $result["status"];
    $date = $result["dateOfCreation"];
    $status = $status ? "Not blocked" : "Blocked";

    echo "<div class='my-1 border border-dark'> <b>Email</b>: <i class='text-light'>" . $email . "</i> - <b>username</b>:  <i class='text-light'>" . $username . "</i> - <b>Created</b>:  <i class='text-light'>" . $date . "</i> - <b>Status</b>:  <i class='text-light'>" . $status . "</i>";
    echo '<button class="btn btn-sm btn-dark mx-3" onclick="changeStatus(' . $result["id"] . ',' . $result["status"] . ', 3' . ')"> Change Status </button> </div> <br>';
}
?>