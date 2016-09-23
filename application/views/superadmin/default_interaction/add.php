<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php
 
$viewname = $this->router->uri->segments[2];
$inte_plan_id = $this->router->uri->segments[4];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fileuploadmulti.min.js"></script>

<style>
.ui-multiselect{width:100% !important;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title add_title">Add New Template</h3>
      </div>
      <div class="modal-body view_page">
			
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



<div id="content">
    <div id="content-header">
      <h1><?php echo $this->lang->line('interaction_header');?></h1>
    </div>
    <div id="content-container">
		
      <div class="">
        <div class="col-md-12">
		
          <div class="portlet">
            <div class="portlet-header">
              <h3> <i class="fa fa-table"></i><?php if(!empty($editRecord)){ echo $this->lang->line('interaction_edit_head');}else{echo $this->lang->line('interaction_add_head');}?></h3>
			<?php /*?>  <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)inte_plan_id" class="btn btn-secondary" title="Back">Back</a> </span><?php */?>
			<?php if(!empty($editRecord[0]['interaction_plan_id'])){?>
                        <span class="pull-right"> 
                             	<?php /*?><a title="View Contact" class="btn btn-secondary" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/view_record/<?=$inte_plan_id?>"><?php echo $this->lang->line('common_view_title')?></a> <?php */?>
                           		<a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('superadmin_base_url')?><?php echo $viewname.'/'.$editRecord[0]['interaction_plan_id'];?>"><?php echo $this->lang->line('common_back_title')?></a> 
                        </span>
			  <?php }
			  else {?>
                  <span class="pull-right">
                           <a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('superadmin_base_url')?><?php echo $viewname.'/'.$inte_plan_id;?>"><?php echo $this->lang->line('common_back_title')?></a>
                   </span> 			<?php } ?>
            </div>
            <!-- /.portlet-header -->
            
            <div class="portlet-content">
              <div class="table-responsive">
			   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate onsubmit="return validation();" >
                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                  <div class="row dt-rb">
                    <div class="col-xs-12">
                      <div class="text-center pull-right"> 
					  		<?php if(!empty($editRecord)){?> 
								<?php if(!empty($previous_interaction)){ ?>
									<a href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?=$previous_interaction?>"><i class="fa fa-backward btn btn-prev-next-avail arrow"></i></a>
								<?php }else{ ?>
									<a href="javascript:void(0);"><i class="fa fa-backward btn btn-tertiary arrow"></i></a>
								<?php } ?>
								
								<?php if(!empty($next_interaction)){ ?>
									<a href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?=$next_interaction?>"><i class="fa fa-forward btn btn-prev-next-avail arrow"></i></a>
								<?php }else{ ?>
									<a href="javascript:void(0);"><i class="fa fa-forward btn btn-tertiary arrow"></i></a>
								<?php } ?>
								
							<?php  } ?>
					  </div>
                    </div>
                    <div class="col-lg-8 col-sm-12">
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="validateSelect">Action Type:</label>
						 
                          <select  class="form-control parsley-validated" name="slt_interaction_type" id="slt_interaction_type" onchange="selecttemplate(this.value)"  >
						  	<?php  for($i=0;$i < count($interaction_type);$i++)
							{?>
<option value="<?php echo $interaction_type[$i]['id'];?>" <?php if(!empty($editRecord[0]['interaction_type']) && $editRecord[0]['interaction_type'] == $interaction_type[$i]['id']){ echo "selected=selected"; } ?>><?php echo $interaction_type[$i]['name'];?></option>
							<?php } ?>
                          </select>
						  
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <fieldset class="edit_main_div">
                            <legend class="edit_title">Action Details</legend>
                            <div class="cf"></div>
                            <div class="col-xs-12 mrgtop1 form-group">
                              <label for="text-input">Action Description:<span class="mandatory_field">*</span></label>
                              <textarea name="txtarea_description" id="txtarea_description" placeholder="e.g. Description" class="form-control" data-required="true"><?php if(!empty($editRecord[0]['description'])){ echo $editRecord[0]['description']; }?></textarea>
                            </div>
                            
                            <div class="col-xs-12" >
                              <label for="text-input">Schedule:</label>
                              <div class="row" id="interaction_state">
                                <div class="col-sm-1">
                                  <label>
            <input type="radio" class="rad_start" name="rad_start_type" <?php if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '1'){ echo 'checked="checked"'; }?> value="1" checked="checked" id="rad_start_type">
                                  </label> 
                                </div>
                                <div class="col-sm-3 mrg">
      <input type="text" class="form-control" id="txt_interaction_stat_1" value="<?php if(isset($editRecord[0]['number_count']) && $editRecord[0]['start_type'] == '1'){ echo $editRecord[0]['number_count'];}?>" name="txt_interaction_stat_1" placeholder="Number" onkeypress="return isNumberKey(event);" >
                                </div>
                                <div class="col-sm-3">
                                  <select  class="form-control parsley-validated" name="slt_nub_type_1" id="slt_nub_type_1">
                                    <option value="Days" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Days'  && $editRecord[0]['start_type'] == '1'){ echo "selected"; }?>>Days</option>
									<option value="Weeks" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Weeks'  && $editRecord[0]['start_type'] == '1'){ echo "selected"; }?>>Weeks</option>
									<option value="Months" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Months'  && $editRecord[0]['start_type'] == '1'){ echo "selected"; }?> >Months</option>
									<option value="Years" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Years'  && $editRecord[0]['start_type'] == '1'){ echo "selected"; }?>>Years</option>
                                  </select>
                                </div>
                                <div class="col-sm-4">
                                  <label for="text-input">From Plan Start Date</label>
                                </div>
                              </div>
                              <div class="row" id="interaction_state">
                                <div class="col-sm-1">
                                  <label>
                                    <input type="radio" class="rad_start" name="rad_start_type"  value="2" <?php if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '2'){ echo 'checked="checked"'; }?> id="rad_start_type_2">
                                  </label>
                                </div>
                                <div class="col-sm-3">
                                  <input type="text" class="form-control" id="txt_interaction_stat_2"  name="txt_interaction_stat_2" value="<?php if(isset($editRecord[0]['number_count']) && $editRecord[0]['start_type'] == '2'){ echo $editRecord[0]['number_count'];  }?>" placeholder="Number" onkeypress="return isNumberKey(event);">
                                </div>
                                <div class="col-sm-3">
                                  <select  class="form-control parsley-validated" name="slt_nub_type_2" id="slt_nub_type_2">				
								  	  <option value="Days" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Days'  && $editRecord[0]['start_type'] == '2'){ echo "selected"; }?>>Days</option>
									<option value="Weeks" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Weeks' && $editRecord[0]['start_type'] == '2'){ echo "selected"; }?>>Weeks</option>
									<option value="Months" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Months' && $editRecord[0]['start_type'] == '2'){ echo "selected"; }?> >Months</option>
									<option value="Years" <?php if(!empty($editRecord[0]['number_type']) && $editRecord[0]['number_type'] == 'Years' && $editRecord[0]['start_type'] == '2'){ echo "selected"; }?>>Years</option>
                                  </select>
                                </div>
                                <div class="col-sm-2">
                                  <label for="text-input" style="line-height:14px;">Following</label>
                                </div>
                                <div class="col-sm-3">
                                  <select class="form-control parsley-validated" name="slt_interaction_stat_2" id="slt_interaction_stat_2">
                                    <option value="">Action</option>
                                     <?php for($i=0;$i < count($interaction_list);$i++)
											{
												if($interaction_list[$i]['id'] != $editRecord[0]['id']){
										?>
												 <option value="<?php echo $interaction_list[$i]['id'];?>" <?php if(!empty($editRecord[0]['interaction_id']) && $editRecord[0]['interaction_id'] == $interaction_list[$i]['id'] && $editRecord[0]['start_type'] == '2' ){ echo "selected=selected"; } ?> ><?php echo ucfirst(substr($interaction_list[$i]['description'],0,50));if(strlen($interaction_list[$i]['description']) > 50)echo "...";?></option>
									<?php 
												}
											}
									?>
                                  </select>
                                </div>
                              </div>
                              <div class="row" id="interaction_state">
                                <div class="col-sm-1">
                                  <label>
                                    <input type="radio" name="rad_start_type"  class="rad_start" value="3" <?php if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '3'){ echo 'checked="checked"'; }?> id="rad_start_type_3">
                                  </label>
                                </div>
                                <div class="col-sm-4">
                                 	<input id="rad_start_type_date" name="rad_start_type_date" class="form-control parsley-validated customdatepickerinput" type="text" value="<?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01' && $editRecord[0]['start_type'] == '3'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])); }?>" placeholder="Specific Date" readonly="readonly">
                                </div>
                              </div>
                            </div>
                            <div class="col-xs-12" style="display:none;">
                              <label for="text-input" style="display:block;">Priority:</label>
                              <div class="row">
                                <div class="col-sm-3">
                                  <label>
                                    <input type="radio" name="txt_priority" checked="checked" value="High" <?php if(!empty($editRecord[0]['priority']) && $editRecord[0]['priority'] == 'High'){ echo 'checked="checked"'; }?>>
                                    High</label>
                                </div>
                                <div class="col-sm-3">
                                  <label>
                                    <input type="radio" name="txt_priority"  value="Medium" <?php if(!empty($editRecord[0]['priority']) && $editRecord[0]['priority'] == 'Medium'){ echo 'checked="checked"'; }?>>
                                    Medium</label>
                                </div>
                                <div class="col-sm-3">
                                  <label>
                                    <input type="radio" name="txt_priority"  value="Low" <?php if(!empty($editRecord[0]['priority']) && $editRecord[0]['priority'] == 'Low'){ echo 'checked="checked"'; }?>>
                                    Low</label>
                                </div>
                              </div>
                            </div>
                            
                            
                            <div style="display:none;">
                            
                            <div class="col-xs-12">
                              <label for="text-input">Drop From Action List</label>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label>
                                    <input type="radio" name="rad_drop_type" checked="checked" value="1" <?php if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '1'){ echo 'checked="checked"'; }?>>
                                    Do Not Drop Off</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <div class="row">
                                <div class="col-sm-3">
                                  <label>
                                    <input type="radio" name="rad_drop_type"  value="2" <?php if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '2'){ echo 'checked="checked"'; }?>>
									
                                    Drop After</label>
                                </div>
                                <div class="col-sm-3 mrg">
                                  <input type="text" value="<?php if(isset($editRecord[0]['drop_after_day']) && $editRecord[0]['drop_type'] == '2'){ echo $editRecord[0]['drop_after_day']; }?>" name="txt_drop_after_day"  id="txt_drop_after_day" class="form-control" placeholder="Number" onkeypress="return isNumberKey(event);">
								   					
                                </div>
                                <div class="col-sm-5"> <span class="checktext">Days From Schedule Date</span> </div>
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <div class="row">
                                <div class="col-sm-3">
                                  <label>
                                    <input type="radio" name="rad_drop_type" value="3" <?php if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '3'){ echo 'checked="checked"'; }?>>
                                    Drop After</label>
									
                                </div>
								 <div class="col-sm-4">
								 	<input id="rad_drop_after_date" name="rad_drop_after_date" class="form-control parsley-validated customdatepickerinput" type="text" value="<?php if(!empty($editRecord[0]['drop_after_date']) && $editRecord[0]['drop_after_date'] != '0000-00-00' && $editRecord[0]['drop_after_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['drop_after_date'])); }?>" placeholder="Specific Date" readonly="readonly">
								 </div>
								 <div class="col-sm-4">
								 </div>
                                
                              </div>
                            </div>
                            
                            </div>
                            
                            <div class="col-xs-12 mrgb2">
                              <label for="text-input">Notes:</label>
                              <textarea class="form-control" placeholder="e.g. Notes" name="txtarea_interaction_notes"><?php if(!empty($editRecord[0]['interaction_notes'])){ echo $editRecord[0]['interaction_notes']; }?></textarea>
                            </div>
                          </fieldset>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <fieldset class="edit_main_div template_main_div">
                            <legend class="edit_title">Template</legend>
                            <div class="cf"></div>
							<div class="col-sm-12">
                            <div class="row">
							 <div class="col-sm-12">
							  <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
							  </div>
							  <div class="col-sm-12">
							  <select class="selectBox" name="slt_category" id="category" onchange="selectsubcategory(this.value)" >
							  <option value="-1">Category</option>
								 <?php if(isset($category) && count($category) > 0){
											foreach($category as $row1){
												if(!empty($row1['id'])){?>
								<option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?></option>
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
								<div class="col-sm-12 topnd_margin">
                                 <a href="#basicModal" class="text_color_red text_size add_new_category" id="basicModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add Category </a>
									<!--<a class="text_color_red text_size" target="_blank" title="Add Category" href="<?=$this->config->item('superadmin_base_url').'marketing_library_masters/add_record'?>">
										<i class="fa fa-plus-square"></i> Add Category
									</a>-->
								</div>
							</div>
							</div>
                            <div class="col-xs-12 mrgb2">
                              <label for="validateSelect">Template:</label>
                              <select  class="form-control parsley-validated" name="slt_template_name" id="slt_template_name">
                                <option value="">Select Template</option>
                              </select>
                            </div>
							<div class="">
								<div class="col-sm-12 topnd_margin">
                             <a href="#basicModal" class="text_color_red text_size add_new_template" id="basicModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add Template </a>
									<!--<a class="text_color_red text_size add_new_template" title="Add Template" href="#basicModal" onclick="add_remplate();" >
										<i class="fa fa-plus-square"></i> Add Template
									</a>-->
								</div>
							</div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
					</div>
					
                  <div class="col-sm-12 text-center margin-top-10"> 
				   <input type="hidden" name="interaction_id" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>" >
				   <input type="hidden" name="email_campaign_id" id="email_campaign_id" value="<?=!empty($email_campaign_id) ?$email_campaign_id:'';?>" >
				   <input type="hidden" name="plan_id" value="<?php if(!empty($editRecord[0]['interaction_plan_id'])){ echo $editRecord[0]['interaction_plan_id']; }else{ echo $plan_id; }?>" >
				   <input type="hidden" name="fileName" value="" id="fileName"  />
				  <input type="submit" class="btn btn-secondary" value="Save Action" name="submitbtn" />
				  <a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel">Cancel</a>
                </div>
              </div>
			  </form>
              <!-- /.table-responsive --> 
              
            </div>
            <!-- /.portlet-content --> 
            
          </div>
        </div>
      </div>
    </div>
    <!-- #content-header --> 
    
    <!-- /#content-container --> 
    
  </div>
  
<script type="text/javascript">
$(document).ready(function(){
	var settings = {
		url: "<?php echo base_url();?>superadmin/emails/upload_image",
		method: "POST",
		allowedTypes:"jpeg,jpg,gif,png,zip,docx,txt,xls,xlsx,pdf,doc",
		fileName: "myfile",
		multiple: true,
		onSuccess:function(files,data,xhr)
		{
			//alert(data);
			var site = $("#fileName").val();
			if(site != '')
				$("#fileName").val(site + ',' + data);
			else
				$("#fileName").val(data);
			$("#status").html("<font color='green'>File uploaded successfully</font>");
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
	
	$( "#rad_drop_after_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	$( "#rad_start_type_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
 
   //Datepicker show on select redio button
  $(".mrgt3").hide();
    $('input[type="radio"]').click(function(){
      //alert($(this).attr("value"));
      if($(this).attr("value")=="2"){
          $(".mrgt3").show();
      }else{
          $(".mrgt3").hide();
      }
  });
});
function validation()
{
	 var data = $('input[name=rad_start_type]:checked', '#<?php echo $viewname;?>').val();
	 var drop_data = $('input[name=rad_drop_type]:checked', '#<?php echo $viewname;?>').val();
	
		var txt_interaction_stat_1=$('#txt_interaction_stat_1').val();
		var txt_interaction_stat_2=$('#txt_interaction_stat_2').val();
		var txt_interaction_stat_3=$('#rad_start_type_date').val();
		var slt_interaction_stat_2=$('#slt_interaction_stat_2').val();
		
		var interactions_count = $('#slt_interaction_stat_2 option').size();
		
		var txt_drop_after_day=$('#txt_drop_after_day').val();
		var rad_drop_after_date=$('#rad_drop_after_date').val();
		
	if(data == '1' && txt_interaction_stat_1 == '' && $("#txtarea_description").val().trim() != '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please enter day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_interaction_stat_1').focus();
									}},  }});
		//alert('Please Enter Day');
		return false;
	}
	if(data == '2' && txt_interaction_stat_2 == '' && $("#txtarea_description").val().trim() != '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please enter day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_interaction_stat_2').focus();
									}},  }});
		//alert('Please Enter Day');
		return false;
	}
	if(data == '2' && slt_interaction_stat_2 == '' && $("#txtarea_description").val().trim() != '')
	{ 
	
		if(interactions_count == 1)
		{
			$.confirm({'title': 'Alert','message': " <strong> There are no actions. Please either select the specific date or schedule day from plan start date. "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#slt_interaction_stat_2').focus();
									}},  }});
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select action "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#slt_interaction_stat_2').focus();
									}},  }});
		}
		
		return false;
	}
	if(data == '3' && txt_interaction_stat_3 == '' && $("#txtarea_description").val().trim() != '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please select date "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#rad_start_type_date').focus();
									}},  }});
		//$.confirm({'title': 'Alert','message': " <strong> Please Select Date"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//alert('Please Select Date');
		//$('#rad_start_type_date').focus();
		 return false;
	}
	if(drop_data == '2' && txt_drop_after_day == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please enter day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_drop_after_day').focus();
									}},  }});
