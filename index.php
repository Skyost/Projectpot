<?php
	require('include/config.php');
	require('include/functions.php');
	$settings = load_settings();
	include('include/languages/' . $settings['metaLanguage'] . '.php');
	if(PP_TRANSLATION_VERSION < 2) {
		die('Please update your translation ! Otherwise, you will get a lot of error messages. Check "https://github.com/' . PP_APP_AUTHOR . '/' . Projectpot . '/tree/master/include/languages".');
	}
	$categories = load_categories();
	$categories_num = count($categories);
	if($categories_num == 0) {
		die(PP_NOCATEGORY);
	}
	$projects = load_projects();
	$adfly = $settings['adflyUse'] && (isset($_COOKIE['pp_adfly']) ? $_COOKIE['pp_adfly'] : true);
?>
<!DOCTYPE html>
<html lang="<?=$settings['metaLanguage']?>">
	<head>
		<title><?=$settings['metaTitle']?></title>
		<meta name="description" content="<?=$settings['metaDescription']?>">
		<meta name="keywords" content="<?=$settings['metaKeywords']?>">
		<meta name="author" content="<?=$settings['metaAuthor']?>">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="generator" content="<?=PP_APP_NAME?>">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/global.css">
		<link rel="stylesheet" href="assets/css/index.css">
		<link rel="icon" href="assets/favicon.ico" type="image/x-icon">
		<!--[if IE]><link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon"/><![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="well centered">
				<?=title($settings['metaTitle'])?>
			</div>
		</div>
		<div class="container">
			<div class="btn-group btn-group-justified">
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '				<a id="button-' . $i . '" class="btn btn-primary' . ($i == 0 ? ' active' : '') . '" role="button">' . $categories[$i] . '</a>' . PHP_EOL;
	}
?>
			</div>
		</div>
		<div class="container" id="project-container">
			<table class="table table-bordered table-hover">
				<thead>
					<?='<tr><th>' . PP_PROJECT_NAME . '</th><th>' . PP_PROJECT_LINK . '</th></tr>' . PHP_EOL?>
				</thead>
<?php
	for($i = 0; $i < $categories_num; $i++) {
		echo '				<tbody id="tbody-' . $i . '"' . ($i == 0 ? '' : ' class="hidden"') . '>' . PHP_EOL;
		$projects_in_category = 0;
		foreach($projects as $project) {
			if($project['category'] == $i) {
				$projects_in_category++;
				echo '					<tr><td class="a-description" category="' . $i . '" description="' . $project['description'] . '">' . $project['name'] . '</td><td><a target="_blank" href="goto.php?category=' . $i . '&project=' . $project['name'] . '&adfly=' . $adfly . '">' . $project['link'] . '</a></td></tr>' . PHP_EOL;
			}
		}
		if($projects_in_category++ == 0) {
			echo '					<tr><td>' . PP_NOPROJECT . '</td><td>' . PP_NOLINK . '</td></tr>' . PHP_EOL;
		}
		echo '				</tbody>' . PHP_EOL;
	}
?>
			</table>
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
					<li><a target="_blank" href="<?='http://' . PP_APP_AUTHOR . '.github.io/' . PP_APP_NAME?>"><img src="assets/favicon.ico"/> <strong><?=PP_POWEREDBY?></strong></a></li>
					<li><a href="admin.php"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?=PP_ADMIN?></a></li>
					<?=$settings['adflyUse'] ? '<li><a id="toggle-adfly" href="#"></a></li>' . PHP_EOL : PHP_EOL?>
				</ul>
			</div>
		</div>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script type="text/javascript" src="assets/js/global.js"></script>
		<script type="text/javascript" src="assets/js/index.js"></script>
<?php
	if($settings['adflyUse']) {
?>
		<script type="text/javascript">
			var toggleAdfly = $('#toggle-adfly');
			var adflyEnabled;
			
			$(document).ready(function() {
				if($.cookie('pp_adfly') == undefined) {
					$.cookie('pp_adfly', '1');
				}
				adflyEnabled = $.cookie('pp_adfly') == '1';
				toggleAdfly.html('<span class="glyphicon ' + (adflyEnabled ? 'glyphicon-minus" aria-hidden="true"></span> <?=htmlspecialchars(PP_DISABLE_ADFLY)?>' : 'glyphicon-plus" aria-hidden="true"></span> <?=PP_ENABLE_ADFLY?>'));
			});
			
			toggleAdfly.click(function() {
				$.cookie('pp_adfly', adflyEnabled ? '0' : '1');
				location.reload();
			});
		</script>
<?php
	}
?>
		<div id="modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 id="modal-title" class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<span id="modal-text"></span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>