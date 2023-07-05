<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">

		<p class="text-danger text-center" style="font-size: 11pt; font-weight: bold"><em>This statistical report is managed by ITSC & The accuracy maybe compromised due to third party tools. **Under Accuracy Testing**</em></p>

		<div class="row">
			<div class="col-md-6">
				<div id="piechart_draft" style="width: 900px; height: 500px;"></div>
			</div>
			<div class="col-md-6">
				<div id="piechart" style="width: 900px; height: 500px;"></div>
			</div>
		</div>

		<div class="row">
				<div class="col-md-12">
				<div id="piechart_district_wise_sindh" style="width: 100%; height: 500px;"></div>
			</div>
		</div>
		<p class="text-danger text-center" style="font-size: 11pt; font-weight: bold"><em>Your browser's local / session storage is being used for better performs if you can't see updated information please clear your browser's cache. Thank you</em></p>

	</div>
</div>
<script type="text/javascript">
	function read_config (){
		$.ajax({
			url:'<?=base_url()?>Statistics/config',
			method: 'POST',
			// data: {},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				localStorage.setItem('config_analytics',JSON.stringify(response));
				// return response;
			}//success
		});
	}
	function drawChart() {
		// var data = google.visualization.arrayToDataTable([
		// 	['Task', 'Hours per Day'],
		// 	['Work',     11],
		// 	['Eat',      2],
		// 	['Commute',  2],
		// 	['Watch TV', 2],
		// 	['Sleep',    7]
		// ]);

		var submitted_campus = JSON.parse(localStorage.getItem('submitted_pie_campus'));
		var draft_campus = JSON.parse(localStorage.getItem('draft_pie_campus'));

		
		// var draft_district = JSON.parse(localStorage.getItem('draft_pie_district'));

		var chartData = [];
		Object.keys(submitted_campus).forEach(function (name) {
			chartData.push([name, submitted_campus[name]]);
		});

		var chartData_draft = [];
		Object.keys(draft_campus).forEach(function (name) {
			chartData_draft.push([name, draft_campus[name]]);
		});
        
        /*
        var submitted_district = JSON.parse(localStorage.getItem('submitted_pie_district'));
		var chartData_submitted_district = [];
		Object.keys(submitted_district).forEach(function (name) {
			chartData_submitted_district.push([name, submitted_district[name]]);
		});
		var data_submitted_districts = google.visualization.arrayToDataTable(chartData_submitted_district,true);
		var options_submitted_districts = {
			title: 'Submitted Applications, Districts of Sindh & other Provinces',
			chartArea: {left:'3%',top:'5%', width: '100%', height: '70%'},
			is3D:true,
			fontSize:11,
		};
        */
        
		// var chartData_draft_district = [];
		// Object.keys(draft_campus).forEach(function (name) {
		// 	chartData_draft.push([name, draft_campus[name]]);
		// });

		// console.log(chartData);
		var data = google.visualization.arrayToDataTable(chartData,true);

		var options = {
			title: 'Submitted Applications',
			chartArea: {left:'3%',top:'5%', width: '50%', height: '70%'},
			is3D:true,
			fontSize:11,
		};
		var data_draft = google.visualization.arrayToDataTable(chartData_draft,true);
		var options_draft = {
			title: 'Draft Applications',
			chartArea: {left:'3%',top:'5%', width: '50%', height: '70%'},
			is3D:true,
			fontSize:11,
		};



		var chart_draft = new google.visualization.PieChart(document.getElementById('piechart_draft'));
		var chart = new google.visualization.PieChart(document.getElementById('piechart'));
// 		var chart_submitted_districts = new google.visualization.PieChart(document.getElementById('piechart_district_wise_sindh'));

		chart_draft.draw(data_draft, options_draft);
		chart.draw(data, options);
// 		chart_submitted_districts.draw(data_submitted_districts, options_submitted_districts);
	}
	function get_statistics (){
		let config = localStorage.getItem('config_analytics');
		config = JSON.parse(config);
        // console.log(config);
		let session = config['SESSION_ID'];
		let program_type = config['PROGRAM_TYPE'];
		let campus = config['CAMPUS_ID'];

		if (session === "" || session === 0 || session == null || isNaN(session))
			session = 0;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			program_type = 0;
		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			campus = 0;

		$.ajax({
			url:'<?=base_url()?>Statistics/getStatistics',
			method: 'POST',
			data: {session:session,program_type:program_type,campus:campus},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				let total_draft 	= 0;
				let total_submitted = 0;
				let total_in_review = 0;
				let total_in_process= 0;
				let total_verified 	= 0;
				let total_rejected 	= 0;
				let total_admit_cards 	= 0;
				let total_not_dispatched= 0;
				let total_dispatched 	= 0;

				let submitted_pie_data = {};
				let draft_pie_data = {};

				$.each(response, function (index,value) {
					i++;
					let DRAFT = value['DRAFT'];
					let SUBMITTED = value['SUBMITTED'];
					let IN_REVIEW = value['IN_REVIEW'];
					let IN_PROCESS = value['IN_PROCESS'];
					let FORM_VERIFIED = value['FORM_VERIFIED'];
					let FORM_REJECTED = value['FORM_REJECTED'];
					let TOTAL_ADMIT_CARDS = value['TOTAL_ADMIT_CARDS'];
					let NOT_DISPATCHED = value['NOT_DISPATCHED'];
					let DISPATCHED = value['DISPATCHED'];
					let NAME = value['NAME'];
					let SESSION_REMARKS = value['REMARKS'];
					let PROGRAM_TITLE = value['PROGRAM_TITLE'];

					if (program_type !==0)
					{
						NAME = NAME+" ("+PROGRAM_TITLE+' DEGREE PROGRAM)';
					}
					total_draft 	+=parseInt(DRAFT);
					total_submitted += parseInt(SUBMITTED);
					total_in_review +=parseInt(IN_REVIEW);
					total_in_process+= parseInt(IN_PROCESS);
					total_verified 	+=parseInt(FORM_VERIFIED);
					total_rejected 	+=parseInt(FORM_REJECTED);
					total_admit_cards 	+=parseInt(TOTAL_ADMIT_CARDS);
					total_not_dispatched+=parseInt(NOT_DISPATCHED);
					total_dispatched 	+=parseInt(DISPATCHED);

					submitted_pie_data[""+NAME+""] = parseInt(SUBMITTED);
					draft_pie_data[""+NAME+""] = parseInt(DRAFT);
				});
				localStorage.setItem('submitted_pie_campus',JSON.stringify(submitted_pie_data));
				localStorage.setItem('draft_pie_campus',JSON.stringify(draft_pie_data));
				loadStatistics ();
			}//success
		});
	}
	function get_statistics_area_wise_sindh (){

		let config = localStorage.getItem('config_analytics');
			config = JSON.parse(config);
		let session = config['SESSION_ID'];
		let program_type = config['PROGRAM_TYPE'];
		let campus = config['CAMPUS_ID'];

		if (session === "" || session === 0 || session == null || isNaN(session))
			session = 0;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			program_type = 0;
		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			campus = 0;

		let province_id = config['PROVINCE_ID'];
		let division_id = config['DIVISION_ID'];
		let district_id = config['DISTRICT_ID'];

		$.ajax({
			url:'<?=base_url()?>Statistics/application_district_wise',
			method: 'POST',
			data: {session:session,program_type:program_type,campus:campus,province_id:province_id,division_id:division_id,district_id:district_id},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				let total_draft 	= 0;
				let total_submitted = 0;
				let total_in_review = 0;
				let total_in_process= 0;
				let total_verified 	= 0;
				let total_rejected 	= 0;
				let total_admit_cards 	= 0;
				let total_not_dispatched= 0;
				let total_dispatched 	= 0;

				let submitted_pie_data = {};
				let draft_pie_data = {};
				let other_province_submitted_applications = 0;

				$.each(response, function (index,value) {
					i++;
					let DRAFT = value['DRAFT'];
					let SUBMITTED = value['SUBMITTED'];
					let IN_REVIEW = value['IN_REVIEW'];
					let IN_PROCESS = value['IN_PROCESS'];
					let FORM_VERIFIED = value['FORM_VERIFIED'];
					let FORM_REJECTED = value['FORM_REJECTED'];
					let TOTAL_ADMIT_CARDS = value['TOTAL_ADMIT_CARDS'];
					let NOT_DISPATCHED = value['NOT_DISPATCHED'];
					let DISPATCHED = value['DISPATCHED'];
					let NAME = value['NAME'];
					let SESSION_REMARKS = value['REMARKS'];
					let DISTRICT_NAME = value['DISTRICT_NAME'];
					let PROVINCE_ID = value['PROVINCE_ID'];
					let PROGRAM_TITLE = value['PROGRAM_TITLE'];

					if (program_type !=0)
					{
						DISTRICT_NAME = DISTRICT_NAME+" ("+PROGRAM_TITLE+' DEGREE PROGRAM)';
					}

						if (PROVINCE_ID != 6 )
						{
							other_province_submitted_applications+=parseInt(SUBMITTED);
							return;
						}
					total_draft 	+=parseInt(DRAFT);
					total_submitted += parseInt(SUBMITTED);
					total_in_review +=parseInt(IN_REVIEW);
					total_in_process+= parseInt(IN_PROCESS);
					total_verified 	+=parseInt(FORM_VERIFIED);
					total_rejected 	+=parseInt(FORM_REJECTED);
					total_admit_cards 	+=parseInt(TOTAL_ADMIT_CARDS);
					total_not_dispatched+=parseInt(NOT_DISPATCHED);
					total_dispatched 	+=parseInt(DISPATCHED);

					submitted_pie_data[""+DISTRICT_NAME+""] = parseInt(SUBMITTED);
					draft_pie_data[""+DISTRICT_NAME+""] = parseInt(DRAFT);
				});
				submitted_pie_data["OTHER PROVINCE"] = parseInt(other_province_submitted_applications);

				localStorage.setItem('submitted_pie_district',JSON.stringify(submitted_pie_data));
				// localStorage.setItem('draft_pie_district',JSON.stringify(draft_pie_data));
				loadStatistics ();
			}//success
		});
	}
	function loadStatistics () {
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
	}
	$(document).ready(function () {
		read_config();
		get_statistics();
		loadStatistics ();
// 		get_statistics_area_wise_sindh();
	});

</script>
