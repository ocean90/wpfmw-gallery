<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Home</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
      <li><a href="#">Pictures</a></li>
      <li><a href="#">Placeholder</a></li>
    </ul>
    <form class="navbar-form navbar-left" role="search">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Search">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="/register">Sign up</a></li>   
		<li class="divider-vertical"></li>
			<li class="dropdown">
				<a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
				<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
					<form method="post" action="login" accept-charset="UTF-8">
						<input style="margin-bottom: 15px;" type="text" placeholder="Username" id="username" name="username">
						<input style="margin-bottom: 15px;" type="password" placeholder="Password" id="password" name="password">
						<input style="float: left; margin-right: 10px;" type="checkbox" name="remember-me" id="remember-me" value="1">
						<label class="string optional" for="user_remember_me"> Remember me</label>
						<input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In">
						<label style="text-align:center;margin-top:5px"></label>
                 	</form>
				</div>
			</li>
      	</li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>