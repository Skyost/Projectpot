<?php
	$categories = load_categories();
	$projects = load_projects();
	if(isset($_POST['method'])) {
		if($_POST['method'] == 1 && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['keywords']) && isset($_POST['author'])) {
			$settings = merge($settings, 'settings', array(
				'metaTitle' => htmlspecialchars($_POST['title']),
				'metaDescription' => htmlspecialchars($_POST['description']),
				'metaKeywords' => htmlspecialchars($_POST['keywords']),
				'metaAuthor' => htmlspecialchars($_POST['author']),
				'metaLanguage' => htmlspecialchars($_POST['language'])
			), PP_MESSAGE_METACHANGED);
		}
		else if($_POST['method'] == 2) {
			$settings = merge($settings, 'settings', isset($_POST['enable']) ? array('adflyUse' => true, 'adflyId' => htmlspecialchars($_POST['id'])) : array('adflyUse' => false), PP_MESSAGE_ADFLYCHANGED);
		}
		else if($_POST['method'] == 3) {
			$categories = merge($categories, 'categories', array(htmlspecialchars($_POST['name'])), PP_MESSAGE_CATEGORYADDED);
		}
		else if($_POST['method'] == 4) {
			if(count($categories) == 1) {
				echo(message(PP_MESSAGE_CANNOTREMOVE, 'alert-danger'));
			}
			else {
				$categories = remove($categories, 'categories', $_POST['category'], PP_MESSAGE_CATEGORYREMOVED);
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
		else if($_POST['method'] == 5) {
			$categories[$_POST['category']] = $_POST['newname'];
			finish($categories, 'categories', PP_MESSAGE_CATEGORYRENAMED);
		}
		else if($_POST['method'] == 6) {
			if(get_project($_POST['category'], $_POST['name']) != null) {
				echo(message(PP_MESSAGE_SAMENAMEEXISTS, 'alert-danger'));
			}
			else {
				$_POST['link'] = correct_link($_POST['link']);
				$projects = merge($projects, 'projects', array(array(
					'name' => htmlspecialchars($_POST['name']),
					'description' => htmlspecialchars($_POST['description']),
					'link' => htmlspecialchars($_POST['link']),
					'category' => htmlspecialchars($_POST['category'])
				)), PP_MESSAGE_PROJECTADDED, true, true);
			}
		}
		else if($_POST['method'] == 7) {
			$projects = remove($projects, 'projects', $_POST['project'], PP_MESSAGE_PROJECTREMOVED);
		}
		else if($_POST['method'] == 8) {
			if($_POST['old-category'] != $_POST['category']) {
				foreach($projects as $project) {
					if($project['name'] != $_POST['name']) {
						continue;
					}
					echo(message(PP_MESSAGE_SAMENAMEEXISTS, 'alert-danger'));
				}
			}
			else if($_POST['old-name'] != $_POST['name']) {
				foreach($projects as $project) {
					if(!($project['category'] == $_POST['category'] && $project['name'] != $_POST['old-name'] && $project['name'] == $_POST['name'])) {
						continue;
					}
					echo(message(PP_MESSAGE_SAMENAMEEXISTS, 'alert-danger'));
				}
			}
			else {
				$projects[$_POST['project']]['name'] = htmlspecialchars($_POST['name']);
				$projects[$_POST['project']]['description'] = htmlspecialchars($_POST['description']);
				$projects[$_POST['project']]['link'] = htmlspecialchars(correct_link($_POST['link']));
				$projects[$_POST['project']]['category'] = htmlspecialchars($_POST['category']);
				finish($projects, 'projects', PP_MESSAGE_PROJECTUPDATED, true);
			}
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
	
	function correct_link($link) {
		$parsed_url = parse_url($link);
		if(empty($parsed_url['scheme'])) {
			return 'http://' . $link;
		}
		return $link;
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
			<h2><span class="glyphicon glyphicon-home" aria-hidden="true"></span> <?=PP_WEBSITE_CONFIGURATION?></h2>
			<a for="website-container" collapsed="1" class="expander"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span> <?=PP_EXPAND?></a>
			<div id="website-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?=PP_WEBSITE_META?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="website-meta-title"><?=PP_WEBSITE_TITLE?></label>
							<input id="website-meta-title" name="title" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_WEBSITE_TITLE)?>" value="<?=$settings['metaTitle']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-description"><?=PP_WEBSITE_DESCRIPTION?></label>
							<input id="website-meta-description" name="description" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_WEBSITE_DESCRIPTION)?>" value="<?=$settings['metaDescription']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-keywords"><?=PP_WEBSITE_KEYWORDS?></label>
							<input id="website-meta-keywords" name="keywords" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_WEBSITE_KEYWORDS)?>" value="<?=$settings['metaKeywords']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-author"><?=PP_WEBSITE_AUTHOR?></label>
							<input id="website-meta-author" name="author" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_WEBSITE_AUTHOR)?>" value="<?=$settings['metaAuthor']?>">
						</div>
						<div class="form-group">
							<label for="website-meta-language"><?=PP_WEBSITE_LANGUAGE?></label>
							<select id="website-meta-language" name="language" class="form-control">
