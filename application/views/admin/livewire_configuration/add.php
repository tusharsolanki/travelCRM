<?php
/*
    @Description: Admin add/edit page
    @Author: Mohit Trivedi
    @Date: 01-09-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
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
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.maskedinput.js"></script>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('admin_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('admin_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('admin_add_head'); } 
	   else{ echo $this->lang->line('admin_edit_head'); }?>
            </h3>
            <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span> </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
                <div class="row tab-pane fade in active" id="home">
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
                  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <div class="col-sm-12 col-lg-7">
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('common_label_name');?>
                          <span class="val">*</span></label>
                        <input id="admin_name" name="admin_name" placeholder="e.g. John" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
			   if(!empty($editRecord[0]['admin_name'])){ echo $editRecord[0]['admin_name'].'-copy'; }}
			   else
			   {
				   if(!empty($editRecord[0]['admin_name'])){ echo $editRecord[0]['admin_name']; }
			   }
			   ?>" data-required="true">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('common_label_email');?>
                          <span class="val">*</span></label>
                        <?php 
							   if(empty($editRecord)){ ?>
                        <input data-parsley-type="email" placeholder="e.g. abc.gmail.com" id="txt_email_id" name="txt_email_id" class="form-control parsley-validated"  type="email" onblur="check_email(this.value);" data-required="true">
                        <?php } else {?>
                        <input id="email_id" name="email_id" class="form-control parsley-validated" type="text" readonly value="<?php if(!empty($editRecord[0]['email_id'])){ echo $editRecord[0]['email_id'];}else{if(!empty($editRecord[0]['email_id'])){ echo $editRecord[0]['email_id']; }}?>" >
                        <?php if(!empty($admin_session['user_type']) && $admin_session['user_type'] == '5') echo "(Assistant email id can not be edited.)"; else echo "(Admin email id can not be edited.)"; ?>
                        <?php } ?>
                      </div>
                    </div>
                    
                    <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_password');?>
              <span class="val">*</span></label>
               <input data-minlength="6" type="password" placeholder="******" name="password" id="password" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true"<?php }?> data-equalto="#password" />
			  </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_cpassword');?>
              <span class="val">*</span></label>
               <input type="password" placeholder="******" name="cpassword" id="cpassword" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true"<?php }?>data-equalto="#password" />
			  </div>
            </div>-->
                    
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input"> Address </label>
                        <textarea placeholder="Address" name="address" id="address" class="form-control parsley-validated" 	/>
                        <?=!empty($editRecord[0]['address'])?htmlentities($editRecord[0]['address']):''?>
                        </textarea>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input"> Phone No </label>
                        <input type="text" placeholder="" name="phone" id="phone" maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" value="<?=!empty($editRecord[0]['phone'])?$editRecord[0]['phone']:''?>" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('user_license_no');?>
                        </label>
                        <input id="user_license_no" name="user_license_no" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['user_license_no'])){ echo $editRecord[0]['user_license_no']; }?>" placeholder="License No">
                      </div>
                    </div>
                    <?php /* ?> 
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input"><?=$this->lang->line('mls_agent_id');?></label>
                        <input type="text" name="mls_agent_id" id="mls_agent_id" class="form-control parsley-validated" value="<?=!empty($editRecord[0]['mls_user_id'])?$editRecord[0]['mls_user_id']:''?>"  placeholder="<?=$this->lang->line('mls_agent_id');?>" />
                      </div>
                    </div>
                    <?php */ ?>
                  </div>
                  <?php /* ?>
                  
                  <div class="col-lg-12 cl">
                    <div class="row newbk_main">
                      <div class="col-lg-5 col-md-5 col-xs-12">
                        <legend class="setup">Twilio Setup</legend>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_account_sid');?>
                            </label>
                            <input id="twilio_account_sid" name="twilio_account_sid" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['twilio_account_sid'])?$editRecord[0]['twilio_account_sid']:''?>" placeholder="<?=$this->lang->line('twilio_account_sid');?>">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_auth_token');?>
                            </label>
                            <input id="twilio_auth_token" name="twilio_auth_token" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['twilio_auth_token'])?$editRecord[0]['twilio_auth_token']:''?>" placeholder="<?=$this->lang->line('twilio_auth_token');?>" >
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_number');?>
                            </label>
                            <input id="twilio_number" name="twilio_number" class="form-control parsley-validated mask_apply_class" type="text" value="<?=!empty($editRecord[0]['twilio_number'])?$editRecord[0]['twilio_number']:''?>" placeholder="<?=$this->lang->line('twilio_number');?>" <?php if(empty($editRecord[0]['twilio_number'])) {?> onblur="check_twilio_number(this.value);" <? } ?> >
                            <!-- <input id="twilio_number" name="twilio_number" class="form-control parsley-validated mask_apply_class" type="text" value="<?=!empty($editRecord[0]['twilio_number'])?$editRecord[0]['twilio_number']:''?>" placeholder="<?=$this->lang->line('twilio_number');?>" <?=!empty($editRecord[0]['twilio_number'])?'readonly="readonly"':''?>> --> 
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-7 col-md-7 col-xs-12">
                        <div class="twilio_main">
                          <legend>Twilio Steps</legend>
                          <div class="twilio">
                            <label>Step: 1</label>
                            <ul>
                                <li>Login to twilio account. <a href="https://www.twilio.com/login" target="_blank">https://www.twilio.com/login</a></li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 2</label>
                            <ul>
                              <li> Click on <b>Account</b> on dropdown.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_dropdown.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 3</label>
                            <ul>
                              <li> Get <b>API Credentials.</b></li>
                              <li>AccountSID</li>
                              <li>AuthToken</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_api.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 4</label>
                            <ul>
                              <li> Click on <b>NUMBERS</b> in menu.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_numbers.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 5</label>
                            <ul>
                              <li> Get <b>selected twilio number</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_sele_nbr.jpg"></span> </li>
                            </ul>
                          </div>
                        </div>
                        <div class="twilio_main smsmrg">
                          <legend>Twilio SMS Steps</legend>
                          <div class="twilio">
                            <label>Step: 1</label>
                            <ul>
                              <li>Login to twilio account. <a href="#">https://www.twilio.com/login</a></li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 2</label>
                            <ul>
                              <li>Click on <b>NUMBERS</b> in menu.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_sms_nbr.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 3</label>
                            <ul>
                              <li> Click on <b>number</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/twilio_sms_click_nbr.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 4</label>
                            <ul>
                              <li> Add sms url under Messaging section in <b>requested url</b>. <a href="http://mylivewiresolution.com/contact_sms_response">http://mylivewiresolution.com/contact_sms_response</a></li>
                            </ul>
                            </div>
                           </div>
                      </div>
                    </div>
                    <div class="row newbk_main">
                      <div class="col-lg-5 col-md-5 col-xs-12">
                        <legend class="setup">Facebook Setup</legend>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('fb_key_id');?>
                            </label>
                            <input id="fb_key_id" name="fb_key_id" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['fb_api_key'])?$editRecord[0]['fb_api_key']:''?>" placeholder="<?=$this->lang->line('fb_key_id');?>" >
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('fb_secret_key');?>
                            </label>
                            <input id="fb_secret_key" name="fb_secret_key" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['fb_secret_key'])?$editRecord[0]['fb_secret_key']:''?>" placeholder="<?=$this->lang->line('fb_secret_key');?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-7 col-md-7 col-xs-12">
                        <div class="twilio_main">
                          <legend>Facebook Steps</legend>
                          <div class="twilio">
                            <label>Step: 1</label>
                            <ul>
                              <li>Login to FB. Click on <b>Developer</b> link.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_deve_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 2</label>
                            <ul>
                              <li>In Developer section, Go to <b>My Apps</b> and Click on Add a new App.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_myapp_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 3</label>
                            <ul>
                              <li> Select <b>WWW website</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_website.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 4</label>
                            <ul>
                              <li><b>Enter the app name</b> (We can enter any name)</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_appname_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 5</label>
                            <ul>
                              <li>Create a new <b>app ID</b></li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_appid_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 6</label>
                            <ul>
                              <li>Select any <b>category</b></li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_sele_cate_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 7</label>
                            <ul>
                              <li>Set website <b>URL</b> as marked in below image.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_seturl_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 8</label>
                            <ul>
                              <li>Click on <b>Skip Quick Start</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_skipquick_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 9</label>
                            <ul>
                              <li>App <b>homapage</b> would open.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_dashboard_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 10</label>
                            <ul>
                              <li>Go to <b>Settings</b> tab and enter contact <b>email id</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_setting_emailid_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 11</label>
                            <ul>
                              <li>Go to <b>Status & Review</b> tab and make the app live.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_status_review_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 12</label>
                            <ul>
                              <li>Go to setting tab and copy <b>App ID</b> and <b>App secret</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_appidappaccept_pic.jpg"></span> </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <?php */ ?>
                  <div class="col-sm-12 col-lg-7">
                    <div class="">
                      <div class="add_emailtype autooverflow">
                        <div class="col-sm-12">
                          <label for="text-input">
                            <?=$this->lang->line('admin_add_contact_pic');?>
                          </label>
                          <div class="browse"> <span class="text"> </span>
                            <div class="browse_btn">
                              <div class="file_input_div">
                                <input type="button" value="Browse" class="file_input_button"  />
                                <input type="file" alt="1" name="admin_pic" id="admin_pic" onchange="showimagepreview(this)" class="file_input_hidden"/>
                              </div>
                            </div>
                            <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
                          </div>
                          <p> <span class="txt">&nbsp;</span>
                            <?php  if(!empty($editRecord[0]['admin_pic']) && file_exists($this->config->item('admin_big_img_path').$editRecord[0]['admin_pic'])){
							?>
                            <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('admin_upload_img_small')?>/<?=(!empty($editRecord[0]['admin_pic'])?$editRecord[0]['admin_pic']:'');?>"/> <a class="img_delete1" onclick="delete_image('admin_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                            <? } else{
				if(!empty($editRecord[0]['admin_pic']) && file_exists($this->config->item('admin_small_img_path').$editRecord[0]['admin_pic'])){
				?>
                            <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('admin_small_img_path')?>/<?=(!empty($editRecord[0]['admin_pic'])?$editRecord[0]['admin_pic']:'');?>" /> <a class="img_delete1" onclick="delete_image('admin_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                            <?
				}else{
				?>
                            <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                            <? } } ?>
                          </p>
                          <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 add_emailtype">
                      <div class="col-sm-12 autooverflow">
                        <label for="text-input">
                          <?=$this->lang->line('brokerage_pic');?>
                        </label>
                        <div class="browse"> <span class="text"> </span>
                          <div class="browse_btn">
                            <div class="file_input_div">
                              <input type="button" value="Browse" class="file_input_button" />
                              <input type="file" alt="1" name="brokerage_pic" id="brokerage_pic" class="file_input_hidden" onchange="showimagepreview1(this,2)"/>
                            </div>
                          </div>
                        </div>
                        <p> <span class="txt">&nbsp;</span>
                          <?php 	
						  if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_big_img_path').$editRecord[0]['brokerage_pic'])){
							?>
                          <img  width="100" height="100" id="uploadPreview2" src="<?=$this->config->item('broker_upload_img_small')?>/<?=(!empty($editRecord[0]['brokerage_pic'])?$editRecord[0]['brokerage_pic']:'');?>"/> <a class="img_delete2" onclick="delete_image('brokerage_pic','uploadPreview2');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                          <? } else{
				if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$editRecord[0]['brokerage_pic'])){
				?>
                          <img  width="100" height="100" id="uploadPreview2" src="<?=$this->config->item('broker_upload_img_big')?>/<?=(!empty($editRecord[0]['brokerage_pic'])?$editRecord[0]['brokerage_pic']:'');?>" /> <a class="img_delete2" onclick="delete_image('brokerage_pic','uploadPreview2');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                          <?
				}else{
				?>
                          <img id="uploadPreview2" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                          <? } } ?>
                        </p>
                        <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
                      </div>
                    </div>
                    
                    <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_dbname');?>
              <span class="val">*</span></label>
              <input id="db_name" name="db_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['db_name'])){ echo $editRecord[0]['db_name']; }
			   ?>" data-required="true">
             </div>
            </div>--> 
                    
                    <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_hostname');?>
              <span class="val">*</span></label>
              <input id="host_name" name="host_name" class="form-control parsley-validated" type="text" value="<?php
				   if(!empty($editRecord[0]['host_name'])){ echo $editRecord[0]['host_name']; }
			   ?>" data-required="true">
             </div>
            </div>--> 
                    
                    <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_dbusername');?>
              <span class="val">*</span></label>
              <input id="db_user_name" name="db_user_name" class="form-control parsley-validated" type="text" value="<?php 
				   if(!empty($editRecord[0]['db_user_name'])){ echo $editRecord[0]['db_user_name']; }
			   ?>" data-required="true">
             </div>
            </div>--> 
                    
                    <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_dbuserpass');?>
              </label>
              <input id="db_user_password" name="db_user_password" class="form-control parsley-validated" type="password" 
			  value="<?php if(!empty($editRecord[0]['db_user_password'])){ echo $editRecord[0]['db_user_password']; }
			   ?>">
             </div>
            </div>--> 
                    
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
                    <input type="submit" id="submit" class="btn btn-secondary-green" value="Save" title="Save"onclick="showloading();" name="submitbtn" />
                    <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
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
  $(document).ready(function(){
         $('.mask_apply_class').mask('999-999-9999');
 });
 function showloading()
 {
	if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
 }
