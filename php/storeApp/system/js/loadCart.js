var products = [];
$(document).ready(function() {
	load();
});

function load() {
	el = $("#productDetails").empty();
	$.ajax({
		url : "system/php/loadCart.php",
		type : "get"
	}).done(function(response) {

		products = JSON.parse(response);
		products.forEach(addProduct);

	});
}

function order() {
	$.ajax({
		url : "system/php/orderProducts.php",
		type : "get"
	}).done(function(response) {

		load();
		alert("Your order has been made");

	});
	
	
}

function addProduct(item, index) {
	newEl = document.createElement("div");
	$(newEl).addClass("product my-4 py-5 bg-light");
	el = $("#productDetails").append(newEl);
	el = newEl;

	newEl = document.createElement("h3");
	$(newEl).addClass("text-center");
	$(newEl).text(item.name);
	$(el).append(newEl);

	newEl = document.createElement("div");
	$(newEl).addClass("row px-3");
	el = $(el).append(newEl);
	el = newEl;

	newEl = document.createElement("img");
	$(newEl).addClass("col-4 img-fluid");
	$(newEl).attr("src", item.imgSrc);
	$(el).append(newEl);

	newEl = document.createElement("div");
	$(newEl).addClass("col-8 row");
	$(el).append(newEl);
	el = newEl;

	newEl = document.createElement("h4");
	$(newEl).addClass("col-3 col-sm-4 my-auto");
	$(newEl).text("$" + parseFloat(item.price).toFixed(2));
	$(el).append(newEl);

	newEl = document.createElement("h4");
	$(newEl).addClass("col-7 col-sm-7 my-auto px-5");
	$(newEl).text("Quantity: " + item.quantity);
	$(el).append(newEl);

	newEl = document.createElement("button");
	$(newEl).addClass("col-2 col-sm-1 btn my-auto");
	$(newEl).html("&times;");
	$(newEl).attr("onclick", "removeProduct(" + item.id + ")");
	$(el).append(newEl);

}

function removeProduct(productID) {
	$.ajax({
		url : "system/php/removeProduct.php?q=" + productID,
		type : "get"
	}).done(function(response) {

		load();

	});

}