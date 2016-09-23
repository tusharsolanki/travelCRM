<?php

/*
    @Description: Lead Capturing add/edit page
    @Author: Mohit Trivedi
    @Date: 13-09-2014

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
.ui-multiselect{width:100% !important;}
.tabelbdr table{ width:100%;}
.tabelbdr_new { padding:15px;}
.tabelbdr table label{ float:left;}

#common_div table{ width:75%;}
#common_div table tr td{ vertical-align:top;}
#common_div table tr td label{ float:left!important;}
#common_div table tr td textarea{ width:96%!important;}
#common_div table tr td .txt_date{ width:66%!important; float:left;}
#common_div .ui-datepicker-trigger{ border:none; background:none; padding:0;}

#basicModal table{ width:75%;}
#basicModal table tr td{ vertical-align:top;}
#basicModal table tr td label{ float:left!important;}
#basicModal table tr td textarea{ width:96%!important;}
#basicModal table tr td .txt_date{ width:66%!important; float:left;background:#eee !important;}
#basicModal .ui-datepicker-trigger{ border:none; background:none; padding:0;}

/*#common_div table tr td:nth-child(3) {  
display:none!important;
  width:0px!important;
 }*/
.val {color:#f00;}
#previewformdata{ overflow:auto; height:400px;}
</style>
<script src="<?=base_url('js');?>/jquery.simple-color.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('lead_capturing_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('lead_capturing_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('lead_capturing_add_head'); } 
	   else{ echo $this->lang->line('lead_capturing_edit_head'); }?> </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
	  </div>
    
      <div class="portlet-content">
	  
       <div class="table_large-responsive">
              <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                
                <div id="common_div">
				                     <form class="form parsley-form" data-validate="parsley" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">     

                  <div class="row">
                    <div class="col-xs-12 mrgb2">
					
                      <fieldset class="edit_main_div">
                        <legend class="edit_title">Form Properties</legend>
                        <div class="cf"></div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                          <div class="col-xs-12 mrgtop1 form-group">
                            <label for="text-input"><?=$this->lang->line('common_label_form_title')?><span class="val">*</span></label>
                            <input id="form_title" name="form_title" class="form-control parsley-validated" data-parsley-group="block2" type="text" value="<?php if(!empty($editRecord[0]['form_title'])){ echo htmlentities($editRecord[0]['form_title']); }?>" data-required="true">

						  </div>
                          <!--<div class="col-xs-1 mrgtop1">
                          	<input type="checkbox" id="show_title" name="show_title" class="form-control parsley-validated" value="1" <?php if(isset($editRecord[0]['show_title']) && $editRecord[0]['show_title'] == '1') echo 'checked=checked'; elseif(!isset($editRecord[0]['show_title'])) echo 'checked=checked'; ?> >
                          </div>
                          <div class="col-xs-12 mrgtop1">
                            <label for="text-input">Show Title In Embed Form</label>
						  </div>-->
                          
                          <div class="col-sm-12 form-group">
                             <div class="row checkbox">
                              <label class="">
                                Show Title In Embed Form
                              <div class="float-left margin-left-10">
                               <input type="checkbox" id="show_title" name="show_title" class="" value="1" <?php if(isset($editRecord[0]['show_title']) && $editRecord[0]['show_title'] == '1') echo 'checked=checked'; elseif(!isset($editRecord[0]['show_title'])) echo 'checked=checked'; ?> >
                              </div>
                              </label>
                             </div>
                          </div>
                          
                          <div class="col-xs-12 mrgtop1 form-group">
                            <label for="text-input"><?=$this->lang->line('common_label_desc')?></label>
			  				<textarea name="form_desc" id="form_desc" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['form_desc'])){ echo htmlentities($editRecord[0]['form_desc']); }?></textarea>
                          </div>
                          
                          <!--<div class="col-xs-11 mrgtop1">
                          	<input type="checkbox" id="show_desc" name="show_desc" class="form-control parsley-validated" value="1" <?php if(isset($editRecord[0]['show_desc']) && $editRecord[0]['show_desc'] == '1') echo 'checked=checked'; elseif(!isset($editRecord[0]['show_desc'])) echo 'checked=checked'; ?> >
                          </div>
  	                      <div class="col-xs-11 mrgtop1">
                            <label for="text-input">Show Description In Form Builder</label>
						  </div>-->
                          
                          <div class="col-sm-12 form-group">
                             <div class="row checkbox">
                              <label class="">
                                Show Description In Embed Form
                              <div class="float-left margin-left-10">
                               <input type="checkbox" id="show_desc" name="show_desc" class="" value="1" <?php if(isset($editRecord[0]['show_desc']) && $editRecord[0]['show_desc'] == '1') echo 'checked=checked'; elseif(!isset($editRecord[0]['show_desc'])) echo 'checked=checked'; ?> >
                              </div>
                              </label>
                             </div>
                          </div>
                          
                          <div class="col-xs-12 mrgtop22">
                            <label for="text-input"><?=$this->lang->line('label_succmessage')?></label>
			    		   <textarea name="success_msg" id="success_msg" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['success_msg'])){ echo htmlentities($editRecord[0]['success_msg']); }?></textarea>
                          </div>
                          <div class="col-sm-12">
                              <label for="text-input"><?=$this->lang->line('common_label_assignuser');?></label>
                              <select class="form-control parsley-validated ui-widget-header" name='slt_user' id='slt_user'>
                                <option value=''>Select User</option>
                              <?php if(isset($userlist) && count($userlist) > 0){
                                
                                            foreach($userlist as $row){
                                                if(!empty($row['user_id'])){?>
                                <option value='<?php echo $row['user_id'];?>' <?php if(isset($editRecord[0]['assign_user_id']) && $editRecord[0]['assign_user_id'] ==$row['user_id']){ echo "selected";}?> ><?php if($row['admin_name']!='') { echo ucwords($row['admin_name']." (".$row['email_id'].")");}else{ echo ucwords($row['user_name']." (".$row['email_id'].")");}?></option>
                                <?php 		}
                                            }
                                        } ?>
                              </select>
            			 </div>
                        </div>
                       
                        <div class="col-lg-6 col-md-6 col-xs-12">
                        
                          <div class="col-xs-12 mrgtop1">
                       
                          <label for="text-input"><?=$this->lang->line('label_assint_contacttype')?></label>
                          <select class="form-control parsley-validated ui-widget-header" name='contact_type_id[]' id='contact_type_id'  multiple="multiple">
                 <?php if(isset($contact_type) && count($contact_type) > 0){
							foreach($contact_type as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($lead_contact_type_trans) && in_array($row1['id'],$lead_contact_type_trans)){ echo "selected=selected"; } ?>><?php echo ucwords($row1['name']);?></option>
                <?php 		}
							}
						} ?>
              </select>

                         </div>
                        <?php /* if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                          <div class="col-xs-12 mrgtop1">
                            <label for="text-input"><?=$this->lang->line('label_assintplan')?><!--<span class="val">*</span>--></label>
                          <select class="form-control parsley-validated ui-widget-header" name='assigned_interaction_plan_id' id='plan_id'>
              <option value="">Select Communication</option>
                 <?php if(isset($plan) && count($plan) > 0){
							foreach($plan as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['assigned_interaction_plan_id']) && $editRecord[0]['assigned_interaction_plan_id'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['plan_name']);?></option>
                <?php 		}
							}
						} ?>
              </select>

                         </div>
                         <? } */ ?>
                          <div class="col-xs-12 mrgtop1">
                            <label for="text-input">Width (In px)</label>
                            <input id="form_width" name="form_width" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['form_width'])){ echo $editRecord[0]['form_width']; }?>" onkeypress="return isNumberKey(event);">

                          </div>
                          <div class="col-xs-12 mrgtop1">
                            <label for="text-input">Height (In px)</label>
                            <input id="form_height" name="form_height" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['form_height'])){ echo $editRecord[0]['form_height']; }?>" onkeypress="return isNumberKey(event);">

                          </div>
                          <div class="col-xs-12 mrgtop22">
                            <label for="text-input">Background Color</label>
                          <input class='simple_color form-control parsley-validated' value='<?=!empty($editRecord[0]['bg_color'])?$editRecord[0]['bg_color']:'#FFF';?>' name="bg_color" id="bg_color"/>
						  </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12">
					  <div class="custom_field">
               		<h4>Add Fields</h4>
               <select  ondblclick="addfieldtoeditor(); common_hide();" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox" >
             	 <option title="First Name" value="1">First Name</option>
                 <option title="Last Name" value="9">Last Name</option>
      			 <option title="Phone" value="2">Phone</option>
      			 <option title="Email" value="3">Email</option>
      			 <option title="Single Line Text" value="4">Single Line Text</option>
      			 <option title="Paragraph Text" value="5">Paragraph Text</option>
      			 <option title="Address" value="6">Address</option>
      			 <option title="Date" value="7">Date</option>
      			 <option title="Website" value="8">Website</option>
                 <option title="Area of Interest" value="10">Area of Interest</option>
                 <option title="Price Range" value="11">Price Range</option>
                 <option title="Bedrooms" value="12">Bedrooms</option>
                 <option title="Bathrooms" value="13">Bathrooms</option>
                 <option title="Buyer Preference Notes" value="14">Buyer Preference Notes</option>
                 <option title="House Style" value="15">House Style</option>
                 <option title="Square Footage" value="16">Square Footage</option>
                 <option title="File" value="17">File</option>
            </select>
            
            
                 <input class="btn btn-secondary-green" type="button" name="submitbtn1" onclick="addfieldtoeditor(); common_hide();" title="Insert Field" value="Insert Field">
               
            </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12">
                      <div class="tabelbdr tabelbdr_new">
                          <div class="dataTables_filter dynamic_content_form" id="form">
							<?php
							if(!empty($editRecord[0]['lead_form']))
							{?>
							 <?php echo $editRecord[0]['lead_form'];?>
							 <?php }
							 else
							 {
								 ?>
                                 <div class="dataTables_filter" id="fnamediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="fname_type[]" id="ftitle_0" value="First Name" /><span id="fname_0">First Name</span><span class="val" id="fname_req_0"> *</span></td><td class="form-group"><label for="f_name"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="f_name[]" id="f_name0" data-required="true" /></label></td><td width="20%"  class=" dynamic_dml"><a href="javascript:void(0);" onclick="fname_edit('0')" title="Edit record" class="btn btn-xs btn-success" ><i class="fa fa-pencil"></i></a></td></tr></table></div>
								 
							<?php  }
							?>
                                </div>
							<input type="hidden" name="divcontent1" id="divcontent1" />				
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12">
                       <input type="hidden" name="fname_edit_id" id="fname_edit_id">
                      <div class="boxrgt" id="fname_edit" style="display:none">
                      <div class="dataTables_filter" id="fieldedit" >
                      <label>First Name</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_fname_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_name_label('1',this.value);">
                     </div>

                     <div class="dataTables_filter first_isrequired" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" id="fname_is_required" type="checkbox" onchange="fname_required();" />
                      </div>
                    </div>
                    
                    <div class="boxrgt" id="lname_edit" style="display:none">
                       <input type="hidden" name="lname_edit_id" id="lname_edit_id">
                      <div class="dataTables_filter" id="fieldedit" >
                      <label>Last Name</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_lname_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_name_label('0',this.value);">
                     </div>
                      <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" id="lname_is_required" type="checkbox" onchange="lname_required();" />
                      </div>
                    </div>
                    
                    
					 <div class="boxrgt" id="phone_edit" style="display:none">
                      <input type="hidden" name="phone_edit_id" id="phone_edit_id">
                      <div class="dataTables_filter" id="fieldedit" >
                      <label>Phone Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_phone_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_phone_label(this.value);">
                      </div>
                      <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" id="phone_is_required" type="checkbox" onchange="phone_required();" />
                      </div>
                    </div>
					  <div class="boxrgt" id="email_edit" style="display:none">
                                              <input type="hidden" name="email_edit_id" id="email_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Email Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_email_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_email_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" id="email_is_required" onchange="email_required();" />
                     </div>
                    </div>
					  <div class="boxrgt" id="linetext_edit" style="display:none">
                                              <input type="hidden" name="single_edit_id" id="single_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Text Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_single_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_single_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="single_is_required" onchange="single_required();" />
                     </div>
                    </div>
					  <div class="boxrgt" id="address_edit" style="display:none">
                                              <input type="hidden" name="address_edit_id" id="address_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Address Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_address_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_address_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="address_is_required" onchange="address_required();" />
                     </div>
                    </div>
					  <div class="boxrgt" id="paratext_edit" style="display:none">
                                              <input type="hidden" name="paratext_edit_id" id="paratext_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Paragraph Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_paratext_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_paratext_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="paratext_is_required" onchange="paratext_required();" />
                     </div>
                    </div>
					  <div class="boxrgt" id="date_edit" style="display:none">
                                              <input type="hidden" name="date_edit_id" id="date_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Date Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_date_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_date_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="date_is_required" onchange="date_required();" />
                     </div>
                    </div>
                      <div class="boxrgt" id="web_edit" style="display:none">
                          <input type="hidden" name="web_edit_id" id="web_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Website Label</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_web_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_web_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="web_is_required" onchange="web_required();" />
                     </div>
                    </div>
                    
                      <div class="boxrgt" id="area_edit" style="display:none">
                                              <input type="hidden" name="area_edit_id" id="area_edit_id">
                      <div class="dataTables_filter" id="fieldedit">
                      <label>Area of Interest</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_area_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_area_label(this.value);">
                     </div>
                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="area_is_required" onchange="area_required();" />
                     </div>
                    </div>
                    
                      <input type="hidden" name="price_edit_id" id="price_edit_id">
                      <div class="boxrgt" id="price_edit" style="display:none">
                      <div class="dataTables_filter" id="fieldedit" >
                      <label>Price Range From (In $)</label>
                        <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_pricefrom_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_price_label('1',this.value);">
                     </div>
                     <div class="dataTables_filter" id="fieldedit" >
                      <label>Price Range To (In $)</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text" id="txt_priceto_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_price_label('0',this.value);">
                     </div>

                     <div class="dataTables_filter" id="isrequired">
                      <label>Is Required?</label>
                                 <input class="check" name="" id="price_is_required" type="checkbox" onchange="price_required();" />
                      </div>
                    </div>
						
                    	<div class="boxrgt" id="bedroom_edit" style="display:none">
                                              <input type="hidden" name="bedroom_edit_id" id="bedroom_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                      	<label>Bedrooms</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_bedroom_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_bedroom_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      	<label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="bedroom_is_required" onchange="bedroom_required();" />
                    	</div>
                    </div>
                    	 <div class="boxrgt" id="bathroom_edit" style="display:none">
                                              <input type="hidden" name="bathroom_edit_id" id="bathroom_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                      	<label>Bathrooms</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_bathroom_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_bathroom_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      	<label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="bathroom_is_required" onchange="bathroom_required();" />
                    	</div>
                    </div>
                    
                    	<div class="boxrgt" id="buyer_edit" style="display:none">
                                              <input type="hidden" name="buyer_edit_id" id="buyer_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                      	<label>Buyer Preference Notes</label>
                              <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_buyer_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_buyer_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      	<label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="buyer_is_required" onchange="buyer_required();" />
                    	</div>
                    </div>
                    	<div class="boxrgt" id="house_edit" style="display:none">
                                              <input type="hidden" name="house_edit_id" id="house_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                      	<label>House Style</label>
                                  <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_house_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_house_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      	<label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="house_is_required" onchange="house_required();" />
                    	</div>
                    </div>
                    
						<div class="boxrgt" id="square_edit" style="display:none">
                                              <input type="hidden" name="square_edit_id" id="square_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                      	<label>Square Footage</label>
                        <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_square_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_square_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      	<label>Is Required?</label>
                                 <input class="check" name="" type="checkbox" value="" id="square_is_required" onchange="square_required();" />
                    	</div>
                    </div>
                    <div class="boxrgt" id="file_edit" style="display:none">
                        <input type="hidden" name="file_edit_id" id="file_edit_id">
                      	<div class="dataTables_filter" id="fieldedit">
                            <label>File</label>
                            <input type="text"  class="textfield12" name="searchtext" title="Search Text"id="txt_file_edit" aria-controls="DataTables_Table_0" placeholder="" onkeyup="change_file_label(this.value);">
                     	</div>
                     	<div class="dataTables_filter" id="isrequired">
                      		<label>Is Required?</label>
                            <input class="check" name="" type="checkbox" value="" id="file_is_required" onchange="file_required();" />
                    	</div>
                    </div>

                    
                    </div>
                  </div>
				  <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
                  <div class="row">
                  <div class="col-xs-12 text-center">
				  <input type="submit" class="btn btn-secondary-green" value="Save Form" title="Save Form" name="submitbtn" id="lc_submitbtn" onclick="return select_box();" />
				   <a title="Preview Form" class="btn btn-success mrg26" data-toggle="modal" href="#basicModal" id="lc_preview" onclick="preview();">Preview Form</a>
                                   <?php 
                                   if(!empty($editRecord[0]['id'])) { ?>
                                   <a title="Embed From" class="btn btn-success mrg26 view_form_btn" data-toggle="modal" href="#basicModal1" data-id="<?=!empty($editRecord[0]['form_widget_id'])? $editRecord[0]['form_widget_id']:''?>">Embed Form</a>
                                   <?php } ?>
                                   <a title="Cancel" class="btn  btn-danger mrg26" href="javascript:history.go(-1)" id="lc_cancel">Cancel</a></div>
                  
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
 <!----popup data-------->
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog  modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup close_preview" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Preview Form</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_contact_popup text-center">
		<div id="previewformdata" align="center" class="col-sm-12">
		</div>
		<!--<input class="btn btn-success mrg26" type="button" name="savepreview" value="Save" />-->
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Embed Form</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup1 text-center">
            <div id="row_data">
            </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
				 <!----popup data end-------->
				  
