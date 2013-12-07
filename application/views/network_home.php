
<h2>My Networks</h2>
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
				
				 <tr data-fid="<?php  echo $iNetwork->networkID; ?>">
							
							
							<td><?php  echo $iNetwork->name ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iNetwork->cDate)); ?>
							</td>
							<td><?php echo $iNetwork->numMembers; ?></td>
							<td>Leave</td>
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
							<th>Cancel?</th>

						</tr>
					</thead>
					<tbody>
			<?php if (empty($networkRequests)) {?>
				<tr><td colspan="3">You have no pending network membership requests.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $networkRequests as $jNetwork ) { ?>
				
				 		<tr>
							<td><?php echo $jNetwork->name; ?></td>
							<td><?php echo date("F d, Y g:i A", strtotime($jNetwork->reqDate)); ?></td>
							<td>Cancel</td>
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
							<th>Cancel?</th>

						</tr>
					</thead>
					<tbody>
			<?php if (empty($approveRequests)) {?>
				<tr><td colspan="3">You have no networks pending approval.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $approveRequests as $aNetwork ) { ?>
				
				 		<tr>
							<td><?php echo $aNetwork->name; ?></td>
							<td><?php echo date("F d, Y g:i A", strtotime($aNetwork->cDate)); ?></td>
							<td>Cancel</td>
						</tr>
				
			 <?php } } ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Create New Network</div>
			<div class="panel-body">

				<table id="network_create_panel" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Created On</th>

						</tr>
					</thead>
					<tbody>
			<?php if (empty($approveRequests)) {?>
				<tr><td colspan="2">You have no networks pending approval.</td></tr>
			
			<?php } else {?>		
			<?php  foreach ( $approveRequests as $aNetwork ) { ?>
				
				 		<tr>
							<td><?php echo $aNetwork->name; ?></td>
							<td><?php echo date("F d, Y g:i A", strtotime($aNetwork->cDate)); ?></td>
						</tr>
				
			 <?php } } ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>





</div>
