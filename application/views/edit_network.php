
<h2>Edit Network</h2>
<div class="row">
	
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Edit a Network</div>
			<div class="panel-body">
				<div class="row"></div>
				<form role="form" action="/networks/edit/<?php echo $network->nid; ?>" method="POST">
					<div class="form-group">
						<label for="nName">Network Name</label> <input class="form-control"
							id="nName" name="nName" placeholder="Enter network name" value="<?php echo set_value('nName', $network->networkName); ?>" >
						<label for="nDesc">Network Description</label> <input class="form-control"
							id="nDesc" name="nDesc" placeholder="Enter network description" value="<?php echo set_value('nDesc', $network->networkDesc); ?>" >
					</div>

					<button type="submit" class="btn btn-default">Edit</button>
					<button type="button" class="btn" onclick="location.href='/networks/manage/'">Cancel</button>
				</form>
			</div>
		</div>
	</div>
	
</div>
