<?php
/*
    @Description: SMS campaign add/edit page
    @Author: Sanjay Chabhadiya
    @Date: 06-08-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[2];
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

<style>
.ui-multiselect{width:100% !important;}
<?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back,.direct_send_sms,#save_campaign{ display:none !important;}
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


<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />
<!--time picker-->
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>
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
            	<select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_ser_to();">
                	<option value="">Contact Type</option>
                    <?php if(!empty($contact_list)){
                    		foreach($contact_list as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_ser_to();">
                	<option value="">Contact Source</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_ser_to();">
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
						{id: '<?=addslashes($row['tag'])?>', name: "<?=addslashes($row['tag'])?>"},
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
          <!--<div class="table-responsive">-->
          <div class="">
		  <?php $this->load->view('user/sms/contact_to');?>
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


<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
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
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="table">
		  <?php $this->load->view('user/sms/add_contact_popup_ajax');?>
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

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('sms_campaign');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3><?=$this->lang->line('sms_campaign');?><?php
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" id="back" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="row">
           <div class="col-sm-8">
		   <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_to');?> <span class="val">*</span></label>
		   <?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
				<label for="text-input">
				<?php if(!empty($email_to) && count($email_to) > 0){
				 echo ucwords($email_to[0]['contact_name']).'('.$email_to[0]['phone_no'].')';
				?>
				</label>
				 <input type="hidden" name="email_to" id="email_to" value="<?=!empty($email_to[0]['id'])?$email_to[0]['id']:'0'?>" />
				 <?php } ?>
		  <?php } else {?>
		  
              <input id="email_to" name="email_to" class="form-control parsley-validated" type="text" value=""   data-required="true" placeholder="Enter Name">
		<script type="text/javascript">
			$(document).ready(function() {
			
				$("#email_to").tokenInput([ 
				<?php foreach($contact as $row){ ?>
					{id: <?=$row['id']?>, name: "<?=!empty($row['first_name'])?ucwords(addslashes(trim($row['first_name']))):''?><?=!empty($row['middle_name'])?' '.ucwords(addslashes(trim($row['middle_name']))):''?><?=!empty($row['last_name'])?' '.ucwords(addslashes(trim($row['last_name']))):''?>(<?=$row['phone_no']?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=ucwords(addslashes($row['contact_name']))?> (<?=$row['phone_no']?>)"},
					<?php } } ?>
					<?php if(isset($contact_type_to)) {
					foreach($contact_type_to as $row){ ?>
						{id: "CT-"+<?=$row['id']?>, name: "<?=ucwords(addslashes($row['name']))?>"},
					<?php } } ?>
				],onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
				/*onDelete: function (item) {
					var str = item.id;
					if(str.trim() != '')
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}*/
				}, 
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
		});
		</script>
        <?php if(($formAction == 'insert_data' && empty($this->router->uri->segments[4])) || $formAction == 'update_data') { ?>
			 <a href="#basicModal_to" class="text_color_red text_size" id="contact_to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
		 
			 <!--<a href="#basicModal1" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>-->
              <?php } } ?>
             </div>
            </div>
            
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
			  </div>
              <div class="col-sm-12">
              <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)" data-required="true">
              <option value="-1">Category</option>
                 <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?> ><?php echo ucwords($row1['category']);?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     			
             <!-- <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
              
            </div>
			 <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?></label>
			   <select class="selectBox" name='template_name' id='template_name'>
              </select>
             </div>
            </div>
		
        </div>
        <div class="col-sm-12 clear">
        	<div class="row">
            <div class="col-sm-8">	
			
			<div class="row">
             <div class="col-sm-12 form-group">
              <label for="select-multi-input">
                 	SMS Message <span class="val">*</span>
                  </label>
                <textarea name="sms_message" placeholder="e.g. SMS Message"  id="sms_message" class="form-control parsley-validated" data-required="true" ><?=!empty($editRecord[0]['sms_message'])?$editRecord[0]['sms_message']:'';?></textarea>

             </div>
            </div>
			
			<div class="row">
             <div class="col-sm-12 form-group">
           	<label id="textarea_feedback"></label>
		   </div></div>
		   
		   <div class="row direct_send_sms">
             <div class="col-sm-12 ">
              <label for="select-multi-input">
                <fieldset class="schedule_campaign">
                     <legend>Schedule Campaign</legend>
                       <div class="schedule_detail">
                         <p>Send this Campagin ?</p> 
                         <div class="col-sm-12 pull-left">
							  
						<div class="float-left margin-left-15">
                         <label class="checkbox margin-left-15">
						  Now
						 	<div class="col-sm-3">
							
							<input type="radio" value="1" class="" id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['sms_send_type']) && $editRecord[0]['sms_send_type'] == '1' || (!empty($send_now) && $send_now == 'send')){ echo "checked=checked"; } ?>>
							
							  
							</div>
							 </label>
                             </div> 
                             
                      <div class="col-sm-12 pull-left">       
						 <div class="float-left margin-left-15"> 
						 <label class="checkbox">
						  Date Time :
						 	<div class="col-sm-2">
							
							<input type="radio" name="chk_is_lead" id="chk_is_lead" class="" value="2"  <?php if(!empty($editRecord[0]['sms_send_type']) && $editRecord[0]['sms_send_type'] == '2' && $send_now == ''){ echo "checked=checked"; } ?> >
							
							  
							</div>
							 </label>
                             </div>
							<div class="col-sm-12 col-lg-5 col-md-8 sms_add_record">
								<input name="send_date" placeholder="Specific Date" id="send_date" class="form-control parsley-validated" type="text" <?php if(!empty($editRecord[0]['sms_send_type']) && $editRecord[0]['sms_send_type'] == '2') { ?>value="<?=!empty($editRecord[0]['sms_send_date'])?$editRecord[0]['sms_send_date']:''?>" <?php } ?> readonly="readonly" />
							  	
							</div>
							<div class="col-sm-12 col-lg-4 col-md-9 sms_add_record">
                            <label class="col-sm-3 col-lg-3 mrg27">Time</label>
								<input name="send_time" placeholder="Time" id="send_time" readonly="readonly" type="text" <?php if(!empty($editRecord[0]['sms_send_type']) && $editRecord[0]['sms_send_type'] == '2') { ?> value="<?=!empty($editRecord[0]['sms_send_time'])?date("h:i A", strtotime($editRecord[0]['sms_send_time'])):''?>" <?php } ?> /> <button type="button" class="timepicker_disable_button_trigger timepick" onclick="send_time_focus()"><img src="<?=base_url('images/timecal.png')?>" alt="..." title="..."/></button>
							</div>
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
               
              
               <select ondblclick="addfieldtoeditor();" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox">
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
                 <input class="btn btn-secondary" type="button" name="submitbtn" onclick="addfieldtoeditor();" value="Insert Field">
               
            </div>
           </div>
      
           </div>
            
		  </div>
          
            </div>
            
		  </div>
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   <input type="hidden" id="email_to_contact_type" name="email_to_contact_type" value="<?=!empty($contact_type_to_selected)?$contact_type_to_selected:''?>" />
			    <input type="hidden" id="email_contact_to" name="email_contact_to" value="<?=!empty($select_to)?$select_to:''?>" />
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />

