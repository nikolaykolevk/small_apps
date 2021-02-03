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

<?php include ("system/php/admin/adminManageScript.php"); ?>

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


	<div class="container pt-4 p-5 text-center">


		<h1 class="display-2 text-light my-3">Create new Administrator</h1>

		<form action="tmpScript.php" method="post">
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
					<input class="btn btn-dark ml-auto" type="submit" name="new_admin"
						value="Create New Administrator">
				</div>
			</div>
		</form>


		<br> <br> <br>
		<h1 class="display-2 text-light my-3">Administrators</h1>
		<div id="server-results"></div>
	
	
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

	<script>

	$( document ).ready(function() {
		$.ajax({
	        url : $("form").attr("action"),
	        type: "post"
	    }).done(function(response){
	        $("#server-results").html(response);
	    });
	});

	$("form").submit(function(event){
	    event.preventDefault(); 
	    var post_url = $(this).attr("action");
	    var request_method = $(this).attr("method");
		var form_data = new FormData(this);
		form_data.append("submit", "1");
	    $.ajax({
	        url : post_url,
	        type: request_method,
	        data : form_data,
			contentType: false,
			cache: false,
			processData:false
			
	    }).done(function(response){
	        $("#server-results").html(response);
	    });
	});
	
	</script>

	</div>


</body>
</html>