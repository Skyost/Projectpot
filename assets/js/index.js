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
	$('#modal-title').text($(this).text());
	$('#modal-text').html($(this).attr('description'));
	$('#modal').modal();
});