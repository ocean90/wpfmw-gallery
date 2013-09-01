<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div class="page-header">
	<h2>Profile <small>Have a look at your profile</small></h2>
</div>


<div class="span4 well">
	<div class="row">
		<div class="span3">
			<div style="algin: center">
			<?php
				echo get_gravatar(   $_[ 'user' ]->user_email, 200, 'mm', 'g', true );
			?>
			</div>
			<?php 
			if (!empty($_[ 'user' ]->firstname)) {
				echo $_[ 'user' ]->firstname;
			}
			?>

			<?php 
			if (!empty($_[ 'user' ]->lastname)) {
				echo $_[ 'user' ]->lastname;
			}
			?>
			
			<h5 class="text-info">Username: </h5><?php echo $_[ 'user' ]->user_login; ?><br><br>
			<h5 class="text-info">Email-Adresse: </h5><?php echo $_[ 'user' ]->user_email; ?><br><br>
			<?php echo $_[ 'user' ]->firstname; ?><br><br>

		</div>
	</div>
</div>



<?php include APP_VIEWS_PATH . 'footer.php'; ?>



