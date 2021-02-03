<?php include('system/php/server.php') ?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css"
	href="system/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="system/css/style.css">

<script src="system/js/jquery.min.js"></script>
<script src="system/js/bootstrap.min.js"></script>
</head>
<body class="bg-info">

	<div class="container-fluid pt-4 p-5 text-light bg-info text-center">
		<h2 class="display-3 mb-5">Login</h2>

		<form method="post" action="login.php">
  	<?php include('system/php/errors.php'); ?>
			<div class="form-group row">
				<label for="usrName" class="col-lg-1 col-sm-2 col-3 ml-auto mr-lg-3">Username</label>
				<input name="username" type="text"
					class="form-control col-5 col-sm-4 col-lg-2 mr-auto" id="usrName"
					placeholder="Enter username">
			</div>
			<div class="form-group row">
				<label for="pwd" class="col-lg-1 col-sm-2 col-3 ml-auto mr-lg-3">Password</label>
				<input name="password" type="password"
					class="form-control col-5 col-sm-4 col-lg-2 mr-auto" id="pwd"
					placeholder="Enter password">
			</div>
			<p>
				Login for <a class="text-light" href="adminLogin.php"><b>Administrators</b></a>
			</p>
			<p>
				Not yet a member? <a class="text-light" href="register.php"><b>Sign
						up</b></a>
			</p>
			<div class="form-group my-3">
				<button type="submit" class="btn btn-dark px-5" name="login_user">Login</button>
			</div>
		</form>

	</div>
</body>
</html>