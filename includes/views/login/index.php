<?php include APP_VIEWS_PATH . 'header.php'; ?>

<form class="form-horizontal" method="POST" action="login.php">
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
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>





<?php include APP_VIEWS_PATH . 'footer.php'; ?>


