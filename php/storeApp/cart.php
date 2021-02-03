<?php
session_start();

if (! isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Shop Item - Start Bootstrap Template</title>

<!-- Bootstrap core CSS -->
<link href="system/css/bootstrap.min.css"
	rel="stylesheet">

<!-- Custom styles for this template -->
<link href="system/css/shop-item.css" rel="stylesheet">
<link href="system/css/style.css" rel="stylesheet">


<script src="system/js/jquery.min.js"></script>
<script src="system/js/bootstrap.min.js"></script>

</head>

<body class="bg-info">

	<!-- Navigation -->
	<?php include("inc/navigation.php")?>
	<script>
    	$( document ).ready(function() {
    		$("#navbarResponsive > ul > li.nav-item.active").removeClass("active");
    		$("#navbarResponsive > ul > li:nth-child(3) > a").addClass("active");
    	});
    	
	</script>

	<!-- Page Content -->
	<script src="system/js/loadCart.js"></script>

	<div id="cart" class="container pt-4 p-5 text-center">
  	<?php if (isset($_SESSION['success'])) : ?>
			<h1 class="display-4">
          <?php
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
      	</h1>
  	<?php endif ?>
		</div>

	<div class="container">

		<div class="row">

			<div class="col-lg-3">
				<h1 class="my-4">Naikoms</h1>
			</div>
			<!-- /.col-lg-3 -->

			<div class="container">

				<div id="productDetails">
					
				</div>
				<!-- /.card -->
				<button onclick="order()" class="btn btn-lg my-3 float-right btn-dark"><b>Order</b></button>



			</div>

		</div>

	</div>
	<!-- /.container -->

	<?php include("inc/footer.php")?>

</body>

</html>
