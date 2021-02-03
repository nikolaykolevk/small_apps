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
<script src="system/js/admin/adminProductsRemove.js"></script>

</head>
<body class="bg-info">




	<!-- Navigation -->
	<?php include("inc/admin/adminNavigation.php")?>

	<div class="container">

		<h1 class="display-2 text-light my-3">Create new Product</h1>

		<form action="system/php/admin/adminProductsScript.php" method="POST"
			enctype="multipart/form-data">
			<div class="form-group row">
				<label for="productName"
					class="col-sm-2 col-form-label my-2 text-light"><b>Product Name</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="productName">
				</div>
				<label for="productCategory"
					class="col-sm-2 col-form-label my-2 text-light"><b>Category</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="productCategory">
				</div>
				<label for="productPrice"
					class="col-sm-2 col-form-label my-2 text-light"><b>Price</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="productPrice">
				</div>
				<label for="productDescription"
					class="col-sm-2 col-form-label my-2 text-light"><b>Description</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="productDescription">
				</div>
				<label for="productRating"
					class="col-sm-2 col-form-label my-2 text-light"><b>Rating</b></label>
				<div class="col-sm-10 my-2">
					<input type="text" class="form-control" name="productRating">
				</div>
				<div class="input-group my-2">
					<div class="input-group-prepend">
						<span class="input-group-text">Upload</span>
					</div>
					<div class="custom-file">
						<input name="image" type="file" class="custom-file-input"> <label
							class="custom-file-label text-center" for="image">Choose Image</label>
					</div>
				</div>

				<div class="input-group my-2">
					<input class="btn btn-dark ml-auto" type="submit" name="submit"
						value="Add">
				</div>
			</div>
		</form>


		<script src="system/js/admin/ajaxFormScript.js"> </script>

		<br> <br> <br>
		<h1 class="display-2 text-light my-3">Products</h1>
		<div id="server-results"></div>





	</div>


</body>
</html>