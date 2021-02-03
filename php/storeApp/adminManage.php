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

</head>
<body class="bg-info">

	<!-- Navigation -->
	<?php include("inc/admin/adminNavigation.php")?>


	<div class="container pt-4 p-5 text-center">


		<h1 class="display-2 text-light my-3">Create new Administrator</h1>

		<form action="system/php/admin/adminManageScript.php" method="post">
			<div class="form-group row">
				<label for="username"
					class="col-sm-2 col-form-label my-2 text-light"><b>Username</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="username">
				</div>
				<label for="password"
					class="col-sm-2 col-form-label my-2 text-light"><b>Password</b></label>
				<div class="col-sm-10 my-2">
					<input type="password" class="form-control" name="password">
				</div>
				<label for="email" class="col-sm-2 col-form-label my-2 text-light"><b>Email</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="email">
				</div>
				<div class="input-group my-2">
					<input class="btn btn-dark ml-auto" type="submit" name="submit"
						value="Add">
				</div>
			</div>
		</form>
		
		<script src="system/js/admin/ajaxFormScript.js"> </script>


		<br> <br> <br>
		<h1 class="display-2 text-light my-3">Administrators</h1>
		<div id="server-results"></div>
	

		</div>




</body>
</html>