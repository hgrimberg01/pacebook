<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="<?php echo $author;?>">

<title>

<?php echo $title;?>


</title>

<!-- Bootstrap core CSS -->


<link
	href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css"
	rel="stylesheet">



<script
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script
	src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>


<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<!-- Favicons -->



</head>
<body>
	<div class="container">
		<nav class="navbar navbar-default" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">CheGriBook</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/home/">Home</a></li>

					<li><a href="/friends/">Friends</a></li>
					<li><a href="/networks/">Networks</a></li>
					
					<?php if($perm_level >= 3){ ?>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Admin<b class="caret"></b></a>
						<ul class="dropdown-menu">


							<li><a href="#">Network Management</a></li>
							<li class="divider"></li>
							<li><a href="#">User Management</a></li>
							<li class="divider"></li>

							<li><a href="#">Settings</a></li>
							<li class="divider"></li>


						</ul></li>
						
							<?php }?>
				</ul>
				<form class="navbar-form navbar-right" role="search">
					<div class="form-group">
						<input type="text" class="form-control"
							placeholder="Find a Person/Network">
					</div>
					<button type="submit" class="btn btn-default">Go</button>
				</form>
				<?php if($loggedIn){?>
				<p class="navbar-text navbar-right">
					Signed in as <a href="/profile/<?php echo $username ?>"
						class="navbar-link"><?php echo $name ?> </a>
				</p>

				<p class="navbar-text navbar-right">
					<a href="/auth/logout/">Sign Out</a>
				</p>
				
				
				<?php }else{?>
			<p class="navbar-text navbar-right">
					<a href="/auth/" class="navbar-link">Sign In </a>
				</p>
				
				<?php }?>
				
				
			</div>
			<!-- /.navbar-collapse -->
		</nav>