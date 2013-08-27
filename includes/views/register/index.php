<?php include APP_VIEWS_PATH . 'header.php'; ?>

<?php
// Check existing POST data and show them
$inputFirstName = '';
if ( ! empty( $_POST[ 'inputFirstName' ] ) ) {
  $inputFirstName = htmlspecialchars( $_POST[ 'inputFirstName' ], ENT_QUOTES );
}

$inputLastName = '';
if ( ! empty( $_POST[ 'inputLastName' ] ) ) {
  $inputLastName = htmlspecialchars( $_POST[ 'inputLastName' ], ENT_QUOTES );
}

$inputUserName = '';
if ( ! empty( $_POST[ 'inputUserName' ] ) ) {
  $inputUserName = htmlspecialchars( $_POST[ 'inputUserName' ], ENT_QUOTES );
}

$inputEmail = '';
if ( ! empty( $_POST[ 'inputEmail' ] ) ) {
  $inputEmail = htmlspecialchars( $_POST[ 'inputEmail' ], ENT_QUOTES );
}

$inputPassword = '';
if ( ! empty( $_POST[ 'inputPassword' ] ) ) {
  $inputPassword = htmlspecialchars( $_POST[ 'inputPassword' ], ENT_QUOTES );
}

$inputSecondPassword = '';
if ( ! empty( $_POST[ 'inputSecondPassword' ] ) ) {
  $inputSecondPassword = htmlspecialchars( $_POST[ 'inputSecondPassword' ], ENT_QUOTES );
}

// Check for errors
$FirstName_extra = $LastName_extra = $UserName_extra = $Email_extra = $Password_extra = $SecondPassword_extra = '';


if ( ! empty( $_[ 'error' ] ) ) {
  ?>
  <div class="alert alert-danger">Sorry, there was an error. Please check the highlighted fields.</div>
  <?php

  if ( in_array( 'emptypassword', $_[ 'error' ] ) || in_array( 'wrongpassword', $_[ 'error' ] ) ) {
    $password_extra = ' has-error';
  }

  if ( in_array( 'emptylogin', $_[ 'error' ] ) || in_array( 'nouser', $_[ 'error' ] ) ) {
    $user_extra = ' has-error';
  }
}
?>

<form class="form-horizontal" method="POST" action="register.php">
 
    <legend>Register</legend>


    <div class="form-group">
    <label for="inputFirstName" class="col-lg-2 control-label">First Name</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputFirstName" placeholder="First Name">
  	</div>
  	</div>

  	<div class="form-group">
    <label for="inputLastName" class="col-lg-2 control-label">Last Name</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputLastName" placeholder="Last Name">
  	</div>
  	</div>

  	<div class="form-group">
    <label for="inputUserName" class="col-lg-2 control-label">Username</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputUserName" placeholder="Username">
  	</div>
  	</div>

    <div class="form-group">
    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputEmail" placeholder="Email">
  	</div>
  	</div>
  
  <div class="form-group">
    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>

  <div class="form-group">
    <label for="inputSecondPassword" class="col-lg-2 control-label">Password again</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputSecondPassword" placeholder="Password again">
    </div>
  </div>

   <button type="submit" class="btn btn-default">Submit</button>
  
</form>


<?php include APP_VIEWS_PATH . 'footer.php'; ?>