<script type="text/javascript">
	$('body').on('click','.view_form_btn',function(e){
            formid = $(this).attr('data-id');
            var form_data = '<table><tr><td align="left"><label for="text-input">iFrame Code:</label></td><td><textarea rows="5" cols="50" class="form-control parsley-validated" onclick="this.focus();this.select();this.copy();"><iframe src="<?php echo $this->config->item('base_url');?>lead_capturing_form/'+formid+'" height="100%" width="100%" frameborder="0" style="boder:none;"></iframe></textarea></td></tr><tr><td align="left"><label for="text-input">Link:</label></td><td><textarea rows="5" cols="50" class="form-control parsley-validated"  onclick="this.focus();this.select();"><a href="<?php echo $this->config->item('base_url');?>lead_capturing_form/'+formid+'" title="New Form">Click Here</a></textarea></td></tr></table>';
            $("#row_data").html(form_data);
	});
	
	$("select#plan_id").multiselect({
		 multiple: false,
		 selectedList: 1
	}).multiselectfilter();
	
	$("select#contact_type_id").multiselect({
		header: "Select Contact Type",
		noneSelectedText: "Select Contact Type",
		selectedList: 1
	}).multiselectfilter();
	
	function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
	
	$('.simple_color').simpleColor({
    cellWidth: 9,
    cellHeight: 9,
    callback: function(hex, element) {
        alert("color picked! " + hex + " for input #" + element.attr('class'));
    }
	
});
var id_array = [];
$(document).ready(function(){
    $('#form').each(function(i, div) {
        $(div).find('input').each(function(j, element){
            var eleid = ($(element).attr('id'));
            if($(element).attr('data-required') == 'true') {
                id_array.push(eleid);
                $(element).removeAttr('data-required');
            }
                
        });
        $(div).find('textarea').each(function(k, element){
            var eleid = ($(element).attr('id'));
            if($(element).attr('data-required') == 'true') {
                id_array.push(eleid);
                $(element).removeAttr('data-required');
            }
        });
    });
});	

