<div ng-controller="productsCtrl"
	class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<label class="align-middle"><b>Минимална цена: {{tmp}} </b></label> 
			<input type="text" class="form-control float-right w-25 text-center" ng-model="min-price">
		</div>
		<div class="col-md-4">
			<label class=""><b>Максимална цена: </b></label> 
			<input type="text" class="form-control float-right w-25 text-center" ng-model="min-price">
		</div>
		<div class="col-md-4">
  			<label class=""><b>Категория</b></label>
  			<select ng-change="pageLoad(this.category)" ng-model="category" class="w-50 float-right d-inline form-control">
  				<option>Всички</option>
                <option ng-repeat="category in categories">{{category.name}}</option>
  			</select>
		</div>
	</div>

	<div class="row">
		<div ng-repeat="product in products" class="col-sm-6 col-md-4 product border my-3">
			<h3 class="text-center">{{product.name}}</h3>
			<img src="{{product.mainImg}}" style="width: 100%; height: 30vh;">
			<div class="caption text-center">
				<a
					href="/legla/product.php?id={{product.id}}" class="btn btn-dark productPrice text-light mx-auto my-3 " style="width: 35%">КУПИ</a>
			</div>
			<h4 class="text-center">
				Цена: {{product.models[0].price | number : 2}} лв
			</h4>
		</div>
	</div>
</div>

<script>
	app.controller('productsCtrl', function($scope, $http) {
	$scope.categories = result.categories;
	$scope.category = "<?php echo $_GET['q'] ?>";
	$scope.products = result.products;
	$scope.pageLoad = function (category) {
	window.location = "/legla/category.php?q="+category;
	}
});

</script>