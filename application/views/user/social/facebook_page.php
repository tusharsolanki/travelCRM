<?php
/*
    @Description: Face book post page
    @Author: Niral Patel
    @Date: 21-11-2014

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
<style>
.ui-multiselect {
	width: 100% !important;
}
<?php if($formAction == 'insert_data' && !empty($this->router->uri->segments[4])) {
?> #sidebar {
display:none;
}
#header, #site-logo, .dropdown, #footer, #back {
display:none !important;
}
#content {
margin-left:0;
}
<?php
}
?>
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

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('phonecallscript_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
          <h3>
            Post Comment
            </h3>
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <div class="portlet-content custom_fb_box_class">
            <div class="">
              <div class="tab-content" id="myTab1Content">
              <div class="tab-pane fade in active" id="home">
              <?php if(!empty($msg)){
				  ?>
              		<div class="row">
                      <div class="col-sm-12">
                        <label for="text-input"><?=$msg?></label>
                      </div>
                      
                    </div>    
				<?  }?>
              
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').'social/fbconnection'?>" novalidate data-validate="parsley">
                <div class="row">
				<div class="col-sm-12">
                        <input type="submit" class="btn btn-secondary" value="Post On Page"  onclick="return setdefaultdata();"   name="submit" />
                      </div>
				
                      <div class="col-sm-12">
                        <label for="text-input">Select Page</label>
                      </div>
                      <div class="col-sm-6">
                        <select class="form-control parsley-validated" name='page' id='page' data-required="true">
                        	<option value="">Please Select</option>
                          <?php if(!empty($pages->data)){
							for($i=0;$i<count($pages->data);$i++)
			{?>
                          <option value="<?=$pages->data[$i]->access_token."-".$pages->data[$i]->id?>">
						  <?=$pages->data[$i]->name?>
                          </option>
                          
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                <div id="facebook_hide_div">
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> : </label>
			  </div>
              <div class="col-sm-6">
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
     			
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
              
            </div>
			<div class="row">
             <div class="col-sm-6 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			   <select class="selectBox" name='template_name' id='template_name'>
              </select>
             </div>
            </div>
			<div class="row">
             <div class="col-sm-6 form-group">
			<div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('label_social_msg');?><span class="val">*</span>
                  </label>
                  <textarea name="social_message" class="form-control parsley-validated" id="social_message" ><?=!empty($editRecord[0]['social_message'])?$editRecord[0]['social_message']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('social_message',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                </div>
				</div>
            </div>
			<?php /*?><div class="row">
             <div class="col-sm-12 form-group">
              <label for="select-multi-input">
                 	SMS Message <span class="val">*</span> : 
                  </label>
                <textarea name="sms_message" id="sms_message" class="form-control parsley-validated" data-required="true" onkeypress="return keypress(event);" ><?=!empty($editRecord[0]['sms_message'])?$editRecord[0]['sms_message']:'';?>
</textarea>

             </div>
            </div><?php */?>
			
			<?php /*?><div class="row">
             <div class="col-sm-12 form-group">
           	<label id="textarea_feedback"></label>
		   </div></div><?php */?>
		   
		   <?php /*?><div class="row">
             <div class="col-sm-12">
              <label for="select-multi-input">
                <fieldset class="schedule_campaign">
                     <legend>Schedule Campaign</legend>
                       <div class="schedule_detail">
                         <p>Send this Campagin ?</p> 
                         <div class="col-sm-12 pull-left">
							  
						 <div class="float-left margin-left-15"><label class="checkbox">
						  Now
						 	<div class="col-sm-3">
							
							<input type="radio" value="1" class="" id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '1' || (!empty($send_now) && $send_now == 'send')){ echo "checked=checked"; } ?>>
							
							  
							</div>
							 </label>
						</div> 
						 <div class="col-sm-12 pull-left"> 
						 	<label class="checkbox">
						  	Date
                            <div class="col-sm-2">
							
							<input type="radio" name="chk_is_lead" id="chk_is_lead" class="" value="2"  <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2' && $send_now == ''){ echo "checked=checked"; } ?>  />
							</div>
							</label>
							<div class="col-sm-5">
                              <input name="send_date" placeholder="Specific Date" id="send_date" class="form-control parsley-validated" type="text" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2') { ?>value="<?=!empty($editRecord[0]['social_send_date'])?$editRecord[0]['social_send_date']:''?>" <?php } ?> readonly="readonly"/>	
							</div>
                           	<div class="col-sm-4">
                            	<label class="checkbox">Time</label>
								<input name="send_time" id="send_time" placeholder="Time" readonly="readonly" type="text" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2') { ?> value="<?=!empty($editRecord[0]['social_send_time'])?date("h:i A", strtotime($editRecord[0]['social_send_time'])):''?>" <?php } ?> /> <button type="button" class="timepicker_disable_button_trigger timepick" onclick="send_time_focus()"><img src="<?=base_url('images/timecal.png')?>" alt="..." title="..."/></button>
							</div>
							
							   
						  </div>                       
						</div>
                        </div>
                  </fieldset>
                  </label>
             </div>
            </div><?php */?>
            </div>
                </div>
                </div>
                <div class="col-sm-12 pull-left text-center margin-top-10">
                  <input id="page_name" name="page_name" type="hidden" value="">
                  <input type="submit" class="btn btn-secondary" value="Post On Page"  onclick="return setdefaultdata();"   name="submit" />
                 
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
$('#page').change(function(){
	var page_name=$("#page option:selected").text();
	$('#page_name').val(page_name);
});
function setdefaultdata()
 {
	
	var ck_edit = CKEDITOR.instances.social_message.getData();
	if(ck_edit == "")
	{
		$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
	else
	{
		var html=CKEDITOR.instances.social_message.getSnapshot();
		var dom=document.createElement("DIV");
		dom.innerHTML=html;
		var plain_text=(dom.textContent || dom.innerText);
		 CKEDITOR.instances.social_message.setData(plain_text);
		
		//create and set a 128 char snippet to the hidden form field
		//var snippet=plain_text.substr(0,140);
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
		$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		
		}	
		$('#submit').submit();
	}
 }
	