function select_box()
{
    for ( var i = 0; i < id_array.length; i = i + 1 ) {
        $('#'+id_array[i]).attr('data-required','true');
    }

	var abc = $("#plan_id").val();
	var content1 = $('#form').html(); 
	$("#divcontent1").val(content1);
		
	if(($('#form_width').val() != '' && $('#form_width').val() <= '200') || ($('#form_height').val() != '' && $('#form_height').val() <= '200'))
	{
		$.confirm({'title': 'CONFIRM','message': " <strong> Width and/or height is too small. Form may not display proper.Are you sure want to continue ? </strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
               if ($('#<?php echo $viewname?>').parsley().isValid()) {
                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                    /*$('#lc_submitbtn').attr('disabled','disabled');
                    $('#lc_preview').attr('disabled','disabled');
                    $('#lc_cancel').attr('disabled','disabled');*/
                }
			$('.parsley-form').submit();
		}},'No'	: {'class'	: 'special'}}});
	}
	else
        {
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                /*$('#lc_submitbtn').attr('disabled','disabled');
                $('#lc_preview').attr('disabled','disabled');
                $('#lc_cancel').attr('disabled','disabled');*/
            }
            $('.parsley-form').submit();
        }
	return false;
	

}


	</script>
<script type="text/javascript">
 	var counterFName=1;
	var counterLName=0;
	var counterPhone=0;
	var counterEmail=0;
	var counterLtext=0;
	var counterParatext=0;
	var counterAddtext=0;
	var counterDate=0;
	var counterWeb=0;
	var counterArea=0;
	var counterRange=0;
	var counterBedrooms=0;
	var counterBathrooms=0;
	var counterBuyers=0;
	var counterHouseStyle=0;
	var counterSquarefoot=0;
	var counterFile=0;
