<?php include APP_VIEWS_PATH . 'header.php'; ?>

<form class="form-horizontal" method="POST" action="register.php">
 
    <legend>Register</legend>


    <div class="form-group">
    <label for="inputFirstName" class="col-lg-2 control-label">FirstNameName</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputFirstName" placeholder="First Name">
  	</div>
  	</div>

  	<div class="form-group">
    <label for="inputLastName" class="col-lg-2 control-label">LastName</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputLastName" placeholder="Last Name">
  	</div>
  	</div>

  	<div class="form-group">
    <label for="inputName" class="col-lg-2 control-label">Username</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputUsername" placeholder="Username">
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
    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>

   <button type="submit" class="btn btn-default">Submit</button>
  
</form>


<?php include APP_VIEWS_PATH . 'footer.php'; ?>



