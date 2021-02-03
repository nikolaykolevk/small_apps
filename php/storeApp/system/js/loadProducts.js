var products = [];
	$( document ).ready(function() {
        loadProducts();
	});

	function loadProducts (cat=0) {
		
    	$.ajax({
    		url : "system/php/getProducts.php?cat="+cat,
    		type : "get"
    	}).done(function(response) {
    		products = JSON.parse(response);
    		
    		
    		$("#products").empty();
	        products.forEach(addProduct);

    	});
	}
	
	function addProduct(item, index) {
		newEl = document.createElement("div");
		$(newEl).addClass("col-lg-4 col-md-6 mb-4 product");
		el = $("#products").append(newEl);
		el = newEl;

		newEl = document.createElement("div");
		$(newEl).addClass("card h-100");
		$(el).append(newEl);
		el = newEl;
		div = newEl;
		
		newEl = document.createElement("a");
		$(newEl).attr("href", "item.php?id="+item.id);
		$(el).append(newEl);
		el = newEl;

		newEl = document.createElement("img");
		$(newEl).addClass("card-img top");
		$(newEl).attr("src", item.imgSrc);
		$(el).append(newEl);
		el = div;

		newEl = document.createElement("div");
		$(newEl).addClass("card-body");
		$(el).append(newEl);
		div = newEl;
		el = newEl;

		newEl = document.createElement("h4");
		$(newEl).addClass("card-title");
		$(el).append(newEl);
		el = newEl;

		newEl = document.createElement("a");
		$(newEl).attr("href", "item.php?id="+item.id);
		$(newEl).text(item.name);
		$(el).append(newEl);
		el = div;

		newEl = document.createElement("h5");
		$(newEl).text("$"+parseFloat(item.price).toFixed(2));
		$(el).append(newEl);

		newEl = document.createElement("p");
		$(newEl).addClass("card-text");
		$(newEl).text(item.description);
		$(el).append(newEl);
		el = div;
		
		newEl = document.createElement("div");
		$(newEl).addClass("card-footer");
		$(el).after(newEl);
		el = newEl;

		newEl = document.createElement("small");
		$(newEl).addClass("text-muted");
		$(newEl).html("&#9733; ".repeat(item.rating)+"&#9734".repeat(5-item.rating));
		$(el).append(newEl);
		
      		}
	