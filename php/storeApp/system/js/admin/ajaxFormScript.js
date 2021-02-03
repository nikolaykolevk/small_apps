$(document).ready(function() {
	$.ajax({
		url : $("form").attr("action"),
		type : "post"
	}).done(function(response) {
		$("#server-results").html(response);
	});
});

$("form").submit(function(event) {
	event.preventDefault();
	var post_url = $(this).attr("action");
	var request_method = $(this).attr("method");
	var form_data = new FormData(this);
	form_data.append("submit", "1");
	$.ajax({
		url : post_url,
		type : request_method,
		data : form_data,
		contentType : false,
		cache : false,
		processData : false

	}).done(function(response) {
		$("#server-results").html(response);
		$("input").val("");
		$("[name='submit']").val("Add");
	});
});
