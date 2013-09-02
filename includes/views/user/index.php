<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div class="page-header">
	<h2>Profile <small>Have a look at your profile</small></h2>
</div>

<div class="span4 well" style="text-align: center">
	<div class="row">

			<?php 
			$isemptyfirstn = $_[ 'user' ]->firstname;
			$isemptylastn = $_[ 'user' ]->lastname;
	   		?>

	   		<div id="avatar_profile">
	   		<?php
	    		echo get_gravatar( $_[ 'user' ]->user_email, 200, 'mm', 'g', true );
	   		?>
	   		</div>
			
			<div class="col-md-8">
			<h5 class="text-info">Username: </h5>
	   		<?php   	
	   		if (!empty($isemptyfirstn)) {
	   			echo $_[ 'user' ]->firstname;
	  		}
			?>
    			<br /><br />
	
			<h5 class="text-info">Username: </h5>
			<?php
			if (!empty($isemptylastn)) {
				echo $_[ 'user' ]->lastname;
			}
			?>
			<br /><br />

			<h5 class="text-info">Username: </h5><?php echo $_[ 'user' ]->user_login; ?><br /><br />
			<h5 class="text-info">Email-Adresse: </h5><?php echo $_[ 'user' ]->user_email; ?><br /><br />
			</div>

		</div>
	</div>
</div>



<?php include APP_VIEWS_PATH . 'footer.php'; ?>
