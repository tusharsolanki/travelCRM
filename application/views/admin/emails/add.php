<?php
/*
	@Description: Emails campaign add/edit page
    @Author: Sanjay Chabhadiya
    @Date: 06-08-2014

*/
// "phpuploader/include_phpuploader.php";

//require_once "phpuploader/ajax-attachments-handler.php";

$viewname = $this->router->uri->segments[2];
$this->filesizeCount = 0;
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
?>
<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
jQuery(document).ready(function(){
	$.unblockUI();
});
</script>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/uploadfilemulti.css" />-->

<style>
.ui-multiselect{width:100% !important;}
<?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back,.direct_send_mail,#save_campaign{ display:none !important;}
#content{ margin-left:0;}
<?php } ?>

</style>



<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tokeninput.js"></script>-->


<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<!--time picker-->
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.form.min.js"> </script>
<!--       BombBomb Video Integration    -->
<script type="text/javascript">
//var flag = 0;
var save_video_id = '<?=!empty($editRecord[0]['video_id'])?$editRecord[0]['video_id']:''?>';
$(document).ready(function() { 
	var options = { 
			target:   '',   // target element(s) to be updated with server response 
			//beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,
			error: error,  // post-submit callback 
			resetForm: true       // reset the form after successful submit 
		};
		
	$('body').on('click','#save',function(e){
		 $('#MyUploadForm').submit();
	});	
	$('#MyUploadForm').submit(function() {
			if($("#email").val().trim() != '' && $("#pw").val().trim() != '')
			{
				if ($('#MyUploadForm').parsley().isValid()) {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> It will take some time to upload video. Please wait...'});
					$(this).ajaxSubmit(options);
					//alert(res);
					return false;
				}
				else
					return false;
			}
			else
			{
				$.confirm({'title': 'Alert','message': " <strong> Please connect your BombBomb account under setting. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
		}}}});
				return false;
			}
			// always return false to prevent standard browser submit and page navigation
		}); 
});

function error(data)
{
    console.log(data.status);
	if(data.status == 500)
	{
		$.confirm({'title': 'Alert','message': " <strong> Something went wrong. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
			$.unblockUI();
		}}}});
	}
	return false;
}

function afterSuccess(data)
{
	$("#priview_doc").text('');
	$("#name").val('');
	$("#description").val('');
	$("#videoFile").val('');
	if(data.trim() != '')
	{
		data = JSON.parse(data);
		$.each(data,function(i,item){ 
			video_id = item.video_id;
		});
		$.unblockUI();
		$("#existing_video").html('');
		$( ".existing_video_click" ).trigger( "click" );
		getVideo(video_id);
	}
	else
	{
		$.unblockUI(video_id);	
	}
}
function getVideo(video_id)
{
	$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('admin_base_url').'bomb_library/VideoList';?>",
		 data: {video_id : video_id},
		 beforeSend: function() {
			$("#existing_video").addClass('holds-the-iframe');
		},
		 success: function(html){
			 $('#existing_video').html(html);
			 $("#existing_video").removeClass('holds-the-iframe');
		 }
    });
}
</script>
 
<div aria-hidden="true" style="display: none;" id="VideoModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
       <button type="button" class="close close_bombbomb_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
       <div class="">
        <ul id="myTab10" class="nav nav-tabs">
            <li  class="active"> <a href="#existing_video" class="existing_video_click" data-toggle="tab" title="Choose an existing video from BombBomb">Choose an existing video from BombBomb</a> </li>
            <li> <a href="#video_upload" data-toggle="tab" title="">Upload a New Video <i class="icon-remove-sign"></i></a> </li>
        </ul>
        <div id="myTab10Content" class="tab-content">
            <div class="smart-drip-plan-con-box1 tab-pane fade in active holds-the-iframe" id="existing_video">
				<!--<img src="<?=base_url()?>images/ajaxloader.gif" />-->
				<?php //$this->load->view('admin/'.$viewname.'/VideoList') ?>
            </div>
            
            <div class="row tab-pane fade in uploadvideomain" id="video_upload">
             <form class="form parsley-form" enctype="multipart/form-data" name="MyUploadForm" id="MyUploadForm" method="POST" accept-charset="utf-8" action="http://app.bombbomb.com/app/api/api.php?method=AddVideo" novalidate >
                <input type="hidden" name="email" id="email" value="<?=!empty($username)?$username:''?>">
                <input type="hidden" name="pw" id="pw" value="<?=!empty($password)?$password:''?>">
                <div class="col-sm-12 form-group">
                    <label for="text-input">Video Name<span class="val">*</span></label>
                    <input id="name" name="name" placeholder="e.g. Subject" class="form-control parsley-validated" type="text" value="" data-required="true">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="text-input">Description</label>
                    <input type="text" name="description" id="description" class="form-control parsley-validated">
                </div>
                <div class="col-sm-8 form-group">
                    <label for="text-input">Video<span class="val">*</span></label>
           <!--         <input type="file" name="videoFile" id="videoFile" class="form-control parsley-validated" data-required="true">-->
         			<div style="vertical-align:top;" class="ajax-upload-dragdrop"><div class="upload" style="position: relative; overflow: hidden; cursor: default;">
            <a class="btn btn-secondary-green">Upload</a><input type="file" name="videoFile" id="videoFile" style="position: absolute; cursor: pointer; top: 0px; width: 93px; height: 46px; left: 0px; z-index: 100; opacity: 0;" class="form-control parsley-validated" data-required="true"></div></div>
                    <span id="priview_doc"></span>
                </div>
                
                
         	</form>
            <div class="col-lg-12 clear form-group text-center">
                    <input type="button" name="save" id="save" value="Save" class="btn btn-secondary">
                    <a class="btn btn-primary" title="Cancel" onclick="bombbomb_close_popup()" id="elp_cancel">Cancel</a>
                </div>
                <!--<div class="col-sm-8 clear"></div>
                <div class="col-sm-4 clear form-group">
                    <input type="button" name="upload" id="upload" value="upload" class="btn btn-secondary">
                </div>-->
            
            </div>
        </div>
      </div>
	 </div>
   </div>
   <div class="modal-body view_page">
			
   </div>
 </div>
    <!-- /.modal-content -->
</div>
<!--       End    -->

<!-- Select Contact To -->

