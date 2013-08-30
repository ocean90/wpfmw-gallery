<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>


<form class="form-horizontal" method="POST" action="register.php">

    <legend>Profile Changes</legend>

    <div class="form-group">
    <label for="inputFirstName" class="col-lg-2 control-label">Firstname*</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" id="inputFirstname" placeholder="Firstname">
      </div>
    </div>

   <div class="form-group">
    <label for="inputLastName" class="col-lg-2 control-label">Lastname*</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" id="inputLastname" placeholder="Lastname">
      </div>
    </div>

    <div class="form-group">
    <label for="inputName" class="col-lg-2 control-label">Username</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" id="inputUsername" value="<?php echo $_[ 'user' ]->user_login; ?>">
      </div>
    </div>

    <div class="form-group">
    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
    <div class="col-lg-4">
      <input type="text" class="form-control" id="inputEmail" value="<?php echo $_[ 'user' ]->user_email; ?>">
    </div>
    </div>

  <div class="form-group">
    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>

  <div class="form-group">
    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>

   <button type="submit" class="btn btn-default">Update</button>

</form>
<?php 
// Check for errors
if ( ! empty( $_[ 'error' ] ) ) {
  ?>
  <div class="alert alert-danger">Sorry, there was an error. Please check the highlighted fields.</div>
  <?php

  if (in_array( 'invalidusername', $_[ 'error' ] ) || in_array( 'usernameexists', $_[ 'error' ] ) ) {
    $username_extra = ' has-error';
  }

  if ( in_array( 'emptyemail', $_[ 'error' ] ) || in_array( 'invalidemail', $_[ 'error' ] ) || in_array( 'mailexists', $_[ 'error' ] ) ) {
    $email_extra = ' has-error';
  }

  if ( in_array( 'emptypassword', $_[ 'error' ] ) || in_array( 'passwordmismatch', $_[ 'error' ] ) ) {
    $password_extra = ' has-error';
  }
}
?>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



