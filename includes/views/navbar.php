<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="#" name="top">Brand Name</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li><a href="#"><i class="icon-home"></i> Home</a></li>
					<li class="divider-vertical"></li>
					<li class="active"><a href="#"><i class="icon-file"></i> Pages</a></li>
					<li class="divider-vertical"></li>
					<li><a href="#"><i class="icon-envelope"></i> Messages</a></li>
					<li class="divider-vertical"></li>
                  	<li><a href="#"><i class="icon-signal"></i> Stats</a></li>
					<li class="divider-vertical"></li>
					<li><a href="#"><i class="icon-lock"></i> Permissions</a></li>
					<li class="divider-vertical"></li>
				</ul>
				<ul class="nav pull-right">
					<li><a href="/signup">Sign Up</a></li>
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
								<label style="text-align:center;margin-top:5px">or</label>
                                <input class="btn btn-primary btn-block" type="button" id="sign-in-google" value="Sign In with Google">
								<input class="btn btn-primary btn-block" type="button" id="sign-in-twitter" value="Sign In with Twitter">
							</form>
						</div>
					</li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
		<!--/.container-fluid -->
	</div>
	<!--/.navbar-inner -->
</div>
<!--/.navbar -->
 
<script>
$(document).ready(function()
{
  //Handles menu drop down
  $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
        });
  });
</script>
