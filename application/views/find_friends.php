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


	$('.cncl').on("click",function(){
		var row = $(this).closest('tr');
		row.fadeOut();
		not_approve(row.data('fid'),'o');
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


<h2>Find Friends</h2>


<div class="row">
	<div  class="col-md-12 ">
		<div class="panel panel-default">
			<div class="panel-heading">All Users</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>
							<th>Active Since</th>
							
							<th>Add as friend?</th>

						</tr>
					</thead>
					<tbody>
				<?php if (empty($friends)) {?>
				<td colspan="12">Either there is nobody or you have friended everybody!</td>
			
			<?php } else {?>		
			<?php  foreach ( $friends as $iFriend ) { ?>
				
				 <tr data-fid="<?php  echo $iFriend->uid ?>">


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

	<
</div>

