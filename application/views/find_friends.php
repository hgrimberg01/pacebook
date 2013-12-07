


<script type="text/javascript">
$(function() {

	
			

	$('#sysError').hide();
	$('#eclose').on('click', function(e){
	
		$(this).parent().slideUp(500);
	});

	
	$('tr').delegate('#my-alert','close.bs.alert', function () {
		$(this).closest('tr').hide();
		});
			
	
	$( ".send_invite" ).on( "click", function() {
		var row = $(this).closest('tr');
		var data = {};
		
		data.fid = row.data('fid');
		row.fadeOut();
		
		$.ajax({
			dataType: "json",
			type: "POST",
			url: '/friends/add/',
			data: data,
			success:function(r,s){
				row.html('<td colspan="3">	<div id="my-alert" class="alert alert-success alert-dismissable"><button type="button"  class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Invite Sent</strong></div></td>');
				
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


<h2>Find Friends</h2>

<div id="sysError" class="alert alert-danger alert-dismissable">
	<button id="eclose" type="button" class="close" aria-hidden="true">&times;</button>
	<strong>A system error has occured. Please try again later.</strong>
</div>

<div class="row">
	<div class="col-md-12 ">
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
				<td colspan="12">Either there is nobody or you have friended
							everybody!</td>
			
			<?php } else {?>		
			<?php  foreach ( $friends as $iFriend ) { ?>
				
				 <tr data-fid="<?php  echo $iFriend->uid ?>">


							<td><?php  echo $iFriend->fName .' '. $iFriend->lName  ; ?>
							</td>

							<td><?php  echo date("F d, Y g:i A", strtotime($iFriend->cDate)); ?>
							</td>

							<td><a class="send_invite">X</a></td>
						</tr>
				
			 <?php } }?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>


</div>