function addfieldtoeditor() {
	var abc = $('#slt_customfields').val();
	for(counter=0;counter<abc.length;counter++)
	{
		a = abc[counter];
        if(a == 1)
        {
            var scntDiv = $('#form');
            var i = $('#fnamediv').size();
            if($('#f_name'+counterFName).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#f_name'+counterFName).length == 1)
						counterFName++;
					 else
					 	break;
				}
            }
			if ($('#f_name'+counterFName).length != 1)
			{
            //alert($(this).parents("#namediv").children().first().attr("id"));
            	$('<div class="dataTables_filter" id="fnamediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="fname_type[]" id="ftitle_'+counterFName+'" value="First Name" /><span id="fname_'+counterFName+'">First Name</span><span class="val" id="fname_req_'+counterFName+'"></span></td><td class="form-group"><label for="f_name"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="f_name[]" id="f_name'+counterFName+'" /></label></td><td width="20%"  class=" dynamic_dml"><a href="javascript:void(0);" onclick="fname_edit('+counterFName+')" title="Edit record" class="btn btn-xs btn-success" ><i class="fa fa-pencil"></i></a><a href="#"  onclick="common_hide()" id="remScnt_fname" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterFName++;
                
            }
        }
		else if(a == 9)
		{
			
            var scntDiv = $('#form');
            var i = $('#lnamediv').size();
            if($('#l_name'+counterLName).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#l_name'+counterLName).length == 1)
						counterLName++;
					 else
					 	break;
				}
            }
			if ($('#l_name'+counterLName).length != 1)
			{
            	$('<div class="dataTables_filter" id="lnamediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="lname_type[]" id="ltitle_'+counterLName+'" value="Last Name" /><span id="lname_'+counterLName+'">Last Name</span><span class="val" id="lname_req_'+counterLName+'"></span></td><td class="form-group"><label for="l_name"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="l_name[]" id="l_name'+counterLName+'" /></label></td><td width="20%"  class=" dynamic_dml"><a href="javascript:void(0);" onclick="lname_edit('+counterLName+')" title="Edit record" class="btn btn-xs btn-success" ><i class="fa fa-pencil"></i></a><a href="#"  onclick="common_hide()" id="remScnt_lname" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterLName++;
                
            }
        	
		}
        else if(a == 2)
        {
            var scntDiv = $('#form');
            var i = $('#phonediv').size();
            if($('#phone'+counterPhone).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#phone'+counterPhone).length == 1)
						counterPhone++;
					 else
					 	break;
				}
            } 
			if($('#phone'+counterPhone).length != 1)
			{
                $('<div class="dataTables_filter" id="phonediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="phone_type[]" id="phone_title_'+counterPhone+'" value="Phone" /><span id="pphone_'+counterPhone+'">Phone</span><span class="val" id="pphone_req_'+counterPhone+'"></span></td><td class="form-group"><label for="phone"><input type="text" maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" data-parsley-group="block1" name="phone[]" id="phone'+counterPhone+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="phone_edit('+counterPhone+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_phone" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterPhone++;
                
            }
        }
        else if(a == 3)
        {
            var scntDiv = $('#form');
            var i = $('#emaildiv').size();
            if($('#email'+counterEmail).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#email'+counterEmail).length == 1)
						counterEmail++;
					 else
					 	break;
				}
            }
			if($('#email'+counterEmail).length != 1)
			{
                $('<div class="dataTables_filter" id="emaildiv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="email_type[]" id="email_title_'+counterEmail+'" value="Email" /><span id="eemail'+counterEmail+'">Email</span><span class="val" id="eemail_req_'+counterEmail+'"></span></td><td class="form-group"><label for="email"><input class="form-control parsley-validated" type="email" name="email[]" id="email'+counterEmail+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="email_edit('+counterEmail+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_email" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterEmail++;
                
            }
        }
        else if(a == 4)
        {
            var scntDiv = $('#form');
            var i = $('#linetextdiv').size();
            if($('#linetext'+counterLtext).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#linetext'+counterLtext).length == 1)
						counterLtext++;
					 else
					 	break;
				}
            } 
			if($('#linetext'+counterLtext).length != 1)
			{
                $('<div class="dataTables_filter" id="linetextdiv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="single_type[]" id="single_title_'+counterLtext+'" value="Line Text" /><span id="lline'+counterLtext+'" >Line Text</span><span class="val" id="lline_req_'+counterLtext+'"></span></td><td class="form-group"><label for="linetext"><input type="text" class="form-control parsley-validated" name="linetext[]" id="linetext'+counterLtext+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="linetext_edit('+counterLtext+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_linetext" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterLtext++;
                
            }
        }
        else if(a == 5)
        {
            var scntDiv = $('#form');
            var i = $('#paratextdiv').size();
            if($('#paratext'+counterParatext).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#paratext'+counterParatext).length == 1)
						counterParatext++;
					 else
					 	break;
				}
            }
			if($('#paratext'+counterParatext).length != 1)
			{
                $('<div class="dataTables_filter" id="paratextdiv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="para_type[]" id="para_title_'+counterParatext+'" value="Paragraph Text" /><span id="para'+counterParatext+'">Paragraph Text</span><span class="val" id="para_req_'+counterParatext+'"></span></td><td class="form-group"><label for="paratext"><textarea name="paratext[]" class="form-control parsley-validated" id="paratext'+counterParatext+'"/></textarea></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="paratext_edit('+counterParatext+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" onclick="common_hide()" id="remScnt_paratext" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterParatext++;
                
            }
        }
        else if(a == 6)
        {
            var scntDiv = $('#form');
            var i = $('#addressdiv').size();
            if($('#address'+counterAddtext).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#address'+counterAddtext).length == 1)
						counterAddtext++;
					 else
					 	break;
				}
            }
			if ($('#address'+counterAddtext).length != 1)
			{
                $('<div class="dataTables_filter" id="addressdiv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="add_type[]" id="add_title_'+counterAddtext+'" value="Address" /><span id="add'+counterAddtext+'">Address</span><span class="val" id="add_req_'+counterAddtext+'"></span></td><td class="form-group"><label for="address"><textarea class="form-control parsley-validated" name="address[]" id="address'+counterAddtext+'"/></textarea></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="address_edit('+counterAddtext+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_address" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterAddtext++;
                
            }
        }

        else if(a == 7)
        {
            var scntDiv = $('#form');
            var i = $('#datediv').size();
            if($('#date'+counterDate).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#date'+counterDate).length == 1)
						counterDate++;
					 else
					 	break;
				}
            }
			if($('#date'+counterDate).length != 1)
			{
                $('<div class="dataTables_filter" id="datediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="date_type[]" id="date_title_'+counterDate+'" value="Date" /><span id="ddate'+counterDate+'">Date </span><span class="val" id="ddate_req_'+counterDate+'"></span></td><td class="form-group"><label for="date"><input type="text" class="form-control parsley-validated txt_date" readonly name="date[]" id="date'+counterDate+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="date_edit('+counterDate+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_date" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterDate++;
                
            }
        }
        else if(a == 8)
        {
            var scntDiv = $('#form');
            var i = $('#websitediv').size();
            if($('#website'+counterWeb).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#website'+counterWeb).length == 1)
						 counterWeb++;
					 else
					 	break;
				}
            } 
			if ($('#website'+counterWeb).length != 1)
			{
                $('<div class="dataTables_filter" id="websitediv"><table><tr><td width="30%" class="web" valign="middle"><input type="hidden" name="web_type[]" id="web_title_'+counterWeb+'" value="Website" /><span id="web'+counterWeb+'">Website</span><span class="val" id="web_req_'+counterWeb+'"></span></td><td class="form-group"><label for="website"><input type="url" class="form-control parsley-validated" name="website[]" id="website'+counterWeb+'" data-parsley-type="url"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="web_edit('+counterWeb+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_website" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterWeb++;
                
            }
        }
		else if(a == 10)
        {
            var scntDiv = $('#form');
            var i = $('#areaofinterestdiv').size();
            if($('#areaofinterest'+counterArea).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#areaofinterest'+counterArea).length == 1)
						 counterArea++;
					 else
					 	break;
				}
            } 
			if ($('#areaofinterest'+counterArea).length != 1)
			{
                $('<div class="dataTables_filter" id="areaofinterestdiv"><table><tr><td width="30%" class="area" valign="middle"><input type="hidden" name="area_type[]" id="area_title_'+counterArea+'" value="Area of Interest" /><span id="areaofinterest_'+counterArea+'">Area of Interest</span><span class="val" id="areaofinterest_req_'+counterArea+'"></span></td><td class="form-group"><label for="areaofinterest"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="areaofinterest[]" id="areaofinterest'+counterArea+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="area_edit('+counterArea+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_areaofinterest" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterArea++;
                
            }
        }
		else if(a == 11)
		{
			
            var scntDiv = $('#form');
            var i = $('#pricediv').size();
			
            if($('#price_from'+counterRange).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#price_from'+counterRange).length == 1)
						 counterRange++;
					 else
					 	break;
				}
            } 
            if ($('#price'+counterRange).length != 1)
			{
            //alert($(this).parents("#namediv").children().first().attr("id"));
            	$('<div class="dataTables_filter" id="pricediv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="pricefrom_type[]" id="pricefrom_title_'+counterRange+'" value="Price Range From (In $)" /><span id="pricefrom_'+counterRange+'">Price Range From (In $)</span><span class="val" id="price_from_req_'+counterRange+'"></span></td><td class="form-group"><label for="price_from"><input type="text" class="form-control parsley-validated txt_price_range_from" data-parsley-group="block1" name="price_from[]" id="price_from'+counterRange+'" /></label></td><td></td></tr><tr><td width="25%"><input type="hidden" name="priceto_type[]" id="priceto_title_'+counterRange+'" value="Price Range To (In $)" /><span id="priceto_'+counterRange+'">Price Range To (In $)</span><span class="val" id="price_to_req_'+counterRange+'"></span></td><td><label for="price_to"><input type="text" class="form-control parsley-validated txt_price_range_to" name="price_to[]" data-parsley-group="block1" id="price_to'+counterRange+'"/></label></td><td width="20%"  class=" dynamic_dml"><a href="javascript:void(0);" onclick="price_edit('+counterRange+')" title="Edit record" class="btn btn-xs btn-success" ><i class="fa fa-pencil"></i></a><a href="#"  onclick="common_hide()" id="remScnt_price" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterRange++;
                
            }
        
		}
		else if(a == 12)
        {
            var scntDiv = $('#form');
            var i = $('#bedroomdiv').size();
            if($('#bedroom'+counterBedrooms).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#bedroom'+counterBedrooms).length == 1)
						 counterBedrooms++;
					 else
					 	break;
				}
            } 
			if ($('#bedroom'+counterBedrooms).length != 1)
			{
                $('<div class="dataTables_filter" id="bedroomdiv"><table><tr><td width="30%" class="bedroom" valign="middle"><input type="hidden" name="bedroom_type[]" id="bedroom_title_'+counterBedrooms+'" value="Bedrooms" /><span id="bedroom_'+counterBedrooms+'">Bedrooms</span><span class="val" id="bedroom_req_'+counterBedrooms+'"></span></td><td class="form-group"><label for="bedroom"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="bedroom[]" id="bedroom'+counterBedrooms+'" onkeypress="return isNumberKey(event);"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="bedroom_edit('+counterBedrooms+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_bedroom" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterBedrooms++;
                
            }
        }
		else if(a == 13)
        {
            var scntDiv = $('#form');
            var i = $('#bathroomdiv').size();
            if($('#bathroom'+counterBathrooms).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#bathroom'+counterBathrooms).length == 1)
						 counterBathrooms++;
					 else
					 	break;
				}
            } 
			if ($('#bathroom'+counterBathrooms).length != 1)
			{
                $('<div class="dataTables_filter" id="bathroomdiv"><table><tr><td width="30%" class="bathroom" valign="middle"><input type="hidden" name="bathroom_type[]" id="bathroom_title_'+counterBathrooms+'" value="Bathrooms" /><span id="bathroom_'+counterBathrooms+'">Bathrooms</span><span class="val" id="bathroom_req_'+counterBathrooms+'"></span></td><td class="form-group"><label for="bathroom"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="bathroom[]" id="bathroom'+counterBathrooms+'" onkeypress="return isNumberKey(event);"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="bathroom_edit('+counterBathrooms+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_bathroom" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterBathrooms++;
                
            }
        }
		else if(a == 14)
        {
            var scntDiv = $('#form');
            var i = $('#buyerdiv').size();
            if($('#buyer'+counterBuyers).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#buyer'+counterBuyers).length == 1)
						 counterBuyers++;
					 else
					 	break;
				}
            } 
			if ($('#buyer'+counterBuyers).length != 1)
			{
                //$('<div class="dataTables_filter" id="buyerdiv"><table><tr><td width="30%" class="buyer" valign="middle"><span id="buyer'+counterBuyers+'">Buyer Preference Notes</span><span class="val" id="buyer_req_'+counterBuyers+'"></span></td><td><label for="buyer"><textarea class="form-control parsley-validated" name="buyer[]" id="buyer'+counterBuyers+'" ></textarea></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="buyer_edit('+counterBuyers+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_buyer" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
				
				$('<div class="dataTables_filter" id="buyerdiv"><table><tr><td width="30%" valign="middle"><input type="hidden" name="buyer_type[]" id="buyer_title_'+counterBuyers+'" value="Buyer Preference Notes" /><span id="buyer_'+counterBuyers+'">Buyer Preference Notes</span><span class="val" id="buyer_req_'+counterBuyers+'"></span></td><td class="form-group"><label for="buyer"><textarea class="form-control parsley-validated" name="buyer[]" id="buyer'+counterBuyers+'"/></textarea></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="buyer_edit('+counterBuyers+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_buyer" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterBuyers++;
                
            }
        }
		else if(a == 15)
        {
            var scntDiv = $('#form');
            var i = $('#housediv').size();
            if($('#house'+counterHouseStyle).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#house'+counterHouseStyle).length == 1)
						 counterHouseStyle++;
					 else
					 	break;
				}
            } 
			if ($('#house'+counterHouseStyle).length != 1)
			{
                $('<div class="dataTables_filter" id="housediv"><table><tr><td width="30%" class="house" valign="middle"><input type="hidden" name="house_type[]" id="house_title_'+counterHouseStyle+'" value="House Style" /><span id="house_'+counterHouseStyle+'">House Style</span><span class="val" id="house_req_'+counterHouseStyle+'"></span></td><td class="form-group"><label for="house"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="house[]" id="house'+counterHouseStyle+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="house_edit('+counterHouseStyle+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_house" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterHouseStyle++;
                
            }
        }
		else if(a == 16)
        {
            var scntDiv = $('#form');
            var i = $('#squarediv').size();
            if($('#square'+counterSquarefoot).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#square'+counterSquarefoot).length == 1)
						 counterSquarefoot++;
					 else
					 	break;
				}
            } 
			if ($('#square'+counterSquarefoot).length != 1)
			{
                $('<div class="dataTables_filter" id="squarediv"><table><tr><td width="30%" class="square" valign="middle"><input type="hidden" name="square_type[]" id="square_title_'+counterSquarefoot+'" value="Square Footage" /><span id="square_'+counterSquarefoot+'">Square Footage</span><span class="val" id="square_req_'+counterSquarefoot+'"></span></td><td class="form-group"><label for="square"><input type="text" class="form-control parsley-validated" data-parsley-group="block1" name="square[]" id="square'+counterSquarefoot+'"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="square_edit('+counterSquarefoot+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_square" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
                counterSquarefoot++;
                
            }
        }
		else if(a == 17)
        {
            var scntDiv = $('#form');
            var i = $('#filediv').size();
            if($('#file'+counterFile).length == 1) {
				for (j = 1; j > 0; j++) {
					if ($('#file'+counterFile).length == 1)
						 counterFile++;
					 else
					 	break;
				}
            } 
			if ($('#file'+counterFile).length != 1)
			{
				//file_format="'jpg','png'";
				file_format="txt,doc,docx,pdf,csv,xls,xlsx,jpg,jpeg,png,bmp,gif";
                $('<div class="dataTables_filter" id="filediv"><table><tr><td width="30%" class="file" valign="middle"><input type="hidden" name="file_type[]" id="file_title_'+counterFile+'" value="File" /><span id="file_'+counterFile+'">File</span><span class="val" id="file_req_'+counterFile+'"></span></td><td class="form-group"><label for="file"><input type="file" class="form-control parsley-validated file_type" data-parsley-group="block1" file-format="'+file_format+'" name="file_name[]" id="file'+counterFile+'" style="margin-bottom:10px;"/></label></td><td width="20%" class=" dynamic_dml"><a href="javascript:void(0);" onclick="file_edit('+counterFile+')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a><a href="#" id="remScnt_file" onclick="common_hide()" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></td></tr></table></div>').appendTo(scntDiv);
                i++;
				//$('#file').trigger('disable');
				//$("#file"+counterFile).on("click", false);
                counterFile++;
				
                
            }
		}
	}
	return false;
}

