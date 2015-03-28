<?php
	/*function update_message() {
		$latest_version = trim(file_get_contents('http://cdn.rawgit.com/' . PP_APP_AUTHOR . '/' . PP_APP_NAME . '/master/version.update'));
		if($latest_version === false) {
			return '<span style="color: #c0392b; font-weight: bold;">An error occurred while checking for updates.</span>';
		}
		return version_compare(PP_APP_VERSION, $latest_version) == -1 ? '<span style="color: #c0392b; font-weight: bold;">An update is available (v' . $latest_version . ') !</span>' : '<span style="color: #2ecc71; font-weight: bold;">You have the latest version (v' . $latest_version . ').</span>';
	}*/
	
	function load_categories() {
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
		if(file_exists('json/projects.json')) {
			$projects = json_decode(file_get_contents('json/projects.json'), true);
		}
		else {
			$projects = array(array(
				'name' => 'Example',
				'description' => 'A simple example.',
				'link' => 'http://www.skyost.eu',
				'category' => 0
			));
			file_put_contents('json/projects.json', json_encode($projects));
		}
		return $projects;
	}

	function load_settings() {
		if(file_exists('json/settings.json')) {
			$settings = json_decode(file_get_contents('json/settings.json'), true);
		}
		else {
			$settings = array(
				'configVersion' => 1,
				'metaTitle' => 'My Projectpot',
				'metaDescription' => 'This is my projects\' pot.',
				'metaKeywords' => 'project,pot,projectpot',
				'metaAuthor' => 'Skyost',
				'adflyUse' => true,
				'adflyId' => 549897
			);
			file_put_contents('json/settings.json', json_encode($settings));
		}
		return $settings;
	}
	
	function delete_cookie($cookie) {
		unset($_COOKIE[$cookie]);
		setcookie($cookie, '', time() - 3600);
	}
	
	function title($title) {
		return '<h1><img class="title" src="assets/img/HoneyPot.png"/> ' . $title . ' <img class="title" src="assets/img/HoneyPot.png"/></h1>';
	}
	
	function message($message, $type) {
		return '<div class="container alert ' . $type . ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>' . PHP_EOL;
	}
?>