function changeStatus(id, status, type) {
	status = status ? 0 : 1;

	var form_data = new FormData();
	form_data.append('id', id);
	form_data.append('status', status);
	form_data.append('type', type);
	$
			.ajax(
					{
						url : "http://localhost/education/issue7/storeApp/system/php/admin/changeStatus.php",
						type : "post",
						data : form_data,
						contentType : false,
						cache : false,
						processData : false

					}).done(function(response) {
						
						switch (type) {
						case 1:
							$.ajax({
								url : "http://localhost/education/issue7/storeApp/system/php/admin/adminOrdersDisplay.php",
								type : "post"
							}).done(function(response) {
								$("#server-results").html(response);
							});
							
						break;
						
						case 2:
							$.ajax({
								url : $("form").attr("action"),
								type : "post"
							}).done(function(response) {
								$("#server-results").html(response);
							});
						break;
						
						case 3:
							$.ajax({
								url : "http://localhost/education/issue7/storeApp/system/php/admin/adminUsersDisplay.php",
								type : "post"
							}).done(function(response) {
								$("#server-results").html(response);
							});
							break;
						}
			});
	
	
}