<div aria-hidden="true" style="display: none;" id="basicModal_to" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_to" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_to();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_to();">View All</button>
		   </div>
        </div>
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type_to' id='contact_type_to' onchange="contact_ser_to();">
                	<option value="">Please Select</option>
                    <?php if(!empty($contact_list)){
                    		foreach($contact_list as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='contact_source_to' id='contact_source_to' onchange="contact_ser_to();">
                	<option value="">Please Select</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="contact_status_to" id="contact_status_to" onchange="contact_ser_to();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag_to" class="form-control inputsrh pull-left" name="search_tag_to">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag_to").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{onAdd: function (item) {
					contact_ser_to();
				},onDelete: function (item) {
					contact_ser_to();
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_to"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 contact_to">
          <div class="">
		  <?php $this->load->view('admin/emails/contact_to');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- Select Contact CC -->

<div aria-hidden="true" style="display: none;" id="basicModal_cc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_cc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_cc();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_cc();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type_cc' id='contact_type_cc' onchange="contact_ser_cc();">
                	<option value="">Please Select</option>
                    <?php if(!empty($contact_list)){
                    		foreach($contact_list as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='contact_source_cc' id='contact_source_cc' onchange="contact_ser_cc();">
                	<option value="">Please Select</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="contact_status_cc" id="contact_status_cc" onchange="contact_ser_cc();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag_cc" class="form-control inputsrh pull-left" name="search_tag_cc">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag_cc").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{onAdd: function (item) {
					contact_ser_cc();
				},onDelete: function (item) {
					contact_ser_cc();
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_cc"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection_cc();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 contact_cc">
          <div class="">
		  <?php $this->load->view('admin/emails/contact_cc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan_cc();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- Select Contact BCC -->

<div aria-hidden="true" style="display: none;" id="basicModal_bcc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_bcc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_bcc();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_bcc();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type_bcc' id='contact_type_bcc' onchange="contact_ser_bcc();">
                	<option value="">Please Select</option>
                    <?php if(!empty($contact_list)){
                    		foreach($contact_list as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='contact_source_bcc' id='contact_source_bcc' onchange="contact_ser_bcc();">
                	<option value="">Please Select</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="contact_status_bcc" id="contact_status_bcc" onchange="contact_ser_bcc();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag_bcc" class="form-control inputsrh pull-left" name="search_tag_bcc">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag_bcc").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{onAdd: function (item) {
					contact_ser_bcc();
				},onDelete: function (item) {
					contact_ser_bcc();
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_bcc"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection_bcc();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 contact_bcc">
          <div class="">
		  <?php $this->load->view('admin/emails/contact_bcc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan_bcc();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- To select Contact Type -->


<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type </h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_type"></label> | 
              <a class="text_color_red text_size " onclick="remove_selected_type_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="table-responsive">
		  <?php $this->load->view('admin/emails/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->
<!-- CC select Contact Type -->

<div aria-hidden="true" style="display: none;" id="basicModal2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax_cc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax_cc">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search_cc();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact_cc();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_type_cc"></label> | 
              <a class="text_color_red text_size " onclick="remove_selected_type_cc();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup1">
          <div class="table-responsive">
		  <?php $this->load->view('admin/emails/add_contact_popup_ajax_cc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan_cc();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- End -->

<!-- BCC select Contact Type -->
<div aria-hidden="true" style="display: none;" id="basicModal3" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax_bcc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax_bcc">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search_bcc();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact_bcc();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_type_bcc"></label> | 
              <a class="text_color_red text_size " onclick="remove_selected_type_bcc();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup_bcc">
          <div class="table-responsive">
		  <?php $this->load->view('admin/emails/add_contact_popup_ajax_bcc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan_bcc();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- End -->


<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('email_campaign');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php  if($viewname == 'emails') echo $this->lang->line('email_campaign'); else echo $this->lang->line('bombbomb_email_campaign');
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       	<span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
             <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
                <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                <input id="video_id" name="video_id" type="hidden" value="<?=!empty($editRecord[0]['video_id'])?$editRecord[0]['video_id']:''?>">
                <input id="thumb_url" name="thumb_url" type="hidden" value="<?=!empty($editRecord[0]['thumb_url'])?$editRecord[0]['thumb_url']:''?>">
                <input id="video_title" name="video_title" type="hidden" value="<?=!empty($editRecord[0]['video_title'])?$editRecord[0]['video_title']:''?>">
               <div class="row">
               <div class="col-sm-8">
               <div class="row">
                 <div class="col-sm-12 form-group">
                  
               <?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
                 <label for="validateSelect">Action Type:</label>
                             
                 <select  class="form-control parsley-validated" name="slt_interaction_type" id="slt_interaction_type" onchange="selecttemplate(this.value)"  >
                      <option value="6">Email</option>  
                      <?php if(!empty($connection) && !empty($connection[0]['bombbomb_username']) && !empty($connection[0]['bombbomb_password']) && !empty($this->modules_unique_name) && in_array('bomb_bomb_email_blast_add',$this->modules_unique_name)){ ?><option value="8">Bomb Bomb Emails</option><?php } ?>
                 </select>
                  
                 <label for="text-input"><?=$this->lang->line('email_to');?><span class="val">*</span> </label>
                    <label for="text-input">
                    <?php if(!empty($email_to) && count($email_to) > 0){
                     echo ucwords($email_to[0]['contact_name']).'('.$email_to[0]['email_address'].')';
                    ?>
                    </label>
                     <input type="hidden" name="email_to" id="email_to" value="<?=!empty($email_to[0]['id'])?$email_to[0]['id'].'-'.$this->router->uri->segments[5]:'0'?>" />
                     <?php } ?>
              <?php } else {?>
                                     <label for="text-input"><?=$this->lang->line('email_to');?><span class="val">*</span> </label>
                  <input id="email_to" name="email_to" class="form-control parsley-validated" type="text" value="" data-required="true" placeholder="">
            <script type="text/javascript">
                $(document).ready(function() {
                
                    $("#email_to").tokenInput([ 
                    <?php foreach($contact as $row){ ?>
                        {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=!empty($row['first_name'])?ucwords(addslashes(trim($row['first_name']))):''?><?=!empty($row['middle_name'])?' '.ucwords(addslashes(trim($row['middle_name']))):''?><?=!empty($row['last_name'])?' '.ucwords(addslashes(trim($row['last_name']))):''?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                    <?php } ?>
                    ],
                    {prePopulate:[
                        <?php 
                        if(isset($email_to)) {
                        foreach($email_to as $row){ ?>
                            {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=ucwords(addslashes($row['contact_name']))?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                        <?php } } ?>
                        <?php if(isset($contact_type_to)) {
                        foreach($contact_type_to as $row){ ?>
                            {id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
                        <?php } } ?>
                    ],
                    onAdd: function (item) {
                        var str1 = item.id.split('-');
                        if(!isNaN(str1[0]))
                        {
                            //var myvalue = str.substr(3);
                            add_contact_to(item.id);
                        }
                    },
                    onDelete: function (item) {
                        var str = item.id.split('-');
                        //var char = str.substr(0,2);
                        if(isNaN(str[0]))
                        {
                            var myvalue = str.substr(3);
                            remove_to(myvalue);
                        }
                        else
                        {
                            remove_contact_to(item.id);
                        }
                    },
                    preventDuplicates: true,
                    hintText: "Enter Contact Name",
                    noResultsText: "No Contact Found",
                    searchingText: "Searching...",
                    theme: "facebook",
                    } 
                    );
                
            });
            </script>
                <?php if(($formAction == 'insert_data' && empty($this->router->uri->segments[4])) || $formAction == 'update_data') { ?><a href="#basicModal_to" class="text_color_red text_size" id="contact_to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
                 
                 <!--<a href="#basicModal1" class="text_color_red text_size" id="to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>-->
                 <?php } ?>
                 </div>
                </div>
                <div class="row email_class_cc <?php if($viewname == 'bomb_emails') echo 'display_none'; ?>">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('email_cc');?> </label>
                  <input id="email_cc" name="email_cc" class="form-control parsley-validated" type="text" value="" >
                   <script type="text/javascript">
                        $(document).ready(function() {
                        
                            $("#email_cc").tokenInput([ 
                            <?php foreach($contact as $row){ ?>
                                {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=!empty($row['first_name'])?ucwords(addslashes(trim($row['first_name']))):''?><?=!empty($row['middle_name'])?' '.ucwords(trim(addslashes($row['middle_name']))):''?><?=!empty($row['last_name'])?' '.ucwords(addslashes(trim($row['last_name']))):''?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                            <?php } ?>
                            ],
                            {prePopulate:[
                                <?php 
                                if(isset($email_cc)) {
                                foreach($email_cc as $row){ ?>
                                    {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=ucwords(addslashes($row['contact_name']))?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                                <?php } } ?>
                                
                                <?php if(isset($contact_type_cc)) {
                                foreach($contact_type_cc as $row){ ?>
                                    {id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
                                <?php } } ?>
                            ],
                            onAdd: function (item) {
                                var str1 = item.id.split('-');
                                
                                //var str1 = item.id;
                                if(!isNaN(str1[0]))
                                {
                                    //var myvalue = str.substr(3);
                                    add_contact_cc(item.id);
                                }
                            },
                            onDelete: function (item) {
                                var str = item.id.split('-');
                                //var char = str.substr(0,2);
                                if(isNaN(str[0]))
                                {
                                    var myvalue = str.substr(3);
                                    remove_cc(myvalue);
                                }
                                else
                                {
                                    remove_contact_cc(item.id);
                                }
                            },
                            preventDuplicates: true,
                            hintText: "Enter Contact Name",
                            noResultsText: "No Contact Found",
                            searchingText: "Searching...",
                            theme: "facebook"}
                            );
                        
                    });
                    </script>
                    <?php if(($formAction == 'insert_data' && empty($this->router->uri->segments[4])) || $formAction == 'update_data') { ?>
                    <a href="#basicModal_cc" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
                    
                     <!--<a href="#basicModal2" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>-->
                     <?php } ?>
                 </div>
                </div>
                <div class="row email_class_bcc <?php if($viewname == 'bomb_emails') echo 'display_none'; ?>">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('email_bcc');?> </label>
                  <input id="email_bcc" name="email_bcc" class="form-control parsley-validated" type="text" value="" >
                   
                   <script type="text/javascript">
                        $(document).ready(function() {
                        
                            $("#email_bcc").tokenInput([ 
                            <?php foreach($contact as $row){ ?>
                                {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=!empty($row['first_name'])?ucwords(trim(addslashes($row['first_name']))):''?><?=!empty($row['middle_name'])?' '.ucwords(addslashes(trim($row['middle_name']))):''?><?=!empty($row['last_name'])?' '.ucwords(addslashes(trim($row['last_name']))):''?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                            <?php } ?>
                            ],
                            {prePopulate:[
                                <?php 
                                if(isset($email_bcc)) {
                                foreach($email_bcc as $row){ ?>
                                    {id: '<?=$row['id'].'-'.$row['email_trans_id']?>', name: "<?=ucwords(addslashes($row['contact_name']))?>(<?=$row['email_address']?>)<?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?>"},
                                <?php } } ?>
                                
                                <?php if(isset($contact_type_bcc)) {
                                foreach($contact_type_bcc as $row){ ?>
                                    {id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
                                <?php } } ?>
                            ],
                            onAdd: function (item) {
                                var str1 = item.id.split('-');
                                if(!isNaN(str1[0]))
                                {
                                    //var myvalue = str.substr(3);
                                    add_contact_bcc(item.id);
                                }
                            },
                            onDelete: function (item) {
                                var str = item.id.split('-');
                                //var char = str.substr(0,2);
                                if(isNaN(str[0]))
                                {
                                    var myvalue = str.substr(3);
                                    remove_bcc(myvalue);
                                }
                                else
                                {
                                    remove_contact_bcc(item.id);
                                }
                            },
                            preventDuplicates: true,
                            hintText: "Enter Contact Name",
                            noResultsText: "No Contact Found",
                            searchingText: "Searching...",
                            theme: "facebook"}
                            );
                        
                    });
                    </script>
                    <?php if(($formAction == 'insert_data' && empty($this->router->uri->segments[4])) || $formAction == 'update_data') { ?>
                    <a href="#basicModal_bcc" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
                    
                     <!--<a href="#basicModal3" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>-->
                     <?php } } ?>
                 </div>
                </div>
                
               
                <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"><?=$this->lang->line('common_label_category');?> </label>
                  </div>
                  <div class="col-sm-12">
                  <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)" >
                  <option value="-1">Category</option>
                     <?php if(isset($category) && count($category) > 0){
                                foreach($category as $row1){
                                    if(!empty($row1['id'])){?>
                    <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category_id']) && $editRecord[0]['template_category_id'] == $row1['id']){ echo "selected=selected"; } ?> ><?php echo ucwords($row1['category']);?></option>
                    <?php 		}
                                }
                            } ?>
                  </select>
                  </div>
                    
                  <!--<div class="col-sm-6">
                  <select class="selectBox" name='slt_subcategory' id='subcategory'>
                  </select>
                  <span id="category_loader"></span>
                  </div>-->
                  
                </div>
                 <div class="row">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('template_label_name');?> </label>
                   <select class="selectBox" name='template_name' id='template_name'>
                  </select>
                 </div>
                </div>
               <div class='row mycalclass'>
             <div class="col-sm-12 form-group">
             <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?> <span class="val">*</span></label>
             <input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo $editRecord[0]['template_subject']; }?>" data-required="true" placeholder="e.g. Email Subject">
              </div>
              </div>
              </div>
              <div class="col-sm-4 margin-top-10px <?php if($viewname == 'emails') { echo 'display_none'; } ?>" id="add_new_video" >
              	<a href="#VideoModal" class="margin-top-15px" id="VideoModal" data-toggle="modal"> 
                <div class="text-center font_20px">
                    <img class="img-responsive imgradius" src="<?=base_url()?>images/bomb_image.jpg" alt="Click Here to Add Video" title="Click Here to Add Video"/>
                    <span>Click Here to Add Video</span>
                </div>
                
                 </a>
              	<br />
              </div>
              <? if($viewname == 'emails') {?>
              <div class="col-sm-4">
                
                <div class="attachfiel">
                   <label>Attach File :</label>
          <!--<div class="fileUpload btn btn-primary">-->
          <div>
            <p> 
                <div id="mulitplefileuploader">
                <a class="btn btn-secondary-green">Upload</a></div>
                
                <div id="status">
                </div>
            <!--<button class="uploadercancelbuttonbtn" id="uploadercancelbutton" style="display:none;" onclick="uploadercancelbuttonbtn();" >Cancel</button>
            <button id="submitbutton" onclick="doStart();return false;">Start Uploading Files</button>-->
            <label class="allowed-file col-sm-12"> Allowed File Types: jpeg, jpg, gif, png, zip, docx, txt, xls, xlsx, pdf, doc </label>
            </p>	
            </div>
            <div id="common_div">
            <?php
             if(isset($attachment) && count($attachment) > 0) {
                    $this->load->view('admin/emails/attachmentlist');
                }
             ?>
            
            </div>
             
    </div>
                
              </div>
              <? } ?>
                </div>
               <div class="row">
                 <div class="col-sm-8">
                <div class="form-group">
                      <label for="select-multi-input">
                        Email Message
                      </label>
          <textarea name="email_message" id="email_message" class="form-control parsley-validated" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:'';?></textarea>			  
                      <script type="text/javascript">
                                                    CKEDITOR.replace('email_message',
                                                     {
                                                        fullPage : false,
                                                        
                                                        //toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
                                                        
                                                        baseHref : '<?=$this->config->item('ck_editor_path')?>',
                                                        filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
                                                        filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
                                                    }, {width: 200});												
                                                </script>
                    </div>
                 <div class="row">
                    <div class="col-sm-5">
                   <label for="text-input">Signature
                   </label>
                  <select id="email_signature" name="email_signature" class="form-control parsley-validated" >
                       <option value="">Please Select</option>
                       <?php foreach($email_signature_data as $row) { ?>
                       <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['email_signature']) && $editRecord[0]['email_signature'] == $row['id']){ echo "selected=selected"; } elseif(!isset($editRecord) && $row['is_default'] == '1') { echo "selected=selected"; } ?> > <?=$row['signature_name']?>  </option>
                       <?php } ?>
                       </select>
                    </div>
                    </div>   
                    <div class="row direct_send_mail">
                    <div class="col-sm-12">
                      <fieldset class="schedule_campaign">
                         <legend>Schedule Blast</legend>
                           <div class="schedule_detail">
                             <p>Send this Blast ?</p> 
                             <div class="col-sm-12 pull-left">
                                  
                             <div class="float-left margin-left-15"><label class="checkbox">
                              Now
                                <div class="col-sm-3">
                                
                                <input type="radio" value="1" class="" id="chk_is_lead1" name="chk_is_lead" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '1' || (!empty($send_now) && $send_now == 'send')){ echo "checked=checked"; } ?> >  
                                </div>
                                 </label>
                            </div> 
                                                    
                            </div>
                            <div class="col-sm-12 pull-left"> 
                            <div class="float-left margin-left-15">
                             <label class="checkbox">
                              Date
                                <div class="col-sm-2">
                                
                                <input type="radio" name="chk_is_lead" id="chk_is_lead2" class="" value="2"  <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2' && $send_now == ''){ echo "checked=checked"; } ?> > 
                                </div>
                                 </label>
                                 </div>
                                <div class="col-sm-12 col-lg-5 col-md-8 sms_add_record">
                                    <input name="send_date" placeholder="Specific Date" id="send_date" class="form-control parsley-validated" type="text" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2') { ?>value="<?=!empty($editRecord[0]['email_send_date'])?$editRecord[0]['email_send_date']:''?>" <?php } ?> readonly="readonly"/>
                                    
                                </div>
                                <div class="col-sm-12 col-lg-4 col-md-9 sms_add_record">
                                <label class="col-sm-3 col-lg-3 mrg27">Time</label>
                                    <input name="send_time" placeholder="Time" readonly="readonly" id="send_time" type="text" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2') { ?> value="<?=!empty($editRecord[0]['email_send_time'])?date("h:i A", strtotime($editRecord[0]['email_send_time'])):''?>" <?php } ?> /><!--<i class="fa fa-clock-o"></i>-->             <button type="button" class="col-sm-3 col-md-2 col-lg-1 timepicker_disable_button_trigger timepick" onclick="send_time_focus();"><img src="<?=base_url('images/timecal.png')?>" alt="..." title="..."></button>
                                   
                                </div>
                              </div>
                           </div>
                      </fieldset>
                    </div>
                 </div>
              </div>
			  <div class="col-sm-4">
                <label>Custom Field</label>
                <div class="custom_field">
               
              
               <select ondblclick="addfieldtoeditor()" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox">
             <?php if(isset($tablefield_data) && count($tablefield_data) > 0){ ?>
             <?php foreach($tablefield_data as $row){ 
                    if(!empty($row['name'])){
                    $pattern = $this->config->item('email_param_pattern');
                    if(empty($pattern))
                        $pattern = "{(%s)}";
                    $fieldval = !empty($row['name'])?$row['name']:$row['name'];
                                                             ?>
             <option title="<?php echo $fieldval;?>" value="<?php echo sprintf($pattern, $fieldval);?>"> <?php echo $fieldval;?> </option>
             <?php }
                 } ?>
             <?php } ?>
            </select>
               
                </select>
                 <input class="btn btn-secondary-green" type="button" name="submitbtn1" onclick="addfieldtoeditor();" value="Insert Field">
               
            <div class="unsubscribe" style="display:none;">
             <div class="col-sm-12 pull-left"><label class="checkbox">
              Is Unsubscribe 
              <div class="float-left margin-left-15">
               <input type="checkbox" name="is_unsubscribe" id="is_unsubscribe" class="" value="1" checked="checked" <?php //if(!empty($editRecord[0]['is_unsubscribe']) && $editRecord[0]['is_unsubscribe'] == '1'){ echo "checked=checked"; } ?> >
              </div>
              </label></div> 
          </div>
          </div>
			  </div>
                 <!--<div class="video_data">
                <?php // if(!empty($editRecord[0]['video_id'])){ ?>
                    <div class="addpic addpic1 pull-left margin-top-10px">
                        <div class="videopic">
                         <img src="<? //=!empty($editRecord[0]['thumb_url'])?$editRecord[0]['thumb_url']:base_url('images/no_image.jpg');?>" height="150" width="150" />
                        </div>
                        <span class="videopicname"><? //=!empty($editRecord[0]['video_title'])?$editRecord[0]['video_title']:''?></span>
                    </div>
                    <a class="btn btn-xs btn-primary margin-top-10px" onclick="delete_video();" href="javascript:void(0);" title="Remove Video"><i class="fa fa-times"></i></a>
               <?php // } ?>
                </div>-->
               </div>
              <div class="col-sm-12 pull-left text-center margin-top-10">
              <input type="hidden" id="email_to_contact_type" name="email_to_contact_type" value="<?=!empty($contact_type_to_selected)?$contact_type_to_selected:''?>" />
              <input type="hidden" id="email_cc_contact_type" name="email_cc_contact_type" value="<?=!empty($contact_type_cc_selected)?$contact_type_cc_selected:''?>" />
              <input type="hidden" id="email_bcc_contact_type" name="email_bcc_contact_type" value="<?=!empty($contact_type_bcc_selected)?$contact_type_bcc_selected:''?>" />
              
               <input type="hidden" id="email_contact_to" name="email_contact_to" value="<?=!empty($select_to)?$select_to:''?>" />
                <input type="hidden" id="email_contact_cc" name="email_contact_cc" value="<?=!empty($select_cc)?$select_cc:''?>" />
                 <input type="hidden" id="email_contact_bcc" name="email_contact_bcc" value="<?=!empty($select_bcc)?$select_bcc:''?>" />
              
    <input type="hidden" id="contacttab" name="contacttab" value="1" />
    <input type="hidden" name="last_id" value="" id="last_id" />
    <input type="hidden" name="fileName" value="" id="fileName"  />
    <input type="submit" class="btn btn-secondary-green" id="send_now" value="Send Now" title="Send Now" onclick="return validation_date();" name="submitbtn" />
    <input type="submit" class="btn btn-secondary-green" id="save_campaign" value="Save Blast" title="Save Blast" onclick="return validation_date()" name="submitbtn1" />
    <input type="submit" class="btn btn-secondary-green" id="save_template" value="Save Template As" title="Save Template As" onclick="return validation_date()" name="submitbtn2" />
    <?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
        <input type="hidden" name="email_trans_id" id="email_trans_id" value="<?=$this->router->uri->segments[5]?>" />
        <a class="btn btn-primary" title="Close" onclick="close_popup()">Close</a>
    <?php } else { ?>
        <a class="btn btn-primary" href="javascript:history.go(-1);" title="Close" id="btncancel">Close</a>
    <?php } ?>
             </div>
              </form>
         
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>  
</div>

<script type="text/javascript">

function send_time_focus()
{
	$('#send_time').focus();	
}
function file_attachmentname()
{
	var inp = document.getElementById('file_attachment');
	var name = '';
	for (var i = 0; i < inp.files.length; ++i) {
	  name = name + inp.files.item(i).name + ",";
	}
	var cnt = name.lastIndexOf(",");
    name = name.substring(0,cnt);
	$("#selectedfile").html(name);
}
	$("select#category").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();
	$("select#subcategory").multiselect({
		 multiple: false,
		 header: "Sub-Category",
		 noneSelectedText: "Sub-Category",
		 selectedList: 1
	}).multiselectfilter();
	$("select#template_name").multiselect({
		 multiple: false,
		 header: "Template Name",
		 noneSelectedText: "Template Name",
		 selectedList: 1
	}).multiselectfilter();
 
  
function selectsubcategory(category_id){
//var subcategory_id = $("#subcategory").val();
	//var option1 = $('<option />');
	//option1.attr('value', '').text('Fetching Template(s)...');
	$('#template_name').html("<option>Fetching Template(s)...</option>");
	$("select#template_name").multiselect('refresh').multiselectfilter();
	
	if(category_id!="-1"){
		var subcategory_id = '';
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}	
}

function loadData(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory_id']?>';
			
			<?php } ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#subcategory').append(option);
			});
		$("select#subcategory").multiselect('refresh').multiselectfilter();				
     }
   });
}

$("#subcategory").change(function() {
	var subcategory_id = $("#subcategory").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category").val();
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}
});
var selectedid = 0;
function loadtemplateData(loadType,loadcategoryId){
//	loadsubcategoryId
//,loadsubcategoryId:loadsubcategoryId
//alert(loadcategoryId);

var send_blast_type = $("#slt_interaction_type").val();
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,send_blast_type:send_blast_type},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name_id']?>';
			selectedid = parseInt(selectedid) + 1;
			<?php }  ?>
			
			
			if(result.length)
			{
				$.each(result,function(i,item){ 
						var option = $('<option />');
					
						option.attr('value', item.id).text(this.template_name);
						
						if(selectedsubcat == item.id && selectedid == 1)
							
							option.attr("selected","selected");
						
						$('#template_name').append(option);
				});
			}
			else
			{
				//$("#txt_template_subject").val('');
				//CKEDITOR.instances.email_message.setData('');	
				$("#template_name").html("<option value='-1'>No Template Available</option>");
			}
		
		$("select#template_name").multiselect('refresh').multiselectfilter();
						
     }
   });
}

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>