//		alert('Please Enter Day');
		//$.confirm({'title': 'Alert','message': " <strong> Please Enter Day"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//$('#txt_drop_after_day').focus();
		 return false;
	}
	if(drop_data == '3' && rad_drop_after_date == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please select date "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#rad_drop_after_date').focus();
									}},  }});
		//$.confirm({'title': 'Alert','message': " <strong> Please Select Date"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//alert('Please Select Date');
		//$('#rad_drop_after_date').focus();
		return false;
	}
	
	var slt_interaction_type = $('#slt_interaction_type').val();
	var slt_template_name = $('#slt_template_name').val();	
	
	//alert(slt_interaction_type+"---"+slt_template_name);
	
	if((slt_interaction_type == 3 || slt_interaction_type == 6) && slt_template_name == '')
	{
		if(slt_interaction_type == 6)
			var template_type = 'Email';
		else
			var template_type = 'SMS';
			
		$.confirm({'title': 'Alert','message': " <strong> Please select "+template_type+" template "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#slt_template_name').focus();
									}},  }});
		return false;
	}
	if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
	}
	
}
</script>
<script type="text/javascript">

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
	
	$("#subcategory").change(function() {
		selecttemplate($("#slt_interaction_type").val());
	});
	
	function selectsubcategory(id){
	 if(id!="-1"){
	  	
	   	$("#slt_template_name").html("<option value='-1'>Select Template</option>");
	   loadData('category',id);
	 }else{
		$('#slt_template_name').empty();
	   	var option = $('<option />');
		option.attr('value', '-1').text('Select Template');
		$('#slt_template_name').append(option);
	   	$("#subcategory").html("<option value='-1'>Sub-Category</option>");
	   	$("select#subcategory").multiselect('refresh').multiselectfilter();
	   
	 }
	}
	
	function loadData(loadType,loadId){
	 /* $.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_subcategory';?>",
		 dataType: 'json',
		 data: {loadType:loadType,loadId:loadId},
		 cache: false,
		 async:false,
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
			selecttemplate($("#slt_interaction_type").val());				
		 }
	   });*/
	   selecttemplate($("#slt_interaction_type").val());
	}
	
	<?php if(!empty($editRecord[0]['template_category'])){ ?>

		selectsubcategory('<?=$editRecord[0]['template_category']?>');
	
	<?php } ?>
	
	function selecttemplate(id,val){
		
		if(id == 7)
			$('.template_main_div').css('display','none');
		else
			$('.template_main_div').css('display','');
			
			
		/*if(id == 3 || id == 6)
		{
			$('.show_email_sms_div').css('display','');
			if(id == 3)
				$('.div_only_email').css('display','none');
			else
				$('.div_only_email').css('display','');
		}
		else
			$('.show_email_sms_div').css('display','none');*/
			
		categoryid = $('#category').val();
		subcategoryid = $('#subcategory').val();
		//$('#slt_template_name').text('');
		var option1 = $('<option />');
		option1.attr('value', '').text('Fetching Template(s)...');
		$('#slt_template_name').html(option1);
		
		
		$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_selecttemplate';?>",
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
				
				$('#slt_template_name').empty();
				var option = $('<option />');
				option.attr('value', '').text('Select Template');
				$('#slt_template_name').append(option);
				 
				$.each(result,function(i,item){ 
							var option = $('<option />');
							option.attr('value', item.id).text(this.template_name);
							
							if(val == "selected" && i == 0)
								option.attr("selected","selected");
							else if(selectedsubcat == item.id && val != "selected")
								option.attr("selected","selected");
							
							$('#slt_template_name').append(option);
					});
				//$("select#subcategory").multiselect('refresh').multiselectfilter();
			
			}
			else
			{
				$('#slt_template_name').empty();
				var option = $('<option />');
				option.attr('value', '').text('No Template Available');
				$('#slt_template_name').append(option);
			}
							
		 }
	   });
	   
	}
	
	function selectcategory(id,val)
	{
		$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_selecttemplate';?>",
		 dataType: 'json',
		 data: {loadId:id,selected:val},
		 cache: false,
		 success: function(result){
		 
			
		 	/*$('#category').empty();
			$("#category").html("<option value='-1'>Category</option>");*/
		 	if(result != null)
			{
				//alert(result);
				$.each(result,function(i,item){
							if(val == "selected" && i == 0)
								$('#category option[value="' + item.template_category + '"]').prop('selected', true);
				});
				$("select#category").multiselect('refresh').multiselectfilter();
				selecttemplate(id,val);
			}		
		 }
	   });	
	}
	
	function selectnewcategory()
	{
		$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_selectcategory';?>",
		 dataType: 'json',
		 data: {},
		 cache: false,
		 success: function(result){
		 
		 	$('#category').empty();
			var option = $('<option />');
			option.attr('value','-1').text('Category');
			$('#category').append(option);
		 	if(result != null)
			{
				
				$.each(result,function(i,item){ 
						var option = $('<option />');
						option.attr('value', item.id).text(this.category);
						
						if(i == 0)
							option.attr("selected","selected");
						
						$('#category').append(option);
				});
				$("select#category").multiselect('refresh').multiselectfilter();
				selecttemplate($("#slt_interaction_type").val());
			}		
		 }
	   });
	}
	
	<?php if(!empty($editRecord[0]['interaction_type'])){ ?>

		selecttemplate('<?=$editRecord[0]['interaction_type']?>');
	
	<?php }else{ ?>
	
		selecttemplate($("#slt_interaction_type").val());
	
	<?php } ?>
		
