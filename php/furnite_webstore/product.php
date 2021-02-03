
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css"
	href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
<script
	src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#000"
    },
    "button": {
      "background": "#f1d600"
    }
  },
  "position": "bottom-right"
})});
</script>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel='stylesheet'
	href='https://use.fontawesome.com/releases/v5.6.3/css/all.css'>
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>
<title>Легла</title>

</head>
<body ng-controller="mainCtrl" ng-app="app">
	<!-- Header -->
<?php
require ("header.php");
?>
<style>
.mainImage {
	height: 40vw;
	min-height: 30vh;
}

.secondaryImage {
	height: 8vw;
	min-height: 10vh;
}

.secondaryImage:hover {
    cursor: pointer;
}
</style>
	<br>
	<div id="productPage" ng-controller="productCtrl" class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<div id="carouselExampleIndicators" class="carousel slide w-100 p-3 mx-auto"
					data-ride="carousel">
					<div class="carousel-inner row">
						<div class="carousel-item active">
							<img class="d-block w-100 mx-auto mainImage" src="{{product.mainImg}}"
								alt="First slide">
						</div>
						<div ng-repeat="image in product.images" class="carousel-item">
							<img class="d-block w-100 mx-auto mainImage" src="{{image}}"
								alt="Second slide">
						</div>
					</div>

					<div data-ride="carousel" class="w-100 row my-2">
						<img data-target="#carouselExampleIndicators" data-slide-to="0"
							src="{{product.mainImg}}" class="secondaryImage p-0 col-md-2 col-3">
						<img ng-repeat="image in product.images" data-target="#carouselExampleIndicators" data-slide-to="{{$index + 1}}"
							src="{{image}}" class="secondaryImage p-0 col-md-2 col-3">
							
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<br>
				<div class="row">
					<h2 class="mx-auto">{{product.name}}</h2>
				</div>
				<div class="row">
    				<h4 class="col-12 my-2 pt-3 text-center"> Цена: {{price | number : 2}}лв </h4>
    				<p class="col-12 my-1 text-center">Описание: {{product.description}}</p>
				</div>
				<div class="row">
    				<h4 class="col-6 my-4 text-center"> Размер: </h4> 
    				<select ng-change="update()" ng-model="size" class="col-3 mx-auto my-auto form-control">
    					<option  ng-repeat="model in product.models" class="d-inline" value="{{$index}}">{{model.size}}</option>
    				</select>
				</div>
				<div class="row my-3">
    				<h4 class="col-6 my-4 text-center"> Количество:</h4>
    				<div class="col-6 mx-auto my-auto row">
        				<button ng-click="minus()" class="col-3 btn btn-dark">-</button>
        				<input ng-change="quantityChange()" ng-model="quantity" type="text" class="mx-2 form-control col-4 text-center">
        				<button ng-click="plus()" class="col-3 btn btn-dark">+</button>
    				</div>
				</div>
				<div class="row">
				<button ng-click="addToCart()" class="mx-auto btn btn-lg btn-dark my-4"> Добави в кошница </button>
				</div>
				
			</div>
		</div>
	</div>
	
	<script>
	
	id = "<?php echo $_GET['id']?>";
	var productOk = function () {
    	if (!id || isNaN(id) || id% 1 !=0 || id < 1 || id>result.products.length-1) {
    		return 0;
    	}
    	else {
        	return 1;
    	}
	}

	app.controller('mainCtrl', function($scope) {
		$scope.$on("ProductAdd", function (evt, data) {
			$scope.$broadcast("updatingCart", data);
	    });
		$scope.$on("productAdded", function (evt, data) {
			$scope.$broadcast("productsCountUpdate", data);
	    });
		
	    
	});
	
	app.controller('productCtrl', function($scope) {
		if ( productOk() ) {
    		$scope.product=result.products[id-1];
    		$scope.quantity = 1;
    		$scope.price = $scope.product.models[0].price;
    		$scope.size = "0";
    		cartProduct = {
    				imageSource : $scope.product.mainImg,
    				name : $scope.product.name,
    				size : $scope.product.models[id].size,
    				quantity : parseInt($scope.quantity),
    				price: $scope.price,
    				id: $scope.product.id
    		};
    		$scope.update = function () {
    			id = $scope.size;
    			$scope.price = $scope.product.models[id].price;
    			cartProduct.price = $scope.price;
    			cartProduct.size = $scope.product.models[id].size;
    			cartProduct.quantity = parseInt($scope.quantity);
    		}
    		$scope.quantityChange = function () {
    			if(isNaN($scope.quantity)) {$scope.quantity = 1}
    			$scope.update();
    			}
    		$scope.minus = function () {
    			if($scope.quantity>1) {
    				$scope.quantity--;
    				$scope.update();
    			}
    		}
    		$scope.plus = function () {
    			$scope.quantity++;
    			$scope.update();
    			}
    
    		$scope.addToCart = function () {
				$scope.$emit("ProductAdd", cartProduct);
    		}
		} else {
			$scope.product = {};
    		$scope.product = {
    	    	mainImg: "notFound.png",
    			name: "Не е намерен",
    			id: "-1",
    			description: "Продуктът, който търсите не беше намерен",
    			images: [],
    			models: [{
    				price : 9999.99,
    				size : "опа"
    			}]
			}
    		$scope.quantity = "420";
    		$scope.price = $scope.product.models[0].price;
    		$scope.size = "0";
	}	
	});
	
		

	</script>

	<br>
	<!-- Footer -->
<?php
require ("footer.php");
?>
</body>
</html>