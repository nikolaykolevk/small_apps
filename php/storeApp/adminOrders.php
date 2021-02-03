<?php
session_start();

if (! isset($_SESSION['adminUsername'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: adminLogin.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['adminUsername']);
    header("location: adminLogin.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Home</title>
<link rel="stylesheet" type="text/css"
	href="system/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="system/css/style.css">
<link rel="stylesheet" type="text/css"
	href="system/css/shop-homepage.css">
<link href="system/css/style.css" rel="stylesheet">

<script src="system/js/jquery.min.js"></script>
<script src="system/js/bootstrap.min.js"></script>
<script src="system/js/admin/changeStatus.js"></script>
<script src="system/js/admin/adminOrdersDisplay.js"></script>
</head>
<body class="bg-info">


	<div class="container pt-4 p-5 text-center">
  	<?php if (isset($_SESSION['success'])) : ?>
			<h1 class="display-4">
          <?php
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
      	</h1>
  	<?php endif ?>
		</div>


	<!-- Navigation -->
	<?php include("inc/admin/adminNavigation.php")?>

	<div class="container">

	<div id="server-results"></div>



	</div>


</body>
</html>