$("#template_name").change(function() {
//alert($("#template_name").val());
	<?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
		if($("#slt_interaction_type").val().trim() == 6)
			var viewname = 'emails';
		else if($("#slt_interaction_type").val().trim() == 8)
			var viewname = 'bomb_emails';
	<?php }else{ ?>
		var viewname = '<?=$viewname?>';
	<?php } ?>

	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: '<?php echo $this->config->item('admin_base_url')?>'+viewname+'/ajax_templatename',
		 data: {template_id:$("#template_name").val()},
		 cache: false,
		 success: function(result){
		 //alert(result);
			
		 	if(result != -1)
			{
		 	$.each(result,function(i,item){
				//alert(item.email_message);
				$("#txt_template_subject").val(item.template_subject);
				//editor.insertText("ckEditor");
				CKEDITOR.instances.email_message.setData(item.email_message);	
				//CKEDITOR.instances.email_message.insertText(item.email_message);
				//$("#email_message").innerHTML(item.email_message);
				if(viewname == 'bomb_emails' && item.video_id != '' && item.thumb_url != '' && item.video_title != '')
				{
					$("#thumb_url").val(item.thumb_url);
					$("#video_title").val(item.video_title);
					$("#video_id").val(item.video_id);
					var html = '<div class="addpic addpic1 pull-left margin-top-10px">';
					html += '<div class="videopic">';
                    html += '<img src="'+item.thumb_url+'" height="150" width="150" />';
                    html += '</div>';
                    html += '<span class="videopicname">'+item.video_title+'</span>';
					html += '</div>';
					html += '<a class="btn btn-xs btn-primary margin-top-10px" onclick="delete_video();" href="javascript:void(0);" title="Remove Video"><i class="fa fa-times"></i></a>';
					$('.video_data').html(html);	
				}
				else if(viewname == 'bomb_emails')
				{
					$("#thumb_url").val('');
					$("#video_title").val('');
					$("#video_id").val('');
					$('.video_data').html('');
				}
			});
			}
			else
			{
				$("#txt_template_subject").val('');
				CKEDITOR.instances.email_message.setData('');
				if(viewname == 'bomb_emails')
					$('.video_data').html('');
			}
		 }
		});
});
</script>

