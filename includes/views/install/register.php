<?php include APP_VIEWS_PATH . 'header.php'; ?>

<div class="jumbotron">
	<h1>Installation</h1>

	<div class="progress progress-striped">
		<div class="progress-bar" style="width: 66.6666%;"></div>
	</div>

	<p>Please fill in your data here. It's used for the admin account!</p>

	<form method="post" class="form-horizontal install-register-form clearfix">
		<div class="form-group">
			<label for="username" class="col-lg-3 control-label">Username</label>
			<div class="col-lg-5">
				<input type="text" class="form-control input-lg" id="username" name="username" placeholder="Enter username" maxlength="60">
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-lg-3 control-label">Email</label>
			<div class="col-lg-5">
				<input type="email" class="form-control input-lg" id="email" name="email" placeholder="Enter email" maxlength="100">
			</div>
		</div>
		<div class="form-group">
			<label for="password1" class="col-lg-3 control-label">Password</label>
			<div class="col-lg-5">
				<input type="password" class="form-control input-lg" id="password1" name="password1" placeholder="Enter password" maxlength="100">
			</div>
		</div>
		<div class="form-group">
			<label for="password2" class="col-lg-3 control-label">Repeat Password</label>
			<div class="col-lg-5">
				<input type="password" class="form-control input-lg" id="password2" name="password2" placeholder="Enter password again" maxlength="100">
			</div>
		</div>
		<div class="col-lg-8">
			<button type="submit" class="btn btn-primary btn-lg pull-right">Submit</button>
		</div>
	</form>
</div>

<?php include APP_VIEWS_PATH . 'header.php'; ?>
