<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div class="page-header">
	<h2>Profile</h2>
</div>

<p>Username: <?php echo $_[ 'user' ]->user_login; ?></p><br>
<p>Email-Adresse: <?php echo $_[ 'user' ]->user_email; ?></p><br>
<p>Gravatar:<p>

<?php
echo get_gravatar(   $_[ 'user' ]->user_email, 400, 'mm', 'g', true );
?>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



