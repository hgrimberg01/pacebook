<script>
$(function() {
	$( ".iyes" ).on( "click", function() {
		var row = $(this).closest('tr');
		row.fadeOut();
		approve(row.data('fid'),'i');
		row.html('<td colspan="3">	<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Confirmed</strong></div></td>');


		row.fadeIn();
		
		});
	

	$( ".ino" ).on( "click", function() {
	var row = $(this).closest('tr');
	row.fadeOut();
	not_approve(row.data('fid'),'i');
	row.html('<td colspan="3">	<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Ignored</strong></div></td>');


	row.fadeIn();
	
	});
});
	
function approve(id,dir){

	var data = {};
	data.fid = id;
	data.dir = dir;
	
	$.ajax({
		dataType: "json",
		type: "POST",
		url: '/friends/confirm/',
		data: data,
		complete:function(r,s){
			console.log(r);
		}

		});
	
}

function not_approve(id,dir){

	var data = {};
	data.fid = id;
	data.dir = dir;
	
	$.ajax({
		dataType: "json",
		type: "POST",
		url: '/friends/deny/',
		data: data,
		complete:function(r,s){
			console.log(r);
		}

		});
	
	
}

function hide_inbound(fid) {

	
}

function hide_outbound(fid) {

	
}

</script>


<h2>My Friends</h2>
<div class="row">

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Inbound Friend Requests</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Date Requested</th>
							<th>Approve?</th>


						</tr>
					</thead>
					<tbody>
			<?php if (empty($inbound)) {?>
				<td colspan="3">You have no inbound friend requests.</td>
			
			<?php } else {?>		
			<?php  foreach ( $inbound as $iFriend ) { ?>
				
				 <tr data-fid="<?php  echo $iFriend->friendID; ?>">
							
							
							<td><?php  echo $iFriend->fName .' '. $iFriend->lName  ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iFriend->cDate)); ?>
							</td>
							<td><a class="iyes">Yes </a>/ <a class="ino">No</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Outbound Friend Requests</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>

						</tr>
					</thead>
					<tbody>
			<?php // foreach ( $friend_names as $friend ) { ?>
				
				 <tr>
							<td><a href='/profile/<?php // echo $friend; ?>'><?php //echo $friend; ?></a>
							</td>
						</tr>
				
			 <?php //} ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>





</div>
