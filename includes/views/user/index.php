<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<?php
echo get_gravatar(   $_[ 'user' ]->user_email, 200, 'mm', 'g', true );
?>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



