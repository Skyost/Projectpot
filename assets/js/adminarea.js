// EVERYTHING NEEDED BY THE ADMIN PANEL :

$(document).ready(function() {
	$('.jquery-te').jqte();
	$('#projects-edit-oldname').change(function() {
		updateEditAreas();
	});
	updateEditAreas();
});

$('#logout-link').click(function() {
	$.removeCookie('pp_username');
	$.removeCookie('pp_password');
	$.cookie('pp_logout', '0');
	location.reload();
});

$('.expander').click(function() {
	if($(this).attr('collapsed') == 1) {
		$(this).text('Collapse');
		$(this).attr('collapsed', 0);
		$(this).prepend('<img src="assets/img/collapse.png"/> ');
	}
	else {
		$(this).text('Expand');
		$(this).attr('collapsed', 1);
		$(this).prepend('<img src="assets/img/expand.png"/> ');
	}
	$('#' + $(this).attr('for')).toggleClass('hidden');
});

function updateEditAreas() {
	var project = $.parseJSON(projects[$('#projects-edit-oldname').val()]);
	$('#projects-edit-newname').val(project['name']);
	$('#projects-edit-newcategory').val(project['category']);
	$('#projects-edit-newlink').val(project['link']);
	$('#projects-edit-newdescription').jqteVal(project['description']);
}

// CHECKING FOR UPDATES : (http://stackoverflow.com/a/7717160/3608831)

function cmpVersion(a, b) {
	var i, cmp, len, re = /(\.0)+[^\.]*$/;
	a = (a + '').replace(re, '').split('.');
	b = (b + '').replace(re, '').split('.');
	len = Math.min(a.length, b.length);
	for(i = 0; i < len; i++) {
		cmp = parseInt(a[i], 10) - parseInt(b[i], 10);
		if(cmp !== 0) {
			return cmp;
		}
	}
	return a.length - b.length;
}