


<div ng-controller="newItemsCtrl" class="container-fluid">

	<h1 class="my-3 text-center">Нови Продукти</h1>
	<div id="newItemsCarousel" class="carousel slide w-75 mx-auto"
		data-ride="carousel">

		<ul class="carousel-indicators" id="newProductsIndicators">

			<li ng-repeat="product in newItems" ng-class="{'active': product == newItems[0]}" data-target="#newItemsCarousel" data-slide-to="{{ $index }}"></li>
		</ul>

		<div class="carousel-inner">
			<div ng-repeat="product in newItems" ng-class="{'active': product == newItems[0]}" class="carousel-item">
				<a href="/legla/product.php?id={{product.id}}" class="py-2 btn btn-dark position-absolute mx-auto w-25 text-light" style="background-color: rgba(0, 0, 0, 0.5); top:50%; left:37.5%;">КУПИ</a>
				<img src="{{product.imageUrl}}" class="promotionItemImage w-100">
				<div class="carousel-caption d-none d-md-block">
					<h3>{{product.name}}</h3>
					<p>{{product.price}} лв</p>
				</div>
			</div>
			

		<a class="carousel-control-prev" href="#newItemsCarousel"
			data-slide="prev"> <span class="carousel-control-prev-icon"></span>
		</a> <a class="carousel-control-next" href="#newItemsCarousel"
			data-slide="next"> <span class="carousel-control-next-icon"></span>
		</a>
	</div>

</div>

<br>
<hr>
<br>

<div ng-controller="promotionItemsCtrl" class="container-fluid">

	<h1 class="my-3 text-center">Промоционални Продукти</h1>
	<div id="promotionItemsCarousel" class="carousel slide w-75 mx-auto"
		data-ride="carousel">

		<ul class="carousel-indicators" id="promotionProductsIndicators">

			<li ng-repeat="product in promotionItems" ng-class="{'active': product == promotionItems[0]}" data-target="#promotionItemsCarousel" data-slide-to="{{ $index }}"></li>
		</ul>

		<div class="carousel-inner">
			<div ng-repeat="product in promotionItems" ng-class="{'active': product == promotionItems[0]}" class="carousel-item">
				<a href="/legla/product.php?id={{product.id}}" class="py-2 btn btn-dark position-absolute mx-auto w-25 text-light" style="background-color: rgba(0, 0, 0, 0.5); top:50%; left:37.5%;">КУПИ</a>
				<img src="{{product.imageUrl}}" class="promotionItemImage w-100">
				<div class="carousel-caption d-none d-md-block" >
					<h3>{{product.name}}</h3>
					<p>{{product.price}} лв</p>
				</div>
			</div>
			

		<a class="carousel-control-prev" href="#promotionItemsCarousel"
			data-slide="prev"> <span class="carousel-control-prev-icon"></span>
		</a> <a class="carousel-control-next" href="#promotionItemsCarousel"
			data-slide="next"> <span class="carousel-control-next-icon"></span>
		</a>
	</div>

</div>

<script>
	app.controller('newItemsCtrl', function($scope) {
	$scope.newItems = result.newItems;
});

	app.controller('promotionItemsCtrl', function($scope) {
		$scope.promotionItems = result.promotionItems;
	});
	
 	
 	
</script>
<script>

</script>