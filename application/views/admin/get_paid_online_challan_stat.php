<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="card">
        <div class="card-body">
            <table class='table table-bordered '>
                <?php
                
                 echo "<tr>";
                    echo "<th>Date</th>";
                    echo "<th>Total</th>";
                    echo "</tr>";
                foreach($stats as $stat){
                    echo "<tr>";
                    echo "<td>{$stat['PAID_DATE']}</td>";
                    echo "<td>{$stat['TOTAL']}</td>";
                    echo "</tr>";
                }
                ?>
                </table>
        </div>
    </div>
</div>