		<div class="container">
			<div class="well centered">
				<?=title('Login')?>
			</div>
		</div>
		<div class="container">
			<form action="admin.php" method="post">
				<div class="form-group">
					<label for="username">Username</label>
					<input name="username" type="text" class="form-control" placeholder="Username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input name="password" type="password" class="form-control" placeholder="Password">
				</div>
				<input name="method" type="hidden" value="0">
				<button type="submit" class="btn btn-primary">Login !</button>
			</form>
		</div>
