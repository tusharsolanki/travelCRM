<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 

$viewname = $this->router->uri->segments[2];
$editRecordId = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$editmode = !empty($editRecord)?'style="background:none repeat scroll 0 0 #f9f9f9;"':''; 
$path = $viewname.'/'.$formAction;

?>

<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.min.js"></script>
<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('contact_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <?php /*?><h3 class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </h3><?php */?>
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('contact_add_table_head');}else{ echo $this->lang->line('contact_edit_table_head'); }?>
            </h3>
            <span class="pull-right">
            <?php
		if(!empty($editRecord))
		{?>
            <a title="View Contact" class="btn btn-secondary" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?=$editRecordId?>"><?php echo $this->lang->line('common_view_title')?></a>
            <?php }	?>
            <a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content" <?=$editmode?>>
            <div class="col-sm-12">
              <ul class="nav nav-tabs" id="myTab1">
                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="Contact Information" data-toggle="tab" href="#home">
                  <?=$this->lang->line('contact_add_table_tab1_head');?>
                  </a> </li>
                <?php if(!empty($editRecord[0]['id'])){ ?>
                <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Contact Photo and Documents" data-toggle="tab" href="#profile">
                  <?=$this->lang->line('contact_add_table_tab2_head');?>
                  </a> </li>
                <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Extra Information" data-toggle="tab" href="#profilenew">
                  <?=$this->lang->line('contact_add_table_tab3_head');?>
                  </a> </li>
                <?php /* if(!empty($this->modules_unique_name) && in_array('buyer_preferences',$this->modules_unique_name)){?>
                <?php 
		 	if($right_buyer[0]['is_buyer_tab']== '1')
			{
		 ?>
                <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="Buyer Preferences" data-toggle="tab" href="#buyerpreferences">
                  <?=$this->lang->line('contact_add_table_tab4_head');?>
                  </a> </li>
                <?php }} */ ?>
                <?php } ?>
                <!--<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" id="myTabDrop1" href="javascript:;">Dropdown <b class="caret"></b>
							</a>

							<ul aria-labelledby="myTabDrop1" role="menu" class="dropdown-menu">
								<li><a data-toggle="tab" tabindex="-1" href="#dropdown1">@fat</a></li>
								<li><a data-toggle="tab" tabindex="-1" href="#dropdown2">@mdo</a></li>
							</ul>
						</li>-->
                
              </ul>
              <div class="tab-content" id="myTab1Content">
                <div <?php if($tabid == '' || $tabid == 1){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" >
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate>
                  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <?php if(!empty($editRecord[0]['id'])){ ?>
                  <div class="col-sm-12 pull-left text-center margin-bottom-10">
                    <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" onclick="return setdefaultdata();" name="submitbtn" />
                    <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="return setdefaultdata();" name="submitbtn" />
                    <a class="btn btn-primary" title="cancel" href="javascript:history.go(-1);">Cancel</a> </div>
                  <?php } ?>
                  <div class="col-sm-12 col-lg-7 new-mar-bot">
                    <div class="row">
                      <div class="col-sm-12 col-lg-4">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_prefix');?>
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
                      <div class="col-sm-12  col-lg-4 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_fname');?>
                          <span class="mandatory_field margin-left-5px">*</span></label>
                        <input id="txt_first_name" name="txt_first_name" maxlength="50" class="form-control parsley-validated charval" type="text"  value="<?php if(!empty($editRecord[0]['first_name'])){ echo htmlentities($editRecord[0]['first_name']); }?>" data-required="true" onkeypress="return isCharacterKey(event)" placeholder="e.g. John">
                      </div>
                      <div class="col-sm-12  col-lg-4">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_mname');?>
                        </label>
                        <input id="txt_middle_name" name="txt_middle_name" maxlength="50" class="form-control parsley-validated" onkeypress="return isCharacterKey(event)" type="text" value="<?php if(!empty($editRecord[0]['middle_name'])){ echo htmlentities($editRecord[0]['middle_name']); }?>" placeholder="e.g. Jane">
                      </div>
                      <div class="col-sm-12  col-lg-4">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_lname');?>
                        </label>
                        <input id="txt_last_name" name="txt_last_name" maxlength="50" onkeypress="return isCharacterKey(event)" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['last_name'])){ echo htmlentities($editRecord[0]['last_name']); }?>" placeholder="e.g. Laren">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12  col-lg-4 form-group">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_spousefname');?>
                          <!--<span class="mandatory_field margin-left-5px">*</span>--></label>
                        <input id="txt_spousefirst_name" name="txt_spousefirst_name" maxlength="50" class="form-control parsley-validated" type="text" onkeypress="return isCharacterKey(event)" value="<?php if(!empty($editRecord[0]['spousefirst_name'])){ echo htmlentities($editRecord[0]['spousefirst_name']); }?>" placeholder="e.g. Ruby" >
                      </div>
                      <div class="col-sm-12  col-lg-4">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_spousemname');?>
                        </label>
                        <input id="txt_spousemiddle_name" name="txt_spousemiddle_name" maxlength="50" class="form-control parsley-validated" type="text" onkeypress="return isCharacterKey(event)" value="<?php if(!empty($editRecord[0]['spousemiddle_name'])){ echo htmlentities($editRecord[0]['spousemiddle_name']); }?>" placeholder="e.g. max">
                      </div>
                      <div class="col-sm-12  col-lg-4">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_spouselname');?>
                        </label>
                        <input id="txt_spouselast_name" name="txt_spouselast_name" maxlength="50" class="form-control parsley-validated" type="text" onkeypress="return isCharacterKey(event)" value="<?php if(!empty($editRecord[0]['spouselast_name'])){ echo htmlentities($editRecord[0]['spouselast_name']); }?>" placeholder="e.g. Sen">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-lg-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_company');?>
                        </label>
                        <input id="txt_company_name" name="txt_company_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_name'])){ echo htmlentities($editRecord[0]['company_name']); }?>" placeholder="e.g. Company Name">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-lg-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_title1');?>
                        </label>
                        <input id="txt_company_post" name="txt_company_post" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_post'])){ echo htmlentities($editRecord[0]['company_post']); }?>" placeholder="e.g. Title">
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-sm-12 checkbox">
                        <label class="">
                        Is this Contact a Lead
                        <div class="float-left margin-left-15">
                          <input type="checkbox" value="1" class=""  id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'checked="checked"'; }?>>
                        </div>
                        </label>
                      </div>
                    </div>
                    <div class="add_emailtype">
                      <div class="add_email_address_div">
                        <div class="col-sm-4">
                          <label for="validateSelect">
                            <?=$this->lang->line('common_label_email_type');?>
                          </label>
                        </div>
                        <div class="col-sm-4">
                          <label for="validateSelect">
                            <?=$this->lang->line('contact_add_email_address');?>
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
                        <?php if(!empty($email_trans_data) && count($email_trans_data) > 0){
			 		foreach($email_trans_data as $rowtrans){ ?>
                        <div class="delete_email_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                          <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                            <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
                            <input type="hidden" name="email_type_trans_id[]" id="email_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                            <select class="form-control parsley-validated contact_module" name="slt_email_typee[]" id="slt_email_typee" >
                              <option value="">Please Select</option>
                              <?php if(!empty($email_type)){
								foreach($email_type as $row){?>
                              <option <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                            <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
                            <input id="txt_email_addresse" name="txt_email_addresse[]" class="form-control parsley-validated" type="email"  value="<?php if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }?>" data-parsley-type="email" placeholder="e.g. abc@gmail.com">
                          </div>
                          <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
                            <div class="form-group"> 
                              <!--<label><?=$this->lang->line('common_default');?></label>-->
                              <div class="radio">
                                <label class="">
                                <div class="margin-left-48">
                                  <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> >
                                </div>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label>&nbsp;</label>-->
                              <?php if($rowtrans['is_default'] != '1') { ?>
                              <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Delete Email" onclick="return ajaxdeletetransdata('delete_email_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                        <?php }else{ ?>
                        <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
                          <select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type" >
                            <option value="">Please Select</option>
                            <?php if(!empty($email_type)){
							foreach($email_type as $row){?>
                            <option value="<?=$row['id']?>">
                            <?=ucwords($row['name']);?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
                          <input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email" placeholder="e.g. abc.gmail.com">
                        </div>
                        <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
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
                        <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
                          <div class=""> 
                            <!--<label>&nbsp;</label>--> 
                            <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="clear col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add Email" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
                    </div>
                    
                    <!--<div class="row">
             <div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add Email" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
            </div>--> 
                    
                    <!--Email Complete-->
                    
                    <div class="add_emailtype">
                      <div class="add_phone_number_div">
                        <div class="col-sm-4 col-lg-4 my_col_lg_4">
                          <label for="validateSelect">
                            <?=$this->lang->line('common_label_phone_type');?>
                          </label>
                        </div>
                        <div class="col-sm-4 col-lg-4 my_col_lg_4">
                          <label for="validateSelect">
                            <?=$this->lang->line('contact_add_phone_no');?>
                          </label>
                        </div>
                        <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
                          <div class="">
                            <label>
                              <?=$this->lang->line('common_default');?>
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
                          <div class="">
                            <label>&nbsp;</label>
                            <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                          </div>
                        </div>
                        <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>
                        <div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                          <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                            <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
                            <input type="hidden" name="phone_type_trans_id[]" id="phone_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                            <select class="form-control parsley-validated" name="slt_phone_typee[]" id="slt_phone_type"  >
                              <option value="">Please Select</option>
                              <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
                              <option <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                            <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
                            <input id="txt_phone_no" name="txt_phone_noe[]"  maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" type="text" value="<?php if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }?>" placeholder="e.g. 123-456-7890">
                          </div>
                          <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label><?=$this->lang->line('common_default');?></label>-->
                              <div class="radio">
                                <label class="">
                                <div class="margin-left-48">
                                  <input type="radio" class=""   name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> >
                                </div>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
                            <div class=""> 
                              <!--<label>&nbsp;</label>-->
                              <?php if($rowtrans['is_default'] != '1') { ?>
                              <a class="btn btn-xs btn-primary mar_top_con_my"  title="Delete Phone" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_phone_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                        <?php }else{ ?>
                        <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
                          <select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type" >
                            <option value="">Please Select</option>
                            <?php if(!empty($phone_type)){
			   			foreach($phone_type as $row){?>
                            <option value="<?=$row['id']?>">
                            <?=ucwords($row['name']);?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group"> 
                          <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
                          <input id="txt_phone_no"  maxlength="12"   name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text"  placeholder="e.g. 123-456-7890">
                        </div>
                        <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
                          <div class="form-group"> 
                            <!--<label><?=$this->lang->line('common_default');?></label>-->
                            <div class="radio">
                              <label class="">
                              <div class="margin-left-48">
                                <input type="radio" class=""   name="rad_phone_default" checked="checked" >
                              </div>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
                          <div class=""> 
                            <!--<label>&nbsp;</label>--> 
                            <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="clear col-sm-12 topnd_margin"> <a href="javascript:void(0);"  title="Add Email" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
                    </div>
                    
                    <!--<div class="row">
			<div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);"  title="Add Email" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
            </div>-->
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
                            <?=ucwords($row['name']);?>
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
                            <input type="text" placeholder="Country" id="txt_country" name="txt_countrye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['country'])){ echo $rowtrans['country']; }?>">
                          </div>
                        </div>
                        <div class="col-sm-2"> <a class="btn nomargin btn-xs btn-primary mar_top_con_my" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_address_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
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
                          <?=ucwords($row['name']);?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-sm-6 columns new-mar-bot">
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
                          <div class="col-sm-4 nopadding">
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
                      <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_address"  title="Add Address" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Address</a> </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_notes');?>
                        </label>
                        <textarea name="txtarea_notes" placeholder="e.g. Notes" id="txtarea_notes" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['notes'])){ echo htmlentities($editRecord[0]['notes']); }?>
</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 col-lg-5">
                    <div class="">
                      <div class="add_emailtype autooverflow">
                        <div class="col-sm-12">
                          <label for="text-input">
                            <?=$this->lang->line('contact_add_contact_pic');?>
                          </label>
                          <div class="browse"> <span class="text"> </span>
                            <div class="browse_btn">
                              <div class="file_input_div">
                                <input type="button" value="Browse" class="file_input_button"  />
                                <input type="file" alt="1" name="contact_pic" id="contact_pic" onchange="showimagepreview(this)" class="file_input_hidden"/>
                              </div>
                              <!--<div class="btn btn-success btn-file"></i>Browse<input type="file" alt="1" name="contact_pic" id="contact_pic" onchange="showimagepreview(this)"></div>--> 
                              
                            </div>
                            <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
                          </div>
                          <p> <span class="txt">&nbsp;</span>
                            <?php  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_big_img_path').$editRecord[0]['contact_pic'])){
							?>
                            <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                            <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['contact_pic'])){
				?>
                            <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" /> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
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
                              <?=$this->lang->line('contact_add_website');?>
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
                              <select class="form-control parsley-validated" name="txt_website_typee[]" id="txt_website_typee">
                                <option value="">Please Select</option>
                                <?php if(!empty($website_type)){
										foreach($website_type as $row){?>
                                <option <?php if(!empty($rowtrans['website_type']) && $rowtrans['website_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                                <?=ucwords($row['name']);?>
                                </option>
                                <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-sm-5 form-group">
                              <input type="url" class="form-control parsley-validated" id="txt_website_namee" name="txt_website_namee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>" data-parsley-type="url"  placeholder="e.g. www.xyz.com">
                            </div>
                            <div class="col-sm-1 text-center icheck-input-new">
                              <div class=""> <a title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_website_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                            </div>
                          </div>
                          <?php } ?>
                          <?php } else { ?>
                          <div class="col-sm-5">
                            <select class="form-control parsley-validated" name="txt_website_type[]" id="txt_website_type">
                              <option value="">Please Select</option>
                              <?php if(!empty($website_type)){
							foreach($website_type as $row){?>
                              <option value="<?=$row['id']?>">
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-sm-5 form-group"> 
                            <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                            <input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]" data-parsley-type="url" placeholder="e.g. www.xyz.com">
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
                              <?=$this->lang->line('contact_add_profile_type');?>
                            </label>
                          </div>
                          <div class="col-sm-5">
                            <label for="text-input">
                              <?=$this->lang->line('contact_add_profile_name');?>
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
                                <?=ucfirst($row['name']);?>
                                </option>
                                <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-sm-5 form-group">
                              <input type="text" class="form-control parsley-validated fbid" id="txt_social_profilee" name="txt_social_profilee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>" laceholder="e.g. https://twitter.com/demo">
                            </div>
                            <div class="col-sm-1 text-center icheck-input-new">
                              <div class=""> <a title="Delete Social Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_social_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                            </div>
                          </div>
                          <?php } ?>
                          <?php } else { ?>
                          <div class="col-sm-5"> 
                            <!--<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>-->
                            <select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">
                              <option value="">Please Select</option>
                              <?php if(!empty($profile_type)){
							foreach($profile_type as $row){?>
                              <option value="<?=$row['id']?>">
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-sm-5 form-group"> 
                            <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                            <input type="text" class="form-control parsley-validated fbid" id="txt_social_profile" name="txt_social_profile[]" placeholder="e.g. https://twitter.com/demo">
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
                      <!--<div class="col-sm-12 topnd_margin">
              <h2 class="text_color_red text_size"><?=$this->lang->line('common_label_contact_type');?></h2>
             </div>-->
                      <div class="col-sm-8">
                        <label for="validateSelect">
                          <?=$this->lang->line('contact_add_source');?>
                        </label>
                        <select class="form-control parsley-validated" name="slt_contact_source" id="slt_contact_source">
                          <option value="">Please Select</option>
                          <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
                          <option <?php if(!empty($editRecord[0]['contact_source']) && $editRecord[0]['contact_source'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                          <?=ucwords($row['name']);?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="validateSelect">
                          <?=$this->lang->line('contact_add_method');?>
                        </label>
                        <select class="form-control parsley-validated" name="slt_contact_method" id="slt_contact_method">
                          <option value="">Please Select</option>
                          <?php if(!empty($method_type)){
							foreach($method_type as $row){?>
                          <option <?php if(!empty($editRecord[0]['contact_method']) && $editRecord[0]['contact_method'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                          <?=ucwords($row['name']);?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <div class="form-group">
                          <div>
                            <label class="nomargin">
                              <?=$this->lang->line('common_label_contact_type');?>
                            </label>
                          </div>
                          <?php
			   		$selectedcontacttypes = array(); 
			   		if(!empty($contact_trans_data)){
							foreach($contact_trans_data as $row){ 
							
								$selectedcontacttypes[] = $row['contact_type_id'];
							
							} ?>
                          <?php } ?>
                          <?php if(!empty($contact_type)){
							foreach($contact_type as $row){?>
                          <div class="checkbox nopadding margin-left-20">
                            <label class="">
                            <div class="">
                              <input <?php if(in_array($row['id'],$selectedcontacttypes)){ echo 'checked="checked"';}?> type="checkbox" class="" name="chk_contact_type_id[]" value="<?=$row['id'];?>">
                            </div>
                            <?=$row['name'];?>
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_status');?>
                        </label>
                        <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status">
                          <option value="">Please Select</option>
                          <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
                          <option <?php if(!empty($editRecord[0]['contact_status']) && $editRecord[0]['contact_status'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                          <?=ucwords($row['name']);?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row add_tag_div">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_tag');?>
                        </label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" name="txt_tag" id="txt_tag" />
                      </div>
                      <script type="text/javascript">
			 var common = 0;
			$(document).ready(function() {
                            
				$("#txt_tag").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=$row['tag']?>"},
					<?php } } ?>
					<?php 
					if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
						{id: <?=$rowtrans['id']?>, name: "<?=$rowtrans['tag']?>"},
					<?php } } ?>
				],
				{prePopulate:[
					<?php 
					if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
						{id: <?=$rowtrans['id']?>, name: "<?=$rowtrans['tag']?>"},
					<?php } } ?>
				],onAdd: function (item) {
					common++;
					//alert(common);
				},
				onResult: function (item) {
					try{
						if($.isEmptyObject(item)){
							  return [{id:'NEWTAG-'+common+'{^}'+$("tester").text(),name: $("tester").text()}]
						}else{
							  return item
						}
					}
					catch(e)
					{
							
					}
			
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
                      <?php /*if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
					
				   <div class="delete_tag_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
					<div class="col-sm-8">
						<input type="hidden" name="tag_type_trans_id[]" id="tag_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tage[]" value="<?php if(!empty($rowtrans['tag'])){ echo $rowtrans['tag']; }?>">
					</div>
					<div class="col-sm-1 text-center icheck-input-new">
					 <div class="">
					  <a title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_tag_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
					 </div>
				   	</div>
				   </div>
					
				<?php  } ?>
			<?php }else{ ?>
			
             <div class="col-sm-8">
              <!--<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>-->
              <input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]" placeholder="e.g. Tag">
             </div>
			 
			 <?php } */ ?>
                    </div>
                    <div class="row"> 
                      <!-- <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_tag" href="javascript:void(0);" title="Add Tags"><i class="fa fa-plus-square"></i> Add Tags</a> </div>--> 
                    </div>
                    <?php /* if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                    <div class="row add_communication_plan_div">
                      <div class="col-sm-8">
                        <label for="text-input">
                          <?=$this->lang->line('contact_add_interaction_plan');?>
                        </label>
                      </div>
                      <div class="col-sm-8">
                        <select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id" multiple="multiple">
                          <!--<option value="">Please Select</option>-->
                          <?php if(!empty($communication_plans)){
							foreach($communication_plans as $row){?>
                          <option <?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo "selected";} ?> value="<?=$row['id']?>">
                          <?=$row['plan_name']?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <?php // } ?>
                    </div>
                    <? } */ ?>
                    <!--<div class="row">
             <div class="col-sm-12 topnd_margin"> <a title="Add Communication" class="text_color_red text_size add_new_communication_plan" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Communication</a> </div>
			 
            </div>-->
                    
                    <div class="row">
                      <div class="col-sm-8">
                        <label class="pull-left margin-top-5px">
                          <?=$this->lang->line('user_assign_msg_contact');?>
                        </label> 
                        <select class="form-control parsley-validated" name="slt_user[]" id="slt_user" multiple="multiple">
                          <?php if(!empty($user_list)){
                                foreach($user_list as $row){
                                    $email = !empty($row['email_id'])?"(".$row['email_id'].")":'' ?>
                          <option value="<?=$row['id']?>" <?php if(isset($user_add_list) && is_array($user_add_list) && in_array($row['id'],$user_add_list)){ echo "selected";}?>>
                          <?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email);?>
                          <?=!empty($row['agent_type'])?'('.$row['agent_type'].')':''?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <!--<select class="form-control parsley-validated" name="slt_user" id="slt_user">
				   	<option value="">Users</option>
				   	<?php if(!empty($user_list)){
							foreach($user_list as $row){
								$email = !empty($row['email_id'])?"(".$row['email_id'].")":'' ?>
								<option value="<?=$row['id']?>" <?php if(!empty($user_add_list[0]['user_id']) && $user_add_list[0]['user_id'] == $row['id']){ echo "selected"; }?>><?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email);?></option>
							<?php } ?>
				   <?php } ?>
				  </select>--> 
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 pull-left text-center margin-top-10"> 
                    <!--<a class="btn btn-secondary" href="javascript:void(0)">Save Contact</a>-->
                    <input type="hidden" id="fb_id" name="fbid" value="<?php if(!empty($editRecord[0]['fb_id'])){ echo $editRecord[0]['fb_id']; }?>"/>
                    <input type="hidden" id="contacttab" name="contacttab" value="1" />
                    <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" name="submitbtn" onclick="return setdefaultdata();" />
                    <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="return setdefaultdata();" name="submitbtn" />
                    <a class="btn btn-primary" title="cancel" href="javascript:history.go(-1);">Cancel</a> </div>
                </form>
              </div>
              <div <?php if($tabid == 2){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profile">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
                <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                <input id="doc_id" name="doc_id" type="hidden" value="">
                <div class="col-sm-12 pull-left text-center margin-bottom-10">
                  <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" onclick="setsubmitidtab2(1);" id="savecontacttab2" name="submitbtn" />
                  <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="setsubmitidtab2(2);" name="submitbtn" />
                  <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
                <div class="col-sm-12 clear" id="documenets">
                  <div class="add_emailtype autooverflow">
                    <div class="col-sm-12 col-lg-8">
                      <div class="row">
                        <div class="col-sm-12 col-lg-7 form-group">
                          <label for="text-input">
                            <?=$this->lang->line('contact_add_documents');?>
                          </label>
                        </div>
                        <div class="col-sm-12 col-lg-7 form-group">
                          <label for="text-input">
                            <?=$this->lang->line('contact_add_document_type');?>
                          </label>
                          <select class="form-control parsley-validated" name="slt_doc_type" id="slt_doc_type">
                            <option value="">Please Select</option>
                            <?php if(!empty($document_type)){
										foreach($document_type as $row){?>
                            <option <?php if(!empty($rowtrans['doc_type']) && $rowtrans['doc_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                            <?=$row['name']?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-12 col-lg-7 form-group">
                          <label for="text-input">
                            <?=$this->lang->line('contact_add_document_name');?>
                          </label>
                          <input id="txt_doc_name" name="txt_doc_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['doc_name'])){ echo htmlentities($editRecord[0]['doc_name']); }?>" placeholder="e.g. Document Name">
                        </div>
                        <div class="col-sm-12 col-lg-7 form-group">
                          <label for="text-input">
                            <?=$this->lang->line('common_label_desc');?>
                          </label>
                          <textarea id="txtarea_doc_desc"  placeholder="e.g. Description" name="txtarea_doc_desc" class="form-control parsley-validated"><?php if(!empty($rowtrans['doc_desc'])){ echo htmlentities($rowtrans['doc_desc']); }?>
</textarea>
                        </div>
                        <div class="col-sm-12 col-lg-7 form-group">
                          <label for="text-input" class="fleft">
                            <?=$this->lang->line('contact_add_upload_file');?>
                          </label>
                          <div class="browse_btn clear">
                            <div class="file_input_div">
                              <input type="button" value="Browse" class="file_input_button" />
                              <input type="file" alt="1" name="doc_file" id="doc_file" class="file_input_hidden"/>
                              <input type="hidden" name="hiddenFiledoc" id="hiddenFiledoc" value="" />
                            </div>
                            <span id="priview_doc"></span> </div>
                        </div>
                        <div class="col-sm-12 col-lg-7 form-group margin-top-10">
                          <label >Allowed File Types: txt,doc,docx,pdf,csv,xls,xlsx,jpg,jpeg,png,bmp,gif </label>
                          <input title="Save and Add More Document" type="submit" class="btn btn-secondary" value="Save and Add More Document" onclick="return setsubmitidtab2(3);" id="submitbtn" name="submitbtn2" />
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 clear appendajaxdata toppadding">
                      <?php $this->load->view('admin/contacts/contact_document_ajax'); ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 pull-left text-center">
                  <input type="hidden" id="contacttab" name="contacttab" value="2" />
                  <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="1" />
                  <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" onclick="setsubmitidtab2(1);" id="savecontacttab2" name="submitbtn" />
                  <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="setsubmitidtab2(2);" name="submitbtn" />
                  <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
              </form>
            </div>
            <div <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profilenew">
            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
              <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
              <div class="col-sm-12 pull-left text-center margin-bottom-10">
                <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" name="submitbtn" onclick="return contact_step3();" />
                <?php 
                    if($right_buyer[0]['is_buyer_tab']== '1')
                    {
                 ?>
                
                <?php } ?>
                <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a> </div>
              <div class="col-sm-12 col-lg-7">
                <div class="row">
                  <div class="col-sm-9 col-lg-9 col-md-6">
                    <label for="text-input">
                      <?=$this->lang->line('contact_add_birth_date');?>
                    </label>
                    <input id="txt_birth_date" placeholder="Specific Date" name="txt_birth_date" readonly="" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-9 col-lg-9 col-md-6">
                    <label for="text-input">
                      <?=$this->lang->line('contact_add_anniversary_date');?>
                    </label>
                    <input id="txt_anniversary_date"  placeholder="Specific Date" name="txt_anniversary_date" readonly="" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <label for="validateSelect">
                      <?=$this->lang->line('common_label_field_type');?>
                    </label>
                  </div>
                </div>
                <?php if(!empty($field_trans_data) && count($field_trans_data) > 0){
			 		foreach($field_trans_data as $rowtrans){
						//echo $rowtrans['field_type']; ?>
                <div class="delete_field_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow row">
                  <div class="col-sm-5 form-group"> 
                    <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
                    <input type="hidden" name="field_type_trans_id[]" id="field_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
                    <select class="form-control parsley-validated contact_module select_field_type_change" name="slt_field_typee[]" id="slt_field_type_e_<?=$rowtrans['id']?>">
                      <option value="">Please Select</option>
                      <?php if(!empty($field_type)){
								foreach($field_type as $row){?>
                      <option data-id="<?=$row['field_type']?>" <?php if(!empty($rowtrans['field_type']) && $rowtrans['field_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>">
                      <?=ucwords($row['name']);?>
                      </option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-5 form-group"> 
                    <!--<label for="validateSelect"><?=$this->lang->line('contact_add_field_name');?></label>-->
                    <input id="txt_field_name_e_<?=$rowtrans['id']?>" name="txt_field_namee[]" class="form-control parsley-validated" type="text" value="<?php if(!empty($rowtrans['field_name'])){ echo htmlentities($rowtrans['field_name']); }?>" >
                  </div>
                  <div class="col-sm-1 text-center icheck-input-new">
                    <div class=""> <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Delete Field" onclick="return ajaxdeletetransdata('delete_field_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a> </div>
                  </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="row">
                  <div class="col-sm-5 form-group"> 
                    <!--<label for="validateSelect"><?=$this->lang->line('common_label_field_type');?></label>-->
                    <select class="form-control parsley-validated contact_module select_field_type_change" name="slt_field_type[]" id="slt_field_type" onchange="" >
                      <option value="">Please Select</option>
                      <?php if(!empty($field_type)){
							foreach($field_type as $row){?>
                      <option data-id="<?=$row['field_type']?>" value="<?=$row['id']?>">
                      <?=ucwords($row['name']);?>
                      </option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-5 form-group">
                    <input id="txt_field_name_1" name="txt_field_name[]" class="form-control parsley-validated" type="text">
                  </div>
                </div>
                <?php } ?>
                <div class="add_field_name_div"></div>
                <div class="row">
                  <div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add field" class="text_color_red text_size add_field_name"><i class="fa fa-plus-square"></i> Add Additional Field</a> </div>
                </div>
              </div>
              <div class="col-sm-12 pull-left text-center margin-top-10">
                <input type="hidden" id="contacttab" name="contacttab" value="3" />
                <input type="submit" title="Save Contact" class="btn btn-secondary-green" value="Save Contact" name="submitbtn" onclick="return contact_step3();" />
                <?php 
		 	if(!empty($right_buyer[0]['is_buyer_tab']) && $right_buyer[0]['is_buyer_tab']  == '1' )
			{
		 ?>
                
                <?php } ?>
                <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a> </div>
            </form>
          </div>
          <?php if(!empty($this->modules_unique_name) && in_array('buyer_preferences',$this->modules_unique_name)){?>
          <?php 
		 	if(!empty($right_buyer[0]['is_buyer_tab']) && $right_buyer[0]['is_buyer_tab'] == '1')
			{
		 ?>
          <div <?php if($tabid == 4){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="buyerpreferences">
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
            <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
            <div class="col-sm-12 pull-left text-center margin-bottom-10">
              <input type="submit" title="Save Contact and Finish" class="btn btn-secondary-green" value="Save Contact and Finish" onclick="return contact_step4();" />
              <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a> </div>
            <div class="col-sm-12 clear">
              <div class="row">
                <div class="col-sm-4 form-group">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_price_range_from')." (In ".$this->lang->line('currency').")";?>
                  </label>
                  <input id="txt_price_range_from" name="txt_price_range_from" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['price_range_from'])){ echo $editRecord[0]['price_range_from']; }?>"  onkeypress="return isNumberKey(event)" placeholder="Number" />
                </div>
                <div class="col-sm-4">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_price_range_to')." (In ".$this->lang->line('currency').")";?>
                  </label>
                  <input id="txt_price_range_to" name="txt_price_range_to" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['price_range_to'])){ echo $editRecord[0]['price_range_to']; }?>"  onkeypress="return isNumberKey(event)" placeholder="Number" />
                </div>
              </div>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label for="text-input">
                    <?=$this->lang->line('agent_rr_weightage_label_min_area')." (In ".$this->lang->line('area_units').")";?>
                  </label>
                    <input type="hidden" name="oldminarea" id="oldminarea" value="<?=!empty($editRecord[0]['min_area'])?$editRecord[0]['min_area']:'';?>"  />
                    <select class="form-control" name="txt_min_area" id="txt_min_area" onchange="getmaxarea();">
                        <option value="">Min Area</option>
                        <?php
                        for($i=0;$i<=6000;$i=$i+50)
                        {
                            ?>
                            <option value="<?=$i?>" <?=!empty($editRecord[0]['min_area'])?($editRecord[0]['min_area']==$i)?'selected=selected':'':'';?>><?=$i?></option>
                        <?php 
                        }
                        ?>
                    </select>
                    <?php /*<input id="txt_min_area" name="txt_min_area" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['min_area'])){ echo $editRecord[0]['min_area']; }?>"  onkeypress="return isNumberKey(event)" placeholder="Number" />*/ ?>
                </div>
                <div class="col-sm-4">
                  <label for="text-input">
                    <?=$this->lang->line('agent_rr_weightage_label_max_area')." (In ".$this->lang->line('area_units').")";?>
                  </label>
                    <input type="hidden" name="oldmaxarea" id="oldmaxarea" value="<?=!empty($editRecord[0]['max_area'])?$editRecord[0]['max_area']:'';?>"  />
                    <select  class="form-control" name="txt_max_area" id="txt_max_area" onchange="getminarea();">
                        <option value="">Max Area</option>
                    </select>
                  <?php /*<input id="txt_max_area" name="txt_max_area" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['max_area'])){ echo $editRecord[0]['max_area']; }?>"  onkeypress="return isNumberKey(event)" placeholder="Number" />*/ ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_house_style');?>
                  </label>
                  <input id="txt_house_style" name="txt_house_style"  class="form-control parsley-validated" type="text"  value="<?php if(!empty($editRecord[0]['house_style'])){ echo $editRecord[0]['house_style']; }?>" placeholder="e.g. House Style"/>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_area_of_interest');?>
                  </label>
                  <input id="txt_area_of_interest" name="txt_area_of_interest" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['area_of_interest'])){ echo htmlentities($editRecord[0]['area_of_interest']); }?>" placeholder="e.g. Interest" />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_square_footage');?>
                  </label>
                  <input id="txt_square_footage" name="txt_square_footage" placeholder="e.g. Footage" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['square_footage'])){ echo $editRecord[0]['square_footage']; }?>" />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_no_of_bedrooms');?>
                  </label>
                  <input id="txt_no_of_bedrooms" name="txt_no_of_bedrooms" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['no_of_bedrooms'])){ echo $editRecord[0]['no_of_bedrooms']; }?>"  onkeypress="return isNumberKey(event)" placeholder="Number"/>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_no_of_bathrooms');?>
                    (e.g. 1.5 Or 2)</label>
                  <input id="txt_no_of_bathrooms" name="txt_no_of_bathrooms" class="form-control parsley-validated" type="text" onkeypress="return isNumberKeyDecimal(event)"  value="<?php if(!empty($editRecord[0]['no_of_bathrooms'])){ echo $editRecord[0]['no_of_bathrooms']; }?>"  placeholder="Number"  />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_buyer_preferences_notes');?>
                  </label>
                  <textarea name="textarea_buyer_preferences_notes" placeholder="e.g. Notes" id="textarea_buyer_preferences_notes" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['buyer_preferences_notes'])){ echo htmlentities($editRecord[0]['buyer_preferences_notes']); }?>
</textarea>
                </div>
              </div>
            </div>
            <div class="col-sm-12 pull-left text-center margin-top-10">
              <input type="hidden" id="contacttab" name="contacttab" value="4" />
              <input type="submit" title="Save Contact and Finish" class="btn btn-secondary-green" value="Save Contact and Finish" onclick="return contact_step4();" />
              <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a> </div>
          </form>
        </div>
        <?php } ?>
        <?php } ?>
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
var flag=0;
    $(document).ready(function (){
        <?php if(!empty($editRecord[0]['min_area'])) { ?>
            getmaxarea();
        <?php } ?>
    });
$("select#slt_user").multiselect({
		header: "User",
		noneSelectedText: "Users",
		selectedList: 1
}).multiselectfilter();	

	$('.mask_apply_class').mask('999-999-9999');

	$('body').on('click','.add_email_address',function(e){
	
		var inlinehtml = '';
		
		
		inlinehtml += '<div class="remove_email_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type">';
               inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($email_type)){
										foreach($email_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>';
              inlinehtml += '<input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email" placeholder="e.g. abc.gmail.com">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">';
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
             inlinehtml += '<div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_email_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
			
		
		/*$('.add_email_address_div').hide();
		$('.add_email_address_div').append(inlinehtml).fadeIn('slow');*/
		
		$('.add_email_address_div').append(inlinehtml);
	
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		
		
		/*var liData = '<div class="new-rows" style="display:none;"></div>';
		$(liData).appendTo('.add_email_address_div').fadeIn('slow');
	
		jQuery('.new-rows').html(inlinehtml, 500);*/
		
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
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>';
              inlinehtml += '<input id="txt_phone_no" maxlength="12" name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text" placeholder="e.g. 123-456-7890">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">';
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
             inlinehtml += '<div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_phone_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
		
		$('.add_phone_number_div').append(inlinehtml);
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		$('.mask_apply_class').mask('999-999-9999');
		
		
		/*var liData = '<div class="new-rows1" style="display:none;"></div>';
		$(liData).appendTo('.add_phone_number_div').fadeIn('slow');
	
		jQuery('.new-rows1').html(inlinehtml, 500);*/
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
			//inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">';
		   	 inlinehtml += '<select class="form-control parsley-validated" name="txt_website_type[]" id="txt_website_type">';
			 inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($website_type)){
										foreach($website_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
			inlinehtml += '</select>';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5 form-group">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]"  data-parsley-type="url" placeholder="e.g. www.xyz.com">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my delete_website_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_website_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows2" style="display:none;"></div>';
		$(liData).appendTo('.add_website_div').fadeIn('slow');
	
		jQuery('.new-rows2').html(inlinehtml, 500);*/
		
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
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>';
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
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated fbid" id="txt_social_profile" name="txt_social_profile[]" placeholder="e.g. https://twitter.com">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Social WebSite" class="btn btn-xs btn-primary mar_top_con_my delete_social_profile_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_social_profile_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows3" style="display:none;"></div>';
		$(liData).appendTo('.add_social_profile_div').fadeIn('slow');
	
		jQuery('.new-rows3').html(inlinehtml, 500);*/
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
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>';
		  inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]" placeholder="e.g. Tag">';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my delete_tag_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_tag_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows4" style="display:none;"></div>';
		$(liData).appendTo('.add_tag_div').fadeIn('slow');
	
		jQuery('.new-rows4').html(inlinehtml, 500);*/
		
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
			inlinehtml += '<input type="text" placeholder="Zip Code" id="txt_zip_code" maxlength="5" data-minlength="5"  name="txt_zip_code[]" class="form-control parsley-validated">';
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
		
		/*var liData = '<div class="new-rows5" style="display:none;"></div>';
		$(liData).appendTo('.add_address_div').fadeIn('slow');
	
		jQuery('.new-rows5').html(inlinehtml, 500);*/
		
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
		  
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id">';
			   inlinehtml += '<option value="">Please Select</option>';
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
		
		/*var liData = '<div class="new-rows6" style="display:none;"></div>';
		$(liData).appendTo('.add_communication_plan_div').fadeIn('slow');
	
		jQuery('.new-rows6').html(inlinehtml, 500);*/
		
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
		var id1 = $('#id').val();
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('admin_base_url')?><?=$viewname;?>/'+functionname+'/'+id,
											data: {'id1':id1}, 
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
	
        function contact_step3()
        {
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                return true;
            }
        }
        
        function contact_step4()
        {
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                return true;
            }
        }
        
	function setdefaultdata()
	{
		//alert('hiii');
		var returndata = 0;
		//$('.fbid').trigger('blur');
		
		//var boxes = $('input[name="txt_social_profile[]"]');
		//var boxes  = $('input[".fbid"]');
		
		//alert($("#fb_id").val());
		
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
					$.confirm({'title': 'Alert','message': " <strong> Same email id used multiple times. Please insert different email ids. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
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
				$.confirm({'title': 'Alert','message': " <strong> Same phone no used multiple times. Please insert different phone no. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same phone no used multiple times. Please insert different phone no.');
				returndata = 1;
			}
			
		});
		
		if(returndata == 1)
			return false;
		//else if(flag == 1)
			//return false;

		emailchkval = $('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').closest("div.my_col_lg_2").siblings('div.my_col_lg_4').find('input[type=email]').val();
		$('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').val(emailchkval);
		
		phonechkval = $('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').closest("div.my_col_lg_2").siblings('div.my_col_lg_4').find('input[type=text]').val();
		$('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').val(phonechkval);
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
			$(".fbid").each(function(){
				var inp_id = $(this).parent().prev('div').find('select').val();
				if(inp_id==1)
				{
					var thisvar = $(this);
					var url = $(this).val();
					if(url != null && url != "")
					{
						$('#fb_id').val('');
						if(url.toLowerCase().indexOf("id=") >= 0)
						{
							var arr = url.split('id=');
							var url = arr[1];
							if(url.trim() != '')
							{
								$('#fb_id').val(url);
								//return 0;
							}
							else
							{
								flag = 1;
							}
						}
						else 
						{
							if (url.toLowerCase().indexOf("facebook") >= 0)
							{
								var arr = url.split('facebook.com/');
								var url = arr[1];
							}
							else
								var url = url;
							$.ajax({
								url:"https://graph.facebook.com/"+url,
								async:false,
								success:function(data)
								{
									$('#fb_id').val(data.id);
									//return 0;
								},
								error: function(jqXHR, textStatus, errorThrown) {
										alert('Is not a valid fb URL');
										thisvar.val('');
										thisvar.focus();
										flag = 1;
									}
							});
						}
					  }
				}
				if(flag==1)
					return false;
			});
			if(flag==1){
				$.unblockUI();
				flag = 0;
				return false;
			}
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        }
	}
	
</script> 
<script type="text/javascript">

	var addcounterdata = 2;

	$('body').on('click','.add_field_name',function(e){
	
		var inlinehtml = '';
		
		
		inlinehtml += '<div class="remove_field_div padding-top-10 clear autooverflow row">';
             inlinehtml += '<div class="col-sm-5 form-group">';
              inlinehtml += '<select class="form-control parsley-validated contact_module select_field_type_change" name="slt_field_type[]" id="slt_field_type" onchange="">';
               inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($field_type)){
										foreach($field_type as $row){?>
											inlinehtml += '<option data-id="<?=$row['field_type']?>" value="<?=$row['id']?>"><?=mysql_real_escape_string($row['name'])?></option>';
										<?php } ?>
							   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-5 form-group">';

              inlinehtml += '<input id="txt_field_name_'+addcounterdata+'" name="txt_field_name[]" class="form-control parsley-validated" type="text">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_field_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
			
		$('.add_field_name_div').append(inlinehtml);
		addcounterdata++;
	
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		
	});
	
	$('body').on('click','.delete_field_div_button',function(e){
	
		var removediv = $(this).closest('.remove_field_div');
	
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
		var id1 = $('#id').val();
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('admin_base_url')?><?=$viewname;?>/'+functionname+'/'+id,
											data: {'id1':id1}, 
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
		var btnUpload1=$('#doc_file');
		new AjaxUpload(btnUpload1, {
			type: 'post',
			data:{},
			action: '<?=$this->config->item('admin_base_url').$viewname."/upload_document";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(txt|doc|pdf|docx|csv|xls|xlsx|jpg|jpeg|png|bmp|gif )$/.test(ext))){ 
                    // extension is not allowed 
					$.confirm({'title': 'Alert','message': " <strong> You can upload only txt,doc,docx,pdf,csv,xls,xlsx,jpg,jpeg,png,bmp,gif. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					//alert('You can upload only txt,doc,docx,pdf,csv,xls,xlsx.');
					return false;
				}
				
				$('#priview_doc').html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
			},
			onComplete: function(file, response){
			var data = jQuery.parseJSON(response);
				var result=data.document_name.split('-');
				if(result.length > 1)
				{
					var arrayindex = jQuery.inArray( result[0] , result );
					
					if(arrayindex >= 0)
					{
						result.splice( arrayindex, 1 );
					}
				}
				$('#priview_doc').text(result.join('-'));	
				$('#hiddenFiledoc').val(data.document_name);
			}
		});
		
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
							window.location.href = '<?=base_url().'admin/'.$viewname;?>';
						else if(chkval == 3)
							
							$(".toppadding").html(data);
							
						else
							window.location.href = '<?=base_url().'admin/'.$viewname.'/edit_record/';?>'+cid+'/3';
						
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
					$('.img_delete').hide();
			      	$('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
				  }
				}//succsess
			});//ajax
			
			}},'No'	: {'class'	: 'special'}}});
	}
	
	function editdoctransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('admin_base_url').$viewname."/get_doc_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#doc_id").val(msg.id);
						$("#slt_doc_type").val(msg.doc_type);
						$("#txt_doc_name").val(msg.doc_name);
						$("#txtarea_doc_desc").val(msg.doc_desc);
						$("#hiddenFiledoc").val(msg.doc_file);
						
						var result=msg.doc_file.split('-');
						if(result.length > 1)
						{
							var arrayindex = jQuery.inArray( result[0] , result );
							
							if(arrayindex >= 0)
							{
								result.splice( arrayindex, 1 );
							}
						}
						
						$("#priview_doc").text(result.join('-'));
					}
				}//succsess
			});//ajax
	}
	
	function setsubmitidtab2(id)
	{
		$("#submitvaltab2").val(id);
                if ($('#<?php echo $viewname?>').parsley().isValid()) {
                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                }
	}
	
	function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    // DECIMAL DIGIT VALIDATION START ////
	function isNumberKeyDecimal(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31	&& (charCode < 48 || charCode > 57)){
				//setTimeout(function() { $('#txt_size_w').focus(); }, 3000);
				return false;
		}
		else
		{
			if($('#txt_no_of_bathrooms').val() != ""){
			$('#txt_no_of_bathrooms').blur(
				function(e) {
				var number_temp = $('#txt_no_of_bathrooms').val();
			
				if(number_temp > 22)
				{
					$.confirm({'title': 'Alert','message': " <strong> Please enter proper number."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#txt_no_of_bathrooms').val('');
					setTimeout(function() { $('#txt_no_of_bathrooms').focus(); }, 3000);
					return false;
				}else
				{
					var number = number_temp.split(".");
					if(e.keyCode==8)
					{}
					else
					{
						if(number.length>0)
						{
								if(((number[0].length > 0) || (number[0].length > 1)) && (number[1].length > 1)){
									$.confirm({'title': 'Alert','message': " <strong> Please enter proper width."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
								$('#txt_no_of_bathrooms').val('');
								setTimeout(function() { $('#txt_no_of_bathrooms').focus(); }, 3000);
								}    
								else{
									return true;
								}
							}
					}
				}
		
			//return false;
			});
			}
		}
	}

   function isCharacterKey(evt)
    {
        //var charCode = (evt.which) ? evt.which : evt.keyCode;
		var c = (evt.which) ? evt.which : evt.keyCode;
        //if(charCode >= 48 && charCode <= 57 && charCode)
		var a=((c > 64 && c < 91)|| (c > 96 && c < 123) || c == 34 || c == 39 || c == 8 || c == 9 || c == 32);
		 if(!a)
		  {
		    return false;
		  }
    	return true;
	}

	/*$(".colorPicker").colorPicker({

		onSelect: function(ui, c){
			ui.css("background", c);
		}
	});*/
</script> 
<script type="text/javascript">
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
///// Apply For Mask //////////////////

	
</script> 
<script type="text/javascript">
$('body').on('change','.select_field_type_change',function(e){
	
	var field_type = $(this).find(':selected').data('id');
	var inp_id = $(this).parent().next('div').find('input[type=text]').attr('id');
	if(field_type == 2)
	{
		//$(this).parent().next('div').find('input[type=text]').addClass('my_datepicker_class');
		$('#'+inp_id).datepicker({
			showOn: "button",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			maxDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		
		$(this).parent().next('div').find('input[type=text]').css('width','80%');
		$(this).parent().next('div').find('input[type=text]').css('float','left');
		$(this).parent().next('div').find('input[type=text]').attr('readonly','readonly');
		//$(this).parent().next('div').find('input[type=text]').val('');
	}
	else
	{
		//$(this).parent().next('div').find('input[type=text]').removeClass('my_datepicker_class');
		//$('#'+inp_id).datepicker('hide');
		//$('#'+inp_id).datepicker("option", "disabled", true);
		//$('#'+inp_id).datepicker("disable");
		 $('#'+inp_id).datepicker("destroy");
		 
		$(this).parent().next('div').find('input[type=text]').css('width','');
		$(this).parent().next('div').find('input[type=text]').css('float','left');
		$(this).parent().next('div').find('input[type=text]').removeAttr('readonly');
		//$(this).parent().next('div').find('input[type=text]').val('');
	}
});

$("select#slt_communication_plan_id").multiselect({
		header: "Select Communication",
		noneSelectedText: "Select Communication",
		selectedList: 1
	}).multiselectfilter();

</script> 
<script type="text/javascript">
<?php 
if(!empty($field_trans_data) && count($field_trans_data) > 0){
foreach($field_trans_data as $rowtrans){?>
	setTimeout(function(){$('#slt_field_type_e_<?=$rowtrans['id']?>').trigger('change');}, 1000);
<?php }} ?>
</script>
<script>
/*
$('body').on('blur','.fbid',function(e){
	var inp_id = $(this).parent().prev('div').find('select').val();
	if(inp_id==1)
	{
		var thisvar = $(this);
		var url = $(this).val();
		if(url != null && url != "")
		{
			$('#fb_id').val('');
			if(url.toLowerCase().indexOf("id=") >= 0)
			{
				var arr = url.split('id=');
				var url = arr[1];
				if(url.trim() != '')
				{
					$('#fb_id').val(url);
					//return 0;
				}
				else
				{
					//return 1;
					//e.preventDefault();
				}
			}
			else 
			{
				if (url.toLowerCase().indexOf("facebook") >= 0)
				{
					var arr = url.split('facebook.com/');
					var url = arr[1];
				}
				else
					var url = url;
				$.ajax({
					url:"https://graph.facebook.com/"+url,
					//async:false,
					success:function(data)
					{
						$('#fb_id').val(data.id);
						//return 0;
					},
					error: function(jqXHR, textStatus, errorThrown) {
							alert('Is not123 a valid fb URL');
							//$('#fb_id').val('Is not a valid fb URL');
							//return 1;
							thisvar.val('');
							thisvar.focus();
							//return false;
							//e.preventDefault();
						}
				});
			}
		  }
		  //else
			//return 0;
	}
});*/

function FBUrl()
{	
/*var boxes = $('input[name="txt_social_profilee[]"]');
$(boxes).each(function(){
	 // myarray[i]=this.value;
	 var inp_id = $(this).parent().prev('div').find('select').val();
	  alert(inp_id);
});*/
/*			
//alert("hiiifd");
$('.fbid').trigger('blur');	

*/	
	//alert("hi");
	/*var inp_id = $(this1).parent().prev('div').find('select').val();
	if(inp_id==1)
	{
	var thisvar = $(this1);
	var url = $(this1).val();
	if(url != null && url != "")
	{
		$('#fb_id').val('');
		if(url.toLowerCase().indexOf("id=") >= 0)
		{
			var arr = url.split('id=');
			var url = arr[1];
			if(url.trim() != '')
				$('#fb_id').val(url);
			else
			{
				alert('Is not a valid fb URL');
				$(this1).focus();
				flag = 1;
				return false;
			}
		}
		else 
		{
			if (url.toLowerCase().indexOf("facebook") >= 0)
			{
				var arr = url.split('facebook.com/');
				var url = arr[1];
			}
			else
				var url = url;
			$.ajax({
				url:"https://graph.facebook.com/"+url,
				//async:false,
				success:function(data)
				{
						$('#fb_id').val(data.id);
				},
				error: function(jqXHR, textStatus, errorThrown) {
						alert('Is not a valid fb URL');
						$(this1).focus();
						flag = 1;
						thisvar.val('');
						return false;
					}
			});
		}
	  }
	  else
	  {
	  	flag=0;
	  }
	}
	if(flag == 1)
		return false;*/
}

$('#txt_price_range_from').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});

$('#txt_price_range_to').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});

//$("#txt_price_range_from, #txt_price_range_to").change(function (e) {
/*$('body').on('change','#txt_price_range_from, #txt_price_range_to',function(e){
	//alert($("#txt_price_range_from").val());
	
	var from_val = $("#txt_price_range_from").val();
	from_val = from_val.replace(/\,/g, '');
	from_val = from_val.replace('$', '');
	
	var to_val = $("#txt_price_range_to").val();
	to_val = to_val.replace(/\,/g, '');
	to_val = to_val.replace('$', '');
	
    var lil = parseInt(from_val, 10);
    var big = parseInt(to_val, 10);
	
	//alert(lil);
	//alert(big);
	
    $('#lil').text(lil);
    $('#big').text(big);
    if (lil > big) {
        var targ = $(e.target);
        if (targ.is("#txt_price_range_to")) {
            //alert("Max must be greater than Min");
			
			$.confirm({'title': 'Alert','message': " <strong> Max price must be greater than Min "+"<strong></strong>",
			'buttons': {'ok'	: {
					'class'	: 'btn_center alert_ok',	
					'action': function(){
							 $('#txt_price_range_to').val(lil);
							 $('#txt_price_range_to').focus();
						}},  }});
			
            //$('#txt_price_range_to').val(lil);
        }
        if (targ.is("#txt_price_range_from")) {
            //alert("Min must be less than Max");
			
			$.confirm({'title': 'Alert','message': " <strong> Min price must be less than Max "+"<strong></strong>",
			'buttons': {'ok'	: {
					'class'	: 'btn_center alert_ok',	
					'action': function(){
							 $('#txt_price_range_from').val(big);
							 $('#txt_price_range_from').focus();
						}},  }});
			
            //$('#txt_price_range_from').val(big);
        }
    }
});*/

function getmaxarea()
{
    //alert($("#smin_price").val());
    var from_val = $("#txt_min_area").val();
    var html = '';
    if(from_val != '')
    {
        //alert(from_val);
        from_val = from_val.replace(/\,/g, '');
        from_val = from_val.replace('$', '');
        var lil = parseInt(from_val, 10);

        for (var i=lil;i<=6000;i=i+50)
        {
            if($('#oldmaxarea').val() == i)
                html += '<option selected=selected value='+i+'>'+i+'</option>';
            else
                html += '<option value='+i+'>'+i+'</option>';
        }
    } else {
        html += '<option value="">Max Area</option>';
    }
    $('#txt_max_area').html(html);
}
//});

//$('body').on('change','#smax_area1',function(e){
function getminarea()
{
    var from_val = $("#txt_max_area").val();
    var min_val = $("#txt_min_area").val();
    if(from_val != '')
    {
        from_val = from_val.replace(/\,/g, '');
        from_val = from_val.replace('$', '');

        var lil = parseInt(from_val, 10);
        var min = parseInt(min_val, 10);
        var html = '';
        for (var i=0;i<=lil;i=i+50)
        {
            if($('#txt_min_area').val() == i)
                html += '<option selected=selected value='+i+'>'+i+'</option>';
            else
                html += '<option value='+i+'>'+i+'</option>';
        }
    } else {
        html += '<option value="">Min Area</option>';
        for (var i=0;i<=6000;i=i+50)
        {
            html += '<option value='+i+'>'+i+'</option>';
        }     
    }
    $('#txt_min_area').html(html);
}

</script>

<style>
.portlet .portlet-content {min-height:1290px;}
.ui-multiselect {width: 100% !important;  height: 33px; overflow: hidden; text-align: left;}

</style>