function check_email(email)
{
	
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/check_user';?>",
				dataType: 'json',
				async: false,
				data: {'email':email},
				success: function(data){
		
				if(data == '1')
				{$('#txt_email_id').focus();
				$('#submit').attr('disabled','disabled');
				
					$.confirm({'title': 'Alert','message': " <strong> This email already existing ! Please select other email "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
										$('#txt_email_id').focus();
										$('#submit').removeAttr('disabled');
									}}}});
					
				}
				}
			});
			return false;

}
function check_twilio_number(twilio_number)
{
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('admin_base_url').$viewname.'/check_twilio_number';?>",
		dataType: 'json',
		async: false,
		data: {'twilio_number':twilio_number},
		beforeSend: function() {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
		},
		success: function(data){
		if(data == '1')
		{$('#twilio_number').focus();
		$('#submit').attr('disabled','disabled');
		
			$.confirm({'title': 'Alert','message': " <strong> This number already existing. Please select other number. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
								$('#twilio_number').focus();
								$('#submit').removeAttr('disabled');
								$.unblockUI();
							}}}});
			
		}
		else
			$.unblockUI();
		}
	});
	return false;
}

function showimagepreview(input) 
{
	var maximum = input.files[0].size/1024;
	//alert(maximum);
	if (input.files && input.files[0] && maximum <= 2048) 
	{
		var arr1 = input.files[0]['name'].split('.');
		var arr= arr1[1].toLowerCase();	
		if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'bmp' || arr == 'gif')
		{
			var filerdr = new FileReader();
			filerdr.onload = function(e) {
			$('#uploadPreview1').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}	
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Maximum upload size 2 MB "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
	}
}

function delete_image(name,divid)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete image?",'buttons': {'Yes': {'class': '',
'action': function(){
			//loading('Checking');
				 //$('#preloader').html('Deleting...');
		var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:id,name:name},
			url: '<?=$this->config->item('admin_base_url').$viewname."/delete_image";?>',
			success:function(msg){
					if(msg == 'done')
					{
						if(divid == 'uploadPreview1')	
							$('.img_delete1').hide();
						else if(divid == 'uploadPreview2')	
							$('.img_delete2').hide();
			      		$('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
				  }
				}//succsess
			});//ajax
			
			}},'No'	: {'class'	: 'special'}}});
	}
function showimagepreview1(input,preview_id)
{
	var maximum = input.files[0].size/1024;
	if (input.files && input.files[0] && maximum <= 2048) 
	{
		var arr1 = input.files[0]['name'].split('.');
		var arr= arr1[1].toLowerCase();	
		if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'bmp' || arr == 'gif')
		{
		var filerdr = new FileReader();
		filerdr.onload = function(e) {
		$('#uploadPreview'+preview_id).attr('src', e.target.result);
				}
		filerdr.readAsDataURL(input.files[0]);
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}	
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Maximum upload size 2 MB "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
	}

}
</script>