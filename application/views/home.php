



<h2>My Stuff</h2>
<div class="row">

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">My Friends</div>
			<div class="panel-body">

				<table id="friend_list" class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>

						</tr>
					</thead>
					<tbody>
			<?php  foreach ( $friend_names as $friend ) { ?>
				
				 <tr>
							<td><a href='/profile/<?php echo $friend; ?>'><?php echo $friend; ?></a>
							</td>
						</tr>
				
			 <?php } ?>
		
		  </tbody>
				</table>


			</div>
		</div>
	</div>


	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">My Groups</div>
			<div class="panel-body">


				<table class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Network</th>

						</tr>
					</thead>
					<tbody>
			<?php  foreach ( $my_group_names as $friend ) { ?>
				
				 <tr>
							<td><a href='/network/<?php echo $friend; ?>'><?php echo $friend; ?></a>
							</td>
						</tr>
				
			 <?php } ?>
		
		  </tbody>
				</table>

			</div>
		</div>
	</div>
</div>


<?php  if($admin_level > 3){?>
<h2>Admin Stuff</h2>
<div class="row">

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">All Users</div>
			<div class="panel-body">

				<table class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Name</th>

						</tr>
					</thead>
					<tbody>
			<?php  foreach ( $all_people as $friend ) { ?>
				
				 <tr>
							<td><a href='/profile/<?php echo $friend; ?>'><?php echo $friend; ?></a>
							</td>
						</tr>
				
			 <?php } ?>
			  </tbody>
				</table>

			</div>
		</div>
	</div>


	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">All Networks</div>
			<div class="panel-body">

				<table class="table  table-bordered table-hover">
					<thead>
						<tr>

							<th>Network</th>

						</tr>
					</thead>
					<tbody>
			
			
				<?php  foreach ( $all_networks as $friend ) { ?>
				
				 <tr>
							<td><a href='/network/<?php echo $friend; ?>'><?php echo $friend; ?></a>
							</td>
						</tr>
				
			 <?php } ?>
			  </tbody>
				</table>

			</div>
		</div>
	</div>
</div>
<?php }?>

</div>