function sendnow()
{
	var plarform = $("#platform").val();
	if(plarform == 1)
	{
		//FBLogin();
		//return false;
	}
	if(plarform == 3)
	{
		<?php /*?>var contact_id = $("#email_to").val();
			//window.open("http://www.facebook.com/messages/new?to=100001598523327");
			 url = '<?php echo $this->config->item('user_base_url').$viewname.'/linkedin_contact';?>';
			 $.ajax({
				 type: "POST",
				 //dataType: 'json',
				 url: url,
				 data: {login:'login',contactlist:contact_id,template_id:$("#template_name").val()},
				 cache: false,
				
				 success: function(data){
					 if(data == 'done')
					 {}
					 else
					 {alert(data);return false;}
					 
					}
				});<?php */?>
		//return false;
	}
}
function FBLogin(){
	url = '<?php echo $this->config->item('user_base_url').$viewname.'/fb_connection';?>';
	 $.ajax({
				 type: "POST",
				 //dataType: 'json',
				 url: url,
				 data: {login:'login'},
				 cache: false,
				 beforeSend: function() {
					//	$('#facebook_chat_history').block({ message: 'Loading...' }); 
				  },
				 success: function(data){
				 }
				});
}

function FBLogin_send(){
	
	FB.login(function(response){
		if(response.authResponse)
		{
		return false;
				var contact = "<?php echo $this->router->uri->segments[4] ?>"; 
				
				 $.ajax({
					 type: "POST",
					 url: "<?=base_url().'user/'.$fb_path?>",
					 data: {login:'login',contact:contact},
					 cache: false,
	 				 beforeSend: function() {
							$('.loding_win').block({ message: 'Loading...' }); 
						  },
					 success: function(data){
					//alert(data);
					//data = new Array();
					//data = ['100000327018571','100001598523327'];
					if(data != '' && data != 0)
					{	
						FB.ui({
						  method: 'send',
						  to:data,
						  link: 'http://topsdemo.in/',
						});
					}
					else
					{
						$.confirm({'title': 'Alert','message': " <strong> This contact is not your friend "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
						   
					}
					//$("#basicModal_3").dialog("close");
					$( ".close" ).trigger( "click" );
					$('.loding_win').unblock(); 
					}
					});

		   // window.location.href = "<?=base_url().'user/'.$path_view?>";
		}
	}, {scope: 'email,user_likes,user_friends,read_stream, export_stream, read_mailbox'});
}

function send_time_focus()
{
	$('#send_time').focus();	
}
function divhideshow(id)
{
	if(id == 1)
		$("#facebook_hide_div")	.hide();
	else
		$("#facebook_hide_div")	.show();
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
			$("#social_message").val('');
			
			$("#template_name").html("<option value='-1'>No template available</option>");
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
				//alert(item.post_content);
				$("#social_message").val(item.post_content);
				$('#social_message').keyup();
				//editor.insertText("ckEditor");
	//CKEDITOR.instances.email_message.setData(item.email_message);
				
				CKEDITOR.instances.social_message.insertText(item.post_content);
					//$("#email_message").innerHTML(item.email_message);
				});
			}
			else
			{
				
				$("#social_message").val('');
				CKEDITOR.instances.social_message.setData('');
			}
		 }
	});
});
$("#platform").change(function() {
//alert($("#template_name").val());
	var platformid=$("#platform").val();
	if(platformid == '1')
	{
		$('.save_data').hide();
	}
	else
	{
		$('.save_data').show();	
	}
	
			return false;
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
			return true
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter date"+"<strong></strong>",
						'buttons': {'ok'	: {
						'class'	: 'btn_center alert_ok',	
						'action': function(){
						 $('#send_date').focus();
						}},  }});
			return false;
		}
	}
	else
		return true;
}

$(document).ready(function (){
	
	
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
});
</script> 
 
 
 
