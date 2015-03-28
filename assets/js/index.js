$(document).ready(function() {
	$('#project-container').height($(window).height() - 275);
	$.DivasCookies({
		cookiePolicyLink: 'http://www.aboutcookies.org/',
		cookiePolicyLinkText: 'What are those delicious things ?'	
	});
});

$(window).resize(function() {
	$('#project-container').height($(window).height() - 275);
});

$('.btn').click(function() {
	var current = $('.btn.active');
	current.removeClass('active');
	$(this).addClass('active');
	current = current.attr('id').split('button-')[1];
	$('#tbody-' + current).addClass('hidden');
	current = $(this).attr('id').split('button-')[1];
	$('#tbody-' + current).removeClass('hidden');
});

$('.a-description').click(function() {
	// var decoded = $('<textarea/>').html($(this).text()).text();
	$('#modal-title').text($(this).text());
	$('#modal-text').html($(this).attr('description'));
	$('#modal').modal();
});