</script>


<script type='text/javascript'>
/*function CuteWebUI_AjaxUploader_OnTaskComplete(task)
{
	//var div=document.createElement("DIV");
	//div.innerHTML=task.FileName + " is uploaded!";
	//document.body.appendChild(div);
	//alert(task.FileName);
	var site = document.getElementById("fileName");
	var vali=site.value;
	//alert(vali);
	if(vali != '')
	{site.value=vali+','+task.FileName;}
	else
	{site.value=task.FileName;}
		setTimeout(function(){$('.AjaxUploaderQueueTable tbody tr:last').find('td:last').after( "<td><a href='javascript:void(0);' value='"+task.FileName+"' ><img src='<?=base_url()?>images/stop.png' /></a></td>" )}, 1000);
		
	//$('#fileName').val(task.FileName);
}

$('.AjaxUploaderQueueTable tbody tr td a').live( "click", function() {
		//var file_name = $(this).attr("value");
		 $('.AjaxUploaderQueueTable tbody tr:last').remove();
		$.ajax({
			type: "POST",
			url: '<?=base_url()?>superadmin/emails/delete_attachment',
			data: {
			result_type:'ajax',file_name:$(this).attr("value")
		},
		success: function(data){
				var str = $("#fileName").val();
				var myarray = str.split(",");
				var site = '';
				for(j=0;j<myarray.length;j++)
				{
					if(myarray[j] != data)
						site = site + myarray[j] + ",";
				}
				var cnt = site.lastIndexOf(",");
				var string = site.substring(0,cnt);
				$("#fileName").val(string);
				
			}
		});
		//$(this).closest('.AjaxUploaderQueueTableRow').remove();
		return false;
});*/

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if(charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

$('body').on('click','.add_new_template',function(e){
	
	var templatetype = $('#slt_interaction_type').val();
	var modulelink = '';
	
	
	if(templatetype == 1)
		modulelink = 'label_library/add_record';
	else if(templatetype == 2)
		modulelink = 'envelope_library/add_record';
	else if(templatetype == 3)
		modulelink = 'sms_texts/add_record';
	else if(templatetype == 4)
		modulelink = 'phonecall_script/add_record';
	else if(templatetype == 5)
		modulelink = 'letter_library/add_record';
	else if(templatetype == 6)
		modulelink = 'email_library/add_record';
	$(".add_title").html('Add New Template');
	$(".view_page").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	if(modulelink != '')
	{
		var frameSrc = '<?php echo $this->config->item('superadmin_base_url')?>'+modulelink+'/iframe';
		//$('iframe').attr("src",frameSrc);
		$(".view_page").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="490" width="99.6%"></iframe>');
	}
	
});

$('body').on('click','.add_new_category',function(e){
	$(".add_title").html('Add New Category');
	var frameSrc = '<?php echo $this->config->item('superadmin_base_url')?>marketing_library_masters/add_record/iframe';
	//$('iframe').attr("src",frameSrc);
	$(".view_page").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="490" width="99.6%"></iframe>');
});


</script>

<script>
// not selected schedule-  value none
 /*$('.rad_start').click(function(){	
			$('#interaction_state input,#interaction_state select').val('');	
 });*/
</script>
