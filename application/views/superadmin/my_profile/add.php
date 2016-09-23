<?php
/*
    @Description: Super add/edit page
    @Author: Mohit Trivedi
    @Date: 30-08-2014

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
   <h1><?=$this->lang->line('profile_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('profile_header');}
	   else if(!empty($insert_data)){ echo $this->lang->line('profile_header'); } 
	   else{ echo $this->lang->line('profile_header'); }?> </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_email');?>
              <span class="val">*</span></label>
			   <?php 
							  
							   if(empty($editRecord)){ ?>
							  <input data-parsley-type="email" id="txt_email_id" name="txt_email_id" class="form-control parsley-validated"  type="email" onblur="check_email(this.value);" data-required="true">
                            <?php } else {?>
							 <input id="email_id" name="email_id" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['email_id'])){ echo $editRecord[0]['email_id'];}else{if(!empty($editRecord[0]['email_id'])){ echo $editRecord[0]['email_id']; }}?>" readonly>
							<?php } ?>
             </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_currpassword');?>
              <span class="val">*</span></label>
               <input data-minlength="6" type="password" name="currpassword" id="currpassword" class="form-control parsley-validated" data-required="true" onblur="check_pass(this.value);"/>
			  </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_password');?>
              <span class="val">*</span></label>
               <input data-minlength="6" type="password" name="password" id="password" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true"<?php }?> data-equalto="#password" />
			  </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_cpassword');?>
              <span class="val">*</span></label>
               <input type="password" name="cpassword" id="cpassword" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true"<?php }?>data-equalto="#password" />
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
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" id="submit" class="btn btn-secondary" value="Save" title="Save" onclick="return setdefaultdata();" name="submitbtn" />
 <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
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
<script type="text/javascript">
function check_email(email)
{
	
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_user';?>",
				dataType: 'json',
				async: false,
				data: {'email':email},
				success: function(data){
		
				if(data == '1')
				{
						
					$.confirm({'title': 'Alert','message': " <strong> This Email Already Existing ! Please Select other Email "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
										
										
									}}}});
					
				}
				
				}
			});
			return false;
					
}
</script>

<script type="text/javascript">
function check_pass(currpassword)
{
	if(currpassword.trim() != '')
	{
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_pass';?>",
				dataType: 'json',
				async: false,
				data: {'currpassword':currpassword},
				success: function(data){
		
				if(data == '0')
				{
					$('#curr_password').focus();
						$('#submit').attr('disabled','disabled');
						
					$.confirm({'title': 'Alert','message': " <strong>Password not match ! Please enter correct password "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
										$('#currpassword').focus();
										$('#submit').removeAttr('disabled');
										
									}}}});
					
				}
				
				}
			});
			return false;
	}
					
}
</script>