<input type="submit" class="btn btn-secondary-green" value="Send Now" title="Send Now" onclick="return validation();" name="submitbtn" />
<input type="submit" class="btn btn-secondary-green" id="save_campaign" value="Save Campaign" title="Save Campaign" onclick="return validation();" name="submitbtn1" />
<input type="submit" class="btn btn-secondary" value="Save Template As" title="Save Template As" onclick="return validation();" name="submitbtn2" />
 <?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) { ?>
	<input type="hidden" name="phone_trans_id" id="phone_trans_id" value="<?=$this->router->uri->segments[5]?>" />
    <a class="btn btn-primary" title="Close" onclick="close_popup()">Close</a>
<?php } else { ?>
	<a class="btn btn-primary" href="javascript:history.go(-1);" title="Close">Close</a>
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
 <?php
// print_r($subcategory);
 ?>

<script type="text/javascript">
var arraydatacount1 = 0;
var contact_field = Array();
var text_max = 160;
function send_time_focus()
{
	$('#send_time').focus();	
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
 
  
/*function selectsubcategory(id){
 if(id!="-1"){
   	$("#subcategory").html("<option value='-1'>Sub-Category</option>");
   	loadData('category',id);
  	setTimeout(function(){$('#subcategory').change()},1000);
 }else{
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $("select#subcategory").multiselect('refresh').multiselectfilter();
   setTimeout(function(){$('#subcategory').change()},1000);
 }
}*/
function selectsubcategory(category_id){
//var subcategory_id = $("#subcategory").val();

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
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory']?>';
			
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
function loadtemplateData(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,loadsubcategoryId:loadsubcategoryId},
     cache: false,
     success: function(result){
		var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name']?>';
			selectedid = parseInt(selectedid) + 1;
			
			<?php }  ?>
		if(result.length){ 
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
			$("#template_name").html("<option value='-1'>No Template Available</option>");
		}		
		$("select#template_name").multiselect('refresh').multiselectfilter();
					
     }
   });
}

