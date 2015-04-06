$(document).ready(function() {
	$('.jquery-te').jqte();
	$('#projects-edit-project').change(function() {
		updateEditAreas();
	});
	$('#statistics-projects-project').change(function() {
		updateStatistics();
	});
	updateEditAreas();
	updateStatistics();
});

$('#logout-link').click(function() {
	$.removeCookie('pp_username');
	$.removeCookie('pp_password');
	$.cookie('pp_logout', '0');
	location.reload();
});

function updateEditAreas() {
	var project = $.parseJSON(projects[$('#projects-edit-project').val()]);
	$('#projects-edit-newname').val(project['name']);
	$('#projects-edit-newcategory').val(project['category']);
	$('#projects-edit-newlink').val(project['link']);
	$('#projects-edit-oldcategory').val(project['category']);
	$('#projects-edit-oldname').val(project['name']);
	$('#projects-edit-newdescription').jqteVal(project['description']);
}

function updateStatistics() {
	var project = $.parseJSON(projects[$('#statistics-projects-project').val()]);
	$('#statistics-projects-projectclicks').val(project['projectClicks'] == undefined ? 0 : project['projectClicks']);
	$('#statistics-projects-linkclicks').val(project['linkClicks'] == undefined ? 0 : project['linkClicks']);
}

// Comparing versions (http://stackoverflow.com/a/7717160/3608831).
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