<script type="text/javascript">
function validation_date()
{
	if($('input:radio[name=chk_is_lead]:checked').val() == '2')
	{
		//alert($("#send_date").val());
		//alert($('input:radio[name=chk_is_lead]:checked').val() == '2');
		if($("#send_date").val() != '')
		{
                    if ($('#<?php echo $viewname?>').parsley().isValid()) {
                        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                    }
                    return true;
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select specific Date"+"<strong></strong>",
						'buttons': {'ok'	: {
						'class'	: 'btn_center alert_ok',	
						'action': function(){
						 $('#send_date').focus();
						}},  }});
			return false;
		}
	}
	else
        {
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            }
            return true;
        }
}


function removefile()
{
	$( "div" ).remove( ".upload-filename" );
	//$("#upload-filename").removeClass('.upload-filename');
}
var filesizeCount = <?=!empty($this->filesizeCount)?$this->filesizeCount:'0'?>;
var file_count = 0;
var sucess_count =  0;
$(document).ready(function (){
	
	var settings = {
	url: "<?php echo base_url();?>admin/<?=$viewname?>/upload_image",
	method: "POST",
	allowedTypes:"jpeg,jpg,gif,png,zip,docx,txt,xls,xlsx,pdf,doc",
	fileName: "myfile",
	multiple: true,
	onSubmit: function() {
		$('.attachfiel').block({ message: 'Please Wait...' }); 
	},
	onSuccess:function(files,data,xhr)
	{
		//alert(file_count);
		sucess_count++;
		//alert(data);
		var site = $("#fileName").val();
		if(site != '')
			$("#fileName").val(site + ',' + data);
		else
			$("#fileName").val(data);
		if(file_count == sucess_count)
		{
			$('.attachfiel').unblock();
			$("#status").html("<font color='green'>File Uploaded Successfully</font>");
		}
	},
    afterUploadAll:function()
    {
        //alert("all images uploaded!!");
    },
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);
	$('#count_selected_to').text(popupcontact_to.length + ' Record Selected');
	$('#count_selected_cc').text(popupcontact_cc.length + ' Record Selected');
	$('#count_selected_bcc').text(popupcontact_cc.length + ' Record Selected');
	$('#count_selected_type').text(popupcontactlist.length + ' Record Selected');
	$('#count_selected_type_cc').text(popupcontactlist_cc.length + ' Record Selected');
	$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record Selected');
	
	$(function() {
		$( "#send_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
	});
	
	$('#send_time').timepicker({
		showNowButton: true,
		showDeselectButton: true,
		showPeriod: true,
		showLeadingZero: true,
		defaultTime: '',  // removes the highlighted time for when the input is empty.
		showCloseButton: true
	});
});
</script>
<script type="text/javascript">
	var arraydatacount = 0;
	var popupcontactlist = Array();
	
	$('body').on('click','#selecctall',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex >= 0)
				{
					popupcontactlist.splice( arrayindex, 1 );
					arraydatacount--;
				}
				
            });        
        }
		$('#count_selected_type').text(popupcontactlist.length + ' Record selected');
    });
	
	$('body').on('click','#common_tb a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist.length;i++)
				{
					$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup').unblock();
		}
	});
	return false;
	});
	

	$('#search_contact_popup_ajax').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search();
			}
	});
	
	function clearfilter_contact()
	{
		$("#search_contact_popup_ajax").val("");
		contact_search();
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup').unblock(); 
			}
		});
		return false;
	}
	
	function addcontactstointeractionplan()
	{
		/*	$(document).ready(function() {
						
			$("#email_to").tokenInput("add", {id: 47, name: "Java"})
		}); */
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist
		},
		beforeSend: function() {
				$('.added_contacts_list').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_to_contact_type").val().trim() != '')
				{	
					var str = $("#email_to_contact_type").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						//alert(res[i]);
						$("#email_to").tokenInput("remove", {id: "CT-"+res[i]});
					}
				}
				
				//alert(i);
				//var i = 0;
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist );
				
					if(arrayindex == -1)
					{
						$('.mycheckbox:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontactlist[arraydatacount++] = parseInt(item.id);
					}
					$("#email_to").tokenInput("add", {id: "CT-"+item.id, name: item.name});
				});
				$('#count_selected_type').text(popupcontactlist.length + ' Record selected');
				$("#email_to_contact_type").val(popupcontactlist);
				$('.added_contacts_list').unblock(); 
			}
		});
	}	

	
	/*$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		
	});*/
	
	function contact_type_to_checkbox(contact_type_id)
	{
		if($('.mycheckbox:checkbox[value='+parseInt(contact_type_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_type_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected_type').text(popupcontactlist.length + ' Record selected');
	}
	
<?php 
if(isset($contact_type_to)){
	foreach($contact_type_to as $row){?>

		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		//alert(myvalue);
		$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontactlist.splice( arrayindex, 1 );
		arraydatacount--;
	}
	$('#count_selected_type').text(popupcontactlist.length + ' Record selected');
	//alert(popupcontactlist);
}

</script>

<!-- CC Contact type Script  -->

<script type="text/javascript">
	var arraydatacount_cc = 0;
	var popupcontactlist_cc = Array();
	
	$('body').on('click','#selecctall_cc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_cc').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
				
				if(arrayindex == -1)
				{
					popupcontactlist_cc[arraydatacount_cc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_cc').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
				
				if(arrayindex >= 0)
				{
					popupcontactlist_cc.splice( arrayindex, 1 );
					arraydatacount_cc--;
				}
            });        
        }
		$('#count_selected_type_cc').text(popupcontactlist_cc.length + ' Record selected');
    });
	
	$('body').on('click','#common_tb_cc a.paginclass_A',function(e){
	
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax_cc").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup1').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup1").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist_cc.length;i++)
				{
					$('.mycheckbox_cc:checkbox[value='+popupcontactlist_cc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup1').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_popup_ajax_cc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search_cc();
			}
	});
	
	function clearfilter_contact_cc()
	{
		$("#search_contact_popup_ajax_cc").val("");
		contact_search_cc();
	}
	
	function contact_search_cc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_ajax_cc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax_cc").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup1').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup1").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist_cc.length;i++)
					{
						$('.mycheckbox_cc:checkbox[value='+popupcontactlist_cc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup1').unblock(); 
			}
		});
		return false;
	}
	
	/*$('body').on('click','.mycheckbox_cc',function(e){
		
		if($('.mycheckbox_cc:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
			if(arrayindex == -1)
			{				
				popupcontactlist_cc[arraydatacount_cc++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
			if(arrayindex >= 0)
			{
				popupcontactlist_cc.splice( arrayindex, 1 );
				arraydatacount_cc--;
			}
		}
	});*/
	
	function contact_type_cc_checkbox(contact_type_id)
	{
		if($('.mycheckbox_cc:checkbox[value='+parseInt(contact_type_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist_cc );
			if(arrayindex == -1)
			{				
				popupcontactlist_cc[arraydatacount_cc++] = parseInt(contact_type_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist_cc );
			if(arrayindex >= 0)
			{
				popupcontactlist_cc.splice( arrayindex, 1 );
				arraydatacount_cc--;
			}
		}
		$('#count_selected_type_cc').text(popupcontactlist_cc.length + ' Record selected');
	}
	
	function addcontactstointeractionplan_cc()
	{
		//$("#email_cc_contact_type").val(popupcontactlist_cc);
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist_cc
		},
			beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
					  },
				success: function(result){
					if($("#email_cc_contact_type").val().trim() != '')
					{	
						var str = $("#email_cc_contact_type").val();	
						var res = str.split(",");
						for(i=0;i<res.length;i++)
						{
							$("#email_cc").tokenInput("remove", {id: "CT-"+res[i]});
						} 
					}
					$.each(result,function(i,item){
						var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist_cc );
					
						if(arrayindex == -1)
						{
							$('.mycheckbox_cc:checkbox[value='+parseInt(item.id)+']').attr('checked',true)
							popupcontactlist_cc[arraydatacount_cc++] = parseInt(item.id);
						}
						$("#email_cc").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					});
					$('#count_selected_type_cc').text(popupcontactlist_cc.length + ' Record selected');
					$("#email_cc_contact_type").val(popupcontactlist_cc);
					$('.added_contacts_list').unblock(); 
				}
			});
	}
	
