	$( document ).ready(function() {
		id = (getQueryVariable("id")) ? getQueryVariable("id") : 0;
        $.ajax({
    		url : "system/php/getItem.php?id="+id,
    		type : "get"
    	}).done(function(response) {

    		item = JSON.parse(response);
    		changeContent(item);

    	});
        
        $("#addToCart").click(function () {
    		addToCart(getQueryVariable("id"), $("#quantity").val());
    	});
	});
	
	function changeContent(item) {
		$("#p-name").text(item.name);
		$("#p-description").text(item.description);
		$("#p-price").text("$"+parseFloat(item.price).toFixed(2));
		$("#p-rating").html("&#9733; ".repeat(item.rating)+"&#9734".repeat(5-item.rating));
		$("#p-imgSrc").attr("src", item.imgSrc);
	}
	
	
	function getQueryVariable(variable)
	{
	       var query = window.location.search.substring(1);
	       var vars = query.split("&");
	       for (var i=0;i<vars.length;i++) {
	               var pair = vars[i].split("=");
	               if(pair[0] == variable){return pair[1];}
	       }
	       return(false);
	}
	
	function addToCart(id, quantity) {
		$.ajax({
			url : "system/php/addToCart.php?product=" + id + "&q=" + quantity,
			type : "get"
		}).done(function(response) {
			element = document.createElement("div");
			$(element).addClass("alert alert-success");
			$(element).text("Products added to cart!");
			$("#productDetails").append(element);
			
			setTimeout(function() {
				$(element).fadeOut("slow");
			}, 3000);
		});
	}