<?php
/*
    @Description: Change Password
    @Author: Kaushik Valiya
    @Date: 18-09-2014

*/?>
<?php 


$viewname1 = $this->router->uri->segments[2];
	
$formAction1 = !empty($editRecord)?'update_data':'insert_data'; 
$path1 = $viewname1.'/'.$formAction1;
?>
<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path1?>" data-validate="parsley" novalidate >
		<div class="row">
            <div class="col-xs-12 margin-top-bottom">
                
                <div class="col-xs-8 mrgb2 form-group">
                  <label for="validateSelect">Log-in ID : </label>
                  <label for="validateSelect"><?=!empty($user_info[0]['email_id'])?$user_info[0]['email_id']:'-'; ?></label>
                </div>
                 <div class="col-xs-8 mrgb2 form-group">
                  <label for="validateSelect"><b>Change Password : </b></label>
                  <input type="hidden" name="email_id" id="email_id" value="<?=!empty($user_info[0]['email_id'])?$user_info[0]['email_id']:''; ?>" />
                </div>
                
               <div class="col-xs-8 mrgb2 form-group">
                  <label for="validateSelect">Current Password : <span class="mandatory_field margin-left-5px">*</span></label>
                  <input id="txt_oldpassword" name="txt_oldpassword" onchange="check_password(this.value);" class="form-control parsley-validated" type="password" data-required="true">
                  
                </div>
                
                
                <div class="col-xs-8 mrgb2 form-group">
                  <label for="validateSelect">Password <span class="mandatory_field margin-left-5px">*</span></label>
                  <input id="txt_npassword" name="txt_npassword" class="form-control parsley-validated" type="password" data-required="true" data-minlength="6">
                  
                </div>
                <div class="col-xs-8 mrgb2 form-group">
                  <label for="validateSelect">Confirm Password <span class="mandatory_field margin-left-5px">*</span></label>
                  <input id="txt_cpassword" name="txt_cpassword" class="form-control parsley-validated" type="password" data-equalto="#txt_npassword" data-required="true" data-minlength="6">
                </div>
                
            
            </div>
        </div>
		<div class="col-sm-12 pull-left text-center margin-top-10">
			
            <input type="hidden" id="contacttab" name="contacttab" value="2" />
            <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="1" />
            <input type="submit" title="Save Profile and Finish" class="btn btn-secondary-green" value="Save Profile and Finish" onclick="setsubmitidtab2(1);" id="savecontacttab2" name="submitbtn" />
            
            <a title="Cancel" class="btn btn-primary" href="<?=$this->config->item('user_base_url').'user_profile';?>">Cancel</a>
        </div>
			
</form>