<?php
	$languages = array_diff(scandir('include/languages/'), array('..', '.'));
	if(in_array($settings['metaLanguage'] . '.php', $languages)) {
		echo '								<option>' . $settings['metaLanguage'] . '</option>' . PHP_EOL;
	}
	foreach($languages as $language) {
		if(str_endswith($language, '.php')) {
			$language = str_replace('.php', '', $language);
			if($language == $settings['metaLanguage']) {
				continue;
			}
			echo '								<option>' . $language . '</option>' . PHP_EOL;
		}
	}
?>
							</select>
						</div>
						<input name="method" type="hidden" value="1">
						<button type="submit" class="btn btn-primary"><?=PP_UPDATE?></button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> <?=PP_WEBSITE_ADFLY?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="website-adfly-enable"><?=PP_WEBSITE_ADFLY_ENABLE?></label>
							<input id="website-adfly-enable" name="enable" type="checkbox" class="checkbox"<?=$settings['adflyUse'] ? ' checked="checked"' : ''?>>
						</div>
						<div class="form-group">
							<label for="website-adfly-id"><?=PP_WEBSITE_ADFLY_ID?></label>
							<input id="website-adfly-id" name="id" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_WEBSITE_ADFLY_ID)?>" value="<?=$settings['adflyId']?>">
						</div>
						<input name="method" type="hidden" value="2">
						<button type="submit" class="btn btn-primary"><?=PP_UPDATE?></button>
					</form>
				</div>
			</div>
			<h2><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <?=PP_CATEGORIES?></h2>
			<a for="categories-container" collapsed="1" class="expander"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span> <?=PP_EXPAND?></a>
			<div id="categories-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?=PP_CATEGORIES_ADD?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-add-name"><?=PP_CATEGORIES_NAME?></label>
							<input id="categories-add-name" name="name" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_CATEGORIES_NAME)?>">
						</div>
						<input name="method" type="hidden" value="3">
						<button type="submit" class="btn btn-success"><?=PP_ADD?></button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=PP_CATEGORIES_REMOVE?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-remove-category"><?=PP_CATEGORIES_CATEGORY?></label>
							<select id="categories-remove-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<input name="method" type="hidden" value="4">
						<button type="submit" class="btn btn-danger"><?=PP_REMOVE?></button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <?=PP_CATEGORIES_RENAME?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="categories-rename-category"><?=PP_CATEGORIES_CATEGORY?></label>
							<select id="categories-rename-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="categories-rename-newname"><?=PP_CATEGORIES_RENAME_NEWNAME?></label>
							<input id="categories-rename-newname" name="newname" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_CATEGORIES_RENAME_NEWNAME)?>">
						</div>
						<input name="method" type="hidden" value="5">
						<button type="submit" class="btn btn-primary"><?=PP_UPDATE?></button>
					</form>
				</div>
			</div>
			<h2><span class="glyphicon glyphicon-th" aria-hidden="true"></span> <?=PP_PROJECTS?></h2>
			<a for="projects-container" collapsed="1" class="expander"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span> <?=PP_EXPAND?></a>
			<div id="projects-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?=PP_PROJECTS_ADD?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-create-name"><?=PP_PROJECTS_NAME?></label>
							<input id="projects-create-name" name="name" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_PROJECTS_NAME)?>">
						</div>
						<div class="form-group">
							<label for="projects-create-category"><?=PP_PROJECTS_CATEGORY?></label>
							<select id="projects-create-category" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-create-link"><?=PP_PROJECTS_LINK?></label>
							<input id="projects-create-link" name="link" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_PROJECTS_LINK)?>">
						</div>
						<div class="form-group">
							<label for="projects-create-description"><?=PP_PROJECTS_DESCRIPTION?></label>
							<textarea class="jquery-te" id="projects-create-description" name="description"></textarea>
						</div>
						<input name="method" type="hidden" value="6">
						<button type="submit" class="btn btn-success"><?=PP_ADD?></button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=PP_PROJECTS_REMOVE?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-remove-name"><?=PP_PROJECTS_PROJECT?></label>
							<select id="projects-remove-name" name="project" class="form-control">
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '								<option value="' . $i . '">' . $projects[$i]['name'] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<input name="method" type="hidden" value="7">
						<button type="submit" class="btn btn-danger"><?=PP_REMOVE?></button>
					</form>
				</div>
				<div class="container well">
					<h3><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <?=PP_PROJECTS_EDIT?></h3>
					<form action="admin.php" method="post">
						<div class="form-group">
							<label for="projects-edit-project"><?=PP_PROJECTS_PROJECT?></label>
							<select id="projects-edit-project" name="project" class="form-control">
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '								<option value="' . $i . '">' . $projects[$i]['name'] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-edit-newname"><?=PP_PROJECTS_NAME?></label>
							<input id="projects-edit-newname" name="name" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_PROJECTS_NAME)?>">
						</div>
						<div class="form-group">
							<label for="projects-edit-newcategory"><?=PP_PROJECTS_CATEGORY?></label>
							<select id="projects-edit-newcategory" name="category" class="form-control">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '								<option value="' . $i . '">' . $categories[$i] . '</option>' . PHP_EOL;
	}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="projects-edit-newlink"><?=PP_PROJECTS_LINK?></label>
							<input id="projects-edit-newlink" name="link" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_PROJECTS_LINK)?>">
						</div>
						<div class="form-group">
							<label for="projects-edit-newdescription"><?=PP_PROJECTS_DESCRIPTION?></label>
							<textarea class="jquery-te" id="projects-edit-newdescription" name="description"></textarea>
						</div>
						<input name="method" type="hidden" value="8">
						<input id="projects-edit-oldcategory" name="old-category" type="hidden">
						<input id="projects-edit-oldname" name="old-name" type="hidden">
						<button type="submit" class="btn btn-primary"><?=PP_UPDATE?></button>
					</form>
				</div>
			</div>
			<h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> <?=PP_STATS?></h2>
			<a for="statistics-container" collapsed="1" class="expander"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span> <?=PP_EXPAND?></a>
			<div id="statistics-container" class="shifted hidden">
				<div class="container well">
					<h3><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> <?=PP_STATS_PROJECTSTATS?></h3>
					<div class="form-group">
						<label for="statistics-projects-project"><?=PP_STATS_PROJECT?></label>
						<select id="statistics-projects-project" class="form-control">
