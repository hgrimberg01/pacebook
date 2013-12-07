<script>
$(function() {

	$('#sysError').hide();
	$('#eclose').on('click', function(e){
	
		$(this).parent().slideUp(500);
	});

	
	$('tr').delegate('#my-alert','close.bs.alert', function () {
		$(this).closest('tr').hide();
		});

	
	$( ".iyes" ).on( "click", function() {
		var row = $(this).closest('tr');
		row.fadeOut();
		approve(row.data('fid'),'i',row);


		row.fadeIn();
		
		});
	

	$( ".ino" ).on( "click", function() {
	var row = $(this).closest('tr');
	row.fadeOut();
	not_approve(row.data('fid'),'i',row);


	row.fadeIn();
	
	});


	$('.cncl').on("click",function(){
		var row = $(this).closest('tr');
		row.fadeOut();
		not_approve(row.data('fid'),'o',row);


		row.fadeIn();
	});
});
	
function approve(id,dir,row){

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
		},error:function(jqXHR,  textStatus, errorThrown){
			console.log(errorThrown);
			$('#sysError').slideDown(500);
		},success:function(){
			row.html('<td colspan="3">	<div id="my-alert" class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Confirmed</strong></div></td>');
				
			}

		});
	
}

function not_approve(id,dir,row){

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
		},
		error:function(jqXHR,  textStatus, errorThrown){
			console.log(errorThrown);
			$('#sysError').slideDown(500);
		},success:function(){
			row.html('<td colspan="3">	<div id="my-alert" class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Ignored</strong></div></td>');
			
		}

		});
	
	
}

function hide_inbound(fid) {

	
}

function hide_outbound(fid) {

	
}

</script>


<h2>
	My Friends <a href="/friends/find"><small> Find Friends</small></a>
</h2>
<div id="sysError" class="alert alert-danger alert-dismissable">
	<button type="button" class="close" id="eclose" aria-hidden="true">&times;</button>
	<strong>A system error has occured. Please try again later.</strong>
</div>
<div class="row">

	<div class="col-md-6" data-ss-colspan="6">
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

	<div class="col-md-6 data-ss-colspan="6">
		<div class="panel panel-default">
			<div class="panel-heading">Outbound Friend Requests</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Date Requested</th>
							<th>Cancel?</th>

						</tr>
					</thead>
					<tbody>
				<?php if (empty($outbound)) {?>
				<td colspan="3">You have no inbound friend requests.</td>
			
			<?php } else {?>		
			<?php  foreach ( $outbound as $iFriend ) { ?>
				
				 <tr data-fid="<?php  echo $iFriend->friendID; ?>">


							<td><?php  echo $iFriend->fName .' '. $iFriend->lName  ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iFriend->cDate)); ?>
							</td>
							<td><a class="cncl">X</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>



</div>

<div class="row">
	<div data-ss-colspan="8" class="col-md-8 ">
		<div class="panel panel-default">
			<div class="panel-heading">All Friends</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Date Requested</th>
							<th>Date Approved</th>
							<th>Delete?</th>

						</tr>
					</thead>
					<tbody>
				<?php if (empty($friends)) {?>
				<td colspan="4">You have no friends.</td>
			
			<?php } else {?>		
			<?php  foreach ( $friends as $iFriend ) { ?>
				
				 <tr data-fid="<?php  echo $iFriend->friendID; ?>">


							<td><?php  echo $iFriend->fName .' '. $iFriend->lName  ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iFriend->cDate)); ?>
							</td>
							<td><?php  echo date("F d, Y g:i A", strtotime($iFriend->aDate)); ?>
							</td>
							<td><a class="cncl">X</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Invite a Friend</div>
			<div class="panel-body">
				<div class="row"></div>
				<form role="form">
					<div class="form-group">
						<label for="sterm">Search Term</label> <input class="form-control"
							id="sterm" placeholder="Enter name or email">
					</div>

					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
