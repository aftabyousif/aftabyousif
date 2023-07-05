
<body class="login-page sidebar-collapse">

<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1200px" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3 id="alert_title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="alert_body">

            </div>
        </div>
    </div>
</div>
<button data-toggle="modal" data-target="#alertModal"  id="alert_btn" hidden>alert</button>
<nav class="navbar navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
   
    <div class="container-fluid">
      <div class="navbar-translate">
         <a  href="#" style="color:black;">
            <img src="<?=base_url()?>assets/icon/usindh_icon.ico" alt="">
           <a href="#" style="font-size: 14pt; color: black; font-weight: bold; font-family: 'Times New Roman'">Directorate of Admissions, University of Sindh</a>
            </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      
       <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url()?>" onclick="">
              <span class="fa fa-home"></span> Home
            </a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="<?=base_url()?>register" onclick="">
              <span class="fa fa-arrow-right"></span> New Registration
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href='https://usindh.edu.pk/wp-content/uploads/2021/12/Prospectus-2022-Complete.pdf' target='new'>
              <span class="fa fa-arrow-right"></span> Prospectus 2022
            </a>
          </li>
                <li class="nav-item">
            <a class="nav-link" href='<?=base_url()?>assets/undertakin 2022.pdf' target='new'>
              <span class="fa fa-arrow-right"></span> Undertaking
            </a>
          </li>
         <!--   <li class="nav-item">-->
          <!--  <a class="nav-link" href='../../eportal_resource/files/Prospectus_2021.pdf' target='new'>-->
          <!--    <span class="fa fa-arrow-right"></span> Prospectus 2021-->
          <!--  </a>-->
          <!--</li>-->

			<li class="dropdown nav-item">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
				<b class="">	<i class="badge" style="background-color:red">new</i> Bachelor Merit Lists</b>
				</a>
			
				<div class="dropdown-menu dropdown-with-icons">
				    	<a href="<?=base_url()?>view_candidate_profile" class="dropdown-item">
						<i class="material-icons">content_paste</i>View Candidate Profile
					</a>
					<a href="<?=base_url()?>candidate_merit_list_bachelor" class="dropdown-item">
						<i class="material-icons">content_paste</i> Provisional Merit List Search by Candidate
					</a>
					<a href="<?=base_url()?>merit_list_bachelor" class="dropdown-item">
						<i class="material-icons">content_paste</i> Provisional Merit List Department Wise
					</a>
					<!--selection_list	<a href="<?=base_url()?>
					<?=base_url()?>dept_wise_selection_list
					
					candidate_merit_list_bachelor" class="dropdown-item">-->
					<!--	<i class="material-icons">content_paste</i>Provisional Merit Lists - Search by CNIC Number -->
					<!--</a>-->
					<!--<a href="<?=base_url()?>merit_list_bachelor" class="dropdown-item">-->
					<!--	<i class="material-icons">content_paste</i>Provisional Merit Lists - Department Wise-->
					<!--</a>-->
				</div>
			</li>
			
				<!--<a href="<?=base_url()?>selection_list" class="dropdown-item">-->
					<!--	<i class="material-icons">content_paste</i> Provisional Merit List Search by Candidate (For Objections)-->
					<!--</a>-->
					<!--<a href="<?=base_url()?>dept_wise_selection_list" class="dropdown-item">-->
					<!--	<i class="material-icons">content_paste</i> Provisional Merit List Department Wise (For Objections)-->
					<!--</a>-->
					
					
			<li class="dropdown nav-item">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
					<i class="material-icons">apps</i> Master Merit Lists
				</a>
			
				<div class="dropdown-menu dropdown-with-icons">
				    	<a href="<?=base_url()?>view_candidate_profile?t=2" class="dropdown-item">
						<i class="material-icons">content_paste</i>View Candidate Profile
					    </a>
				
					<a href="<?=base_url()?>candidate_merit_list_master" class="dropdown-item">
						<i class="material-icons">content_paste</i> Provisional Merit Lists - Search by CNIC Number
					</a>
					<a href="<?=base_url()?>merit_list_master" class="dropdown-item">
						<i class="material-icons">content_paste</i> Provisional Merit Lists - Department Wise
					</a>
				</div>
			</li>
          
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url()?>web/news" onclick="">
              <span class="glyphicon glyphicon-bullhorn"></span> Updates / Announcements
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url()?>application_status" onclick="">
              <span class="fa fa-arrow-right"></span> Check Application Status
            </a>
          </li>
         <li class="nav-item">
            <a class="nav-link" href="https://itsc.usindh.edu.pk/eportal/public/verify_challan.php" onclick="">
              <span class="fa fa-arrow-right"></span>Verify Online Payments
            </a>
          </li> 
        </ul>
      </div>
    </div>
  </nav>
