<?php	
	require_once('include/functions.php');

	function increment_field($project_index, $field) {
		$projects = load_projects();
		$projects[$project_index][$field] = isset($projects[$project_index][$field]) ? $projects[$project_index][$field] + 1 : 1;
		file_put_contents('json/projects.json', json_encode($projects));
	}
	
	if(isset($_POST['stat'])) {
		if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
			die('What are you doing bro ? Only AJAX is allowed to do that !');
		}
		if(!isset($_POST['category']) || !isset($_POST['project'])) {
			die('Missing parameter(s) : category or project.');
		}
		$project = get_project($_POST['category'], $_POST['project']);
		if($project == null) {
			die('Project not valid.');
		}
		$project_index = get_project_index($project);
		if($_POST['stat'] == 0) {
			increment_field($project_index, 'linkClicks');
			die(true);
		}
		if($_POST['stat'] == 1) {
			increment_field($project_index, 'projectClicks');
			die(true);
		}
		die('Invalid parameter : stat.');
	}
?>