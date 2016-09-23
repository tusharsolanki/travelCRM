<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
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

<div id="content">
  <div id="content-header">
   <h1></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php  echo "SMS Details";
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?=$viewname?>/interaction_mailsms" novalidate>
           <div class="row">
           <div class="col-sm-8">
		   <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('email_to');?> : </label> <label for="text-input"><?=!empty($datalist[0]['contact_name'])?ucwords($datalist[0]['contact_name']):''?>(<?=!empty($datalist[0]['phone_no'])?($datalist[0]['phone_no']):''?>)</label>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> : </label> <label for="text-input"><?=!empty($datalist[0]['category'])?ucwords($datalist[0]['category']):''?></label>
			  </div>
            </div>
			 <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			  <label for="text-input"><?=!empty($datalist[0]['template_name'])?ucwords($datalist[0]['template_name']):''?></label>
             </div>
            </div>
          </div>
            </div>
            <div class="row">
          <div class="col-sm-8">
          <div class="form-group">
                  <label for="select-multi-input">
                 	SMS Message : 
                  </label>
                  <textarea name="sms_message" id="sms_message" class="form-control parsley-validated" onkeypress="return keypress(event);" ><?=!empty($datalist[0]['sms_message'])?ucwords($datalist[0]['sms_message']):'';?></textarea>
                  <label id="textarea_feedback"></label>
                </div>
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
 			<!--<a class="btn btn-primary" href="<?php echo $this->config->item('admin_base_url').$viewname; ?>" title="Close">Close</a>-->
            <input type="hidden" name="interaction_plan_id" id="interaction_plan_id" value="<?php echo $this->uri->segment(5);?>"/>
            <input type="hidden" name="interaction_id" id="interaction_id" value="<?=!empty($datalist[0]['interaction_id'])?($datalist[0]['interaction_id']):''?>"/>
            <input type="hidden" name="id" id="id" value="<?=!empty($datalist[0]['id'])?($datalist[0]['id']):''?>"/>
            <input type="submit" class="btn btn-secondary" id="send_now" value="Send Now" title="Send Now" name="submitbtn" />
			<a class="btn btn-primary" onclick="history.go(-1)" href="<?php echo $this->config->item('admin_base_url').$viewname."/interaction_plan_queued_list/".$this->uri->segment(5); ?>" title="Close">Close</a>
            
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
 <?php
// print_r($subcategory);
 ?>
<script>
var cnt = $('#sms_message').val();
$('#textarea_feedback').html('Count : ' + cnt.length);
$('#sms_message').keyup(function (){
	var cnt = $('#sms_message').val();
	$('#textarea_feedback').html('Count : ' + cnt.length);
	return true;
});

</script>