<?php if(!empty($editRecord[0]['template_category'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category']?>');

<?php } ?>

$("#template_name").change(function() {
//alert($("#template_name").val());
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name").val()},
		 cache: false,
		 success: function(result){
		 	
		 	if(result != -1)
			{
				$.each(result,function(i,item){
				$("#sms_message").val(item.sms_message);
				$('#sms_message').keyup();
				//editor.insertText("ckEditor");
	//CKEDITOR.instances.email_message.setData(item.email_message);
				//CKEDITOR.instances.email_message.insertText(item.email_message);
					//$("#email_message").innerHTML(item.email_message);
				});
			}	
			else
			{
				$("#sms_message").val('');
			}
		 }
	});
});



	</script>
<script type="text/javascript">
function validation()
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
			return true
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select specific date"+"<strong></strong>",
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
function replaceLast(origin,text){
    textLenght = text.length;
    originLen = origin.length
    if(textLenght == 0)
        return origin;

    start = originLen-textLenght;
    if(start < 0){
        return origin;
    }
    if(start == 0){
        return "";
    }
    for(i = start; i >= 0; i--){
        k = 0;
        while(origin[i+k] == text[k]){
            k++
            if(k == textLenght)
                break;
        }
        if(k == textLenght)
            break;
    }
    //not founded
    if(k != textLenght)
        return origin;

    //founded and i starts on correct and i+k is the first char after
    end = origin.substring(i+k,originLen);
    if(i == 0)
        return end;
    else{
        start = origin.substring(0,i) 
        return (start + end);
    }
}

function keypress(evt)
{
	var text = $('#sms_message').val();
	var text_length = $('#sms_message').val().length;
	var new_str1 = text;
	
	for(i=0;i<contact_field.length;i++)
	{
		new_str1 = new_str1.replace(contact_field[i],"","g");
	}
	
	text_length = new_str1.length;
	console.log(text_length);
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
	if(text_length >= 160)
	{
		if(charCode == 8 || charCode == 46 || charCode == 37 || charCode == 38 || charCode == 39 || charCode == 40)
			return true;
		return false;	
	}
}

