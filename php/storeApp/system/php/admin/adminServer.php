<?php
session_start();
include ("dbConn.php");

$username = "";
$email = "";
$level = 3;
$errors = array();

if (isset($_POST['reg_admin'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_1 = $_POST['password_1'];

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=:username OR email=:email LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "email already exists");
        }
    }

    if (count($errors) == 0) {
        $password = md5($password_1);

        $stmt = $conn->prepare("INSERT INTO admins (username, email, password, level) VALUES(:username, :email, :password, :level)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Your apply will be verified!";
        header('location: adminLogin.php');
    }
}

if (isset($_POST['login_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username=:username AND password=:password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch();
        if ($stmt->rowCount() == 1) {
            if ($user["status"] == true) {
                $_SESSION['adminUsername'] = $username;
                $_SESSION['adminID'] = $user["ID"];
                $_SESSION['success'] = "Welcome back admin, " . $username;
                header('location: admin.php');
            } else {
                array_push($errors, "Your account has been blocked");
            }
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

?>