<?php 
if(isset($contact_type_cc)){
	foreach($contact_type_cc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist_cc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_cc:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist_cc[arraydatacount_cc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_cc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist_cc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_cc:checkbox[value='+myvalue+']').attr('checked',false);
		popupcontactlist_cc.splice( arrayindex, 1 );
		arraydatacount_cc--;
	}
	$('#count_selected_type_cc').text(popupcontactlist_cc.length + ' Record selected');
}	
	
</script>
<!-- End -->

<!-- BCC Contact type Script  -->
<script type="text/javascript">
	var arraydatacount_bcc = 0;
	var popupcontactlist_bcc = Array();
	
	$('body').on('click','#selecctall_bcc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_bcc').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
				
				if(arrayindex == -1)
				{
					popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_bcc').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
				
				if(arrayindex >= 0)
				{
					popupcontactlist_bcc.splice( arrayindex, 1 );
					arraydatacount_bcc--;
				}
				
            });        
        }
		$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record selected');
    });
	
	$('body').on('click','#common_tb_bcc a.paginclass_A',function(e){
	
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax_bcc").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup_bcc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup_bcc").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist_bcc.length;i++)
				{
					$('.mycheckbox_bcc:checkbox[value='+popupcontactlist_bcc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup_bcc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_popup_ajax_bcc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search_bcc();
			}
	});
	
	function clearfilter_contact_bcc()
	{
		$("#search_contact_popup_ajax_bcc").val("");
		contact_search_bcc();
	}
	
	function contact_search_bcc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_ajax_bcc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax_bcc").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup_bcc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup_bcc").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist_bcc.length;i++)
					{
						$('.mycheckbox_bcc:checkbox[value='+popupcontactlist_bcc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup_bcc').unblock(); 
			}
		});
		return false;
	}
	
	/*$('body').on('click','.mycheckbox_bcc',function(e){
		
		if($('.mycheckbox_bcc:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
			if(arrayindex == -1)
			{				
				popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
			if(arrayindex >= 0)
			{
				popupcontactlist_bcc.splice( arrayindex, 1 );
				arraydatacount_bcc--;
			}
		}
		
	});*/
	
	function contact_type_bcc_checkbox(contact_type_id)
	{
		if($('.mycheckbox_bcc:checkbox[value='+parseInt(contact_type_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist_bcc );
			if(arrayindex == -1)
			{				
				popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(contact_type_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist_bcc );
			if(arrayindex >= 0)
			{
				popupcontactlist_bcc.splice( arrayindex, 1 );
				arraydatacount_bcc--;
			}
		}
		$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record selected');
	}
	
	function addcontactstointeractionplan_bcc()
	{
		//$("#email_bcc_contact_type").val(popupcontactlist_bcc);
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist_bcc
		},
			beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
					  },
				success: function(result){
					if($("#email_bcc_contact_type").val().trim() != '')
					{	
						var str = $("#email_bcc_contact_type").val();	
						var res = str.split(",");
						for(i=0;i<res.length;i++)
						{
							$("#email_bcc").tokenInput("remove", {id: "CT-"+res[i]});
						} 
					}
					$.each(result,function(i,item){
						var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist_bcc );
				
						if(arrayindex == -1)
						{
							$('.mycheckbox_bcc:checkbox[value='+parseInt(item.id)+']').attr('checked',true)
							popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(item.id);
						}
						$("#email_bcc").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					});
					$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record selected');
					$("#email_bcc_contact_type").val(popupcontactlist_bcc);
					$('.added_contacts_list').unblock(); 
				}
			});
	}
	
