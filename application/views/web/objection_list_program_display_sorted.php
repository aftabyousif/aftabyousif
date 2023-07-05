
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Table Sortable</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/table_sorter/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/table_sorter/styles.css">
    <script src="./assets/table_sorter/jquery.min.js"></script>
</head>
<body>
<div class="">
    <div class="">

        <div class="row mt-5 mb-3 align-items-center">

            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search in List..." id="searchField">
            </div>
            <div class="col-md-4">
                
            </div>
            <div class="col-md-2 text-right">
                <!--<span class="pr-3">Rows Per Page:</span>-->
            </div>
            <!--<div class="col-md-2">-->
            <!--    <div class="d-flex justify-content-end">-->
            <!--        <select class="custom-select" name="rowsPerPage" id="changeRows">-->
            <!--            <option value="1">1</option>-->
            <!--            <option value="5" >5</option>-->
            <!--            <option value="10">10</option>-->
            <!--            <option value="15">15</option>-->
            <!--            <option value="50" selected>50</option>-->
            <!--            <option value="100" >100</option>-->
            <!--        </select>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
        <div id="root"><div class="gs-table-container">
                <table class="table gs-table">
                    <thead class="gs-table-head">

                    </thead>
                    <tbody class="gs-table-body">

                    </tbody>
                </table>
                <!--<div class="gs-pagination">-->

                <!--</div>-->
            </div>
        </div>

    </div>
</div>

<script src="./assets/table_sorter/table-sortable.js"></script>

<script>


    var data = [

        <?php
        foreach ($CANDIDATE as $choices){
        $cpn = $choices['TEST_CPN'];
        if(!$cpn){
            $cpn = 0;
        }
        $cpn = $this->TestResult_model->truncate_cpn($cpn,2);
        ?>
            {
                Application: '<?=$choices['APPLICATION_ID']?>',
                Seat: '<?=$choices['CARD_ID']?>',
                Name: '<?=$choices['FIRST_NAME']?>',
                fName: '<?=$choices['FNAME']?>',
                Surname: '<?=$choices['LAST_NAME']?>',
                District: '<?=$choices['DISTRICT_NAME']?>',
              
                Campus: '<?=$choices['NAME']?>',
                Degree: '<?=$choices['PROGRAM_TITLE_CATE']?>',
                Choice: '<?=$choices['PROGRAM_TITLE']?>',
                Shift: '<?=$choices['SHIFT_NAME']?>',
                Category: '<?=$choices['CATEGORY_NAME']?>',
                List: '<?=($choices['LIST_NO']%10)?>',
                ChoiceNo: '<?=$choices['CHOICE_NO']?>',
                CPN: '<?=$cpn?>',
            },

    <?php
    }
    ?>

    ]

    var columns = {
        Application: 'Application No ▲',
        Seat: 'Seat No ▲',
        Name: 'Name ▲',
        fName: 'Father\'s Name ▲',
        Surname: 'Surname ▲',
        District: 'District ▲',
       
        Campus: 'Campus ▲',
        Degree: 'Degree ▲',
        Choice: 'Choice ▲',
        Shift: 'Shift ▲',
        Category: 'Category ▲',
        List: 'List ▲',
        ChoiceNo: 'ChoiceNo ▲',
        CPN: 'CPN ▲',
    }

    var table = $('#root').tableSortable({
        data: data,
        columns: columns,
        searchField: '#searchField',
        responsive: {
            1100: {
                columns: {
                    Application: 'Application No',
                    Seat: 'Seat No',
                    Name: 'Name',
                    fName: 'Father\'s Name',
                    Surname: 'Surname',
                    District: 'District',
                    
                    Campus: 'Campus',
                    Degree: 'Degree',
                    Choice: 'Choice',
                    Shift: 'Shift',
                    Category: 'Category',
                    List: 'List',
                    ChoiceNo: 'ChoiceNo',
                    CPN: 'CPN',
                },
            },
        },
        rowsPerPage: 300,
        pagination: true,
        tableWillMount: function() {
            console.log('table will mount')
        },
        tableDidMount: function() {
            console.log('table did mount')
        },
        tableWillUpdate: function() {console.log('table will update')},
        tableDidUpdate: function() {console.log('table did update')},
        tableWillUnmount: function() {console.log('table will unmount')},
        tableDidUnmount: function() {console.log('table did unmount')},
        onPaginationChange: function(nextPage, setPage) {
            setPage(nextPage);
        }
    });

    $('#changeRows').on('change', function() {
        table.updateRowsPerPage(parseInt($(this).val(), 10));
    })

    $('#rerender').click(function() {
        table.refresh(true);
    })

    $('#distory').click(function() {
        table.distroy();
    })

    $('#refresh').click(function() {
        table.refresh();
    })

    $('#setPage2').click(function() {
        table.setPage(1);
    })
</script>


</body></html>