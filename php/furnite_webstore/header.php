<nav id="header" ng-controller="headerCtrl"
	class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
	<a href="/legla/" class="navbar-brand"> <i class='text-light fas fa-bed'
		style='font-size: 36px;'></i>
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav mx-auto my-auto"">
			<li class="nav-item mx-2">
				<a href="/legla" class="nav-link btn btn-lg btn-outline-dark">Начало {{tmp}} </a>
			</li>
			<li class="nav-item mx-2">
				<a href="/legla/category.php?q=Всички" class="nav-link btn btn-lg btn-outline-dark">Продукти</a>
			</li>
			<li class="nav-item mx-2 dropdown">
				<button class="mx-auto nav-link dropdown-toggle btn btn-lg btn-outline-dark"
					href="#" id="navbardrop" data-toggle="dropdown">Категория</button>
				<div class="dropdown-menu bg-dark border border-0">
					<a ng-repeat="category in categories" href="{{category.link}}"
						class="btn btn-lg btn-dark text-light text-left w-100">{{category.name}}</a>
				</div>
			</li>

			<li class="nav-item mx-2 dropdown">
				<button
					class="mx-auto d-md-none nav-link dropdown-toggle btn btn-lg btn-outline-dark"
					ng-show="login" href="#" id="navbardrop" data-toggle="dropdown">Акаунт</button>
				<div class="d-md-none dropdown-menu bg-dark border border-0">
					<button class="btn btn-lg btn-dark text-left w-100">Кошница</button>
					<button class="btn btn-lg btn-dark text-left w-100">Поръчки</button>
					<button class="btn btn-lg btn-dark text-left w-100">Настройки</button>
					<button class="btn btn-lg btn-dark text-left w-100">Изход</button>
				</div>
			</li>

		</ul>
		<a class="btn btn-outline-dark text-light float-right" ng-hide="login"
			data-toggle="modal" data-target="#loginModal" ><b>Вход/Регистрация</b></a>

		<a class="d-none d-md-block btn btn-lg btn-outline-dark text-light float-right"
			ng-show="login" data-toggle="modal" id="cartButton" data-target="#cartModal"><i
				class="fas fa-shopping-cart"></i> <span>{{productsCount}}</span> </a>
		<div ng-show="login" class="dropdown d-none d-md-block">
			<button class="btn btn-lg btn-outline-dark text-light" type="button"
				data-toggle="dropdown">акаунт</button>
			<ul class="dropdown-menu-right dropdown-menu">
				<button class="dropdown-item btn btn-lg btn-outline-dark">Поръчки</button>
				<button class="dropdown-item btn btn-lg btn-outline-dark">Настройки</button>
				<button class="dropdown-item btn btn-lg btn-outline-dark">Изход</button>
				<li class="dropdown-header">Здравей, {{loginName}}</li>
			</ul>
		</div>
	</div>
</nav>

<!-- Cart -->
<div ng-controller="cartCtrl">
	<div class="modal fade" id="cartModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Кошница</h2>
					<button type="button" class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div>
					<div class="modal-body" id="cartBody">
						<div ng-repeat="product in products" class="row product my-4">
							<div class="col-md-7">
								<h4>
									{{product.name}}
									<button ng-click="removeItem(product);" class="close float-right">
										<span>&times;</span>
									</button>
								</h4>
								<p>Размер: {{product.size}}</p>
								<p>Цена: {{product.price}} лв</p>
								<div class="row text-center">
									<p class="col-5 text-left">Количество: </p> 
									<button ng-click="minus(product)" class="col-2 btn btn-sm btn-dark h-50 text-light">-</button>
									<input ng-change="quantityChange(product)" class="form-control col-2 h-50 text-center mx-2"  ng-model="product.quantity" type="text" value="{{product.quantity}}">
									<button ng-click="plus(product)" class="col-2 btn btn-sm btn-dark h-50 text-light">+</button>
								</div>
							</div>
							<img class="col-md-5 cartProductImage"
								src="{{product.imageSource}}">
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<h3 class="mr-auto">Общо: {{total | number : 2}} лв</h3>
					<button ng-click="authorize()" class="btn btn-primary">Купи</button>
				</div>
			</div>
		</div>
	</div>


<!-- Login Register -->

