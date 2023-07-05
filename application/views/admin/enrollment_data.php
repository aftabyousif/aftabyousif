   
        <div id = "min-height" class="container-fluid" style="padding:30px">
 
  <?php
$table_column = ["APPLICATION_ID","USER_ID","CNIC_NO","FIRST NAME","LAST NAME","FATHER NAME","CHALLAN NO","PAID AMOUNT","PAID DATE","LAST BORAD/UNIVERSITY","PROGRAM_TITLE","PROGRAM","CAMPUS"];
?>            
        
                     
                        
  <div class="data-table-area mg-b-15">
            <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline13-list">
                <div class="sparkline13-hd">
                    <div class="main-sparkline13-hd">
                        <h1>Enrollment Data</h1>
<!--
                        <div class="col-md-12" align="right" style="margin-top:-35px;">
                                <a href="add_news.php">
                                    <button class="btn btn-custon-rounded-three btn-primary btn-lg" style="align:right;">Add News</button>
                                </a>
                            </div>
-->
                    </div>
                    
                </div>
                
                
                <div class="sparkline13-graph">
                    <div class="datatable-dashv1-list custom-datatable-overright">

            <div id="toolbar">
            <select class="form-control dt-tb">
                <option value="">Export Basic</option>
                <option value="all">Export All</option>
                <option value="selected">Export Selected</option>
            </select>
           </div>
            <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
            data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                <thead>
                    <tr>
                      <th data-field="state" data-checkbox="true"></th>
                       <?php 
                        foreach($table_column as $col){
                        ?>

                        <th data-field="<?=$col?>"><?=$col?></th>
                        <?php
                        }
                        ?>
                    </tr>
                </thead>
                            <tbody>
            <?php    $i = 0;
        foreach($data  as $obj){
            $i++;
            $qualifications = $obj['qualifications'];
            $qualification = $qualifications[0];
            if($obj['PROGRAM_TYPE_ID']==1){
                $qualification = find_qualification($qualifications,3);
            }
            echo "<tr>";
            echo "<td></td>";
               
                echo "<td>{$obj['APPLICATION_ID']}</td>";
                echo "<td>{$obj['USER_ID']}</td>";
                echo "<td>{$obj['CNIC_NO']}</td>";
                echo "<td>{$obj['FIRST_NAME']}</td>";
                echo "<td>{$obj['LAST_NAME']}</td>";
                echo "<td>{$obj['FNAME']}</td>";
                 echo "<td>{$obj['CHALLAN_NO']}</td>";
                  echo "<td>{$obj['PAID_AMOUNT']}</td>";
                  echo "<td>{$obj['PAID_DATE']}</td>";
                echo "<td>{$qualification['INSTITUTE_NAME']}</td>";
                echo "<td>{$obj['PROGRAM_TITLE']}</td>";
                echo "<td>{$obj['PROGRAM_TYPE_TITLE']}</td>";
                 echo "<td>{$obj['CAMPUS_NAME']}</td>";
                
                
            echo"</tr>";
        }
        ?>
             
            </tbody>

                           
                        </table>


                    </div> <!-- /.table-stats -->
                </div>
            </div>



        </div>
    </div>
        </div>
        </div>




   </div>
 