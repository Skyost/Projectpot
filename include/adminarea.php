<?php
	$categories = load_categories();
	$projects = load_projects();
	$settings = load_settings();
	if(isset($_POST['method'])) {
		if($_POST['method'] == 0 && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['keywords']) && isset($_POST['author'])) {
			$settings = merge($settings, 'settings', array(
				'metaTitle' => htmlspecialchars($_POST['title']),
				'metaDescription' => htmlspecialchars($_POST['description']),
				'metaKeywords' => htmlspecialchars($_POST['keywords']),
				'metaAuthor' => htmlspecialchars($_POST['author'])
			), 'Successfully changed website\'s meta !');
		}
		else if($_POST['method'] == 1) {
			$settings = merge($settings, 'settings', isset($_POST['enable']) ? array('adflyUse' => true, 'adflyId' => htmlspecialchars($_POST['id'])) : array('adflyUse' => false), 'Successfully changed adf.ly preferences !');
		}
		else if($_POST['method'] == 2) {
			$categories = merge($categories, 'categories', array(htmlspecialchars($_POST['name'])), 'Category added !');
		}
		else if($_POST['method'] == 3) {
			if(count($categories) == 1) {
				echo(message('You cannot delete the last category !', 'alert-danger'));
			}
			else {
				$categories = remove($categories, 'categories', $_POST['category'], 'Category removed.');
				$categories_clone = array_merge(array(), $categories);
				$projects_changed = false;
				for($i = 0; $i < count($projects); $i++) {
					if($projects[$i]['category'] != $_POST['category']) {
						continue;
					}
					$projects = remove($projects, null, $i, null, false);
					$projects_changed = true;
				}
				if($projects_changed) {
					finish($projects, 'projects', null);
				}
			}
		}
		else if($_POST['method'] == 4) {
			$categories[$_POST['category']] = $_POST['newname'];
			finish($categories, 'categories', 'Category renamed.');
		}
		else if($_POST['method'] == 5) {
			$parsed_url = parse_url($_POST['link']);
			if(empty($parsed_url['scheme'])) {
				$_POST['link'] = 'http://' . $_POST['link'];
			}
			$projects = merge($projects, 'projects', array(array(
				'name' => htmlspecialchars($_POST['name']),
				'description' => htmlspecialchars($_POST['description']),
				'link' => htmlspecialchars($_POST['link']),
				'category' => htmlspecialchars($_POST['category'])
			)), 'Project added !', true, true);
		}
		else if($_POST['method'] == 6) {
			$projects = remove($projects, 'projects', $_POST['project'], 'Project removed.');
		}
		else if($_POST['method'] == 7) {
			$projects[$_POST['project']] = array(
				'name' => htmlspecialchars($_POST['name']),
				'description' => htmlspecialchars($_POST['description']),
				'link' => htmlspecialchars($_POST['link']),
				'category' => htmlspecialchars($_POST['category'])
			);
			finish($projects, 'projects', 'Project edited !', true);
		}
	}
	$categories_num = count($categories);
	$projects_num = count($projects);
	
	function merge($main_array, $file, $array, $message = null, $finish = true, $sort = false) {
		$main_array = array_merge($main_array, $array);
		if($finish) {
			finish($main_array, $file, $message, $sort);
		}
		return $main_array;
	}
	
	function remove($main_array, $file, $remove_index, $message = null, $finish = true) {
		unset($main_array[$remove_index]);
		$main_array = array_values($main_array);
		if($finish) {
			finish($main_array, $file, $message);
		}
		return $main_array;
	}
	
	function finish($main_array, $file, $message = null, $sort = false) {
		if($sort) {
			usort($main_array, 'compare_projects_names');
		}
		file_put_contents('json/' . $file . '.json', json_encode($main_array));
		if($message != null) {
			echo message($message, 'alert-success');
		}
	}
	
	function compare_projects_names($project_a, $project_b) {
        return strcmp(strtolower($project_a['name']), strtolower($project_b['name']));
	}
	
	// echo(message('<strong>Be aware !</strong> Characters <strong>are not</strong> escaped. Be cautious about what you enter.', 'alert-warning'));
?>
		<link rel="stylesheet" href="assets/css/adminarea.css">
		<script type="text/javascript">
			var projects = {};
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '			projects["' . $i . '"] = "' . str_replace('"', '\"', json_encode($projects[$i])) . '";' . PHP_EOL;
	}
