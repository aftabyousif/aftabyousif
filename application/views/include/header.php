<?php
$project_name ="Admission";
?>
<!doctype html>
<html class="no-js" lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?=$project_name;?></title>
    <meta name="description" content="">
    <!-- jquery
		============================================ -->
    <script src="<?=base_url()?>dash_assets/js/vendor/jquery-1.12.4.min.js"></script>
    <link href="<?=base_url()?>assets/select2.min.css" rel="stylesheet" />
    <script src="<?=base_url()?>assets/select2.full.min.js"></script>
    <script src="<?=base_url()?>assets/select2.min.js"></script>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>dash_assets/img/usindh_icon.ico">
 
    <!-- Google Fonts
		============================================ -->
<!--    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">-->
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/owl.carousel.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/owl.theme.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/normalize.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/meanmenu.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/main.css">
    <!-- educate icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/educate-custon-icon.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/calendar/fullcalendar.print.min.css">
    
        <!-- summernote CSS
    ============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/summernote/summernote.css">
    
     <!-- x-editor CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/editor/select2.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/editor/datetimepicker.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/editor/bootstrap-editable.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/editor/x-editor-style.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/data-table/bootstrap-editable.css">
    <!-- modals CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/modals.css">
    <!-- SELECT
		============================================ -->
      <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/duallistbox/bootstrap-duallistbox.min.css">
    
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="<?=base_url()?>dash_assets/js/vendor/modernizr-2.8.3.min.js"></script>
       <!-- Button
		============================================ -->
    <link rel="stylesheet" href="<?=base_url()?>dash_assets/css/buttons.css">
    
    <!--image rotate css yasir added custom-->
    <link rel="stylesheet" href="<?=base_url()?>assets/image_rotate.css">
    
    <script src="<?=base_url()?>assets/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular-sanitize.js"></script>
    
    <script>

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

    </script>
<style>
    .top-margin{
     margin-top: 20px;
    }
    td>img{
            width: 60px;
    
    height: 60px;
    border-radius: 50%;
    }
    

    
    .header-top-area{
        background: #337ab7;
    }
    .sidebar-nav{
    background:#337ab7;
    }
    .sidebar-nav .metismenu {
    background:#337ab7;
        padding-top:10px; 
}
    #sidebar .sidebar-header{
      background:#337ab7;
    }
    #sidebar {
            box-shadow: 8px 3px 11px 7px rgba(0,0,0,.14), 0px 1px 13px 0px rgb(152, 152, 152);
background: #337ab7;
/*        background-image: url(assets/img/dashboard/sidebar-7.jpg) ;*/
   
}
    .sidebar-nav .metismenu a {
    color: #fff;
}
    .sidebar-nav .metismenu li .icon-wrap {
    color: #fff;
}
    .footer-copyright-area{
      background:#337ab7;
    }
    .sidebar-nav ul{
       background:#337ab7;
    }
    #sidebar.active .sidebar-nav ul.metismenu li ul.submenu-angle{
        background:#337ab7;
        padding-top:5px; 
            border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
         box-shadow: 8px 3px 11px 7px rgba(0,0,0,.14), 0px 1px 13px 0px rgb(152, 152, 152);
    }
    .sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active {
       box-shadow: 1px 1px 7px 0px rgb(131, 131, 131), 0px 1px 13px 0px rgb(198, 197, 197);
    background: #337ab7;
    border-radius: 10px;
}
    .sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active {
        color: #fffefe;}
    #sidebar.active .sidebar-nav .metismenu a:hover, #sidebar.active .sidebar-nav .metismenu a:focus, #sidebar.active .sidebar-nav .metismenu a:active {
     box-shadow: 1px 1px 7px 0px rgb(131, 131, 131), 0px 1px 13px 0px rgb(198, 197, 197);
    background: #337ab7;
    border-radius: 10px;
}
    .menu-switcher-pro .btn-info:active, .menu-switcher-pro .btn-info:focus, .menu-switcher-pro .btn-info:hover, .menu-switcher-pro .btn-info:visited, .header-drl-controller-btn.btn-info:active:focus {
         background: #337ab7;
    box-shadow: 1px 1px 7px 0px rgb(131, 131, 131), 0px 1px 13px 0px rgb(198, 197, 197);
   
    border-radius: 10px;
        
}
    .card{
           background: white;
    margin: 20px;
    border-radius: 10px;
    box-shadow: 1px 1px 20px 10px rgb(218, 218, 218), 0px 1px 13px 0px rgb(74, 71, 71);
    }
    .card .card-header{
        color:#fff;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.14);
        background: linear-gradient(60deg,#204D7F,#204D7F);
        border-radius: 3px;
/*    margin-top: -20px;*/
        margin-left: 20px;
        margin-right: 20px;
    padding: 25px;
    }
    .card .card-body{
        border-radius: 3px;
/*    margin-top: -20px;*/
    padding: 20px;
    }
    

.student-inner-std{
            border-radius: 10px;
    box-shadow:  1px 1px 11px 4px rgb(218, 218, 218), 0px 1px 13px 0px rgb(74, 71, 71);
    }
    .student-dtl{
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.14);
        background: linear-gradient(60deg,#ffa726,#fb8c00);
        border-radius: 3px;

        margin-left: 20px;
        margin-right: 20px;
    padding: 25px;
    }
    


/* width */
::-webkit-scrollbar {
  width: 15px;
    height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
      background: #4e73df;
    box-shadow: inset -2px 2px 6px 3px #cac3c3;
    border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #4e73df;
}
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #dcddff;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sidebar-nav ul {
    background: #204d7f;
}

     .select2-container--default .select2-results__option[aria-disabled=true] {
         color: #f90000;
     }

