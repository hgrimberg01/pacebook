


<script type="text/javascript">
$(function() {

	
			

	$('#sysError').hide();
	$('#eclose').on('click', function(e){
	
		$(this).parent().slideUp(500);
	});

	
	$('tr').delegate('#my-alert','close.bs.alert', function () {
		$(this).closest('tr').hide();
		});
			
	
	$( ".nApprove" ).on( "click", function() {
		var row = $(this).closest('tr');
		var data = {};
		
		data.nid = row.data('nid');
		row.fadeOut();
		
		$.ajax({
			dataType: "json",
			type: "POST",
			url: '/networks/approve/',
			data: data,
			success:function(r,s){
				row.html('<td colspan="5">	<div id="my-alert" class="alert alert-success alert-dismissable"><button type="button"  class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Network Approved</strong></div></td>');
				
			},
			error:function(jqXHR,  textStatus, errorThrown){
				console.log(errorThrown);
				$('#sysError').slideDown(500);
			}
			});



		
		row.fadeIn();
		
		});

	$( ".nDeny" ).on( "click", function() {
		var row = $(this).closest('tr');
		var data = {};
		
		data.nid = row.data('nid');
		row.fadeOut();
		
		$.ajax({
			dataType: "json",
			type: "POST",
			url: '/networks/deny/',
			data: data,
			success:function(r,s){
				row.html('<td colspan="5">	<div id="my-alert" class="alert alert-success alert-dismissable"><button type="button"  class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Network Denied</strong></div></td>');
				
			},
			error:function(jqXHR,  textStatus, errorThrown){
				console.log(errorThrown);
				$('#sysError').slideDown(500);
			}
			});



		
		row.fadeIn();
		
		});
	

});
	

</script>


<h2>Manage Networks</h2>

<div id="sysError" class="alert alert-danger alert-dismissable">
	<button id="eclose" type="button" class="close" aria-hidden="true">&times;</button>
	<strong>A system error has occured. Please try again later.</strong>
</div>

<div class="row">
	<div class="col-md-12 ">
		<div class="panel panel-default">
			<div class="panel-heading">My Networks</div>
			<div class="panel-body">

				<table id="network_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Description</th>
							<th>Members</th>
							<th>Edit?</th>
							<th>Delete?</th>

						</tr>
					</thead>
					<tbody>
				<?php if (empty($networks)) {?>
				<td colspan="12">Either there are no networks or you are in every network!</td>
			
			<?php } else {?>		
			<?php  foreach ( $networks as $iNetwork ) { ?>
				
				 <tr data-nid="<?php  echo $iNetwork->nid ?>">


							<td><?php  echo $iNetwork->name  ; ?>
							</td>
							
							<td><?php  echo $iNetwork->descr  ; ?>
							</td>
							
							<td><?php  echo $iNetwork->numMembers  ; ?>
							</td>
							
							<td><a class="nEdit">Edit</a></td>

							<td><a class="nDelete">Delete</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>


</div>

<div class="row">
	<div class="col-md-12 ">
		<div class="panel panel-default">
			<div class="panel-heading">Pending Networks</div>
			<div class="panel-body">

				<table id="network_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Description</th>
							<th>Owner</th>
							<th>Approve/Deny?</th>

						</tr>
					</thead>
					<tbody>
				<?php if (empty($pending)) {?>
				<td colspan="12">There are no pending networks!</td>
			
			<?php } else {?>		
			<?php  foreach ( $pending as $iNetwork ) { ?>
				
				 <tr data-nid="<?php  echo $iNetwork->nid ?>">


							<td><?php  echo $iNetwork->name  ; ?>
							</td>
							
							<td><?php  echo $iNetwork->descr  ; ?>
							</td>
							
							<td><?php  echo $iNetwork->owner  ; ?>
							</td>
							
							<td><a class="nApprove">Approve</a> <a class="nDeny">Deny</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>


</div>
