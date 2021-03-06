
<h2>My Networks <a href="/networks/join"><small> Join Networks</small></a></h2>
<div class="row">

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Current Networks Memberships</div>
			<div class="panel-body">

				<table id="network_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Date Created</th>
							<th>Members</th>
							<th>Leave?</th>


						</tr>
					</thead>
					<tbody>
			<?php if (empty($currentNetworks)) {?>
				<tr><td colspan="4">You have no network memberships.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $currentNetworks as $iNetwork ) { ?>
				
				 <tr data-nid="<?php  echo $iNetwork->networkID; ?>">
							
							
							<td><?php  echo $iNetwork->name ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iNetwork->cDate)); ?>
							</td>
							<td><?php echo $iNetwork->numMembers; ?></td>
							<td><a class="iLeaveNetwork" href="/networks/leave/<?php echo $iNetwork->networkID; ?>">Leave</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Network Join Requests</div>
			<div class="panel-body">

				<table id="network_join_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Requested On</th>
							<th>Status</th>							
							<th>Cancel?</th>

						</tr>
					</thead>
					<tbody>
			<?php if (empty($networkRequests)) {?>
				<tr><td colspan="3">You have no pending network membership requests.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $networkRequests as $jNetwork ) { ?>
				
				 		<tr data-nid="<?php  echo $jNetwork->networkID; ?>">
							<td><?php echo $jNetwork->name; ?></td>
							<td><?php echo date("F d, Y g:i A", strtotime($jNetwork->reqDate)); ?></td>
							<td><?php echo $jNetwork->status; ?></td>
							<td><a class="iCancelJoin" href="/networks/leave/<?php echo $jNetwork->networkID; ?>">
							<?php 
								if ($jNetwork->status == "Pending") {
									echo "Cancel";
								} else {
									echo "Clear";
								}
							?>
							</a></td>
						</tr>
				
			 <?php } } ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Network Approval Requests</div>
			<div class="panel-body">

				<table id="network_approve_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Created On</th>
							<th>Status</th>
							<th>Cancel?</th>

						</tr>
					</thead>
					<tbody>
			<?php if (empty($approveRequests)) {?>
				<tr><td colspan="4">You have no networks pending approval.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $approveRequests as $aNetwork ) { ?>
				
				 		<tr data-nid="<?php  echo $aNetwork->networkID; ?>">
							<td><?php echo $aNetwork->name; ?></td>
							<td><?php echo date("F d, Y g:i A", strtotime($aNetwork->cDate)); ?></td>
							<td><?php echo $aNetwork->status; ?></td>
							<td><a class="iCancelNetwork" href="/networks/cancel/<?php echo $aNetwork->networkID; ?>">
							<?php 
								if ($aNetwork->status == "Pending") {
									echo "Cancel";
								} else {
									echo "Clear";
								}
							?>
							</a></td>
						</tr>
				
			 <?php } } ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Create a Network</div>
			<div class="panel-body">
				<div class="row"></div>
				<form role="form" action="/networks/" method="POST">
					<div class="form-group">
						<label for="nName">Network Name</label> <input class="form-control"
							id="nName" name="nName" placeholder="Enter network name">
						<label for="nDesc">Network Description</label> <input class="form-control"
							id="nDesc" name="nDesc" placeholder="Enter network description">
					</div>

					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
	</div>





</div>
