var cookieValidated;

$(document).ready(function() {
	if($.cookie('pp_cookie') == undefined) {
		$.cookie('pp_cookie', '0');
	}
	cookieValidated = $.cookie('pp_cookie') == '1';
	if(!cookieValidated) {
		$(document.body).prepend('<div style="text-align: center;" class="container alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" id="cookie-button"><span aria-hidden="true">&times;</span></button>This website is using cookies ! <a target="_blank" href="http://www.aboutcookies.org/">What are those delicious things ?</a></div>');
		$('#cookie-button').click(function() {
			$.cookie('pp_cookie', '1');
			cookieValidated = true;
			resizeContainer();
		});
	}
	resizeContainer();
});

$(window).resize(function() {
	resizeContainer();
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
	var project = $(this).text();
	$('#modal-title').text(project);
	$('#modal-text').html($(this).attr('description'));
	$('#modal').modal();
	$.post('stats.php', {
		stat: 1,
		category: $(this).attr('category'),
		project: project
	});
});

function resizeContainer() {
	$('#project-container').height($(window).height() - 275 - (cookieValidated ? 0 : 75));
}