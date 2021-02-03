<footer ng-controller="footerCtrl" id="footer" class="page-footer font-small pt-4 bg-dark">

	<div class="container-fluid text-center text-md-left">

		<div class="row">

			<div class="col-md-6 mt-md-0 mt-3">

				<h5 class="text-uppercase text-light col-md-10 text-center">Информация за Сайта</h5>
				<p class="text-light text-center col-md-10">{{websiteInfo}}</p>
				<p class="text-light text-center col-md-10 my-3"> <i>Designed by Nikolay K </i> </p>

			</div>

			<hr class="clearfix w-100 d-md-none pb-3">

			<div class="col-md-3 mb-md-0 mb-3 text-center">

				<h5 class="text-uppercase text-light">Категории</h5>

				<ul class="list-unstyled">
					<li ng-repeat="category in categories" ><a class="text-light" href="{{category.link}}">{{category.name}}</a></li>

				</ul>
			</div>

			<div class="col-md-3 mb-md-0 mb-3 text-center">

				<h5 class="text-uppercase text-light">Полезни Връзки</h5>

				<ul class="list-unstyled">
					<li><p class="text-light">телефон: {{phoneNumber}}</p></li>
					<li><a class="text-light" href="{{facebookLink}}"><i class='fab fa-facebook-square' style='font-size:36px;color:white'></i></a></li>
				</ul>

			</div>

		</div>

	</div>

	<div class="footer-copyright text-center py-3 text-light">
		© 2019 Copyright: <a class="text-light"
			href="{{copyrightLink}}"> {{copyright}} </a>
	</div>

</footer>


<script>

	app.controller('footerCtrl', function($scope) {
		$scope.facebookLink = result.facebookLink;
		$scope.phoneNumber = result.phoneNumber;
		$scope.copyright = result.copyright;
		$scope.copyrightLink = result.copyrightLink;
		$scope.websiteInfo = result.websiteInfo; 
		$scope.categories = result.categories;
	});

</script>