<?php
	for($i = 0; $i < $projects_num; $i++) {
		echo '								<option value="' . $i . '">' . $projects[$i]['name'] . '</option>' . PHP_EOL;
	}
?>
						</select>
					</div>
					<div class="form-group">
						<label for="statistics-projects-projectclicks"><?=PP_STATS_PROJECTSTATS_DESCR?></label>
						<input id="statistics-projects-projectclicks" type="text" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label for="statistics-projects-linkclicks"><?=PP_STATS_PROJECTSTATS_LINK?></label>
						<input id="statistics-projects-linkclicks" type="text" class="form-control" readonly>
					</div>
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
					<li><a id="update-link" target="_blank" href="<?='https://github.com/' . PP_APP_AUTHOR . '/' . PP_APP_NAME . '/releases'?>"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <i><?=PP_UPDATE_CHECKING?></i></a></li>
					<li><a id="logout-link" href="#"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> <?=PP_LOGOUT?></a></li>
				</ul>
			</div>
		</div>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css">
		<script type="text/javascript" src="assets/js/global.js"></script>
		<script type="text/javascript" src="assets/js/adminarea.js"></script>
		<script type="text/javascript">
			var updateLink = $('#update-link');
			
			var request = $.get('https://api.github.com/repos/<?=PP_APP_AUTHOR . '/' . PP_APP_NAME . '/contents/version.update'?>', function(data) {
				var remoteVersion = $.trim(window.atob(data['content']));
				if(cmpVersion('<?=PP_APP_VERSION?>', remoteVersion) == -1) {
					updateLink.addClass('update-available');
					updateLink.html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?=str_replace('/version/', '\' + remoteVersion + \'', PP_UPDATE_AVAILABLE)?>');
				}
				else {
					updateLink.addClass('update-noupdate');
					updateLink.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=str_replace('/version/', '\' + remoteVersion + \'', PP_UPDATE_NOUPDATE)?>');
				}
			});
			request.fail(function() {
				updateLink.addClass('update-error');
				updateLink.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?=PP_UPDATE_ERROR?>');
			});
			
			$('.expander').click(function() {
				if($(this).attr('collapsed') == 1) {
					$(this).html('<span class="glyphicon glyphicon-collapse-down" aria-hidden="true"></span> <?=PP_COLLAPSE?>');
					$(this).attr('collapsed', 0);
				}
				else {
					$(this).html('<span class="glyphicon glyphicon-expand" aria-hidden="true"></span> <?=PP_EXPAND?>');
					$(this).attr('collapsed', 1);
				}
				$('#' + $(this).attr('for')).toggleClass('hidden');
			});
		</script>
