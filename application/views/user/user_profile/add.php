<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
$path_reset = $viewname.'/change_password';
?>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('user_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('user_add_table_head');}else{ echo $this->lang->line('user_edit_table_head'); }?>
            </h3>
            <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <div class="col-sm-12">
              <ul class="nav nav-tabs" id="myTab1">
                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="User Information" data-toggle="tab" href="#home">User Profile</a> </li>
                <?php if(!empty($editRecord[0]['id'])){ ?>
                <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a data-toggle="tab" title="Assign Contacts" href="#profile">
                  <?=$this->lang->line('user_add_user_tab2_head');?>
                  </a> </li>
                <?php } ?>
              </ul>
              <div class="tab-content" id="myTab1Content">
                <div <?php if($tabid == '' || $tabid == 1){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" >
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate >
                  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <div class="col-sm-7">
                    <div class="row">
                      <div class="col-sm-4">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_prefix');?>
                        </label>
                        <select class="form-control parsley-validated" name="slt_prefix" id="slt_prefix">
                          <option value="">Please Select</option>
                          <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mr.'){ echo "selected"; }?> value="Mr.">Mr.</option>
                          <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Ms.'){ echo "selected"; }?> value="Ms.">Ms.</option>
                          <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mrs.'){ echo "selected"; }?> value="Mrs.">Mrs.</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_fname');?>
                          <span class="mandatory_field margin-left-5px">*</span></label>
                        <input id="txt_first_name" name="txt_first_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['first_name'])){ echo htmlentities($editRecord[0]['first_name']); }?>" data-required="true" onkeypress="return isCharacterKey(event)">
                      </div>
                      <div class="col-sm-4">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_mname');?>
                        </label>
                        <input id="txt_middle_name" name="txt_middle_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['middle_name'])){ echo htmlentities($editRecord[0]['middle_name']); }?>" onkeypress="return isCharacterKey(event)">
                      </div>
                      <div class="col-sm-4">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_lname');?>
                        </label>
                        <input id="txt_last_name" name="txt_last_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['last_name'])){ echo htmlentities($editRecord[0]['last_name']); }?>" onkeypress="return isCharacterKey(event)">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_company');?>
                        </label>
                        <input id="txt_company_name" name="txt_company_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_name'])){ echo htmlentities($editRecord[0]['company_name']); }?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_title1');?>
                        </label>
                        <input id="txt_company_post" name="txt_company_post" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_post'])){ echo htmlentities($editRecord[0]['company_post']); }?>">
                      </div>
                    </div>
                    <div class="add_emailtype add_email_address_div">
                      <div class="col-sm-4">
                        <label for="validateSelect">
                          <?=$this->lang->line('common_label_email_type');?>
                        </label>
                      </div>
                      <div class="col-sm-4">
                        <label for="validateSelect">
                          <?=$this->lang->line('user_add_email_address');?>
                        </label>
                      </div>
                      <div class="col-sm-2 text-center icheck-input-new">
                        <div class="">
                          <label>
                            <?=$this->lang->line('common_default');?>
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class="">
                          <label>&nbsp;</label>
                        </div>
                      </div>
                      <?php 
			//print_r($email_trans_data);exit;
			 if(!empty($email_trans_data) && count($email_trans_data) > 0){
			 		
					foreach($email_trans_data as $rowtrans){ ?>
                      <div class="delete_email_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                        <div class="col-sm-4 form-group">
                          <input type="hidden" name="email_type_trans_id[]" id="email_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                          <select class="form-control parsley-validated" name="slt_email_typee[]" id="slt_email_typee">
                            <option value="">Please Select</option>
                            <?php if(!empty($email_type)){
								foreach($email_type as $row){?>
                            <option <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                            <?=$row['name']?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('user_add_email_address');?></label>-->
                          <input id="txt_email_addresse" name="txt_email_addresse[]" class="form-control parsley-validated" type="email" value="<?php if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }?>" data-parsley-type="email">
                        </div>
                        <div class="col-sm-2 text-center icheck-input-new">
                          <div class="form-group"> 
                            <!--<label><?=$this->lang->line('common_default');?></label>-->
                            <div class="radio">
                              <label class="">
                              <div class="margin-left-48">
                                <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true">
                              </div>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1 text-center icheck-input-new">
                          <div class=""> 
                            <!--<label>&nbsp;</label>-->
                            <?php if($rowtrans['is_default'] != '1')
					   {?>
                            <a title="Delete Email" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_email_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
                            <?php }?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php }else{ ?>
                      <div class="col-sm-4 form-group"> 
                        <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
                        <select class="form-control parsley-validated" name="slt_email_type[]" id="slt_email_type">
                          <option value="">Please Select</option>
                          <?php if(!empty($email_type)){
							foreach($email_type as $row){?>
                          <option value="<?=$row['id']?>">
                          <?=$row['name']?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group"> 
                        <!--<label for="validateSelect"><?=$this->lang->line('user_add_email_address');?></label>-->
                        <input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email">
                      </div>
                      <div class="col-sm-2 text-center icheck-input-new">
                        <div class="form-group"> 
                          <!--<label><?=$this->lang->line('common_default');?></label>-->
                          <div class="radio">
                            <label class="">
                            <div class="margin-left-48">
                              <input type="radio" class=""  name="rad_email_default" checked="checked" >
                            </div>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class=""> 
                          <!--<label>&nbsp;</label>--> 
                          <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 topnd_margin"> <a title="Add Email" href="javascript:void(0);" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
                    </div>
                    
                    <!--Email Complete-->
                    
                    <div class="add_emailtype add_phone_number_div">
                      <div class="col-sm-4">
                        <label for="validateSelect">
                          <?=$this->lang->line('common_label_phone_type');?>
                        </label>
                      </div>
                      <div class="col-sm-4">
                        <label for="validateSelect">
                          <?=$this->lang->line('user_add_phone_no');?>
                        </label>
                      </div>
                      <div class="col-sm-2 text-center icheck-input-new">
                        <div class="">
                          <label>
                            <?=$this->lang->line('common_default');?>
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class="">
                          <label>&nbsp;</label>
                          <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                        </div>
                      </div>
                      <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>
                      <div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                        <div class="col-sm-4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
                          <input type="hidden" name="phone_type_trans_id[]" id="phone_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                          <select class="form-control parsley-validated" name="slt_phone_typee[]" id="slt_phone_type">
                            <option value="">Please Select</option>
                            <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
                            <option <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                            <?=$row['name']?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('user_add_phone_no');?></label>-->
                          <input id="txt_phone_no" name="txt_phone_noe[]"  maxlength="12" data-type="number_mask" data-maxlength="12" class="form-control parsley-validated mask_apply_class" type="text" value="<?php if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }?>">
                        </div>
                        <div class="col-sm-2 text-center icheck-input-new">
                          <div class="form-group"> 
                            <!--<label><?=$this->lang->line('common_default');?></label>-->
                            <div class="radio">
                              <label class="">
                              <div class="margin-left-48">
                                <input type="radio" class=""  name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?>>
                              </div>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1 text-center icheck-input-new">
                          <div class=""> 
                            <!--<label>&nbsp;</label>-->
                            <?php if($rowtrans['is_default'] != '1')
					   		{?>
                            <a title="Delete Phone" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_phone_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php }else{ ?>
                      <div class="col-sm-4 form-group"> 
                        <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
                        <select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type">
                          <option value="">Please Select</option>
                          <?php if(!empty($phone_type)){
			   			foreach($phone_type as $row){?>
                          <option value="<?=$row['id']?>">
                          <?=$row['name']?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group"> 
                        <!--<label for="validateSelect"><?=$this->lang->line('user_add_phone_no');?></label>-->
                        <input id="txt_phone_no" maxlength="12" data-type="number_mask" name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text" >
                      </div>
                      <div class="col-sm-2 text-center icheck-input-new">
                        <div class="form-group"> 
                          <!--<label><?=$this->lang->line('common_default');?></label>-->
                          <div class="radio">
                            <label class="">
                            <div class="margin-left-48">
                              <input type="radio" class=""   name="rad_phone_default" checked="checked">
                            </div>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class=""> 
                          <!--<label>&nbsp;</label>--> 
                          <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 topnd_margin"> <a title="Add Phone" href="javascript:void(0);" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
                    </div>
                    <div class="row add_address_div">
                      <?php if(!empty($address_trans_data) && count($address_trans_data) > 0){
			 		foreach($address_trans_data as $rowtrans){ ?>
                      <div class="delete_address_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                        <div class="col-sm-3 columns">
                          <input type="hidden" name="address_type_trans_id[]" id="address_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                          <select class="form-control parsley-validated" name="slt_address_typee[]" id="slt_address_type">
                            <option value="">Please Select</option>
                            <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
                            <option <?php if(!empty($rowtrans['address_type']) && $rowtrans['address_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                            <?=$row['name']?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-6 columns">
                          <div class="row">
                            <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1e[]" class="form-control parsley-validated"><?php if(!empty($rowtrans['address_line1'])){ echo htmlentities($rowtrans['address_line1']); }?>
</textarea>
                          </div>
                          <div class="row">
                            <input type="text" placeholder="Address Line 2" name="txtarea_address_line2e[]" id="txtarea_address_line2" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['address_line2'])){ echo htmlentities($rowtrans['address_line2']); }?>">
                          </div>
                          <div class="row">
                            <div class="col-sm-5 nopadding">
                              <input type="text" placeholder="City" id="txt_city" name="txt_citye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['city'])){ echo htmlentities($rowtrans['city']); }?>">
                            </div>
                            <div class="col-sm-3 nopadding">
                              <input type="text" placeholder="State" id="txt_state" name="txt_statee[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['state'])){ echo htmlentities($rowtrans['state']); }?>">
                            </div>
                            <div class="col-sm-4 nopadding form-group">
                              <input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_codee[]" maxlength="5" data-minlength="5" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['zip_code'])){ echo $rowtrans['zip_code']; }?>">
                            </div>
                          </div>
                          <div class="row">
                            <input type="text" placeholder="Country" id="txt_country" name="txt_countrye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['country'])){ echo htmlentities($rowtrans['country']); }?>">
                          </div>
                        </div>
                        <div class="col-sm-2"> <a title="Delete Address" class="btn nomargin btn-xs btn-primary mar_top_con_my" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_address_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                        <div> </div>
                      </div>
                      <?php } ?>
                      <?php }else{ ?>
                      <div class="col-sm-3 columns">
                        <select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">
                          <option value="">Please Select</option>
                          <?php if(!empty($address_type)){
			   			foreach($address_type as $row){?>
                          <option value="<?=$row['id']?>">
                          <?=$row['name']?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-sm-6 columns">
                        <div class="row">
                          <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>
                        </div>
                        <div class="row">
                          <input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">
                        </div>
                        <div class="row">
                          <div class="col-sm-5 nopadding">
                            <input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">
                          </div>
                          <div class="col-sm-3 nopadding">
                            <input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">
                          </div>
                          <div class="col-sm-4 nopadding form-group">
                            <input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_code[]" maxlength="5" data-minlength="5" class="form-control parsley-validated">
                          </div>
                        </div>
                        <div class="row">
                          <input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">
                        </div>
                      </div>
                      <div class="col-sm-2"> 
                        <!--<button class="btn nomargin btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                      </div>
                      <div> </div>
                      <?php } ?>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 topnd_margin"> <a title="Add Address" class="text_color_red text_size add_new_address" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Address</a> </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('user_add_notes');?>
                        </label>
                        <textarea name="txtarea_notes" id="txtarea_notes" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['notes'])){ echo htmlentities($editRecord[0]['notes']); }?>
</textarea>
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
                              <input type="file" alt="1" name="brokerage_pic" id="brokerage_pic" class="file_input_hidden" onchange="showimagepreview(this,2)"/>
                            </div>
                          </div>
                        </div>
                        <p> <span class="txt">&nbsp;</span>
                          <?php 	
							  if(!empty($user_info[0]['brokerage_pic']) && file_exists($this->config->item('broker_big_img_path').$user_info[0]['brokerage_pic'])){
								?>
                          <img  width="100" height="100" id="uploadPreview2" src="<?=$this->config->item('broker_upload_img_small')?>/<?=(!empty($user_info[0]['brokerage_pic'])?$user_info[0]['brokerage_pic']:'');?>"/> <a class="img_delete2" onclick="delete_image('brokerage_pic','uploadPreview2');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                          <? } else{
					if(!empty($user_info[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$user_info[0]['brokerage_pic'])){
					?>
                          <img  width="100" height="100" id="uploadPreview2" src="<?=$this->config->item('broker_upload_img_big')?>/<?=(!empty($user_info[0]['brokerage_pic'])?$user_info[0]['brokerage_pic']:'');?>" /> <a class="img_delete2" onclick="delete_image('brokerage_pic','uploadPreview2');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                          <?
					}else{
					?>
                          <img id="uploadPreview2" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                          <? } } ?>
                        </p>
                        <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5"> 
                    
                    <!--<div class="margin-bottom-5px">
                <label for="text-input">Status</label>
                <select class="form-control parsley-validated" name="slt_status" id="slt_status">
               <option <?php if(!empty($editRecord[0]['status']) && $editRecord[0]['status'] == '1'){ echo "selected"; }?> value="1">Active</option>
			   <option <?php if(!empty($editRecord[0]['status']) && $editRecord[0]['status'] == '2'){ echo "selected"; }?> value="2">Inactive</option>
			   
              </select>
               </div>-->
                    <div class="add_website">
                      <div>
                        <div class="row add_website_div">
                          <div class="col-sm-5">
                            <label for="text-input">
                              <?=$this->lang->line('common_label_website_type');?>
                            </label>
                          </div>
                          <div class="col-sm-5">
                            <label for="text-input">
                              <?=$this->lang->line('user_add_website');?>
                            </label>
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> </div>
                          </div>
                          <?php if(!empty($website_trans_data) && count($website_trans_data) > 0){
			 		foreach($website_trans_data as $rowtrans){ ?>
                          <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                            <div class="col-sm-5">
                              <input type="hidden" class="form-control" id="txt_website_typeid" name="txt_website_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                              <input type="text" class="form-control parsley-validated" id="txt_website_typee" name="txt_website_typee[]" value="<?php if(!empty($rowtrans['website_type'])){ echo $rowtrans['website_type']; }?>">
                            </div>
                            <div class="col-sm-5 form-group">
                              <input type="url" class="form-control parsley-validated" id="txt_website_namee" name="txt_website_namee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo htmlentities($rowtrans['website_name']); }?>" data-parsley-type="url">
                            </div>
                            <div class="col-sm-1 text-center icheck-input-new">
                              <div class=""> <a title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_website_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                            </div>
                          </div>
                          <?php } ?>
                          <?php } else { ?>
                          <div class="col-sm-5"> 
                            <!--<label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>-->
                            <input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">
                          </div>
                          <div class="col-sm-5 form-group"> 
                            <!--<label for="text-input"><?=$this->lang->line('user_add_website');?></label>-->
                            <input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]" data-parsley-type="url">
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label>&nbsp;</label>--> 
                              <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 topnd_margin"> <a title="Add Website" class="text_color_red text_size add_new_website" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Website</a> </div>
                        </div>
                      </div>
                      <div>
                        <div class="row add_social_profile_div">
                          <div class="col-sm-5">
                            <label for="text-input">
                              <?=$this->lang->line('user_add_profile_type');?>
                            </label>
                          </div>
                          <div class="col-sm-5">
                            <label for="text-input">
                              <?=$this->lang->line('user_add_website');?>
                            </label>
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label>&nbsp;</label>--> 
                            </div>
                          </div>
                          <?php if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ ?>
                          <div class="delete_social_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                            <div class="col-sm-5">
                              <input type="hidden" class="form-control" id="slt_profile_typeid" name="slt_profile_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                              <select class="form-control parsley-validated" name="slt_profile_typee[]" id="slt_profile_typee">
                                <option value="">Please Select</option>
                                <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
                                <option <?php if(!empty($rowtrans['profile_type']) && $rowtrans['profile_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                                <?=$row['name']?>
                                </option>
                                <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-sm-5 form-group">
                              <input type="text" class="form-control parsley-validated" id="txt_social_profilee" name="txt_social_profilee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo htmlentities($rowtrans['website_name']); }?>"  data-parsley-type="url">
                            </div>
                            <div class="col-sm-1 text-center icheck-input-new">
                              <div class=""> <a title="Delete Social Profile" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_social_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                            </div>
                          </div>
                          <?php } ?>
                          <?php } else { ?>
                          <div class="col-sm-5"> 
                            <!--<label for="text-input"><?=$this->lang->line('user_add_profile_type');?></label>-->
                            <select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">
                              <option value="">Please Select</option>
                              <?php if(!empty($profile_type)){
							foreach($profile_type as $row){?>
                              <option value="<?=$row['id']?>">
                              <?=$row['name']?>
                              </option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-sm-5 form-group"> 
                            <!--<label for="text-input"><?=$this->lang->line('user_add_website');?></label>-->
                            <input type="url" class="form-control parsley-validated" id="txt_social_profile" name="txt_social_profile[]"  data-parsley-type="url">
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label>&nbsp;</label>
                 <button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 topnd_margin"> <a title="Add Social Profile" class="text_color_red text_size add_new_social_profile" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Social Profile</a> </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 topnd_margin">
                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                        <input id="doc_id" name="doc_id" type="hidden" value="">
                        <div class="col-sm-12 add_emailtype">
                          <div class="col-sm-12 autooverflow">
                            <label for="text-input">
                              <?=$this->lang->line('user_add_user_pic');?>
                            </label>
                            <div class="browse"> <span class="text"> </span>
                              <div class="browse_btn">
                                <div class="file_input_div">
                                  <input type="button" value="Browse" class="file_input_button" />
                                  <input type="file" alt="1" name="contact_pic" id="contact_pic" class="file_input_hidden" onchange="showimagepreview(this,1)"/>
                                </div>
                              </div>
                              <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
                            </div>
                            <p> <span class="txt">&nbsp;</span>
                              <?php 	
						  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('user_big_img_path').$editRecord[0]['contact_pic'])){
							?>
                              <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('user_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/> <a class="img_delete1" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                              <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('user_small_img_path').$editRecord[0]['contact_pic'])){
				?>
                              <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('user_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" /> <a class="img_delete1" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
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
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="col-sm-12">
                                <label for="text-input">
                                  <?=$this->lang->line('user_add_birth_date');?>
                                </label>
                                <input id="txt_birth_date" name="txt_birth_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }?>" readonly="readonly">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <label for="text-input">
                                  <?=$this->lang->line('user_add_anniversary_date');?>
                                </label>
                                <input id="txt_anniversary_date" name="txt_anniversary_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }?>" readonly="readonly">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <label for="text-input">
                                  <?=$this->lang->line('user_license_no');?>
                                </label>
                                <input id="user_license_no" name="user_license_no" class="form-control parsley-validated" type="text" value="<?php if(!empty($user_info[0]['user_license_no'])){ echo $user_info[0]['user_license_no']; }?>" placeholder="License No">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php /*
                  
                  <div class="col-lg-12 cl">
                    <div class="row newbk_main">
                      <div class="col-lg-5 col-md-5 col-xs-12">
                        <legend class="setup">Twilio Setup</legend>
                        <?php if(isset($user_info)) { ?>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_account_sid');?>
                            </label>
                            <input <?=!empty($editRecord[0]['twilio_account_sid'])?'readonly=""':''?> id="twilio_account_sid" name="twilio_account_sid" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['twilio_account_sid'])?$editRecord[0]['twilio_account_sid']:''?>" placeholder="<?=$this->lang->line('twilio_account_sid');?>" <?=!empty($editRecord[0]['twilio_account_sid'])?'readonly="readonly"':''?>>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_auth_token');?>
                            </label>
                            <input <?=!empty($editRecord[0]['twilio_auth_token'])?'readonlyreadonly=""':''?> id="twilio_auth_token" name="twilio_auth_token" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['twilio_auth_token'])?$editRecord[0]['twilio_auth_token']:''?>" placeholder="<?=$this->lang->line('twilio_auth_token');?>" <?=!empty($editRecord[0]['twilio_auth_token'])?'readonly="readonly"':''?>>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">
                              <?=$this->lang->line('twilio_number');?>
                            </label>
                            <input id="twilio_number" name="twilio_number" class="form-control parsley-validated mask_apply_class" type="text" value="<?=!empty($editRecord[0]['twilio_number'])?$editRecord[0]['twilio_number']:''?>" placeholder="<?=$this->lang->line('twilio_number');?>" <?=!empty($editRecord[0]['twilio_number'])?'readonly="readonly"':''?> <?php if(empty($editRecord[0]['twilio_number'])) {?> onblur="check_twilio_number(this.value);" <? } ?> >
                            <!-- <input id="twilio_number" name="twilio_number" class="form-control parsley-validated mask_apply_class" type="text" value="<?=!empty($editRecord[0]['twilio_number'])?$editRecord[0]['twilio_number']:''?>" placeholder="<?=$this->lang->line('twilio_number');?>" <?=!empty($editRecord[0]['twilio_number'])?'readonly="readonly"':''?> onblur="check_twilio_number(this.value);"> --> 
                          </div>
                        </div>
                        <?php } if(!isset($user_info) || (isset($user_info) && empty($user_info[0]['twilio_account_sid']))) {?>
                        <? } ?>
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="row">
                              <div class="col-lg-12 form-group">
                                <label for="text-input">
                                  <?=$this->lang->line('user_twilio_no');?>
                                </label>
                                <input type="text" placeholder="" name="phone" id="phone" maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" value="<?=!empty($user_info[0]['phone'])?$user_info[0]['phone']:''?>" />
                              </div>
                            </div>
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
                      </div>
                    </div>
                    <div class="row newbk_main">
                      <div class="col-lg-5 col-md-5 col-xs-12">
                        <legend class="setup">Facebook Setup</legend>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">Facebook API key</label>
                            <input type="text" placeholder="Facebook API key" value="" class="form-control parsley-validated" name="fb_key_id" id="fb_key_id">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label for="text-input">Facebook secret key</label>
                            <input type="text" placeholder="Facebook secret key" value="" class="form-control parsley-validated" name="fb_secret_key" id="fb_secret_key">
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
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover picset1"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_dashboard_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 10</label>
                            <ul>
                              <li>Go to <b>Settings</b> tab and enter contact <b>email id</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover picset2"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_setting_emailid_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 11</label>
                            <ul>
                              <li>Go to <b>Status & Review</b> tab and make the app live.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover picset3"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_status_review_pic.jpg"></span> </li>
                            </ul>
                          </div>
                          <div class="twilio">
                            <label>Step: 12</label>
                            <ul>
                              <li>Go to setting tab and copy <b>App ID</b> and <b>App secret</b>.</li>
                            </ul>
                            <ul class="detail_pic">
                              <li> <a onmouseover="javascript:hover_event();" href="#">See the details</a> <span class="image_hover picset4"> <img alt="twilio_dropdown" src="<?=base_url()?>images/face_appidappaccept_pic.jpg"></span> </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--komal added this is code  --> 
                    
                  </div>
                  
                  <? */ ?>
                  <div class="col-sm-12 pull-left text-center margin-top-10"> 
                    <!--<a class="btn btn-secondary" href="#">Save User</a>-->
                    <input type="hidden" id="contacttab" name="contacttab" value="1" />
                    <input type="submit" class="btn btn-secondary-green" value="Save Profile" title="Save Profile" onclick="return setdefaultdata();" name="submitbtn" />
                    <input type="submit" class="btn btn-secondary" value="Save Profile and Continue" title="Save Profile and Continue" onclick="return setdefaultdata();" name="submitbtn" />
                    <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
                </form>
              </div>
              <div <?php 
		  if($tabid == 2){?> class="tab-pane fade in active" <?php }else{ ?> class="tab-pane fade in" <?php } ?> id="profile">
              <?php $this->load->view('user/'.$viewname.'/change_password')?>
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

<!-- <script>
	function contact_search()
	{
		var perpage = $("#perpage").val();
		$.ajax({
			type: "POST",
url: "<?php echo base_url();?>user/user_management/edit_record/<?php echo $this->router->uri->segments[4]; ?>/",
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
		},
		beforeSend: function() {
					$('#common_div').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div").html(html);
				$('#common_div').unblock(); 
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('#searchtext').keyup(function(event) 
		  {
			  /*if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}*/
				
				if (event.keyCode == 13) {
						contact_search();
				}
			//return false;
		  });
	});
	
	function clearfilter_contact()
	{
		$("#searchtext").val("");
		contact_search();
	}
	
	function changepages()
	{
		contact_search();	
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search();
	}
	
</script>--> 
<script type="text/javascript">
$('.mask_apply_class').mask('999-999-9999');
  $(document).ready(function(){
	$('#txt_email_id').val('');
	$('#txt_npassword').val('');
	find_agent();
    });
	
	$('body').on('click','.add_email_address',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_email_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated" name="slt_email_type[]" id="slt_email_type">';
               inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($email_type)){
										foreach($email_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>';
              inlinehtml += '<input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio" class=""  name="rad_email_default">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_email_div_button" title="Delete Email"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
	
		$('.add_email_address_div').append(inlinehtml);
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
	});
	
	$('body').on('click','.delete_email_div_button',function(e){
	
		var removediv = $(this).closest('.remove_email_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_phone_number',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_phone_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type">';
               inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('user_add_phone_no');?></label>';
              inlinehtml += '<input id="txt_phone_no" maxlength="12" data-type="number_mask" name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio"  class="" name="rad_phone_default">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button title="Delete Phone" class="btn btn-xs btn-primary mar_top_con_my delete_phone_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
		
		$('.add_phone_number_div').append(inlinehtml);
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		$('.mask_apply_class').mask('999-999-9999');
	});
	
	$('body').on('click','.delete_phone_div_button',function(e){
		
		var removediv = $(this).closest('.remove_phone_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_website',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_website_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5 form-group">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('user_add_website');?></label>';
			inlinehtml += '<input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]"  data-parsley-type="url">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my delete_website_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_website_div').append(inlinehtml);
		
	});
	
	$('body').on('click','.delete_website_div_button',function(e){
		
		var removediv = $(this).closest('.remove_website_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_social_profile',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_social_profile_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('user_add_profile_type');?></label>';
			inlinehtml += '<select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
			inlinehtml += '</select>';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('user_add_website');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_social_profile" name="txt_social_profile[]">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Social Website" class="btn btn-xs btn-primary mar_top_con_my delete_social_profile_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_social_profile_div').append(inlinehtml);
	
	});
	
	$('body').on('click','.delete_social_profile_div_button',function(e){
		
		var removediv = $(this).closest('.remove_social_profile_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_tag',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_tag_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('user_add_tag');?></label>';
		  inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]">';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_tag_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_tag_div').append(inlinehtml);
		
	});
	
	$('body').on('click','.delete_tag_div_button',function(e){
		
		var removediv = $(this).closest('.remove_tag_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_address',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_address_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-3 columns">';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">';
		   inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-6 columns">';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<div class="col-sm-5 nopadding">';
			inlinehtml += '<input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-3 nopadding">';
			inlinehtml += '<input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-4 nopadding">';
			inlinehtml += '<input type="text" placeholder="Zip Code" id="txt_zip_code"  name="txt_zip_code[]" maxlength="5" data-minlength="5" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-2">';
		  inlinehtml += '<button class="btn nomargin btn-xs btn-primary mar_top_con_my delete_address_div_button"> <i class="fa fa-times"></i> </button>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div> </div>';
		inlinehtml += '</div>';
		
		$('.add_address_div').append(inlinehtml);
		
	});
	
	$('body').on('click','.delete_address_div_button',function(e){
		
		var removediv = $(this).closest('.remove_address_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_communication_plan',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_communication_plan_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('user_add_communication_plan');?></label>';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($communication_plans)){
										foreach($communication_plans as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_communication_plan_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_communication_plan_div').append(inlinehtml);
		
	});
	
	$('body').on('click','.delete_communication_plan_div_button',function(e){
		
		var removediv = $(this).closest('.remove_communication_plan_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	function ajaxdeletetransdata(functionname,id)
	{	
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('user_base_url')?><?=$viewname;?>/'+functionname+'/'+id, 
											success: function(msg1) 
											{
												$('.'+functionname+id).remove();
											}
										});	
								
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
	}
	
	function setdefaultdata()
	{
		var returndata = 0;
		
		// get all the inputs into an array.
		var $inputs = $('.add_email_address_div :input[type=email]');
		var $inputs1 = $('.add_phone_number_div :input[type=text]');
	
		// not sure if you wanted this, but I thought I'd add it.
		// get an associative array of just the values.
		var unique_values = {};
		$inputs.each(function() {
		
			if ( ! unique_values[this.value] ) {
				unique_values[this.value] = true;
			} else {
				// We have duplicate values!
				$.confirm({'title': 'Alert','message': " <strong> Same email id used multiple times. Please insert different email ids."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same email id used multiple times. Please insert different email ids.');
				returndata = 1;
			}
			
		});
		
		var unique_values1 = {};
		$inputs1.each(function() {
		
			if ( ! unique_values1[this.value] ) {
				unique_values1[this.value] = true;
			} else {
				// We have duplicate values!
				  	$.confirm({'title': 'Alert','message': " <strong> Same phone no used multiple times. Please insert different phone no."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same phone no used multiple times. Please insert different phone no.');
				returndata = 1;
			}
			
		});
		
		if(returndata == 1)
			return false;
		
		emailchkval = $('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').closest("div.col-sm-2").siblings('div.col-sm-4').find('input[type=email]').val();
		$('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').val(emailchkval);
		
		phonechkval = $('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').closest("div.col-sm-2").siblings('div.col-sm-4').find('input[type=text]').val();
		$('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').val(phonechkval);
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
		$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				
		}
		
	}
	
</script> 
<script type="text/javascript">
$(function() {
	$( "#txt_birth_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		maxDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	$( "#txt_anniversary_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		maxDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
</script> 
<script type="text/javascript">
	$(function(){
/*		var image=$('#hiddenFile').val();
        var btnUpload=$('#contact_pic');

		new AjaxUpload(btnUpload, {
			type: 'post',
			data:{image:image},
			action: '<?=$this->config->item('user_base_url').$viewname."/upload_image";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
			//alert(JSON.stringify(file));
			//alert(this.files[0]);
			if (! (ext && /^(jpg|png|jpeg|gif|bmp)$/.test(ext))){ 
					
	               	$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					//alert('Please upload jpg | jpeg | png | bmp | gif file only');
					return false;
				}
				
				
			
				$('#uploadPreview1').attr('width','16');
				$('#uploadPreview1').attr('height','16');
				$('#uploadPreview1').attr('src','<?=$this->config->item('image_path').'ajax-loader.gif'?>');
			},
			onComplete: function(file, response){
				$('#hiddenFile').val(response);
				$('#uploadPreview1').attr('width','100');
				$('#uploadPreview1').attr('height','100');
				$('#uploadPreview1').attr('src','<?=base_url().$this->config->item('temp_small_img_path')?>'+response);
			}
		});
*/		
		var btnUpload1=$('#doc_file');

		
	});
	
	var frm = $('#<?php echo $viewname;?>ajax');
    frm.submit(function (ev) {
	
		$("#savecontacttab2").hide();
	
		try{
		
			$.ajax({
					type: frm.attr('method'),
					url: frm.attr('action'),
					data: frm.serialize(),
					success: function (data) {
						
						$(".appendajaxdata").html(data);
						frm[0].reset();
						$("#priview_doc").text('');
						$("#hiddenFiledoc").val('');
						$("#hiddenFile").val('');
						
						var chkval = $('#submitvaltab2').val();
						var cid = $('#id').val();
						
						if(chkval == 1)
							window.location.href = '<?=base_url().'user/'.$viewname;?>';
						else
							window.location.href = '<?=base_url().'user/'.$viewname.'/edit_record/';?>'+cid+'/3';
						
					}
			});
			
		}
		catch(e){ alert('Something went wrong.Please try again.');window.location.reload();}
		
		$("#savecontacttab2").show();

        ev.preventDefault();
    });
	
	//delete image
	function delete_image(name,divid)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete image?",'buttons': {'Yes': {'class': 'special',
'action': function(){
			//loading('Checking');
				 //$('#preloader').html('Deleting...');
		var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:id,name:name},
			url: '<?=$this->config->item('user_base_url').$viewname."/delete_image";?>',
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
			
			}},'No'	: {'class'	: ''}}});
	}
	
	function editdoctransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_doc_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						$.confirm({'title': 'Alert','message': " <strong> Something went wrong. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
						//alert('Something went wrong.');
					}
					else
					{
						$("#doc_id").val(msg.id);
						$("#slt_doc_type").val(msg.doc_type);
						$("#txt_doc_name").val(msg.doc_name);
						$("#txtarea_doc_desc").val(msg.doc_desc);
						$("#hiddenFiledoc").val(msg.doc_file);
						$("#priview_doc").text(msg.doc_file);
					}
				}//succsess
			});//ajax
	}
	
	function setsubmitidtab2(id)
	{
		$("#submitvaltab2").val(id);
	}
	
	
	$('#agent_hidden').hide();	
	
	<?php
		
		if(!empty($editRecord[0]['agent_id'])){ ?>
		
			find_agent();
			
	<?php } ?>
	
	function find_agent()
	{
	var data = $('#slt_user_type').val();
		if(data == '4')
		{
			$('#agent_hidden').show();
		}
		else
		{
			$('#agent_hidden').hide();		
		}

	}
	
	
	
</script>
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h4 class="modal-title">Reset Password</h4>
      </div>
      <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php //echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_reset;?>" novalidate >
        <div class="modal-body">
          <div class="col-xs-12 mrgb2 form-group">
            <label for="validateSelect">Password</label>
            <input id="npassword" name="npassword" class="form-control parsley-validated" type="password" data-required="true"/>
          </div>
          <div class="col-xs-12 mrgb2 form-group">
            <label for="validateSelect">Confirm Password</label>
            <input id="cpassword" name="cpassword" class="form-control parsley-validated" type="password" data-equalto="#npassword" data-required="true" onkeypress="view_reset_button();">
          </div>
        </div>
        <div class="col-sm-12 text-center mrgb4">
          <input id="user_id" name="user_id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
          <input type="submit" class="btn btn-success" id="reset_pass" value="Reset Password">
        </div>
      </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<script type="text/javascript">
function change_password()
	{
		
		var user_id = $('#id').val();
		var npass = $('#npassword').val();
		var cpass = $('#cpassword').val();
		if(npass != cpass && npass =='' && cpass =='')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter correct password "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			//alert('Please Enter Correct Password');
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/user_management/change_password",
			
			data: {
			result_type:'ajax',user_id:user_id,password:npass,
		},
		success: function(html){
				location.reload();
			}
		});
	}
function view_reset_button()
{
	var npass = $('#npassword').val();
	var cpass = $('#cpassword').val();
	
	
}
function check_email(email)
{
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('user_base_url').$viewname.'/check_user';?>",
				dataType: 'json',
				async: false,
				data: {'email':email},
				success: function(data){
		
				if(data == '1')
				{
					//alert('This Email Already Existing ! Please Select other Email');
					$.confirm({'title': 'Alert','message': " <strong> This email already existing ! Please select other email "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#txt_email_id').val('');
					$('#txt_email_id').focus();
				}
				
				}
			});
			return false;
					
}
 function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script> 
<script type="text/javascript">
function showimagepreview(input,preview_id) 
{
	if (input.files && input.files[0]) 
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

}
function check_password(password)
{
		var email = $('#email_id').val();
		
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('user_base_url').$viewname.'/check_user';?>",
				dataType: 'json',
				async: false,
				data: {'password':password,'email':email},
				success: function(data){
		
				if(data == '0')
				{
					//alert('This Email Already Existing ! Please Select other Email');
					$.confirm({'title': 'Alert','message': " <strong> Old password not match "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#txt_oldpassword').val('');
					$('#txt_oldpassword').focus();
				}
				
				}
			});
			return false;
					
}
 function isCharacterKey(evt)
    {
        //var charCode = (evt.which) ? evt.which : evt.keyCode;
		var c = (evt.which) ? evt.which : evt.keyCode;
        //if(charCode >= 48 && charCode <= 57 && charCode)
		//alert(c);
		var a=((c > 64 && c < 90)|| (c > 97 && c < 122) || c == 34 || c == 39 || c == 8 || c == 9 || c == 32);
		 if(!a)
		  {
		    return false;
		  }
    	return true;
	}
function check_twilio_number(twilio_number)
{
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('user_base_url').$viewname.'/check_twilio_number';?>",
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
</script>