$(document).ready(function (){
	$('#textarea_feedback').html('Count: 0 letters: Max '+text_max + ' letters');
	$('#count_selected_to').text('0 Record Selected');
	$('#count_selected').text('0 Record Selected');
	
	$(function() {
		$( "#send_date" ).datepicker({
			showOn: "button",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
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
	/*$('#send_time').timepicker({
		showLeadingZero: false,
		showOn: 'both',
		button: '.timepicker_disable_button_trigger',
		showNowButton: true,
		showDeselectButton: true,
		defaultTime: '',  // removes the highlighted time for when the input is empty.
		showCloseButton: true
	});*/
	
	<?php
	 if($tablefield_data!='')
	 {
		foreach($tablefield_data as $row){?>
		
			var arrayindex = jQuery.inArray( "<?=!empty($row['name'])?$row['name']:''?>", contact_field );
			if(arrayindex == -1)
			{
				contact_field[arraydatacount1++] = '{(<?=!empty($row['name'])?$row['name']:''?>)}';
			}
		
	<?php }?>
	<?php }?>
	
	
	//$('#textarea_feedback').html('Count: 0 letters: Max '+text_max + ' letters');
	
	$('#sms_message').keyup(function() {
		//alert(contact_field);
        var text = $('#sms_message').val();
		var text_length = $('#sms_message').val().length;
		
		var new_str1 = text;
		
		for(i=0;i<contact_field.length;i++)
		{
			new_str1 = new_str1.replace(contact_field[i],"","g");
		}
		
		text_length = new_str1.length;
		if(text_length > 160)
		{
			var cnt_text = new_str1.substr(160);
						
			//alert(text);
			var a = replaceLast(text,cnt_text);
			
			$('#sms_message').val(a);

			text = $('#sms_message').val();
			
			text_length = $('#sms_message').val().length;
			
			new_str1 = text;
			
			for(i=0;i<contact_field.length;i++)
			{
				new_str1 = new_str1.replace(contact_field[i],"","g");
			}
			text_length = new_str1.length;

		}
		console.log(text_length);
        var text_remaining = text_length;
		
		
		
        $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: Max ' +text_max+ ' letters');
    });
	
$("#sms_message")
    //.bind("dragover", false)
    //.bind("dragenter", false)
    .bind("drop", function(e) {
		setTimeout(function(){$('#sms_message').keyup()},1);
});	
   /* var text_max = 180;
    $('#textarea_feedback').html('Count: 0 letters: Max '+text_max + ' letters');
	
    $('#sms_message').keyup(function() {
        var text_length = $('#sms_message').val().length;
		if(text_length > 180)
		{	
			str = $('#sms_message').val();
			var res = str.substring(0, 180);
			$('#sms_message').val(res);
			text_length = 180;
		}
        var text_remaining = text_length;

        $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: Max ' +text_max+ ' letters');
    });*/
});

</script>
<script type="text/javascript">
function addfieldtoeditor()
{
	var a=document.sms.sms_message,b=document.<?php echo $viewname;?>.slt_customfields;
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
			else if(document.sms.sms_message.selectionStart||document.sms.sms_message.selectionStart=="0"){
				b=document.sms.sms_message.selectionEnd;
				d=document.sms.sms_message.value;
				a.value = d.substring(0,document.sms.sms_message.selectionStart)+c+d.substring(b,d.length);
				//a.insertText(d.substring(0,document.sms_texts.sms_message.selectionStart)+c+d.substring(b,d.length));
			}
			else
			{ 
				a.insertText(c);
				sql_box_locked=false
			}
		}
		$('#sms_message').keyup();
}

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
		$('#count_selected').text(popupcontactlist.length + ' Record selected');
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
			url: "<?php echo base_url();?>user/sms/search_contact_ajax/",
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
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/sms/add_contacts_to_email/",
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
						$("#email_to").tokenInput("remove", {id: "CT-"+res[i]});
					}
				}
				$.each(result,function(i,item){
				
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist );
				
					if(arrayindex == -1)
					{
						$('.mycheckbox:checkbox[value='+parseInt(item.id)+']').attr('checked',true);	
						popupcontactlist[arraydatacount++] = parseInt(item.id);
					}
				
					$("#email_to").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					
					//$('.mycheckbox:checkbox[value='+item.id+']').attr('checked',true);
					
				});
				$('#count_selected').text(popupcontactlist.length + ' Record selected');
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
	
	function contact_type_checkbox(contact_type_id)
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
		$('#count_selected').text(popupcontactlist.length + ' Record selected');
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
		$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontactlist.splice( arrayindex, 1 );
		arraydatacount--;
	}
	$('#count_selected').text(popupcontactlist.length + ' Record selected');
	//alert(popupcontactlist);
}

</script>

<!-------  Start the Contact To selection in popup   ------>

<script type="text/javascript">

	var arraydata_to = 0;
	var popupcontact_to = Array();
	
	$('body').on('click','#select_contact_to',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_to').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				
				if(arrayindex == -1)
				{
					popupcontact_to[arraydata_to++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_to').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				
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
		result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield_to").val(),sortby:$("#sortby_to").val(),perpage:$("#perpage_to").val(),search_tag:$("#search_tag_to").val()
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
		$("#search_tag").tokenInput("clear");
		$("#search_contact_to").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
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
			url: "<?php echo base_url();?>user/sms/search_contact_to/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield_to").val(),sortby:$("#sortby_to").val(),perpage:$("#perpage_to").val(),search_tag:$("#search_tag_to").val()
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
	function checkboxchecked(contact_id)
	{
		if($('.mycheckbox_to:checkbox[value='+parseInt(contact_id)+']:checked').is(':checked'))
		{	
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = parseInt(contact_id);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontact_to );
			
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
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/sms/contacts_to_email/",
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
						$("#email_to").tokenInput("remove", {id: parseInt(res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontact_to );
					if(arrayindex == -1)
					{
						$('.mycheckbox_to:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontact_to[arraydata_to++] = parseInt(item.id);
					}
					$("#email_to").tokenInput("add", {id: parseInt(item.id), name: item.contact_name + '(' + item.phone_no +')'});
				});
				$("#email_contact_to").val(popupcontact_to);
				$('.contact_to').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_to) && count($email_to) > 0){
	foreach($email_to as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontact_to );
		if(arrayindex == -1)
		{
			$('.mycheckbox_to:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontact_to[arraydata_to++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_contact_to(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontact_to);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_to:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontact_to.splice( arrayindex, 1 );
		arraydata_to--;
	}
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
}	
function add_contact_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontact_to );
	//alert(arrayindex);
	if(arrayindex == -1)
	{
		popupcontact_to[arraydata_to++] = parseInt(myvalue);
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

<!-------             END                  -->