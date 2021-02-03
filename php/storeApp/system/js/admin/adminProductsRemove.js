function remove(id) {	
	var form_data = new FormData();
	form_data.append('removeProductID', id);
	$.ajax({
		url : "http://localhost/education/issue7/storeApp/system/php/admin/adminProductsScript.php",
		type : "post",
		data : form_data,
		contentType : false,
		cache : false,
		processData : false

	}).done(function(response) {
		$("#server-results").html(response);
		$("input").val("");
		$("[name='submit']").val("Add");
	});
}