$(function() {
	$("#nav-response-btn").click(function(e) {
		e.preventDefault();
		$($(this).data("open")).toggle();
	});
});