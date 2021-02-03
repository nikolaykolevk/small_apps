<?php include('system/php/server.php') ?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" type="text/css"
	href="system/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="system/css/style.css">

<script src="system/js/jquery.min.js"></script>
<script src="system/js/bootstrap.min.js"></script>
</head>
<body class="bg-info">

	<div class="container-fluid pt-4 p-5 text-light bg-info text-center">
		<h2 class="display-3 mb-5">Register</h2>

		<form method="post" action="register.php">
		<?php include('system/php/errors.php'); ?>
			<div class="form-group row">
				<label for="usrName" class="col-lg-1 col-sm-2 col-3 ml-auto mr-lg-3">Username</label>
				<input name="username" type="text"
					class="form-control col-5 col-sm-4 col-lg-2 mr-auto" id="usrName"
					placeholder="Enter username" value="<?php echo $username; ?>">
			</div>
			<div class="form-group row">
				<label for="email" class="col-lg-1 col-sm-2 col-3 ml-auto mr-lg-3">Email</label>
				<input name="email" type="text"
					class="form-control col-5 col-sm-4 col-lg-2 mr-auto" id="email"
					placeholder="Enter email" value="<?php echo $email; ?>">
			</div>
			<div class="form-group row">
				<label for="pwd1" class="col-lg-1 col-sm-2 col-3 ml-auto mr-lg-3">Password</label>
				<input name="password_1" type="password"
					class="form-control col-5 col-sm-4 col-lg-2 mr-auto" id="pwd1"
					placeholder="Enter password">
			</div>
		<p class="my-3">
			Already a member? <a class="text-light" href="login.php"><b>Sign in</b></a>
		</p>
			<button type="submit" class="btn btn-dark px-5" name="reg_user">Register</button>
		</form>
	</div>





</body>
</html>