$('body').on('click', '.file_type', function(e){
	e.preventDefault();
});

$('body').on('keypress', '.dynamic_content_form input,.dynamic_content_form textarea', function(e){
	e.preventDefault();
});
	
$('body').on('keypress', '#previewformdata input,#previewformdata textarea', function(e){
	e.preventDefault();
});

//Remove div which is dynamically created....

$('body').on('click', '#remScnt_fname', function(){
	var i = $('#fnamediv').size();
	
		if( i > 0 ) {
				$(this).parents('#fnamediv').remove();
                                //$(this).parents('#namediv').removeAttr('data-required');
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_lname', function(){
	var i = $('#lnamediv').size();
	
		if( i > 0 ) {
				$(this).parents('#lnamediv').remove();
                                //$(this).parents('#namediv').removeAttr('data-required');
				i--;
		}
		return false;
 });


$('body').on('click', '#remScnt_phone', function(){
	var i = $('#phonediv').size();
		if( i > 0 ) {
				$(this).parents('#phonediv').remove();
				i--;
		}
		return false;
 });


$('body').on('click', '#remScnt_email', function(){
	var i = $('#emaildiv').size();
		if( i > 0 ) {
				$(this).parents('#emaildiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_linetext', function(){
	var i = $('#linetextdiv').size();
		if( i > 0 ) {
				$(this).parents('#linetextdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_paratext', function(){
	var i = $('#paratextdiv').size();
		if( i > 0 ) {
				$(this).parents('#paratextdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_address', function(){
	var i = $('#addressdiv').size();
		if( i > 0 ) {
				$(this).parents('#addressdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_date', function(){
	var i = $('#datediv').size();
		if( i > 0 ) {
				$(this).parents('#datediv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_website', function(){
	var i = $('#websitediv').size();
		if( i > 0 ) {
				$(this).parents('#websitediv').remove();
				i--;
		}
		return false;
 });


$('body').on('click', '#remScnt_areaofinterest', function(){
	var i = $('#areaofinterestdiv').size();
		if( i > 0 ) {
				$(this).parents('#areaofinterestdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_price', function(){
	var i = $('#pricediv').size();
		if( i > 0 ) {
				$(this).parents('#pricediv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_bedroom', function(){
	var i = $('#bedroomdiv').size();
		if( i > 0 ) {
				$(this).parents('#bedroomdiv').remove();
				i--;
		}
		return false;
 });


$('body').on('click', '#remScnt_bathroom', function(){
	var i = $('#bathroomdiv').size();
		if( i > 0 ) {
				$(this).parents('#bathroomdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_buyer', function(){
	var i = $('#buyerdiv').size();
		if( i > 0 ) {
				$(this).parents('#buyerdiv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_house', function(){
	var i = $('#housediv').size();
		if( i > 0 ) {
				$(this).parents('#housediv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_square', function(){
	var i = $('#squarediv').size();
		if( i > 0 ) {
				$(this).parents('#squarediv').remove();
				i--;
		}
		return false;
 });

$('body').on('click', '#remScnt_file', function(){
	var i = $('#filediv').size();
		if( i > 0 ) {
				$(this).parents('#filediv').remove();
				i--;
		}
		return false;
 });
 
/* function fname_edit(id)
 {
	$("#web_edit").hide();
 	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#linetext_edit").hide();
	$("#email_edit").hide();
	$("#phone_edit").hide();
	$("#fname_edit").show();
	$("#lname_edit").hide();
        $("#fname_edit_id").val(id);
        var name = $('#f_name'+id).attr('data-required');
        //if(name == 'true') {
			
        if(name == 'true' || jQuery.inArray("f_name"+id, id_array) != -1) {
            $('#fname_is_required').attr("checked","checked");
        } else {
            $('#fname_is_required').removeAttr("checked");
        }
        $("#txt_fname_edit").val($('#fname_'+id).html());
		
       // $("#txt_lname_edit").val($('#lname_'+id).html());
 }*/

 
 function fname_edit(id)
 {
	$("#web_edit").hide();
 	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#linetext_edit").hide();
	$("#email_edit").hide();
	$("#phone_edit").hide();
	$("#fname_edit").show();
	$("#lname_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#fname_edit_id").val(id);
        var name = $('#f_name'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("f_name"+id, id_array) != -1) {
            $('#fname_is_required').attr("checked","checked");
        } else {
            $('#fname_is_required').removeAttr("checked");
        }
        $("#txt_fname_edit").val($('#fname_'+id).html());
		if(id==0)
			$(".first_isrequired").hide();
		else
			$(".first_isrequired").show();
       // $("#txt_lname_edit").val($('#lname_'+id).html());
 }
 
 function lname_edit(id)
 {
	$("#web_edit").hide();
 	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#linetext_edit").hide();
	$("#email_edit").hide();
	$("#phone_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#lname_edit_id").val(id);
        var name = $('#l_name'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("l_name"+id, id_array) != -1) {
            $('#lname_is_required').attr("checked","checked");
        } else {
            $('#lname_is_required').removeAttr("checked");
        }
        //$("#txt_fname_edit").val($('#fname_'+id).html());
        $("#txt_lname_edit").val($('#lname_'+id).html());
 }
 
 function phone_edit(id)
 {
	$("#web_edit").hide();
 	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#linetext_edit").hide();
	$("#email_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#phone_edit_id").val(id);
        var name = $('#phone'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("phone"+id, id_array) != -1) {
            $('#phone_is_required').attr("checked","checked");
        } else {
            $('#phone_is_required').removeAttr("checked");
        }
        $("#txt_phone_edit").val($('#pphone_'+id).html());
 }
 
 function email_edit(id)
 {
	$("#web_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#linetext_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#email_edit_id").val(id);
        var name = $('#email'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("email"+id, id_array) != -1) {
            $('#email_is_required').attr("checked","checked");
        } else {
            $('#email_is_required').removeAttr("checked");
        }
        $("#txt_email_edit").val($('#eemail'+id).html());
 }

 function linetext_edit(id)
 {
	$("#web_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#paratext_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#single_edit_id").val(id);
        var name = $('#linetext'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("linetext"+id, id_array) != -1) {
            $('#single_is_required').attr("checked","checked");
        } else {
            $('#single_is_required').removeAttr("checked");
        }
        $("#txt_single_edit").val($('#lline'+id).html());
 }
 
 function paratext_edit(id)
 {
	$("#web_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#paratext_edit_id").val(id);
        var name = $('#paratext'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("paratext"+id, id_array) != -1) {
            $('#paratext_is_required').attr("checked","checked");
        } else {
            $('#paratext_is_required').removeAttr("checked");
        }
        $("#txt_paratext_edit").val($('#para'+id).html());
 }
 
 function address_edit(id)
 {
	$("#web_edit").hide();
	$("#date_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#address_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#address_edit_id").val(id);
        var name = $('#address'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("address"+id, id_array) != -1) {
            $('#address_is_required').attr("checked","checked");
        } else {
            $('#address_is_required').removeAttr("checked");
        }
        $("#txt_address_edit").val($('#add'+id).html());
 }
 
 function date_edit(id)
 {
	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#address_edit").hide();
	$("#date_edit").show();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
	
        $("#date_edit_id").val(id);
        var name = $('#date'+id).attr('data-required');
        //if(name == 'true') {
        if(name == 'true' || jQuery.inArray("date"+id, id_array) != -1) {
            $('#date_is_required').attr("checked","checked");
        } else {
            $('#date_is_required').removeAttr("checked");
        }
        $("#txt_date_edit").val($('#ddate'+id).html());
 }
 
 
  function web_edit(id)
 {
	<!--$("#web_edit").hide();-->
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	$("#web_edit").show();
	$("#file_edit").hide();
	
	
	$("#web_edit_id").val(id);
	var name = $('#web'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("file"+id, id_array) != -1) {
		$('#web_is_required').attr("checked","checked");
	} else {
		$('#web_is_required').removeAttr("checked");
	}
	$("#txt_file_edit").val($('#web_'+id).html());
 }
 
 function area_edit(id)
 {

	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#area_edit").show();
	$("#file_edit").hide();
	
	$("#area_edit_id").val(id);
	var name = $('#areaofinterest'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("areaofinterest"+id, id_array) != -1) {
		$('#area_is_required').attr("checked","checked");
	} else {
		$('#area_is_required').removeAttr("checked");
	}
	$("#txt_area_edit").val($('#areaofinterest_'+id).html());
 }
 
 function change_area_label(label_text)
 {
    var edit_id = $("#area_edit_id").val();
    if(label_text.trim() != '') {
        $('#areaofinterest_'+edit_id).text(label_text);
        $('#area_title_'+edit_id).text(label_text);
    }
	if($('#area_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#areaofinterest_req_"+edit_id).html(' *');
	}
 }
 
 function area_required()
 {
     var edit_id = $("#area_edit_id").val();
     if($('#area_is_required').is(":checked")) {
        $('#areaofinterest'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#areaofinterest_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'areaofinterest'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#areaofinterest_req_"+edit_id).html(' ');
        $('#areaofinterest'+edit_id).removeAttr('data-required');
     }
 }
 
 
 function price_edit(id)
 {

	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();

	$("#area_edit").hide();
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#price_edit").show();
	$("#file_edit").hide();
	
	$("#price_edit_id").val(id);
	var name = $('#price_from'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("price_from"+id, id_array) != -1) {
		$('#price_is_required').attr("checked","checked");
	} else {
		$('#price_is_required').removeAttr("checked");
	}
	$("#txt_pricefrom_edit").val($('#pricefrom_'+id).html());
	$("#txt_priceto_edit").val($('#priceto_'+id).html());
 }
 
 
 function change_price_label(name,label_text)
 {
     var edit_id = $("#price_edit_id").val();
     if(label_text.trim() != '') {
        if(name == '1')
		{
            $('#pricefrom_'+edit_id).text(label_text);
            $('#pricefrom_title_'+edit_id).val(label_text);
			if($('#price_is_required').is(":checked"))
			{
				//$("#fname_"+edit_id).append('<span class="val"> *</span>');
                                $("#price_from_req_"+edit_id).html(' *');
                                //$('<span class="val"> *</span>').insertAfter("#fname_"+edit_id);
			}
		}
        else
		{
            $('#priceto_'+edit_id).text(label_text);
            $('#priceto_title_'+edit_id).val(label_text);
			if($('#price_is_required').is(":checked"))
			{
				//$("#lname_"+edit_id).append('<span class="val"> *</span>');
                                $("#price_to_req_"+edit_id).html(' *');
			} 
		}
	}
	
 }
 
 function price_required()
 {
     var edit_id = $("#price_edit_id").val();
     //alert($('#name_is_required').is(":checked"));
     if($('#price_is_required').is(":checked")) {
        $('#price_from'+edit_id).attr('data-required',"true");
        $('#price_to'+edit_id).attr('data-required',"true");
        //$("#fname_"+edit_id).append('<span class="val"> *</span>');
        //$("#lname_"+edit_id).append('<span class="val"> *</span>');
        $("#price_from_req_"+edit_id).html(' *');
        $("#price_to_req_"+edit_id).html(' *');
     } else {
         
         id_array = $.grep(id_array, function(value) {
            return value != 'price_from'+edit_id;
          });
          id_array = $.grep(id_array, function(value) {
            return value != 'price_to'+edit_id;
          });
		  //$("#fname_"+edit_id).find('span').remove();
		  //$("#lname_"+edit_id).find('span').remove();
                  $("#price_from_req_"+edit_id).html(' ');
		  $("#price_to_req_"+edit_id).html(' ');
        //id_array.remove('f_name'+edit_id);
        $('#price_from'+edit_id).removeAttr('data-required');
        $('#price_to'+edit_id).removeAttr('data-required');
     }
 }

function bedroom_edit(id)
 {

	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#bedroom_edit").show();
	$("#file_edit").hide();
	
	$("#bedroom_edit_id").val(id);
	var name = $('#bedroom'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("bedroom"+id, id_array) != -1) {
		$('#bedroom_is_required').attr("checked","checked");
	} else {
		$('#bedroom_is_required').removeAttr("checked");
	}
	$("#txt_bedroom_edit").val($('#bedroom_'+id).html());
 }
 
 function change_bedroom_label(label_text)
 {
    var edit_id = $("#bedroom_edit_id").val();
    if(label_text.trim() != '') {
        $('#bedroom_'+edit_id).text(label_text);
        $('#bedroom_title_'+edit_id).val(label_text);
    }
	if($('#bedroom_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#bedroom_req_"+edit_id).html(' *');
	}
 }
 
 function bedroom_required()
 {
     var edit_id = $("#bedroom_edit_id").val();
     if($('#bedroom_is_required').is(":checked")) {
        $('#bedroom'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#bedroom_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'bedroom'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#bedroom_req_"+edit_id).html(' ');
        $('#bedroom'+edit_id).removeAttr('data-required');
     }
 }


function bathroom_edit(id)
 {

	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#bathroom_edit").show();
	$("#file_edit").hide();
	
	
	$("#bathroom_edit_id").val(id);
	var name = $('#bathroom'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("bathroom"+id, id_array) != -1) {
		$('#bathroom_is_required').attr("checked","checked");
	} else {
		$('#bathroom_is_required').removeAttr("checked");
	}
	$("#txt_bathroom_edit").val($('#bathroom_'+id).html());
 }
 
 function change_bathroom_label(label_text)
 {
    var edit_id = $("#bathroom_edit_id").val();
    if(label_text.trim() != '') {
        $('#bathroom_'+edit_id).text(label_text);
        $('#bathroom_title_'+edit_id).val(label_text);
    }
	if($('#bathroom_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#bathroom_req_"+edit_id).html(' *');
	}
 }
 
 function bathroom_required()
 {
     var edit_id = $("#bathroom_edit_id").val();
     if($('#bathroom_is_required').is(":checked")) {
        $('#bathroom'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#bathroom_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'bathroom'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#bathroom_req_"+edit_id).html(' ');
        $('#bathroom'+edit_id).removeAttr('data-required');
     }
 }
 
 function buyer_edit(id)
 {

	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide();
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#buyer_edit").show();
	$("#file_edit").hide();
	
	$("#buyer_edit_id").val(id);
	var name = $('#buyer'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("buyer"+id, id_array) != -1) {
		$('#buyer_is_required').attr("checked","checked");
	} else {
		$('#buyer_is_required').removeAttr("checked");
	}
	$("#txt_buyer_edit").val($('#buyer_'+id).html());
 }
 
 function change_buyer_label(label_text)
 {
    var edit_id = $("#buyer_edit_id").val();
    if(label_text.trim() != '') {
        $('#buyer_'+edit_id).text(label_text);
        $('#buyer_title_'+edit_id).val(label_text);
    }
	if($('#buyer_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#buyer_req_"+edit_id).html(' *');
	}
 }
 
 function buyer_required()
 {
     var edit_id = $("#buyer_edit_id").val();
     if($('#buyer_is_required').is(":checked")) {
        $('#buyer'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#buyer_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'buyer'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#buyer_req_"+edit_id).html(' ');
        $('#buyer'+edit_id).removeAttr('data-required');
     }
 }
 
 function house_edit(id)
 {
	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#square_edit").hide();
	$("#house_edit").show();
	$("#file_edit").hide();
	
	$("#house_edit_id").val(id);
	var name = $('#house'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("house"+id, id_array) != -1) {
		$('#house_is_required').attr("checked","checked");
	} else {
		$('#house_is_required').removeAttr("checked");
	}
	$("#txt_house_edit").val($('#house_'+id).html());
 }
 
 function change_house_label(label_text)
 {
    var edit_id = $("#house_edit_id").val();
    if(label_text.trim() != '') {
        $('#house_'+edit_id).text(label_text);
        $('#house_title_'+edit_id).val(label_text);
    }
	if($('#house_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#house_req_"+edit_id).html(' *');
	}
 }
 
 function house_required()
 {
     var edit_id = $("#house_edit_id").val();
     if($('#house_is_required').is(":checked")) {
        $('#house'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#house_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'house'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#house_req_"+edit_id).html(' ');
        $('#house'+edit_id).removeAttr('data-required');
     }
 }

 function square_edit(id)
 {
	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").show();
	$("#file_edit").hide();
	
	$("#square_edit_id").val(id);
	var name = $('#square'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("square"+id, id_array) != -1) {
		$('#square_is_required').attr("checked","checked");
	} else {
		$('#square_is_required').removeAttr("checked");
	}
	$("#txt_square_edit").val($('#square_'+id).html());
 }
 
 function change_square_label(label_text)
 {
    var edit_id = $("#square_edit_id").val();
    if(label_text.trim() != '') {
        $('#square_'+edit_id).text(label_text);
        $('#square_title_'+edit_id).val(label_text);
    }
	if($('#square_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#square_req_"+edit_id).html(' *');
	}
 }
 
 function square_required()
 {
     var edit_id = $("#square_edit_id").val();
     if($('#square_is_required').is(":checked")) {
        $('#square'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#square_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'square'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#square_req_"+edit_id).html(' ');
        $('#square'+edit_id).removeAttr('data-required');
     }
 }
 
 function file_edit(id)
 {
	$("#web_edit").hide();
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#date_edit").hide();
	$("#address_edit").hide();
	
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#square_edit").hide();
	$("#house_edit").hide();
	$("#file_edit").show();
	
	$("#file_edit_id").val(id);
	var name = $('#file'+id).attr('data-required');
	//if(name == 'true') {
	if(name == 'true' || jQuery.inArray("file"+id, id_array) != -1) {
		$('#file_is_required').attr("checked","checked");
	} else {
		$('#file_is_required').removeAttr("checked");
	}
	$("#txt_file_edit").val($('#file_'+id).html());
 }
 
 function change_file_label(label_text)
 {
    var edit_id = $("#file_edit_id").val();
    if(label_text.trim() != '') {
        $('#file_'+edit_id).text(label_text);
        $('#file_title_'+edit_id).val(label_text);
    }
	if($('#file_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#file_req_"+edit_id).html(' *');
	}
 }
 
 function file_required()
 {
     var edit_id = $("#file_edit_id").val();
     if($('#file_is_required').is(":checked")) {
        $('#file'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#file_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'file'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#file_req_"+edit_id).html(' ');
        $('#file'+edit_id).removeAttr('data-required');
     }
 }
 

 function common_hide()
 {
	$("#fname_edit").hide();
	$("#lname_edit").hide();
	$("#phone_edit").hide(); 	
	$("#email_edit").hide();
	$("#linetext_edit").hide();
	$("#paratext_edit").hide();
	$("#address_edit").hide();
	$("#date_edit").hide();
	$("#web_edit").hide();
	$("#area_edit").hide();
	$("#price_edit").hide()
	$("#bedroom_edit").hide();
	$("#bathroom_edit").hide();
	$("#buyer_edit").hide();
	$("#house_edit").hide();
	$("#square_edit").hide();
	$("#file_edit").hide();
 }
 function change_name_label(name,label_text)
 {
     if(label_text.trim() != '') {
        if(name == '1')
		{
			var edit_id = $("#fname_edit_id").val();
            $('#fname_'+edit_id).text(label_text);
            $('#ftitle_'+edit_id).val(label_text);
			if($('#fname_is_required').is(":checked"))
			{
				//$("#fname_"+edit_id).append('<span class="val"> *</span>');
                                $("#fname_req_"+edit_id).html(' *');
                                //$('<span class="val"> *</span>').insertAfter("#fname_"+edit_id);
			}
		}
        else
		{
			var edit_id = $("#lname_edit_id").val();
            $('#lname_'+edit_id).text(label_text);
             $('#ltitle_'+edit_id).val(label_text);
			if($('#lname_is_required').is(":checked"))
			{
				//$("#lname_"+edit_id).append('<span class="val"> *</span>');
                                $("#lname_req_"+edit_id).html(' *');
			} 
		}
	}
	
 }
 function fname_required()
 {
     var edit_id = $("#fname_edit_id").val();
	 
     //alert($('#name_is_required').is(":checked"));
     if($('#fname_is_required').is(":checked")) {
        $('#f_name'+edit_id).attr('data-required',"true");
        //$('#l_name'+edit_id).attr('data-required',"true");
        
        $("#fname_req_"+edit_id).html(' *');
        //$("#lname_req_"+edit_id).html(' *');
     } else {
         
         id_array = $.grep(id_array, function(value) {
            return value != 'f_name'+edit_id;
          });
          /*id_array = $.grep(id_array, function(value) {
            return value != 'l_name'+edit_id;
          });*/
		  //$("#fname_"+edit_id).find('span').remove();
		  //$("#lname_"+edit_id).find('span').remove();
          $("#fname_req_"+edit_id).html(' ');
		  //$("#lname_req_"+edit_id).html(' ');
        //id_array.remove('f_name'+edit_id);
        $('#f_name'+edit_id).removeAttr('data-required');
        //$('#l_name'+edit_id).removeAttr('data-required');
     }
 }
 
 function lname_required()
 {
     var edit_id = $("#lname_edit_id").val();
     //alert($('#name_is_required').is(":checked"));
     if($('#lname_is_required').is(":checked")) {
        //$('#f_name'+edit_id).attr('data-required',"true");
        $('#l_name'+edit_id).attr('data-required',"true");
        //$("#fname_"+edit_id).append('<span class="val"> *</span>');
        //$("#lname_"+edit_id).append('<span class="val"> *</span>');
        //$("#fname_req_"+edit_id).html(' *');
        $("#lname_req_"+edit_id).html(' *');
     } else {
         
         /*id_array = $.grep(id_array, function(value) {
            return value != 'f_name'+edit_id;
          });*/
          id_array = $.grep(id_array, function(value) {
            return value != 'l_name'+edit_id;
          });
		  //$("#fname_"+edit_id).find('span').remove();
		  //$("#lname_"+edit_id).find('span').remove();
          //$("#fname_req_"+edit_id).html(' ');
		  $("#lname_req_"+edit_id).html(' ');
        //id_array.remove('f_name'+edit_id);
        //$('#f_name'+edit_id).removeAttr('data-required');
        $('#l_name'+edit_id).removeAttr('data-required');
     }
 }
 
 function change_phone_label(label_text)
 {
    var edit_id = $("#phone_edit_id").val();
    if(label_text.trim() != '') {
        $('#pphone_'+edit_id).text(label_text);
        $('#phone_title_'+edit_id).val(label_text);
    }
	if($('#phone_is_required').is(":checked")) {
			 //$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                         $("#pphone_req_"+edit_id).html(' *');
	}
 }
 function phone_required()
 {
     var edit_id = $("#phone_edit_id").val();
     if($('#phone_is_required').is(":checked")) {
        $('#phone'+edit_id).attr('data-required',"true");
		//$("#pphone_"+edit_id).append('<span class="val"> *</span>');
                $("#pphone_req_"+edit_id).html(' *');
     } else {
         id_array = $.grep(id_array, function(value) {
            return value != 'phone'+edit_id;
          });
        //id_array.remove("phone"+edit_id);
		//$("#pphone_"+edit_id).find('span').remove();
                $("#pphone_req_"+edit_id).html(' ');
        $('#phone'+edit_id).removeAttr('data-required');
     }
 }
 function change_email_label(label_text)
 {
     var edit_id = $("#email_edit_id").val();
     if(label_text.trim() != '') {
        $('#eemail'+edit_id).text(label_text);
        $('#email_title_'+edit_id).val(label_text);
     }
	 if($('#email_is_required').is(":checked")) {
			 //$("#eemail"+edit_id).append('<span class="val"> *</span>');
                         $("#eemail_req_"+edit_id).html(' *');
		 }
 }
 function email_required()
 {
     var edit_id = $("#email_edit_id").val();
     if($('#email_is_required').is(":checked")) {
        $('#email'+edit_id).attr('data-required',"true");
		//$("#eemail"+edit_id).append('<span class="val"> *</span>');
                $("#eemail_req_"+edit_id).html(' *');
     }
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'email'+edit_id;
          });
	 	//$("#eemail"+edit_id).find('span').remove();
                $("#eemail_req_"+edit_id).html(' ');
        $('#email'+edit_id).removeAttr('data-required');
    }
 }
 function change_single_label(label_text)
 {
     var edit_id = $("#single_edit_id").val();
     if(label_text.trim() != '') {
        $('#lline'+edit_id).text(label_text);
        $('#single_title_'+edit_id).val(label_text);
     }
	  if($('#single_is_required').is(":checked")) {
			 //$("#lline"+edit_id).append('<span class="val"> *</span>');
                         $("#lline_req_"+edit_id).html(' *');
		 }
 }
 function single_required()
 {
     var edit_id = $("#single_edit_id").val();
     if($('#single_is_required').is(":checked")) {
        $('#linetext'+edit_id).attr('data-required',"true");
		//$("#lline"+edit_id).append('<span class="val"> *</span>');
                $("#lline_req_"+edit_id).html(' *');
     }
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'linetext'+edit_id;
          });
	   //$("#lline"+edit_id).find('span').remove();
           $("#lline_req_"+edit_id).html(' ');
        $('#linetext'+edit_id).removeAttr('data-required');
    }
 }
 
 function change_paratext_label(label_text)
 {
     var edit_id = $("#paratext_edit_id").val();
     if(label_text.trim() != '') {
        $('#para'+edit_id).text(label_text);
        $('#para_title_'+edit_id).val(label_text);
     }
	  if($('#address_is_required').is(":checked")) {
			 //$("#para"+edit_id).append('<span class="val"> *</span>');
                         $("#para_req_"+edit_id).html(' *');
		 }
	 
 }
 function paratext_required()
 {
     var edit_id = $("#paratext_edit_id").val();
     if($('#paratext_is_required').is(":checked")) {
        $('#paratext'+edit_id).attr('data-required',"true");
		//$("#para"+edit_id).append('<span class="val"> *</span>');
                $("#para_req_"+edit_id).html(' *');
     }
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'paratext'+edit_id;
          });
		   //$("#para"+edit_id).find('span').remove();
                   $("#para_req_"+edit_id).html(' ');
        $('#paratext'+edit_id).removeAttr('data-required');
    }
 }
 
 function change_address_label(label_text)
 {
     var edit_id = $("#address_edit_id").val();
     if(label_text.trim() != '') {
        $('#add'+edit_id).text(label_text);
        $('#add_title_'+edit_id).val(label_text);
     }
	 if($('#address_is_required').is(":checked")) {
			 //$("#add"+edit_id).append('<span class="val"> *</span>');
                         $("#add_req_"+edit_id).html(' *');
		 }
 }
 function address_required()
 {
     var edit_id = $("#address_edit_id").val();
     if($('#address_is_required').is(":checked")) {
        $('#address'+edit_id).attr('data-required',"true");
		//$("#add"+edit_id).append('<span class="val"> *</span>');
                $("#add_req_"+edit_id).html(' *');
     }
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'address'+edit_id;
          });
		  //$("#add"+edit_id).find('span').remove();
                  $("#add_req_"+edit_id).html(' ');
        $('#address'+edit_id).removeAttr('data-required');
    }
 }
 
 function change_date_label(label_text)
 {
     var edit_id = $("#date_edit_id").val();
     if(label_text.trim() != '') {
        $('#ddate'+edit_id).text(label_text);
        $('#date_title_'+edit_id).val(label_text);
		 if($('#date_is_required').is(":checked")) {
			 //$("#ddate"+edit_id).append('<span class="val"> *</span>');
                         $("#ddate_req_"+edit_id).html(' *');
		 }
     }
 }
 function date_required()
 {
     var edit_id = $("#date_edit_id").val();
     if($('#date_is_required').is(":checked")) {
        $('#date'+edit_id).attr('data-required',"true");
		//$("#ddate"+edit_id).append('<span class="val"> *</span>');
                $("#ddate_req_"+edit_id).html(' *');
     }
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'date'+edit_id;
          });
	    //$("#ddate"+edit_id).find('span').remove();
            $("#ddate_req_"+edit_id).html(' ');
        $('#date'+edit_id).removeAttr('data-required');
    }
 }
 
 function change_web_label(label_text)
 {
     var edit_id = $("#web_edit_id").val();
     if(label_text.trim() != '') {
		 
        $('#web'+edit_id).text(label_text);
        $('#web_title_'+edit_id).val(label_text);
		 if($('#web_is_required').is(":checked")) {
			 //$("#web"+edit_id).append('<span class="val"> *</span>');
                         $("#web_req_"+edit_id).html(' *');
		 }
     }
 }
 function web_required()
 {
     var edit_id = $("#web_edit_id").val();
     if($('#web_is_required').is(":checked")) {
        $('#website'+edit_id).attr('data-required',"true");
		//$("#web"+edit_id).append('<span class="val"> *</span>');
                $("#web_req_"+edit_id).html(' *');
     } 
    else {
        id_array = $.grep(id_array, function(value) {
            return value != 'website'+edit_id;
			
          });
		 //$("#web"+edit_id).find('span').remove();
                 $("#web_req_"+edit_id).html(' ');
        $('#website'+edit_id).removeAttr('data-required');

    }
 }
</script>
<script type="text/javascript">
$("select#slt_user").multiselect({
	 multiple: false,
	 header: "User",
	 noneSelectedText: "User",
	 selectedList: 1
}).multiselectfilter();
function preview()
{
    var form_title = '';
	var form_desc = '';
	var height = '700px';
	var width = '700px';
	//alert($('#show_title').is(':checked'));
	
	var background_color = '#FFFFFF';
	
	if($("#form_height").val().trim() != '')
		height = $("#form_height").val() + 'px';
	if($("#form_width").val().trim() != '')
		width = $("#form_width").val() + 'px';
	if($("#bg_color").val().trim() != '')
		background_color = $("#bg_color").val();
	if($("#form_title").val().trim() != '' && $('#show_title').is(':checked'))
		form_title = $("#form_title").val();
		
	if($("#form_desc").val().trim() != '' && $('#show_desc').is(':checked'))
		form_desc = $("#form_desc").val();	
	if($("#success_msg").val().trim() != '')
		success_msg = $("#success_msg").val();	

		

	var AddForm = '<form style="background-color:' + background_color + '; height:' + height + '; width:' + width +';" > <div> <h2> ' + form_title + '</h2> </div> <div> ' + form_desc + ' </div>';
	var divHTML = document.getElementById('form');
	var firstDivContent = AddForm + divHTML.innerHTML + "</form>";
	//document.getElementById('form') 
	///alert(divHTML.innerHTML);
	
	var secondDivContent = document.getElementById('previewformdata');
	
	secondDivContent.innerHTML = firstDivContent;
	
	$('#previewformdata .dynamic_dml').remove();
	
	$( "#basicModal .txt_date" ).datepicker({
        showOn: "button",
        changeMonth: true,
        changeYear: true,
        //yearRange: "-100:+2",
        //minDate: "0",
        buttonImage: "<?=base_url('images');?>/calendar.png",
        dateFormat:'mm/dd/yy',
        buttonImageOnly: false
    });
	
	return true;
}
$('body').on('click','#basicModal',function(e){
    $('.dynamic_dml').show();
});
</script>
