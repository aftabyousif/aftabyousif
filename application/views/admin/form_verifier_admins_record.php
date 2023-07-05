<div class="library-book-area mg-t-30">
	<div class="container-fluid">
	    <form>
	    <div class="row">
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
			    <div class="single-review-st-item res-mg-t-30 table-mg-t-pro-n">
    			     <select class="form-control" name="program_type">
    			         <option value=1>BACHELOR</option>
    			         <option value=2>MASTER</option>
    			    </select>   
			    </div>
			 </div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
			    <div class="single-review-st-item res-mg-t-30 table-mg-t-pro-n">
    			     <select class="form-control" name="year">
    			         <?php
    			         for($i=2021;$i<=date('Y')+1;$i++){
    			             $s = "";
    			             if(isset($_GET['year'])&&$_GET['year']==$i){
    			                 $s = "selected";
    			             }
    			            echo "<option $s>$i</option>";
    			         }
    			         ?>
    			    </select>   
			    </div>
			 </div>
			 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
			     <br>
			     <button class='btn btn-info'>Search</button>
			 </div>
		</div>
		</form>
		<div class="row">
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="single-review-st-item res-mg-t-30 table-mg-t-pro-n">
					<div class="single-review-st-hd">
						<h2>Verifier List</h2>
					</div>
					<?php
					$sum_total = 0;
					foreach ($ADMINS_DATA as $admins){
//						prePrint($admins);
						$first_name = $admins['FIRST_NAME']." ".$admins['LAST_NAME'];
						$total = $admins['TOTAL_COUNT'];
						$role_name = $admins['ROLE_NAME'];
						$PROFILE_IMAGE = $admins['PROFILE_IMAGE'];
						$sum_total+=$total;
						$cnic_no = $admins['CNIC_NO'];
					?>
					<div class="single-review-st-text">
						<?php
						if(isset($admins['PROFILE_IMAGE'])){
							$v =itsc_url().PROFILE_IMAGE_PATH;
						    echo " <img src='$v{$PROFILE_IMAGE}' alt='PROFILE IMAGE'  >";
						}else {
							$image_path_default = base_url() . "dash_assets/img/avatar/default-avatar.png";
							echo " <img src='$image_path_default' alt='PROFILE IMAGE'  >";
						}
						?>
						<div class="review-ctn-hf">
							<h3><?=$first_name?></h3>
							<p><?=$role_name?></p>
							<p><?=$cnic_no?></p>
						</div>
						<div class="review-item-rating">
							<i class="educate-icon educate-star"><?=$total?></i>
						</div>
					</div>
					<?php
					}
					?>
					<br/>
					<div class="single-review-st-hd">
						<h2>SUM: <?=$sum_total?></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
