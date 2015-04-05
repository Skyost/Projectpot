<?php
	require('include/functions.php');
	
	if(!isset($_GET['category']) || !isset($_GET['project'])) {
		die('Missing parameter(s) : category or project.');
	}
	if(!isset($_GET['adfly'])) {
		$_GET['adfly'] = 0;
	}
	$projects = load_projects();
	$project = get_project($_GET['category'], $_GET['project']);
	if($project == null) {
		die('Project not valid.');
	}
	if(boolval($_GET['adfly'])) {
		$settings = load_settings();
		$link = 'http://adf.ly/' . $settings['adflyId'] . '/' . $project['link'];
	}
	else {
		$link = $project['link'];
	}
	require('stats.php');
	increment_field(get_project_index($project), 'linkClicks');
	header('Location: ' . $link);
?>