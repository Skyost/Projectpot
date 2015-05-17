<?php
	/*function update_message() {
		$latest_version = trim(file_get_contents('http://cdn.rawgit.com/' . PP_APP_AUTHOR . '/' . PP_APP_NAME . '/master/version.update'));
		if($latest_version === false) {
			return '<span style="color: #c0392b; font-weight: bold;">An error occurred while checking for updates.</span>';
		}
		return version_compare(PP_APP_VERSION, $latest_version) == -1 ? '<span style="color: #c0392b; font-weight: bold;">An update is available (v' . $latest_version . ') !</span>' : '<span style="color: #2ecc71; font-weight: bold;">You have the latest version (v' . $latest_version . ').</span>';
	}*/
	
	function load_categories() {
		check_json_dir();
		if(file_exists('json/categories.json')) {
			$categories = json_decode(file_get_contents('json/categories.json'), true);
		}
		else {
			$categories = array('First', 'Second', 'Third');
			file_put_contents('json/categories.json', json_encode($categories));
		}
		return $categories;
	}
	
	function load_projects() {
		check_json_dir();
		if(file_exists('json/projects.json')) {
			$projects = json_decode(file_get_contents('json/projects.json'), true);
		}
		else {
			$projects = array(array(
				'name' => 'Example',
				'description' => 'A simple example.',
				'link' => 'http://www.skyost.eu',
				'category' => 0,
				'projectClicks' => 0,
				'linkClicks' => 0
			));
			file_put_contents('json/projects.json', json_encode($projects));
		}
		return $projects;
	}

	function load_settings() {
		check_json_dir();
		if(file_exists('json/settings.json')) {
			$settings = json_decode(file_get_contents('json/settings.json'), true);
			if($settings['configVersion'] < 2) {
				$settings = array_merge($settings, array(
					'configVersion' => 2,
					'metaLanguage' => 'en_US'
				));
				file_put_contents('json/settings.json', json_encode($settings));
			}
		}
		else {
			$settings = array(
				'configVersion' => 2,
				'metaTitle' => 'My Projectpot',
				'metaDescription' => 'This is my Projectpot.',
				'metaKeywords' => 'project,pot,projectpot',
				'metaAuthor' => 'Skyost',
				'metaLanguage' => 'en_US',
				'adflyUse' => true,
				'adflyId' => 549897
			);
			file_put_contents('json/settings.json', json_encode($settings));
		}
		return $settings;
	}
	
	function check_json_dir() {
		if(!file_exists('json') || !is_dir('json')) {
			mkdir('json');
		}
	}
	
	function get_project($category, $name) {
		$projects = load_projects();
		$categories = load_categories();
		if(is_numeric($category)) {
			foreach($projects as $project) {
				if($project['category'] == $category && $project['name'] == $name) {
					return $project;
				}
			}
		}
		else {
			foreach($projects as $project) {
				if($categories[$project['category']] == $category && $project['name'] == $name) {
					return $project;
				}
			}
		}
	}
	
	function get_project_index($project) {
		$projects = load_projects();
		for($i = 0; $i < count($projects); $i++) {
			if($projects[$i] == $project) {
				return $i;
			}
		}
		return -1;
	}
	
	function delete_cookie($cookie) {
		unset($_COOKIE[$cookie]);
		setcookie($cookie, '', time() - 3600);
	}
	
	function message($message, $type) {
		return '<div class="container alert ' . $type . ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>' . PHP_EOL;
	}
	
	function str_endswith($haystack, $needle) {
		$length = strlen($needle);
		if($length == 0) {
			return true;
		}
		return(substr($haystack, -$length) === $needle);
	}
	
	function compare_projects_names($project_a, $project_b) {
        return strcmp(strtolower($project_a['name']), strtolower($project_b['name']));
	}
?>