?>
		</script>
		<div class="container">
			<div class="well centered">
				<?=title('Admin Area')?>
			</div>
		</div>
		<div class="container">
			<h2><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Website configuration</h2>
			<a for="website-container" collapsed="1" class="expander"><img src="assets/img/expand.png"/> Expand</a>
			<div id="website-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Site meta</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="website-meta-title">Title</label>
							<input id="website-meta-title" name="title" type="text" class="form-control" placeholder="Title" value="<?=$settings['metaTitle']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-description">Description</label>
							<input id="website-meta-description" name="description" type="text" class="form-control" placeholder="Description" value="<?=$settings['metaDescription']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-keywords">Keywords</label>
							<input id="website-meta-keywords" name="keywords" type="text" class="form-control" placeholder="Keywords" value="<?=$settings['metaKeywords']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-author">Author</label>
							<input id="website-meta-author" name="author" type="text" class="form-control" placeholder="Author" value="<?=$settings['metaAuthor']?>">
						</div>
						<input name="method" type="hidden" value="0">
						<button type="submit" class="btn btn-primary">Update !</button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> AdFly</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="website-adfly-enable">Enable</label>
							<input id="website-adfly-enable" name="enable" type="checkbox" class="checkbox"<?=$settings['adflyUse'] ? ' checked="checked"' : ''?>>
						</div>
						<div class="form-group">
							<label for="website-adfly-id">ID</label>
							<input id="website-adfly-id" name="id" type="text" class="form-control" placeholder="ID" value="<?=$settings['adflyId']?>">
						</div>
						<input name="method" type="hidden" value="1">
						<button type="submit" class="btn btn-primary">Update !</button>
					</form>
				</div>
			</div>
			<h2><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Categories</h2>
			<a for="categories-container" collapsed="1" class="expander"><img src="assets/img/expand.png"/> Expand</a>
			<div id="categories-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a category</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-add-name">Name</label>
							<input id="categories-add-name" name="name" type="text" class="form-control" placeholder="Name">
						</div>
						<input name="method" type="hidden" value="2">
						<button type="submit" class="btn btn-success">Add !</button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove a category</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-remove-category">Category</label>
							<select id="categories-remove-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<input name="method" type="hidden" value="3">
						<button type="submit" class="btn btn-danger">Remove !</button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Rename a category</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-rename-category">Category</label>
							<select id="categories-rename-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="categories-rename-newname">New name</label>
							<input id="categories-rename-newname" name="newname" type="text" class="form-control" placeholder="New name">
						</div>
						<input name="method" type="hidden" value="4">
						<button type="submit" class="btn btn-primary">Rename !</button>
					</form>
				</div>
			</div>
			<h2><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Projects</h2>
			<a for="projects-container" collapsed="1" class="expander"><img src="assets/img/expand.png"/> Expand</a>
			<div id="projects-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create a project</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-create-name">Name</label>
							<input id="projects-create-name" name="name" type="text" class="form-control" placeholder="Name">
						</div>
						<div class="form-group">
							<label for="projects-create-category">Category</label>
							<select id="projects-create-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-create-link">Link</label>
							<input id="projects-create-link" name="link" type="text" class="form-control" placeholder="Link">
						</div>
						<div class="form-group">
							<label for="projects-create-description">Description</label>
							<textarea class="jquery-te" id="projects-create-description" name="description"></textarea>
						</div>
						<input name="method" type="hidden" value="5">
						<button type="submit" class="btn btn-success">Create !</button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove a project</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-remove-name">Project</label>
							<select id="projects-remove-name" name="project" class="form-control">
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '								<option value="' . $i . '">' . $projects[$i]['name'] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<input name="method" type="hidden" value="6">
						<button type="submit" class="btn btn-danger">Remove !</button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit a project</h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-edit-oldname">Project</label>
							<select id="projects-edit-oldname" name="project" class="form-control">
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '								<option value="' . $i . '">' . $projects[$i]['name'] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-edit-newname">New name</label>
							<input id="projects-edit-newname" name="name" type="text" class="form-control" placeholder="New name">
						</div>
						<div class="form-group">
							<label for="projects-edit-newcategory">New category</label>
							<select id="projects-edit-newcategory" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-edit-newlink">New link</label>
							<input id="projects-edit-newlink" name="link" type="text" class="form-control" placeholder="New link">
						</div>
						<div class="form-group">
							<label for="projects-edit-newdescription">New description</label>
							<textarea class="jquery-te" id="projects-edit-newdescription" name="description"></textarea>
						</div>
						<input name="method" type="hidden" value="7">
						<button type="submit" class="btn btn-primary">Edit !</button>
					</form>
				</div>
			</div>
		</div>
		<div class="navbar navbar-default navbar-fixed-bottom" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a id="update-link" target="_blank" href="<?='https://github.com/' . PP_APP_AUTHOR . '/' . PP_APP_NAME . '/releases'?>"><img src="assets/img/loading.gif"/> <i>Checking for updates...</i></a></li>
					<li><a id="logout-link" href="#"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
				</ul>
			</div>
		</div>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css">
		<script type="text/javascript" src="assets/js/adminarea.js"></script>
		<script type="text/javascript">
			var updateLink = $('#update-link');
			
			var request = $.get('<?='https://cdn.rawgit.com/' . PP_APP_AUTHOR . '/' . PP_APP_NAME . '/master/version.update'?>', function(data) {
				var remoteVersion = $.trim(data);
				if(cmpVersion('<?=PP_APP_VERSION?>', remoteVersion) == -1) {
					updateLink.addClass('update-available');
					updateLink.html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> An update is available (v' + remoteVersion + ')');
				}
				else {
					updateLink.addClass('update-noupdate');
					updateLink.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> You have the latest version (v' + remoteVersion + ')');
				}
			});
			request.fail(function() {
				updateLink.addClass('update-error');
				updateLink.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> An error occurred while checking for updates');
			});
		</script>
