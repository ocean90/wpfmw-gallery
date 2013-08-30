<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<legend>Your Profile</legend>

<p>Username: <?php echo $_[ 'user' ]->user_login; ?></p><br>
<p>Email-Adresse: <?php echo $_[ 'user' ]->user_email; ?></p><br>
<p>Gravatar:<p>

<?php
echo get_gravatar(   $_[ 'user' ]->user_email, 200, 'mm', 'g', true );

?>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



