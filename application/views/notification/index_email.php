<div class="mailbox-compose-area mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<!--
			<div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
				<div class="hpanel shadow-inner responsive-mg-b-30">
					<div class="panel-body">
						<a href="mailbox_compose.html" class="btn btn-success compose-btn btn-block m-b-md">Compose</a>
						<ul class="mailbox-list">
							<li>
								<a href="#">
									<span class="pull-right">12</span>
									<i class="fa fa-envelope"></i> Inbox
								</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-paper-plane"></i> Sent</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-pencil"></i> Draft</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-trash"></i> Trash</a>
							</li>
						</ul>
						<hr>
						<ul class="mailbox-list">
							<li>
								<a href="#"><i class="fa fa-plane text-danger"></i> Travel</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-bar-chart text-warning"></i> Finance</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-users text-info"></i> Social</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-tag text-success"></i> Promos</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-flag text-primary"></i> Updates</a>
							</li>
						</ul>
						<hr>
						<ul class="mailbox-list">
							<li>
								<a href="#"><i class="fa fa-gears"></i> Settings</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-info-circle"></i> Support</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			--->
			<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
			    
				<div class="hpanel email-compose">
					<div class="panel-heading hbuilt">
						<div class="p-xs h4">
							New message
						</div>
					</div>
					<div class="panel-heading hbuilt">
						<div class="p-xs">
							<form method="get" class="form-horizontal">
								<div class="form-group">
									<label class="col-lg-1 control-label text-left">To:</label>
									<div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">
										<textarea name="email_to" id="email_to" class="form-control input-sm" placeholder="example@email.com;example@email.com" style="height: 50px"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-1 control-label text-left">Cc:</label>
									<div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">
										<input id="cc_to" name="cc_to" type="text" class="form-control input-sm" placeholder="example@email.com">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-1 control-label text-left">Bcc:</label>
									<div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">
										<input id="bcc_to" name="bcc_to" type="text" class="form-control input-sm" placeholder="example@email.com">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-1 control-label text-left">Subject:</label>
									<div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">
										<input type="text" name="subject" id="subject" class="form-control input-sm" placeholder="Subject">
									</div>
								</div>
							</form>
						</div>
					</div>
					<textarea class="summernote6" id="email_body" name="email_body"></textarea>

<!--					<div class="panel-body no-padding">-->
<!--						<div id="dropzone" class="dropmail">-->
<!--							<form action="/upload" class="dropzone dropzone-custom needsclick" id="demo-upload">-->
<!--								<div class="dz-message needsclick download-custom">-->
<!--									<i class="fa fa-cloud-download" aria-hidden="true"></i>-->
<!--									<h2>Drop files here or click to upload.</h2>-->
<!--									<p><span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>-->
<!--									</p>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--					</div>-->

					<div class="panel-footer">
<!--						<div class="pull-right">-->
<!--							<div class="btn-group active-hook">-->
<!--								<button class="btn btn-default"><i class="fa fa-view" id="view"></i> View Draft</button>-->
<!--								<button class="btn btn-default"><i class="fa fa-edit" id="save"></i> Save Draft</button>-->
<!--								<button class="btn btn-default"><i class="fa fa-trash" id="discard"></i> Discard</button>-->
<!--							</div>-->
<!--						</div>-->
						<button class="btn btn-primary ft-compse" id="send_email">Send email</button>
						<span id="msg"></span>
<!--						<div class="btn-group active-hook mail-btn-sd">-->
<!--							<button class="btn btn-default"><i class="fa fa-paperclip"></i> </button>-->
<!--							<button class="btn btn-default"><i class="fa fa-image"></i> </button>-->
<!--						</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#send_email").click(function (){
		send_email();
	});
	$("#save").click(function (){
		save_draft();
	});
	function save_draft(){

		let email_body 	= $("#email_body").val();
		let subject 	= $.trim($("#subject").val());
		let email_to 	= $.trim($("#email_to").val());
		let cc_to 		= $.trim($("#cc_to").val());
		let bcc_to 		= $.trim($("#bcc_to").val());

		email_to = replace_new_line(email_to);
		cc_to    = replace_new_line(cc_to);
		bcc_to   = replace_new_line(bcc_to);

		email_to= JSON.stringify(email_to);
		cc_to 	= JSON.stringify(cc_to);
		bcc_to 	= JSON.stringify(bcc_to);
		email_body 	= JSON.stringify(email_body);
		subject 	= JSON.stringify(subject);
		var  email_detail =  Array();
		 email_detail['TO']=email_to;
		 email_detail['CC']=cc_to;
		 email_detail['BCC']=bcc_to;
		 email_detail['BODY']=email_body;
		 email_detail['SUBJECT']=subject;

		console.log(email_detail);

	}
    function chunks_of_email_array(array){
        var i,j, chunk = 200;var temporary=[];
        for (i = 0,j = array.length; i < j; i += chunk) {
             temporary.push(array.slice(i, i + chunk));
                // do whatever
            }
            return temporary;
    }
	function send_email(){

		let email_body 	= $("#email_body").val();
		let subject 	= $.trim($("#subject").val());
		let email_to 	= $.trim($("#email_to").val());
		let cc_to 		= $.trim($("#cc_to").val());
		let bcc_to 		= $.trim($("#bcc_to").val());

		email_to = replace_new_line(email_to);
		email_to = email_array(email_to);
		chunks_email_to = chunks_of_email_array(email_to);

		cc_to = replace_new_line(cc_to);
		cc_to = email_array(cc_to)

		bcc_to = replace_new_line(bcc_to);
		bcc_to = email_array(bcc_to);

	

		if (confirm("Do you want to send email?") === false) return;
        for(let i = 0 ; i <chunks_email_to.length ; i++){
        email_to=chunks_email_to[i];
	    email_to = JSON.stringify(email_to);
		cc_to = JSON.stringify(cc_to);
		bcc_to = JSON.stringify(bcc_to);
// 		$('#msg').html('');
		$.ajax({
			url:'<?=base_url()?>Notification/email_ready_queue',
			method: 'POST',
			data: {email_body:email_body,subject:subject,email_to:email_to,cc_to:cc_to,bcc_to:bcc_to},
		
			// dataType: 'json',
			success: function(response){
				console.log(response);
				$('#loading').html('');
				$('#msg').append(response);
			},
			beforeSend:function (response) {
				//$('#msg').html("LOADING...!");
				//$('#msg').html(response);
			},
			error:function (response) {
				alertMsg("Error",resonse.responseText);
				$('#msg').html(response);
			},
				async:false,
		});
        }
	}

	function replace_new_line(text) {
		text = text.replace(/\r?\n|\r/g, "");
		return text;
	}//function
	function email_array(emails) {
		if (emails==null || emails==="" ) return null;
		emails = emails.split(";");
		return emails;
	}//function
</script>
