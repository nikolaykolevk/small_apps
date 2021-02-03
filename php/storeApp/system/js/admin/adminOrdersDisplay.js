$(document)
		.ready(
				function() {
					$
							.ajax(
									{
										url : "http://localhost/education/issue7/storeApp/system/php/admin/adminOrdersDisplay.php",
										type : "post"
									}).done(function(response) {
								$("#server-results").html(response);
							});
				});