<?php 
if(isset($contact_type_bcc)){
	foreach($contact_type_bcc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist_bcc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_bcc:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist_bcc[arraydatacount_bcc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_bcc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist_bcc);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_bcc:checkbox[value='+myvalue+']').attr('checked',false);
		popupcontactlist_bcc.splice( arrayindex, 1 );
		arraydatacount_bcc--;
	}
	$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record selected');
}		
	
</script>
<!-- End -->

<!-- insert custom field in Sibject and message -->
<script type="text/javascript">

CKEDITOR.on( 'instanceReady', function( event ) {
    event.editor.on( 'focus', function() {
	//console.log($(this).attr('id') + ' just got focus!!');
	//window.last_focus = $(this).attr('id');
	$('#last_id').val($(this).attr('id'));
    });
});

$('input:text').on('focus', function() { 
	//console.log($(this).attr('id') + ' just got focus!!');
	//window.last_focus = $(this).attr('id');
	$('#last_id').val($(this).attr('id'));
	});
	  
function addfieldtoeditor() {
var a=$('#last_id').val();
		//alert(a);
		if(a == 'txt_template_subject')
		{
			var text_length = $('#txt_template_subject').val().length;
			if(text_length < 180){
			var a=document.<?php echo $viewname;?>.txt_template_subject,b=document.<?php echo $viewname;?>.slt_customfields;
			if(b.options.length>0){
				sql_box_locked=true;
				for(var c="",d=0,e=0;e<b.options.length;e++)
					if(b.options[e].selected)
					{
						d++;
						if(d>1)
							c+=", ";
						c+=b.options[e].value
					}
					if(document.selection){
						a.focus();
						sel=document.selection.createRange();
						sel.text=c;document.<?php echo $viewname;?>.insert.focus()
					}
					else if(document.<?php echo $viewname;?>.txt_template_subject.selectionStart||document.<?php echo $viewname;?>.txt_template_subject.selectionStart=="0"){
						b=document.<?php echo $viewname;?>.txt_template_subject.selectionEnd;
						d=document.<?php echo $viewname;?>.txt_template_subject.value;
						a.value = d.substring(0,document.<?php echo $viewname;?>.txt_template_subject.selectionStart)+c+d.substring(b,d.length);
						//a.insertText(d.substring(0,document.sms_texts.sms_message.selectionStart)+c+d.substring(b,d.length));
					}
					else
					{ 
						a.insertText(c);
						sql_box_locked=false
					}
				}
				$('#txt_template_subject').keyup();
		}
	
	}
	 if(a == 'cke_1'|| a == '')
	{
				var a=CKEDITOR.instances['email_message'],b=document.<?php echo $viewname;?>.slt_customfields;
		if(b.options.length>0){
			sql_box_locked=true;
			for(var c="",d=0,e=0;e<b.options.length;e++)
				if(b.options[e].selected)
				{
					d++;
					if(d>1)
						c+=", ";
					c+=b.options[e].value
				}
				if(document.selection){
					a.focus();
					sel=document.selection.createRange();
					sel.text=c;document.<?php echo $viewname;?>.insert.focus()
				}
				else if(CKEDITOR.instances['email_message'].selectionStart||CKEDITOR.instances['email_message'].selectionStart=="0"){
					b=CKEDITOR.instances['email_message'].selectionEnd;
					d=CKEDITOR.instances['email_message'].value;
					a.insertText(d.substring(0,CKEDITOR.instances['email_message'].selectionStart)+c+d.substring(b,d.length));
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}

	}

}

</script>
<!--END insert custom field in Subject and message -->

<!-- Start the Contact To selection in popup -->

<script type="text/javascript">

	var arraydata_to = 0;
	var popupcontact_to = Array();
	
	$('body').on('click','#select_contact_to',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_to').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray(this.value, popupcontact_to );
				
				if(arrayindex == -1)
				{
					popupcontact_to[arraydata_to++] = this.value;
				}
				             
            });
        }else{
            $('.mycheckbox_to').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray(this.value, popupcontact_to );
				
				if(arrayindex >= 0)
				{
					popupcontact_to.splice( arrayindex, 1 );
					arraydata_to--;
				}
				
            });        
        }
		$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
    });
	
	$('body').on('click','#common_contact_to a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#contact_status_to').val(),contact_source:$('#contact_source_to').val(),contact_type:$('#contact_type_to').val(),sortfield:$("#sortfield_to").val(),sortby:$("#sortby_to").val(),perpage:$("#perpage_to").val(),search_tag:$("#search_tag_to").val()
	},
	beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_to").html(html);
			try
			{
				for(i=0;i<popupcontact_to.length;i++)
				{
					$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_to').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_to').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_to();
			}
	});
	
	function clear_contact_to()
	{
		$("#search_tag_to").tokenInput("clear");
		$("#search_contact_to").val("");
		$('#contact_status_to').val("");
		$('#contact_source_to').val("");
		$('#contact_type_to').val("");
		$("#sortfield_to").val('');
		$("#sortby_to").val('');
		$("#perpage_to").val('');
		contact_ser_to();
	}
	
	function applysortfilte_contact_to(sortfilter,sorttype)
	{
		$("#sortfield_to").val(sortfilter);
		$("#sortby_to").val(sorttype);
		contact_ser_to();
	}
	
	function contact_ser_to()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_to/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#contact_status_to').val(),contact_source:$('#contact_source_to').val(),contact_type:$('#contact_type_to').val(),sortfield:$("#sortfield_to").val(),sortby:$("#sortby_to").val(),perpage:$("#perpage_to").val(),search_tag:$("#search_tag_to").val()
		},
		beforeSend: function() {
					$('.contact_to').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_to").html(html);
				//alert(popupcontact_to);
				try
				{
					for(i=0;i<popupcontact_to.length;i++)
					{
						$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_to').unblock(); 
			}
		});
		return false;
	}
	
	/*$('body').on('click','.mycheckbox_to',function(e){
		
		if($('.mycheckbox_to:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			
			if(arrayindex >= 0)
			{
				popupcontact_to.splice( arrayindex, 1 );
				arraydata_to--;
			}
		}
		
	});*/
	
	function checkbox_to(contact_id)
	{
		if($('.mycheckbox_to:checkbox[value='+(contact_id)+']:checked').is(':checked'))
		{	
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = (contact_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_to );
			
			if(arrayindex >= 0)
			{
				popupcontact_to.splice( arrayindex, 1 );
				arraydata_to--;
			}
		}
		$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
	}
	
	function addcontactstoemailplan()
	{
		//alert(popupcontact_to);
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_to
		},
		beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_to").val().trim() != '')
				{	
					var str = $("#email_contact_to").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_to").tokenInput("remove", {id: (res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray(item.id+'-'+item.email_trans_id,popupcontact_to );
					if(arrayindex == -1)
					{
						$('.mycheckbox_to:checkbox[value='+item.id+'-'+item.email_trans_id+']').attr('checked',true);				
						popupcontact_to[arraydata_to++] = (item.id+'-'+item.email_trans_id);
					}
					if(item.email_type == 1)
						$("#email_to").tokenInput("add", {id:(item.id+'-'+item.email_trans_id),name: item.contact_name + '(' + item.email_address + ')(Spouse)'});
					else
						$("#email_to").tokenInput("add", {id:(item.id+'-'+item.email_trans_id),name: item.contact_name + '(' + item.email_address + ')'});
				});
				$("#email_contact_to").val(popupcontact_to);
				$('.contact_to').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_to) && count($email_to) > 0 && !isset($this->router->uri->segments[4])){
	foreach($email_to as $row){?>		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>", popupcontact_to );
		if(arrayindex == -1)
		{
			$('.mycheckbox_to:checkbox[value='+'<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>'+']').attr('checked',true);				
			popupcontact_to[arraydata_to++] = '<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>';
		}
	
<?php }
}
?>

