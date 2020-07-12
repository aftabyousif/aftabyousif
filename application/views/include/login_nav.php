
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
   
    <div class="container">
      <div class="navbar-translate">
         <a  href="#" style="color:black;">
            <img src="<?=base_url()?>assets/icon/usindh_icon.ico" alt="">

            <a href="" style="font-size: 1.3rem; color: black;"><a href="#" style="font-size: 1.5rem; color: black;">University of Sindh, Jamshoro </a></a>
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
            <a class="nav-link" href="<?=base_url()?>login" onclick="">
              <span class="fa fa-home"></span> Home
            </a>
          </li>


        </ul>
      </div>
    </div>
  </nav>
