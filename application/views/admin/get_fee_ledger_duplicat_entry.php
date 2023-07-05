<div id = "min-height" class="container-fluid" style="padding:30px">
         <?php

            $table_column = ["ACCOUNT_ID","APPLICATION_ID","CNIC_NO","Name","Surname","Father Name","CHOICE_NO","LIST_NO","PROGRAM_TITLE","CATEGORY_NAME"];
            ?>



            <div class="data-table-area mg-b-15">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="sparkline13-list">
                                <div class="sparkline13-hd">
                                    <div class="main-sparkline13-hd">
                                        <h1>All Duplication in Fee Ledger</h1>
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
                                                $i=0;
                                                foreach($table_column as $col){
                                                    ?>

                                                    <th data-field="<?=$col?>" onclick="sortTable(<?=$i?>)" ><?=$col?></th>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                             foreach($result as $row){
           
                                            if(count($row)>1){
                                                      
                                                       foreach($row as $value){
                                                           echo "<tr><td></td>";
                                                            echo "<td>{$value['ACCOUNT_ID']}</td>";
                                                        echo "<td>{$value['APPLICATION_ID']}</td>";
                                                        echo "<td>{$value['CNIC_NO']}</td>";
                                                        echo "<td>{$value['FIRST_NAME']}</td>";
                                                        echo "<td>{$value['LAST_NAME']}</td>";
                                                        echo "<td>{$value['FNAME']}</td>";
                                                         echo "<td>{$value['CHOICE_NO']}</td>";
                                                        echo "<td>{$value['LIST_NO']}</td>";
                                                        echo "<td>{$value['PROGRAM_TITLE']}</td>";
                                                        echo "<td>{$value['CATEGORY_NAME']}</td>";
                                                        echo "</tr>";
                                                       }
                                                       
                                                   }
                                                   
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