<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<label>User ID</label>
						<input type="text" id="user_id" name="user_id" class="form-control" value="<?=$USER_PROFILE_DATA['USER_ID']?>" readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Name</label>
						<input type="text" id="first_name" name="first_name" class="form-control" value="<?=$USER_PROFILE_DATA['FIRST_NAME']?>" readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Last Name</label>
						<input type="text" id="last_name" name="last_name" class="form-control" value="<?=$USER_PROFILE_DATA['LAST_NAME']?>" readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Email</label>
						<input type="text" id="email" name="email" class="form-control" value="<?=$USER_PROFILE_DATA['EMAIL']?>" readonly/>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label>Role</label>
					<select id="role_id" name="role_id" CLASS="form-control">
						<option value=""></option>
						<?php
					
						foreach ($ROLE_LIST as $role)
						{
							?>
							<option value="<?=$role['ROLE_ID']?>"><?=$role['ROLE_NAME']?></option>
						<?php
						}//foreach
					
						?>
					</select>
				</div>

				<div class="button-style-two btn-mg-b-10">
					<button type="button" id="AddRole" class="btn btn-custon-rounded-two btn-success">  <i class="fa fa-save edu-informatio" aria-hidden="true"></i>  Assign Role</button>
				</div>

				<table class="table table-condensed">
					<thead>
					<tr>
						<th>S.No</th>
						<th>Role ID</th>
						<th>R R ID</th>
						<th>Role Name</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>
					<?php
					$sno=0;
						if(is_array($USER_ROLES) || is_object($USER_ROLES)){
					foreach ($USER_ROLES as $user_role)
					{
						$sno++;
						if ($user_role['ACTIVE'] == 1)
							$active = "<i class='fa fa-check text-primary' title='This is the current status'>  Active</i>";
						elseif ($user_role['ACTIVE'] == 0)
							$active = "<i class='fa fa-ban text-danger' title='This is the current status'>  De-Active</i>";
						else $active = $user_role['ACTIVE'];
						?>
						<tr>
							<td><?=$sno?></td>
							<td><?=$user_role['ROLE_ID']?></td>
							<td><?=$user_role['R_R_ID']?></td>
							<td><?=$user_role['ROLE_NAME']?></td>
							<td><?=$active?></td>
							<td>
								<?php if ($user_role['ACTIVE'] == 1){?>
								<button onclick="deleteRoll('<?=$user_role['R_R_ID']?>','<?=$user_role['USER_ID']?>','0')"class="btn btn-danger" title="Click here to De Active role">
									<i class="fa fa-ban"> De Active</i></button>
								<?php }elseif($user_role['ACTIVE'] == 0){?>
								<button onclick="deleteRoll('<?=$user_role['R_R_ID']?>','<?=$user_role['USER_ID']?>','1')"class="btn btn-primary" title="Click here to Active Role">
									<i class="fa fa-check"> Active </i></button>
								<?php } ?>
							</td>
						</tr>
					<?php
					}//foreach
						}//if
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<script>

	$("#AddRole").click(function (){
		AddRole();
	});
	function AddRole (){
		// alert("working");
		let user_id = $.trim($("#user_id").val());
		let role_id = $.trim($("#role_id").val());
		// $('#loading').html('');
		if (cnic_no == 0 || cnic_no == "" || cnic_no == null )
		{
			return;
		}else if (role_id == 0 || role_id == "" || role_id == null )
		{
			return;
		}
		$.ajax({
			url:'<?=base_url()?>AdminAccount/addUserRole',
			method: 'POST',
			data: {user_id:user_id,role_id:role_id},
			// dataType: 'json',
			success: function(response){
				// console.log(response);
				alertMsg("Message",response);
				getUserData();
				// $('#loading').html('');
				// $('#displayUserData').html(response);
			}
		});
	}

	function deleteRoll(id,user_id,active){
		if(confirm("Are You Sure?\nDo You want to perform this action..!")){

			jQuery.ajax({
				url:'<?=base_url()?>AdminAccount/disableUserRole',
				type: "POST",
				// enctype: 'multipart/form-data',
				// processData: false,
				// contentType: false,
				cache: false,
				async: false,
				data: {user_id:user_id,r_r_id:id,active:active},
				success: function (data, status) {
					alertMsg("Message",data.responseText);
					getUserData();
				},
				beforeSend:function (data, status) {
					alertMsg("Message",data.responseText);
					getUserData();
				},
				error:function (data, status) {
					alertMsg("Message",data.responseText);
					getUserData();
				},
			});
		}
	}
</script>