function remove_contact_to(myvalue)
{
	var arrayindex = jQuery.inArray((myvalue),popupcontact_to);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_to:checkbox[value='+(myvalue)+']').attr('checked',false);
		popupcontact_to.splice( arrayindex, 1 );
		arraydata_to--;
	}
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
}	
function add_contact_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray(myvalue,popupcontact_to);
	//alert(arrayindex);
	if(arrayindex == -1)
	{
		popupcontact_to[arraydata_to++]=myvalue;
		$('.mycheckbox_to:checkbox[value='+myvalue+']').attr('checked',true);
		
		if($("#email_contact_to").val().trim() != '')
		{
			var str = $("#email_contact_to").val();
			$("#email_contact_to").val(str+","+myvalue);
		}
		else
			$("#email_contact_to").val(myvalue);
		//alert(popupcontact_to);
	}
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
}		
</script>
<!-- END -->
<!-- Start the Contact CC selection in popup -->
<script type="text/javascript">

	var arraydata_cc = 0;
	var popupcontact_cc = Array();
	
	$('body').on('click','#select_contact_cc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_cc1').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( (this.value), popupcontact_cc );
				
				if(arrayindex == -1)
				{
					popupcontact_cc[arraydata_cc++] = (this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_cc1').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( (this.value), popupcontact_cc );
				
				if(arrayindex >= 0)
				{
					popupcontact_cc.splice( arrayindex, 1 );
					arraydata_cc--;
				}
				
            });        
        }
		$('#count_selected_cc').text(popupcontact_cc.length + ' Record selected');
    });
	
	$('body').on('click','#common_contact_cc a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_cc").val(),contact_status:$('#contact_status_cc').val(),contact_source:$('#contact_source_cc').val(),contact_type:$('#contact_type_cc').val(),sortfield:$("#sortfield_cc").val(),sortby:$("#sortby_cc").val(),perpage:$("#perpage_cc").val(),search_tag:$("#search_tag_cc").val()
	},
	beforeSend: function() {
				$('.contact_cc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_cc").html(html);
			try
			{
				for(i=0;i<popupcontact_cc.length;i++)
				{
					$('.mycheckbox_cc1:checkbox[value='+popupcontact_cc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_cc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_cc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_cc();
			}
	});
	
	function clear_contact_cc()
	{
		$("#search_tag_cc").tokenInput("clear");
		$("#search_contact_cc").val("");
		$('#contact_status_cc').val("");
		$('#contact_source_cc').val("");
		$('#contact_type_cc').val("");
		$("#sortfield_cc").val('');
		$("#sortby_cc").val('');
		$("#perpage_cc").val('');
		contact_ser_cc();
	}
	
	function applysortfilte_contact_cc(sortfilter,sorttype)
	{
		$("#sortfield_cc").val(sortfilter);
		$("#sortby_cc").val(sorttype);
		contact_ser_cc();
	}
	
	function contact_ser_cc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_cc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_cc").val(),contact_status:$('#contact_status_cc').val(),contact_source:$('#contact_source_cc').val(),contact_type:$('#contact_type_cc').val(),sortfield:$("#sortfield_cc").val(),sortby:$("#sortby_cc").val(),perpage:$("#perpage_cc").val(),search_tag:$("#search_tag_cc").val()
		},
		beforeSend: function() {
					$('.contact_cc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_cc").html(html);
				
				try
				{
					for(i=0;i<popupcontact_cc.length;i++)
					{
						$('.mycheckbox_cc1:checkbox[value='+popupcontact_cc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_cc').unblock(); 
			}
		});
		return false;
	}
	
	function checkbox_cc(contact_id)
	{
		if($('.mycheckbox_cc1:checkbox[value='+(contact_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_cc );
			if(arrayindex == -1)
			{				
				popupcontact_cc[arraydata_cc++] = (contact_id);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_cc );
			
			if(arrayindex >= 0)
			{
				popupcontact_cc.splice( arrayindex, 1 );
				arraydata_cc--;
			}
		}
		$('#count_selected_cc').text(popupcontact_cc.length + ' Record selected');
	}
	
	function addcontactstoemailplan_cc()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_cc
		},
		beforeSend: function() {
				$('.contact_cc').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_cc").val().trim() != '')
				{	
					var str = $("#email_contact_cc").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_cc").tokenInput("remove", {id: (res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( (item.id+'-'+item.email_trans_id), popupcontact_cc );
					if(arrayindex == -1)
					{
						$('.mycheckbox_cc1:checkbox[value='+(item.id+'-'+item.email_trans_id)+']').attr('checked',true);				
						popupcontact_cc[arraydata_cc++] = (item.id+'-'+item.email_trans_id);
					}
					if(item.email_type == 1)
						$("#email_cc").tokenInput("add", {id: (item.id+'-'+item.email_trans_id), name: item.contact_name + '(' + item.email_address + ')(Spouse)'});
					else
						$("#email_cc").tokenInput("add", {id: (item.id+'-'+item.email_trans_id), name: item.contact_name + '(' + item.email_address + ')'});
						
				});
				$("#email_contact_cc").val(popupcontact_cc);
				$('.contact_cc').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_cc) && count($email_cc) > 0 && !isset($this->router->uri->segments[4])){
	foreach($email_cc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>", popupcontact_cc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_cc1:checkbox[value='+'<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>'+']').attr('checked',true);				
			popupcontact_cc[arraydata_cc++] = '<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>';
		}
	
<?php }
}
?>

