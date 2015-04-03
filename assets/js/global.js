$(document).ready(function() {
	var ua = window.navigator.userAgent;
	if(ua.indexOf('MSIE ') > 0 || ua.indexOf('Trident/') > 0 || ua.indexOf('Edge/') > 0) {
		$('.nav.navbar-nav').append('<li><a target="_blank" href="http://donotuseie.com/" style="color: #c0392b;"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Looks like you\'re using IE. This website may not display properly.</a></li>');
	}
});