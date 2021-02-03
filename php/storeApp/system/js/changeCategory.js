$(document).ready(function() {
	$("a.changeCatBtn").click(function() {
		
		event.preventDefault();
		cat = $(this).attr("href");
		loadProducts(cat);
	});
});

