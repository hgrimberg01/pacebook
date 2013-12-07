<?php 
// THIS IS JUST A REALLY ROUGH DRAFT. NOT SURE WHAT TO GO OFF OF YET
?>


<?php
$prefix = '<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert"
		aria-hidden="true">&times;</button><strong>Error: </strong>';

$suffix = '</div>';

?>





<form role="form" action="/auth/network/" method="POST">
	<div class="form-group">
		<label for="email">Email Address</label> <input class="form-control"
			id="email" name="email" type="email"
			value="<?php echo set_value('email'); ?>" placeholder="Email"> <label
			for="email">Username</label> <input class="form-control"
			id="username" name="username"
			value="<?php echo set_value('username'); ?>" placeholder="Username">

		<label for="firstName">First Name</label> <input class="form-control"
			id="firstName" name="firstName"
			value="<?php echo set_value('firstName'); ?>"
			placeholder="First Name"> <label for="lastName">Last Name</label> <input
			class="form-control" id="lastName" name="lastName"
			value="<?php echo set_value('lastName'); ?>" placeholder="Last Name">

		<label for="password">Password</label> <input type="password"
			class="form-control" id="password" name="password"
			placeholder="Password"> <label for="password2">Confirm Password</label>
		<input type="password" class="form-control" id="password2"
			name="password2" placeholder="Confirm Password"> <label for="nets">Networks</label>
		<select multiple class="form-control" id="nets" name="nets[]">
			
			<?php
			foreach ( $nets as $net ) {
				?>
<option value="<?php echo $net->networkID ;?>"><?php echo $net->networkName ;?></option>
<?php
			}
			
			?>
			
		</select>



		<div class="checkbox">
			<label> <input name="superuser" id="superuser" value="true"
				type="checkbox" checked> I am Staff?
			</label>
		</div>

	</div>
	<button type="submit" class="btn  btn-primary btn-lg">Register</button>
</form>

