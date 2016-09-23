<?php
/*
    @Description: Task add/edit page
    @Author: Mohit Trivedi
    @Date: 04-08-2014

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
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>


<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('task_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('task_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('task_add_head'); } 
	   else{ echo $this->lang->line('task_edit_head'); }?> </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" title="Back" class="btn btn-secondary">Back</a> </span>
	    
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
         
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('task_label_name');?><span class="val">*</span></label>
              <input id="txt_task_name" name="txt_task_name" class="form-control parsley-validated" type="text" value="<?php 
			  if(isset($insert_data)){
			  if(!empty($editRecord[0]['task_name'])){			  
			  echo $editRecord[0]['task_name'].'-copy'; }}
			  else{
			  if(!empty($editRecord[0]['task_name'])){			  
			  echo $editRecord[0]['task_name'];}}?>" data-required="true">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('common_label_desc');?></label>
			  <textarea name="txtarea_desc" id="txtarea_desc" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['desc'])){ echo $editRecord[0]['desc']; }?></textarea>
             </div>
            </div>

 			 
            <div class='row mycalclass'>
         <div class="col-sm-8 form-group">
         <label for="text-input"><?=$this->lang->line('taskdate_label_name');?><span class="val">*</span></label>
          <input id="txt_task_date" name="txt_task_date" class="form-control parsley-validated calendarclass" type="text" value="<?php if(!empty($editRecord[0]['task_date']) && $editRecord[0]['task_date'] != '0000-00-00' && $editRecord[0]['task_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['task_date'])); }?>" data-required="true" readonly="readonly">
          </div>
          </div>
          <div class="row">
             <div class="col-sm-8">
             
              <label for="text-input"><?=$this->lang->line('common_label_assignuser');?></label>
			  <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='slt_user[]' id='slt_user'data-required="true">
                <!--<option value=''>Select Employee</option>-->
              <?php if(isset($userlist) && count($userlist) > 0){
				
							foreach($userlist as $row){
								if(!empty($row['id'])){?>
                <option value='<?php echo $row['id'];?>' <?php if(isset($slt_user) && is_array($slt_user) && in_array($row['id'],$slt_user)){ echo "selected";}?> ><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></option>
                <?php 		}
							}
						} ?>
              </select>
             </div>
            </div>
            
<div class="row form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
              Is Task Completed
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_completed" name="is_completed" <?php if(!empty($editRecord[0]['is_completed']) && $editRecord[0]['is_completed'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
            </div>
            
 <div class="row form-group">
 <div class="">
 <div class="col-sm-3">
 Reminders:
 </div>
             <div class="col-sm-3 checkbox">
              <label class="">
              Email Before
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_email" name="is_email" <?php if(!empty($editRecord[0]['is_email']) && $editRecord[0]['is_email'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
             <div class="col-sm-3">
              <input id="email_time_before" name="email_time_before" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['email_time_before'])){ echo $editRecord[0]['email_time_before']; }?>">
             </div>
             <div class="col-sm-3">
               <select class="form-control parsley-validated" name="email_time_type" id="email_time_type">
               <option value="">Please Select</option>
               <option <?php if(!empty($editRecord[0]['email_time_type']) && $editRecord[0]['email_time_type'] == '1'){ echo "selected"; }?> value="1">Hour</option>
			   <option <?php if(!empty($editRecord[0]['email_time_type']) && $editRecord[0]['email_time_type'] == '2'){ echo "selected"; }?> value="2">Day</option>
			   </select>
             </div>

             </div>
             <div class="clear">
 <div class="col-sm-3">
 </div>         
             <div class="col-sm-3 checkbox">
              <label class="">
              Pop-Up Before
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_popup" name="is_popup" <?php if(!empty($editRecord[0]['is_popup']) && $editRecord[0]['is_popup'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
             <div class="col-sm-3">
              <input id="popup_time_before" name="popup_time_before" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['popup_time_before'])){ echo $editRecord[0]['popup_time_before']; }?>">
             </div>
             <div class="col-sm-3">
               <select class="form-control parsley-validated" name="popup_time_type" id="popup_time_type">
               <option value="">Please Select</option>
               <option <?php if(!empty($editRecord[0]['popup_time_type']) && $editRecord[0]['popup_time_type'] == '1'){ echo "selected"; }?> value="1">Hour</option>
			   <option <?php if(!empty($editRecord[0]['popup_time_type']) && $editRecord[0]['popup_time_type'] == '2'){ echo "selected"; }?> value="2">Day</option>
			   </select>
             </div>

			</div>
             </div>
            </div>
           
          
            </div>
		      </div>
           
         
		  
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               </div>
           
          <div class="col-sm-12 pull-left text-center margin-top-10">
<!--<a class="btn btn-secondary" href="#">Save Contact</a>-->
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" class="btn btn-secondary" value="Save Task" title="Save Task" onclick="return setdefaultdata();" name="submitbtn" />
 <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
         </div>
         
          </form>
         
         </div>
  
        </div>
       </div>
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
	$( "#txt_task_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
</script>
<script type="text/javascript">
	$("select#slt_user").multiselect({
	}).multiselectfilter();
 
  
</script>