function remove_contact_cc(myvalue)
{
	var arrayindex = jQuery.inArray(myvalue,popupcontact_cc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_cc1:checkbox[value='+myvalue+']').attr('checked',false);
		popupcontact_cc.splice( arrayindex,1);
		arraydata_cc--;
	}
	$('#count_selected_cc').text(popupcontact_cc.length + ' Record selected');
}	
function add_contact_cc(myvalue)
{
	var arrayindex = jQuery.inArray(myvalue,popupcontact_cc);
	if(arrayindex == -1)
	{
		popupcontact_cc[arraydata_cc++] = myvalue;
		$('.mycheckbox_cc1:checkbox[value='+myvalue+']').attr('checked',true);
		if($("#email_contact_cc").val().trim() != '')
		{
			var str = $("#email_contact_cc").val();
			$("#email_contact_cc").val(str+","+myvalue);
		}
		else
			$("#email_contact_cc").val(myvalue);
	}
	$('#count_selected_cc').text(popupcontact_cc.length + ' Record selected');
}
</script>
<!-- End -->
<!--  Start the Contact BCC selection in popup -->
<script type="text/javascript">

	var arraydata_bcc = 0;
	var popupcontact_bcc = Array();
	
	$('body').on('click','#select_contact_bcc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_bcc1').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( (this.value), popupcontact_bcc );
				
				if(arrayindex == -1)
				{
					popupcontact_bcc[arraydata_bcc++] = (this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_bcc1').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( (this.value), popupcontact_bcc );
				
				if(arrayindex >= 0)
				{
					popupcontact_bcc.splice( arrayindex, 1 );
					arraydata_bcc--;
				}
				
            });        
        }
		$('#count_selected_bcc').text(popupcontact_bcc.length + ' Record selected');
    });
	
	$('body').on('click','#common_contact_bcc a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_bcc").val(),contact_status:$('#contact_status_bcc').val(),contact_source:$('#contact_source_bcc').val(),contact_type:$('#contact_type_bcc').val(),sortfield:$("#sortfield_bcc").val(),sortby:$("#sortby_bcc").val(),perpage:$("#perpage_bcc").val(),search_tag:$("#search_tag_bcc").val()
	},
	beforeSend: function() {
				$('.contact_bcc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_bcc").html(html);
			try
			{
				for(i=0;i<popupcontact_bcc.length;i++)
				{
					$('.mycheckbox_bcc1:checkbox[value='+popupcontact_bcc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_bcc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_bcc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_bcc();
			}
	});
	
	function clear_contact_bcc()
	{
		$("#search_tag_bcc").tokenInput("clear");
		$("#search_contact_bcc").val("");
		$('#contact_status_bcc').val("");
		$('#contact_source_bcc').val("");
		$('#contact_type_bcc').val("");
		$("#sortfield_bcc").val('');
		$("#sortby_bcc").val('');
		$("#perpage_bcc").val('');
		contact_ser_bcc();
	}
	
	function applysortfilte_contact_bcc(sortfilter,sorttype)
	{
		$("#sortfield_bcc").val(sortfilter);
		$("#sortby_bcc").val(sorttype);
		contact_ser_bcc();
	}
	
	function contact_ser_bcc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/emails/search_contact_bcc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_bcc").val(),contact_status:$('#contact_status_bcc').val(),contact_source:$('#contact_source_bcc').val(),contact_type:$('#contact_type_bcc').val(),sortfield:$("#sortfield_bcc").val(),sortby:$("#sortby_bcc").val(),perpage:$("#perpage_bcc").val(),search_tag:$("#search_tag_bcc").val()
		},
		beforeSend: function() {
					$('.contact_bcc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_bcc").html(html);
				
				try
				{
					for(i=0;i<popupcontact_bcc.length;i++)
					{
						$('.mycheckbox_bcc1:checkbox[value='+popupcontact_bcc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_bcc').unblock(); 
			}
		});
		return false;
	}
	
	/*$('body').on('click','.mycheckbox_bcc1',function(e){
		
		if($('.mycheckbox_bcc1:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
			if(arrayindex == -1)
			{				
				popupcontact_bcc[arraydata_bcc++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
			
			if(arrayindex >= 0)
			{
				popupcontact_bcc.splice( arrayindex, 1 );
				arraydata_bcc--;
			}
		}
		
	});*/
	
	function checkbox_bcc(contact_id)
	{
		if($('.mycheckbox_bcc1:checkbox[value='+(contact_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_bcc );
			if(arrayindex == -1)
			{				
				popupcontact_bcc[arraydata_bcc++] = (contact_id);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( (contact_id), popupcontact_bcc );
			
			if(arrayindex >= 0)
			{
				popupcontact_bcc.splice( arrayindex, 1 );
				arraydata_bcc--;
			}
		}
		$('#count_selected_bcc').text(popupcontact_bcc.length + ' Record selected');
	}
	
	function addcontactstoemailplan_bcc()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>admin/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_bcc
		},
		beforeSend: function() {
				$('.contact_bcc').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_bcc").val().trim() != '')
				{	
					var str = $("#email_contact_bcc").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_bcc").tokenInput("remove", {id: (res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( (item.id+'-'+item.email_trans_id), popupcontact_bcc );
					if(arrayindex == -1)
					{
						$('.mycheckbox_bcc1:checkbox[value='+(item.id+'-'+item.email_trans_id)+']').attr('checked',true);				
						popupcontact_bcc[arraydata_bcc++] = (item.id+'-'+item.email_trans_id);
					}
					if(item.email_type == 1)
						$("#email_bcc").tokenInput("add", {id: (item.id+'-'+item.email_trans_id), name: item.contact_name + '(' + item.email_address + ')(Spouse)'});
					else
						$("#email_bcc").tokenInput("add", {id: (item.id+'-'+item.email_trans_id), name: item.contact_name + '(' + item.email_address + ')'});
				});
				$("#email_contact_bcc").val(popupcontact_bcc);
				$('.contact_bcc').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_bcc) && count($email_bcc) > 0 && !isset($this->router->uri->segments[4])){
	foreach($email_bcc as $row){?>
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>", popupcontact_bcc );
		//alert(arrayindex);
		if(arrayindex == -1)
		{
			$('.mycheckbox_bcc1:checkbox[value='+'<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>'+']').attr('checked',true);				
			popupcontact_bcc[arraydata_bcc++] = '<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:''?>';
		}
	
<?php }
}
?>

function remove_contact_bcc(myvalue)
{
	var arrayindex = jQuery.inArray((myvalue),popupcontact_bcc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_bcc1:checkbox[value='+(myvalue)+']').attr('checked',false);
		popupcontact_bcc.splice( arrayindex, 1 );
		arraydata_bcc--;
	}
	$('#count_selected_bcc').text(popupcontact_bcc.length + ' Record selected');
}	
function add_contact_bcc(myvalue)
{
	var arrayindex = jQuery.inArray( (myvalue), popupcontact_bcc );
	if(arrayindex == -1)
	{
		popupcontact_bcc[arraydata_bcc++] = (myvalue);
		$('.mycheckbox_bcc1:checkbox[value='+(myvalue)+']').attr('checked',true);
		if($("#email_contact_bcc").val().trim() != '')
		{
			var str = $("#email_contact_bcc").val();
			$("#email_contact_bcc").val(str+","+myvalue);
		}
		else
			$("#email_contact_bcc").val(myvalue);
	}
	$('#count_selected_bcc').text(popupcontact_bcc.length + ' Record selected');
}
function selecttemplate(id,val){
		
    $('.add_star').html('');	
	$("#txt_template_subject").val('');
	CKEDITOR.instances.email_message.setData('');
    if(id == 6|| id == 8)
    {
            $('.add_star').html(' *');
            //$('.show_email_sms_div').css('display','');
            if(id == 8)
            {
				setTimeout(function(){
					$('.attachfiel').css('display','none');
					$('#add_new_video').removeClass('display_none');
					$('form#emails').attr('action','<?=base_url('admin/bomb_emails/insert_data')?>');
				} ,100);
            }        
            else
            {
				setTimeout(function(){
					$('.attachfiel').css('display','');
					$('#add_new_video').addClass('display_none');
					$('form#emails').attr('action','<?=base_url('admin/emails/insert_data')?>');
				} ,100);
            }    
    }
    

    categoryid = $('#category').val();
    subcategoryid = $('#subcategory').val();
    //$('#template_name').text('');
    var option1 = $('<option />');
    option1.attr('value', '').text('Fetching Template(s)...');
    $('#template_name').html(option1);


    $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').'interaction/ajax_selecttemplate';?>",
     dataType: 'json',
     data: {loadId:id,'category':categoryid,'subcategory':subcategoryid,selected:val},
     cache: false,
     success: function(result){
    // 	alert(result);
            if(result != null && result != '')
            {
                    var selectedsubcat = 0;

                    <?php if(!empty($editRecord[0]['template_name'])){ ?>

                            selectedsubcat = '<?=$editRecord[0]['template_name']?>';

                    <?php } ?>

                    $('#template_name').empty();
                    var option = $('<option />');
                    option.attr('value', '').text('Select Template');
                    $('#template_name').append(option);

                    $.each(result,function(i,item){ 
                                            var option = $('<option />');
                                            
                                            option.attr('value', item.id).text(this.template_name);

                                            if(val == "selected" && i == 0)
                                                    option.attr("selected","selected");
                                            else if(selectedsubcat == item.id && val != "selected")
                                                    option.attr("selected","selected");

                                            $('#template_name').append(option);
                            });
                    $("select#template_name").multiselect('refresh').multiselectfilter();

            }
            else
            {
                    $('#template_name').empty();
                    var option = $('<option />');
                    option.attr('value', '').text('No Template Available');
                    $('#template_name').append(option);
            }

     }
});

}

$(document).ready(function(){
<?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
        selecttemplate(6);
        //$('.attachfiel').css('display','none');
        $('#emails').attr('action','<?=base_url('admin/emails/insert_data')?>');
        <? } ?>
});

function selectVideo()
{
	/*$('.video_data').html('');
	var video_id = $('input[name="select_video"]:checked').val();
	var thumb_url = $('input[name="select_video"]:checked').attr('data-id');
	var video_title = $('input[name="select_video"]:checked').attr('data-group');
	$("#thumb_url").val(thumb_url);
	$("#video_title").val(video_title);
	$("#video_id").val(video_id);
	if(video_id.trim() != '')
	{
		var html = '<div class="addpic addpic1 pull-left margin-top-10px">';
		html += $(".video_details_"+video_id).html();
		html += '</div>';
		html += '<a class="btn btn-xs btn-primary margin-top-10px" onclick="delete_video();" href="javascript:void(0);" title="Remove Video"><i class="fa fa-times"></i></a>';
		$('.video_data').html(html);
	}*/
	var myarray = new Array;
	var i=0;
	var html = '';
	var boxes = $('input[name="select_video[]"]:checked');
	var thumb_url = '';
	//alert(boxes);
	$(boxes).each(function(){
		thumb_url = $('input[value="'+this.value+'"]:checked').attr('data-id');
		html += '<img class="responsive_image" height="320" id="'+this.value+'" name="bb_video" src="'+thumb_url+'" style="display:block" width="500" /><br/><br/>';
		 $('input[value="'+this.value+'"]:checked').attr('checked',false);
	});
	if(html.trim() != '')
	{
			CKEDITOR.instances.email_message.insertHtml(html, function() {
    		CKEDITOR.instances.email_message.focus();
		});
	}
	$(".close").trigger('click');
}
<?php if(($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) ||  $viewname == 'bomb_emails') {
		 if(!empty($editRecord[0]['video_id'])){ ?>
			getVideo('<?=$editRecord[0]['video_id']?>');
		<?php } else { ?>
			getVideo();
<?php 	}
	}
?>
function delete_video()
{
	$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete video?",'buttons': {'Yes': {'class': '',
'action': function(){
		if(save_video_id != '')
		{
			var id=$('#id').val();
			 $.ajax({
				type: 'post',
				data:{id:id,name:name},
				url: '<?=$this->config->item('admin_base_url').$viewname."/delete_image";?>',
				success:function(msg){
						if(msg == 'done')
						{
							$('.video_data').html('');
							$('#video_id').val('');
							$("#thumb_url").val('');
							$("#video_title").val('');
							save_video_id = '';
					  }
					}//succsess
				});//ajax
		}
		else
		{
			$('#video_id').val('');
			$("#thumb_url").val('');
			$("#video_title").val('');
			$('.video_data').html('');
		}
		}},'No'	: {'class'	: 'special'}}});
}
$("#videoFile").change(function() {
	$('#priview_doc').text(this.value);
});
function bombbomb_close_popup()
{
	$(".close_bombbomb_select_popup").trigger('click');
}
</script>
<!-- END -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fileuploadmulti.min.js"></script>
