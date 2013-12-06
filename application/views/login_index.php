

<?php
$prefix = '<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert"
		aria-hidden="true">&times;</button><strong>Error: </strong>';

$suffix = '</div>';

?>

<div class="page-header">
	<h1>Please Login</h1>
</div>



<?php

echo validation_errors ( $prefix, $suffix );

if (isset ( $bad_login )) {
	
	?>
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert"
		aria-hidden="true">&times;</button>
	<strong>Error: Invalid username or password</strong>;

</div>
<?php
}
?>



<form role="form" action="/auth/" method="POST">
	<div class="form-group">
		<label for="user">Email address or Username</label> <input
			class="form-control" id="user" name="user"
			value="<?php echo set_value('user'); ?>"
			placeholder="Enter email or username.">
	</div>
	<div class="form-group">
		<label for="password">Password</label> <input type="password"
			class="form-control" id="password" name="password"
			placeholder="Password">
	</div>

	<div class="checkbox">
		<label> <input name="extended" id="extended" value="true" type="checkbox" checked> Remember Me
		</label>
	</div>
	<button type="submit" class="btn btn-default">Submit</button>
</form>
