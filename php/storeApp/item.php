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
    	});
	</script>

	<script src="system/js/loadItem.js"></script>

	<!-- Page Content -->

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

	<div class="container">

		<div class="row">

			<div class="col-lg-3">
				<h1 class="my-4">Naikoms</h1>
			</div>
			<!-- /.col-lg-3 -->

			<div class="col-lg-9">

				<div id="productDetails" class="card mt-4">
					<img id="p-imgSrc" class="card-img-top img-fluid"
						src="http://placehold.it/900x400" alt="">
					<div class="card-body">
						<h3 class="card-title" id="p-name">Product Name</h3>
						<h4 id="p-price">$24.99</h4>
						<p id="p-description" class="card-text">Lorem ipsum dolor sit
							amet, consectetur adipisicing elit. Sapiente dicta fugit fugiat
							hic aliquam itaque facere, soluta. Totam id dolores, sint aperiam
							sequi pariatur praesentium animi perspiciatis molestias iure,
							ducimus!</p>
						<span id="p-rating" class="text-warning">&#9733; &#9733; &#9733;
							&#9733; &#9734;</span>

						<div class="row float-right">
							<input id="quantity" class="form-control mx-2 col-2 text-center" value="1" type="text">
							<button id="addToCart" class="btn btn-dark col-6">Add to
								cart</button>
						</div>
					</div>
				</div>
				<!-- /.card -->

				<div class="card card-outline-secondary my-4">
					<div class="card-header">Product Reviews</div>
					<div class="card-body">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis
							et enim aperiam inventore, similique necessitatibus neque non!
							Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi
							mollitia, necessitatibus quae sint natus.</p>
						<small class="text-muted">Posted by Anonymous on 3/1/17</small>
						<hr>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis
							et enim aperiam inventore, similique necessitatibus neque non!
							Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi
							mollitia, necessitatibus quae sint natus.</p>
						<small class="text-muted">Posted by Anonymous on 3/1/17</small>
						<hr>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis
							et enim aperiam inventore, similique necessitatibus neque non!
							Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi
							mollitia, necessitatibus quae sint natus.</p>
						<small class="text-muted">Posted by Anonymous on 3/1/17</small>
						<hr>
						<a href="#" class="btn btn-success">Leave a Review</a>
					</div>
				</div>
				<!-- /.card -->

			</div>
			<!-- /.col-lg-9 -->

		</div>

	</div>
	<!-- /.container -->

	<?php include("inc/footer.php")?>

</body>

</html>