<div id="authorizeApp"
	ng-controller="authorizeCtrl as child2">
	<div class="modal fade" id="loginModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 ng-hide="func" class="modal-title">Вход</h5>
					<h5 ng-show="func" class="modal-title">Регистрация</h5>
					<button type="button" class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div ng-hide="func">
					<div class="modal-body">
						<div class="form-group">
							<label for="username" class="col-form-label">Потребителско Име:</label>
							<input ng-model="username" type="text" class="form-control"
								id="username">
						</div>
						<div class="form-group">
							<label for="password" class="col-form-label">Парола:</label> <input
								ng-model="password" type="password" class="form-control"
								id="password"></input>
						</div>
					</div>
				</div>
				<div ng-show="func">
					<div class="form-inline my-3">

						<input type="text" placeholder="Потребителско Име"
							ng-model="username" class="form-control col-10 mx-auto">
					</div>
					<div class="form-inline my-3">
						<input type="password" placeholder="парола" ng-model="password"
							class="form-control col-10 mx-auto">
					</div>
					<div class="form-inline my-3">
						<input type="text" placeholder="email" ng-model="email"
							class="form-control col-10 mx-auto">
					</div>
					<div class="form-inline my-3">
						<input type="tel" placeholder="телефон" ng-model="phone"
							class="form-control col-10 mx-auto">
					</div>
				</div>
				<div class="modal-footer">
					<button ng-show="func" ng-click="func=0"
						class="mr-auto btn btn-dark">Имаш Акаунт</button>
					<button ng-hide="func" ng-click="func=1"
						class="mr-auto btn btn-dark">Регистрирай се</button>
					<button ng-click="authorize()" class="btn btn-primary">готово</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script>

	var app = angular.module('app', []);

	
	app.controller('headerCtrl', function($scope, $http) {
		$scope.login = result.login;
		$scope.loginName = result.loginName;
		$scope.products = result.cartProducts;
		$scope.productsCount = result.cartProducts.length;
		$scope.categories = result.categories;
		tmpData = {};
		tmpData.tmp = "tmp1";
		$scope.$on("productsCountUpdate", function (evt, count) {
			$scope.productsCount = count;
	    });

		
	});

	app.controller('cartCtrl', function ($scope, $http) {
		$scope.Math = window.Math;
		$scope.total = 0;
		for (i=0; i<result.cartProducts.length; i++) {
			$scope.total += result.cartProducts[i].price * result.cartProducts[i].quantity;
		}
		$scope.products = result.cartProducts;

		$scope.quantityChange = function (product) {
			if(isNaN(product.quantity)) {product.quantity = 1}
			product.quantity = parseInt(product.quantity); 
			$scope.update();
		}
			
		$scope.minus = function(product) {
			if(product.quantity>1) {
				product.quantity--;
				$scope.update();
			}
		}
		$scope.plus = function(product) {
			product.quantity++;
			$scope.update();
		}
		$scope.removeItem = function (product) {
			$scope.products.splice(product, 1);
			$scope.update();
			
		}
		$scope.update = function () {
			result.cartProducts = $scope.products;
			$scope.total = 0;
			for (i=0; i<result.cartProducts.length; i++) {
				$scope.total += result.cartProducts[i].price * result.cartProducts[i].quantity;//product.price * product.quantity;
			} 
		}
		$scope.addProduct = function (product) {
			$scope.added=0;
			for (i=0; i<result.cartProducts.length; i++) {
				if (product.id == $scope.products[i].id) {$scope.added=1;}
			}
			if ($scope.added) {
				$scope.products[product.id].quantity=product.quantity;
			} else {
			$scope.products.push(product);
			$scope.update();
			$scope.$emit("productAdded", $scope.products.length);
			}
			$("#cartButton").click();
		}

		$scope.$on("updatingCart", function (evt, product) {
			$scope.addProduct(product);
	    });
		
		
	});
	
	app.controller('authorizeCtrl', function($scope, $http) {
		$scope.func = 0;
		data = {};
		$scope.tmp = "asd";
		$scope.authorize = function() {
			data.username = $scope.username;
			data.password = $scope.password;
			data.func = $scope.func;
			if ($scope.func = 1) {
				data.email = $scope.email;
				data.phone = $scope.phone;
			}
			$http.post("authorize.php", JSON.stringify(data)).then(
					function(response) {
						
						$scope.tmp = result.login;

					});
		}
	});


</script>
