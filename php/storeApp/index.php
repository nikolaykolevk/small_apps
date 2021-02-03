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
<script src="system/js/changeCategory.js"> </script>
</head>
<body class="bg-info">


	<script src="system/js/loadProducts.js"> </script>


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
	<?php include("inc/navigation.php")?>

	<!-- Page Content -->
	<div class="container">

		<div class="row">

			<div class="col-lg-3">

				<h1 class="my-4">Naikoms</h1>
				<div class="list-group">
					<a href="1" class="changeCatBtn list-group-item">Category 1</a>
					<a href="2" class="changeCatBtn list-group-item">Category 2</a>
					<a href="3" class="changeCatBtn list-group-item">Category 3</a>
					<a href="4" class="changeCatBtn list-group-item">Category 4</a>
					<a href="5" class="changeCatBtn list-group-item">Category 5</a>
				</div>

			</div>
			<!-- /.col-lg-3 -->

			<div class="col-lg-9">

				<div id="carouselExampleIndicators" class="carousel slide my-4"
					data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carouselExampleIndicators" data-slide-to="0"
							class="active"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="carousel-item active">
							<img class="d-block img-fluid" src="http://placehold.it/900x350"
								alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="http://placehold.it/900x350"
								alt="Second slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="http://placehold.it/900x350"
								alt="Third slide">
						</div>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleIndicators"
						role="button" data-slide="prev"> <span
						class="carousel-control-prev-icon" aria-hidden="true"></span> <span
						class="sr-only">Previous</span>
					</a> <a class="carousel-control-next"
						href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>

				<div id="products" class="row"></div>
				<!-- /.row -->

			</div>
			<!-- /.col-lg-9 -->

		</div>
		<!-- /.row -->

	</div>
	<!-- /.container -->

<?php include("inc/footer.php")?>


</body>
</html>