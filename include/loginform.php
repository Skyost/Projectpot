		<div class="container">
			<div class="well centered">
				<?=title('Login')?>
			</div>
		</div>
		<div class="container">
			<form action="admin.php" method="post">
				<div class="form-group">
					<label for="username"><?=PP_USERNAME?></label>
					<input name="username" type="text" class="form-control" placeholder="<?=htmlspecialchars(PP_USERNAME)?>">
				</div>
				<div class="form-group">
					<label for="password"><?=PP_PASSWORD?></label>
					<input name="password" type="password" class="form-control" placeholder="<?=htmlspecialchars(PP_PASSWORD)?>">
				</div>
				<input name="method" type="hidden" value="0">
				<button type="submit" class="btn btn-primary"><?=PP_LOGIN?></button>
			</form>
		</div>