.stepper-wrapper {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.stepper-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
@media (max-width: 768px) {
    font-size: 12px;
}
}
.stepper-item:hover{
    cursor: pointer;
}


.stepper-item::before {
    position: absolute;
    content: "";
    border-bottom: 2px solid red;
    width: 100%;
    top: 20px;
    left: -50%;
    z-index: 2;
}

.stepper-item::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid red;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 2;
}

.stepper-item .step-counter {
    position: relative;
    z-index: 5;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgb(255, 253, 253);
    border: 2px solid red;
    margin-bottom: 6px;
}

.stepper-item.active {
    font-weight: bold;
}

.stepper-item.completed .step-counter {
    color:#FFF;
    background-color: #7cd43b;
        border: 0px solid #7cd43b;
}

.stepper-item.completed::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #7cd43b;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 3;
}
.stepper-item.disabled .step-counter {
    color:#FFF;
    background-color: #b5b3b3;
        border: 0px solid #b5b3b3;
}

.stepper-item.disabled::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #b5b3b3;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 3;
}
.stepper-item.pennding .step-counter {
    color:#FFF;
    background-color: #adaeaf;
        border: 0px solid #adaeaf;
}

.stepper-item.pennding::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #adaeaf;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 3;
}

.stepper-item:first-child::before {
    content: none;
}

.stepper-item:last-child::after {
    content: none;
}
.step-name{
    text-align: center;
}
</style>

</head>

<body>
<?php 

    
    $side_bar_values = array(
    //for single menu item
    array("is_submenu"=>"0","value"=>"Dashborad","link"=>"dashboard.php","class"=>"educate-icon educate-home icon-wrap"),
   //  array("is_submenu"=>"0","value"=>"Profile","link"=>"profile.php","class"=>"educate-icon educate-home icon-wrap"),
       array("is_submenu"=>"0","value"=>"Logout","link"=>"logout.php","class"=>"educate-icon educate-home icon-wrap")
   // for submenu 
//   array("is_submenu"=>"1","value"=>"Education","link"=>"events.html","class"=>"educate-icon educate-event icon-wrap sub-icon-mg","sub_menu"=>array(
//       array("value"=>"Dashboard v.1","link"=>"events.html")
//       ,
//       array("value"=>"Dashboard v.2","link"=>"events.html")
//       ,
//       array("value"=>"Dashboard v.3","link"=>"events.html")
//   )
//)
    
                            );
    $nav_values = array(
    //for single menu item
    array("is_submenu"=>"0","value"=>"Dashboard","link"=>"dashboard.php",)
    //for submenu 
//   array("is_submenu"=>"1","value"=>"Education","link"=>"events.html","sub_menu"=>array(
//       array("value"=>"Dashboard v.1","link"=>"events.html")
//       ,
//       array("value"=>"Dashboard v.2","link"=>"events.html")
//       ,
//       array("value"=>"Dashboard v.3","link"=>"events.html")
//   )
//)
    
                            );
           
                    
                    $status = array(array("0","Rejected","class='text-danger'"),
                                    array("1","Pending","class='text-warning'"),
                                   array("2","Approved","class='text-success'")
                                   );
                  $candidate_active_status = array(array("-1","Rejected","class='text-danger'"),
                                    array("0","Saved","class='text-success'"),
                                   array("1","Submitted","class='text-success'"),
                                   array("2","Selected","class='text-success'"),
                                   array("3","Approved","class='text-success'"),
                                   );
                $can_form_status = array(
                                    array("0","Pending","class='text-warning'"),
                                   array("1","Approved","class='text-success'"),
                                   array("2","Rejected","class='text-danger'"),
                                    array("3","Objection","class='text-danger'")
                                   );
                
                

?>
