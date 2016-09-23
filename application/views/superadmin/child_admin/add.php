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
           <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
        <h3 class="modal-title">Theme Preview</h3>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
                                            
<div id="content">
    <div id="content-header">
        <h1>
            <?=$this->lang->line('child_admin_header');?>
        </h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i>
                          <?php if(empty($editRecord)){ echo $this->lang->line('child_admin_add_head');}
                            else if(!empty($insert_data)){ echo $this->lang->line('child_admin_add_head'); } 
                            else{ echo $this->lang->line('child_admin_edit_head'); if(!empty($editRecord[0]['domain'])) echo ' ('.$editRecord[0]['domain'].')'; }?>
                        </h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
                    </div>
                    <div class="portlet-content">
                        <div class="col-sm-12">

                            <ul class="nav nav-tabs" id="myTab1">
                                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('Website_head');?>" data-toggle="tab" href="#website">
                                    <?=$this->lang->line('Website_head');?>
                                    </a> </li>
                                <?php if(!empty($editRecord[0]['id'])){ ?>
                                    <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('label_analytics_code');?>" data-toggle="tab" href="#analytics_code">
                                        <?=$this->lang->line('label_analytics_code');?>
                                        </a> </li>
                                    <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('label_carousels');?>" data-toggle="tab" href="#carousels">
                                        <?=$this->lang->line('label_carousels');?>
                                        </a> </li>
                                    <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('nearbyplace_label');?>" data-toggle="tab" href="#nearby_area">
                                        <?=$this->lang->line('nearbyplace_label')?>
                                        </a> </li>
                                    <li <?php if($tabid == 5){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('child_home_page_meta_data');?>" data-toggle="tab" href="#home_page_meta_data">
                                        <?=$this->lang->line('child_home_page_meta_data')?>
                                        </a> </li>
                                    <li <?php if($tabid == 6){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('child_footer_management');?>" data-toggle="tab" href="#footer_management">
                                        <?=$this->lang->line('child_footer_management')?>
                                        </a> </li>
                                    <li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('child_policy_pages');?>" data-toggle="tab" href="#policy_pages">
                                        <?=$this->lang->line('child_policy_pages')?>
                                        </a> </li>
                                        
                                <?php } ?>
                            </ul>

                            <div class="tab-content" id="myTab1Content">
                                <!-- Tab 1 - Website -->
                                <div <?php if($tabid == '' || $tabid == 1 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="website" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>1" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate >
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> Domain Name <span class="val">*</span></label>
                                                    <?php if(!empty($editRecord[0]['domain'])) { ?>
                                                    <label for="text-input"><?=$editRecord[0]['domain'];?></label>
                                                    <input id="domain" name="domain" type="hidden" class="form-control parsley-validated" data-type="map_url" data-required="true" value="<?=$editRecord[0]['domain'];?>">
                                                    <?php } else { ?>
                                                    <input id="domain" name="domain" class="form-control parsley-validated" data-type="map_url" data-required="true" <?php if(!empty($editRecord[0]['domain'])){ ?> value="<?=$editRecord[0]['domain'];?>" readonly="readonly" <?php } ?> data-parsley-type="map_url"  placeholder="e.g. http://xyz.com">
                                                    <?php } ?>
                                                    <input name="old_admin_id" type="hidden" value="<?php if(!empty($editRecord[0]['lw_admin_id'])){ echo $editRecord[0]['lw_admin_id'];}?>">
                                                </div>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> Please enter following dns in your admin panel : <br>
                                                    <?php echo $this->config->item('ns_record_details');?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> Slug <span class="val">*</span></label>
                                                    <input id="slug" name="slug" class="form-control parsley-validated" placeholder="Slug" data-required="true" value="<?=!empty($editRecord[0]['slug'])?$editRecord[0]['slug']:''?>" onkeyup="create_slug(this.value);">
                                                    <label for="text-input"> Note : When website is in Draft mode, Slug would be displayed as subdomain. For e.g. slug.website.com </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <?php if(!empty($editRecord[0]['lw_admin_id'])) { ?>
                                                    <label for="text-input">Selected Admin <span class="val">*</span></label>
                                                    <label for="text-input"><?=$editRecord[0]['admin_name']?> (<?=$editRecord[0]['admin_email_id']?>)</label>
                                                    <input id="lw_admin_id" name="lw_admin_id" type="hidden" class="form-control parsley-validated" data-required="true" value="<?=$editRecord[0]['lw_admin_id'];?>">
                                                    <?php } else { ?>
                                                        <label for="text-input">Select Admin <span class="val">*</span></label>
                                                        <select class="form-control parsley-validated" id='lw_admin_id' name="lw_admin_id" data-required="true" onchange="get_mls()" <?php if(!empty($editRecord) && $editRecord[0]['status'] != 2) echo 'disabled=disabled'; ?> >    <option value="" > -- Select Admin -- </option>
                                                            <?php foreach($admin_name as $row) { ?>
                                                            <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['lw_admin_id'])) { if($editRecord[0]['lw_admin_id'] == $row['id']){echo "selected";} } ?>>
                                                            <?=$row['admin_name']?> (<?=$row['email_id']?>)
                                                            </option>
                                                            <?php }?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <?php if(!empty($editRecord[0]['mls_id'])) { ?>
                                                    <label for="text-input"> Selected MLS <span class="val">*</span></label>
                                                    <label for="text-input"><?=$editRecord[0]['mls_name']?></label>
                                                    <input id="mls_id" name="mls_id" type="hidden" class="form-control parsley-validated" data-required="true" value="<?=$editRecord[0]['mls_id'];?>">
                                                    <?php } else { ?>
                                                        <label for="text-input"> Select MLS <span class="val">*</span></label>                       
                                                        <select class="form-control parsley-validated" id='mls_id' name="mls_id" data-required="true" <?php if(!empty($editRecord[0]['mls_id'])) echo 'disabled=disabled'; ?> >
                                                            <option value="" > -- Select MLS -- </option>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> Status <span class="val">*</span></label>						  
                                                    <select class="form-control parsley-validated" id='status' name="status" data-required="true">
<!--                                                        <option value="" > -- Select status -- </option>-->
                                                        <?php if(empty($editRecord) || (!empty($editRecord) && ($editRecord[0]['status'] == 2))) { ?>
                                                            <option value="2" <?php if(isset($editRecord[0]['status']) && $editRecord[0]['status'] == 2){ echo 'selected=selected'; } ?> > Draft </option>
                                                            <option value="1" <?php if(isset($editRecord[0]['status']) && $editRecord[0]['status'] == 1){ echo 'selected=selected'; } ?> > Publish </option>
                                                        <?php } else { ?>
                                                            <option value="1" <?php if(isset($editRecord[0]['status']) && $editRecord[0]['status'] == 1){ echo 'selected=selected'; } ?> > Publish </option>
                                                            <option value="0" <?php if(isset($editRecord[0]['status']) && $editRecord[0]['status'] == 0){ echo 'selected=selected'; } ?> > Suspend </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="themebox">
                                                      <h2>Theme1</h2>
                                                      <img class="img-responsive " src="<?=base_url('images/theme.jpg')?>" alt="img">
                                                      <div class="col-lg-12 mrg13photo text-center"> 
                                                        <a href="#basicModal" data-toggle="modal" class="btn btn-secondary" id="theme1" onclick="theme_preview(this.id);">Preview</a> 
                                                        <a class="btn btn-secondary active_class_1 <?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 1) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('1');"><?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 1) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-4 col-md-4">
                                                    <div class="themebox">
                                                      <h2>Theme2</h2>
                                                      <img class="img-responsive " src="<?=base_url('images/no_image.jpg')?>" alt="img">
                                                      <div class="col-lg-12 mrg13photo text-center"> 
                                                        <a href="#basicModal" data-toggle="modal" class="btn btn-secondary" id="theme2" onclick="theme_preview(this.id);">Preview</a> 
                                                        <a class="btn btn-secondary active_class_2 <?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 2) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('2');"><?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 2) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="themebox">
                                                      <h2>Theme3</h2>
                                                      <img class="img-responsive " src="<?=base_url('images/no_image.jpg')?>" alt="img">
                                                      <div class="col-lg-12 mrg13photo text-center"> 
                                                        <a href="#basicModal" data-toggle="modal" class="btn btn-secondary" id="theme3" onclick="theme_preview(this.id);">Preview</a> 
                                                        <a class="btn btn-secondary active_class_3 <?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 3) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('3');"><?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 3) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 clear">
                                                    <div class="themebox">
                                                      <h2>Theme4</h2>
                                                      <img class="img-responsive " src="<?=base_url('images/no_image.jpg')?>" alt="img">
                                                      <div class="col-lg-12 mrg13photo text-center"> 
                                                        <a href="#basicModal" data-toggle="modal" class="btn btn-secondary" id="theme4" onclick="theme_preview(this.id);">Preview</a> 
                                                        <a class="btn btn-secondary active_class_4 <?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 4) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('4');"><?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 4) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="themebox">
                                                      <h2>Theme5</h2>
                                                      <img class="img-responsive " src="<?=base_url('images/no_image.jpg')?>" alt="img">
                                                      <div class="col-lg-12 mrg13photo text-center"> 
                                                        <a href="#basicModal" data-toggle="modal" class="btn btn-secondary" id="theme5" onclick="theme_preview(this.id);">Preview</a> 
                                                        <a class="btn btn-secondary active_class_5 <?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 5) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('5');"><?php if(!empty($editRecord[0]['selected_theme']) && $editRecord[0]['selected_theme'] == 5) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                                                    </div>
                                                </div> -->
                                                <input type ="hidden" name="selected_theme" id="selected_theme">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> <?=$this->lang->line('contact_add_fname');?> <span class="val">*</span></label>
                                                    <input id="first_name" name="first_name" class="form-control parsley-validated"  data-required="true" value="<?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name'];}?>" placeholder="e.g. John">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> <?=$this->lang->line('contact_add_lname');?> <span class="val">*</span></label>
                                                    <input id="last_name" name="last_name" class="form-control parsley-validated" data-required="true" value="<?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name'];}?>" placeholder="e.g. Jane">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> <?=$this->lang->line('common_label_email');?> <span class="val">*</span></label>
                                                    <?php if(!empty($editRecord[0]['email_id'])) { ?>
                                                    <label for="text-input"><?=$editRecord[0]['email_id'];?></label>
                                                    <input id="txt_email_id" name="txt_email_id" type="hidden" class="form-control parsley-validated" type="email" data-required="true" value="<?=$editRecord[0]['email_id'];?>">
                                                    <?php } else { ?>
                                                    <input data-parsley-type="email" placeholder="e.g. abc@gmail.com" id="txt_email_id" name="txt_email_id" class="form-control parsley-validated" type="email" data-required="true" <?php if(!empty($editRecord[0]['email_id'])) { ?>value="<?=$editRecord[0]['email_id']?>" readonly="readonly" <?php } ?> >
                                                    <?php } ?>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> <?=$this->lang->line('common_label_password');?><?php if(!isset($editRecord)) {?><span class="val">*</span><?php }?></label>
                                                    <input data-minlength="6" type="password" placeholder="******" name="password" id="password" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true"<?php }?>>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"> <?=$this->lang->line('common_label_cpassword');?><?php if(!isset($editRecord)) {?><span class="val">*</span><?php }?></label>
                                                    <input type="password" placeholder="******" name="cpassword" id="cpassword" class="form-control parsley-validated" <?php if(!isset($editRecord)) {?> data-required="true" <?php }?> data-equalto="#password">
                                                </div>
                                            </div>

                                            
                                            <?php /*
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('livechat_script')?></label>
                                                    <textarea id="zopim_livechat_script" name="zopim_livechat_script" class="form-control parsley-validated"  placeholder="Zopim Livechat Script"><?php if(!empty($editRecord[0]['zopim_livechat_script'])){ echo $editRecord[0]['zopim_livechat_script'];}?></textarea>
                                                </div>
                                            </div>
                                            <?php */ ?>
                                        </div>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="1" />
                                            <input type="submit" title="Save" class="btn btn-secondary-green" value="Save" id="submit" name="submitbtn" onclick="return check_domain('0')" />
                                            <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" name="submitbtn"  onclick="return check_domain('0')" />
                                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                                <!-- Tab 2 - Analytics Code -->
                                <div <?php if($tabid == 2 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="analytics_code" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>2" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate >
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('label_google_analytics_code')?></label>
                                                    <textarea id="google_analytics_code" name="google_analytics_code" class="form-control parsley-validated"  placeholder="<?=$this->lang->line('label_google_analytics_code')?>"><?php if(!empty($editRecord[0]['google_analytics_code'])){ echo $editRecord[0]['google_analytics_code'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5>Google AdWords Conversion Tracking Code</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Registration</label>
                                                    <textarea id="adword_registration" name="adword_registration" class="form-control parsley-validated"  placeholder="Google Adword Registration Code"><?php if(!empty($editRecord[0]['adword_registration'])){ echo $editRecord[0]['adword_registration'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Login</label>
                                                    <textarea id="adword_login" name="adword_login" class="form-control parsley-validated"  placeholder="Google Adword Login Code"><?php if(!empty($editRecord[0]['adword_login'])){ echo $editRecord[0]['adword_login'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Property Valuation</label>
                                                    <textarea id="adword_property_valuation" name="adword_property_valuation" class="form-control parsley-validated"  placeholder="Google Adword Property Valuation Code"><?php if(!empty($editRecord[0]['adword_property_valuation'])){ echo $editRecord[0]['adword_property_valuation'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Force Registration when the user views more than two property images</label>
                                                    <textarea id="adword_reg_two_property" name="adword_reg_two_property" class="form-control parsley-validated"  placeholder="Google Adword Code"><?php if(!empty($editRecord[0]['adword_reg_two_property'])){ echo $editRecord[0]['adword_reg_two_property'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Force Registration when user selects detail property view</label>
                                                    <textarea id="adword_detail_property" name="adword_detail_property" class="form-control parsley-validated"  placeholder="Google Adword Code"><?php if(!empty($editRecord[0]['adword_detail_property'])){ echo $editRecord[0]['adword_detail_property'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Force Registration when the user selects a new property page in the grid</label>
                                                    <textarea id="adword_new_property" name="adword_new_property" class="form-control parsley-validated"  placeholder="Google Adword Code"><?php if(!empty($editRecord[0]['adword_new_property'])){ echo $editRecord[0]['adword_new_property'];}?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="2" />
                                            <input type="submit" title="Save" class="btn btn-secondary-green" value="Save" id="submit" name="submitbtn" onclick="showloading('<?=$viewname?>2');"/>
                                            <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" name="submitbtn" onclick="showloading('<?=$viewname?>2');" />
                                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Tab 3 - Carousels -->
                                <div <?php if($tabid == 3 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="carousels" > 
                                    <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                                        <div class="row dt-rt">
                                            <?php if(!empty($msg)){?>
                                                <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row dt-rt">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-11">
                                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                                    <label>
                                                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                                        <input type="text" name="searchtext" title="Search Text"id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                                                        <button class="btn btn-secondary howler" title="Search Carousels" data-type="danger" onclick="contact_search('changesearch');">Search</button>
                                                        <button class="btn btn-secondary howler" title="View All Carousels" data-type="danger" onclick="clearfilter_contact();">View All</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row dt-rt">
                                            <div class="col-sm-6">
                                                <button class="btn btn-danger howler" title="Delete Carousels" data-type="danger" onclick="deletepopup1('0');">Delete</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <?php $edit_id = !empty($editRecord[0]['id'])?$editRecord[0]['id']:0; ?>
                                                <input type="hidden" value="<?=$edit_id?>" id="edited_id">
                                                <a title="<?=$this->lang->line('label_carousels_add');?>" class="btn pull-right btn-secondary-green howler" href="<?=base_url('superadmin/'.$viewname.'/add_carousels/'.$edit_id);?>"><?=$this->lang->line('label_carousels_add');?></a>
                                            </div>
                                        </div>
                                        <div id="common_div">
                                            <?=$this->load->view('superadmin/'.$viewname.'/carousels_ajax_list')?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tab 4 - Nearby Places -->
                                <div <?php if($tabid == 4 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="nearby_area" > 
                                    <div id="common_area_div">
                                        <?=$this->load->view('superadmin/'.$viewname.'/nearby_area_list')?>
                                    </div>
                                </div>
                                
                                <!-- Tab 5 - Home Page Meta Data -->
                                <div <?php if($tabid == 5 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home_page_meta_data" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>5" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate >
                                        <div class="row dt-rt">
                                            <?php if(!empty($msg5)){?>
                                                <div class="col-sm-12 text-center" id="div_msg5"><?php echo '<label class="error">'.urldecode ($msg5).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_title')?></label>
                                                    <input id="meta_data_title" name="meta_data_title" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['meta_data_title'])){ echo $editRecord[0]['meta_data_title'];}?>" placeholder="Meta Title">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_desc')?></label>
                                                    <textarea id="meta_data_description" name="meta_data_description" class="form-control parsley-validated" maxlength="152" placeholder="Meta Description"><?php if(!empty($editRecord[0]['meta_data_description'])){ echo $editRecord[0]['meta_data_description'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_keywords')?></label>
                                                    <input id="meta_data_keywords" name="meta_data_keywords" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['meta_data_keywords'])){ echo $editRecord[0]['meta_data_keywords'];}?>" placeholder="Meta Keywords">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('child_meta_robot_follow')?></label>
                                                    <label class="lblwidthac"><input type='radio' name="meta_data_robot" value="1" <?php if(!empty($editRecord[0]['meta_data_robot'])){ echo "checked='checked'";}?> > Follow</label>
                                                    <label class="lblwidthac"><input type='radio' name="meta_data_robot" value="0" <?php if(empty($editRecord[0]['meta_data_robot'])){ echo "checked='checked'";}?> > No Follow</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('child_meta_default_city')?></label>
                                                    <input id="meta_data_default_city" name="meta_data_default_city" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['meta_data_default_city'])){ echo $editRecord[0]['meta_data_default_city'];}?>" placeholder="Meta Data Default City">
                                                </div>
                                            </div>
                                        </div>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="5" />
                                            <input type="submit" title="Save" class="btn btn-secondary-green" value="Save" id="submit" name="submitbtn" onclick="showloading('<?=$viewname?>5');"/>
                                            <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" name="submitbtn" onclick="showloading('<?=$viewname?>5');"/>
                                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Tab 6 - Footer Management -->
                                <div <?php if($tabid == 6 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="footer_management" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>6" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $viewname.'/'.'insert_footer' ?>" novalidate >
                                        <div class="row dt-rt">
                                            <?php if(!empty($msg6)){?>
                                                <div class="col-sm-12 text-center" id="div_msg6"><?php echo '<label class="error">'.urldecode ($msg6).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('child_footer_mls_disclaimer')?></label>
                                                    <textarea id="footer_mls_disclaimer" name="footer_mls_disclaimer" class="form-control parsley-validated" placeholder="Footer MLS Disclaimer"><?php if(!empty($editRecord[0]['footer_mls_disclaimer'])){ echo $editRecord[0]['footer_mls_disclaimer'];}?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('child_footer_copyright')?></label>
                                                    <input id="copyright_statement" name="copyright_statement" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['copyright_statement'])){ echo $editRecord[0]['copyright_statement'];}?>" placeholder="Copyright Statement">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Footer Links</label>
                                                    <div class="col-sm-6 form-group">
                                                        <span>Link 1</span>
                                                        <input id="link_1" name="link_1" class="form-control parsley-validated" placeholder="Title" value="<?php if(!empty($footer_links[0]['link_1'])){ echo $footer_links[0]['link_1'];}?>">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <input id="page_type_1_page" name="page_type_1" type="radio" class="parsley-validated" value="0" <?php if(isset($footer_links[0]['page_type_1']) && $footer_links[0]['page_type_1']==0) { echo 'checked';}?>> 
                                                        Page<select id="page_1" name="page_1" class="form-control parsley-validated" type="dropdown" onchange="document.getElementById('page_type_1_page').checked = true;">
                                                            <option value="">Select Page</option>
                                                            <?php
                                                                foreach($cms_list as $value)
                                                                {
                                                                    echo "<option value='".$value['slug']."' ";
                                                                    if(!empty($footer_links[0]['page_1']))
                                                                    {
                                                                        if($footer_links[0]['page_1'] == $value['slug'])
                                                                            echo "selected";
                                                                    }
                                                                    echo ">".$value['title']."</option>";
                                                                }
                                                            ?>
                                                        </select><br/>
                                                        <input id="page_type_1_url" name="page_type_1" type="radio" class="parsley-validated" value="1" <?php if(!empty($footer_links[0]['page_type_1']) && $footer_links[0]['page_type_1']==1) { echo 'checked';}?>>
                                                        URL<input id="url_1" name="url_1" class="form-control parsley-validated" value="<?php if(!empty($footer_links[0]['url_1'])){ echo $footer_links[0]['url_1'];}?>" data-type="map_url" data-parsley-type="map_url" onfocus="document.getElementById('page_type_1_url').checked = true;">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <span>Link 2</span>
                                                        <input id="link_2" name="link_2" class="form-control parsley-validated" placeholder="Title" value="<?php if(!empty($footer_links[0]['link_2'])){ echo $footer_links[0]['link_2'];}?>">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <input id="page_type_2_page" name="page_type_2" type="radio" class="parsley-validated" value="0" <?php if(isset($footer_links[0]['page_type_2']) && $footer_links[0]['page_type_2']==0) { echo 'checked';}?>>
                                                        Page<select id="page_2" name="page_2" class="form-control parsley-validated" type="dropdown" onchange="document.getElementById('page_type_2_page').checked = true;">
                                                            <option value="">Select Page</option>
                                                            <?php
                                                                foreach($cms_list as $value)
                                                                {
                                                                    echo "<option value='".$value['slug']."' ";
                                                                    if(!empty($footer_links[0]['page_2']))
                                                                    {
                                                                        if($footer_links[0]['page_2'] == $value['slug'])
                                                                            echo "selected";
                                                                    }
                                                                    echo ">".$value['title']."</option>";
                                                                }
                                                            ?>
                                                        </select><br/>
                                                        <input id="page_type_2_url" name="page_type_2" type="radio" class="parsley-validated" value="1" <?php if(!empty($footer_links[0]['page_type_2']) && $footer_links[0]['page_type_2']==1) { echo 'checked';}?>>
                                                        URL<input id="url_2" name="url_2" class="form-control parsley-validated" value="<?php if(!empty($footer_links[0]['url_2'])){ echo $footer_links[0]['url_2'];}?>" data-type="map_url" data-parsley-type="map_url" onfocus="document.getElementById('page_type_2_url').checked = true;">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <span>Link 3</span>
                                                        <input id="link_3" name="link_3" class="form-control parsley-validated" placeholder="Title" value="<?php if(!empty($footer_links[0]['link_3'])){ echo $footer_links[0]['link_3'];}?>">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <input id="page_type_3_page" name="page_type_3" type="radio" class="parsley-validated" value="0" <?php if(isset($footer_links[0]['page_type_3']) && $footer_links[0]['page_type_3']==0) { echo 'checked';}?>>
                                                        Page<select id="page_3" name="page_3" class="form-control parsley-validated" type="dropdown" onchange="document.getElementById('page_type_3_page').checked = true;">
                                                            <option value="">Select Page</option>
                                                            <?php
                                                                foreach($cms_list as $value)
                                                                {
                                                                    echo "<option value='".$value['slug']."' ";
                                                                    if(!empty($footer_links[0]['page_3']))
                                                                    {
                                                                        if($footer_links[0]['page_3'] == $value['slug'])
                                                                            echo "selected";
                                                                    }
                                                                    echo ">".$value['title']."</option>";
                                                                }
                                                            ?>
                                                        </select><br/>
                                                        <input id="page_type_3_url" name="page_type_3" type="radio" class="parsley-validated" value="1" <?php if(!empty($footer_links[0]['page_type_3']) && $footer_links[0]['page_type_3']==1) { echo 'checked';}?>>
                                                        URL<input id="url_3" name="url_3" class="form-control parsley-validated" value="<?php if(!empty($footer_links[0]['url_3'])){ echo $footer_links[0]['url_3'];}?>" data-type="map_url" data-parsley-type="map_url" onfocus="document.getElementById('page_type_3_url').checked = true;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <input id="footer_id" name="footer_id" type="hidden" value="<?php if(!empty($footer_links[0]['id'])){ echo $footer_links[0]['id']; }?>">
                                        <input id="child_record_id" name="child_record_id" type="hidden" value="<?php if(!empty($footer_links[0]['child_record_id'])){ echo $footer_links[0]['child_record_id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="6" />
                                            <input type="submit" title="Save" class="btn btn-secondary-green" value="Save" id="submit" name="submitbtn" onclick="showloading('<?=$viewname?>6');"/>
                                            <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" name="submitbtn" onclick="showloading('<?=$viewname?>6');"/>
                                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                                    
                                <div <?php if($tabid == 7 ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="policy_pages" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>7" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate >
                                        <div class="row dt-rt">
                                            <?php if(!empty($msg7)){?>
                                                <div class="col-sm-12 text-center" id="div_msg6"><?php echo '<label class="error">'.urldecode ($msg7).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input">
                                                        Terms of Use
                                                    </label>
                                                    <input id="terms_of_use_id" name="terms_of_use_id" type="hidden" value="<?php if(!empty($policy_terms[0]['id'])){ echo $policy_terms[0]['id']; }?>">
                                                    <textarea name="terms_of_use" id="terms_of_use" ><?=!empty($policy_terms[0]['description'])?$policy_terms[0]['description']:''; ?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('terms_of_use',
                                                        {
                                                            fullPage : false,
                                                            baseHref : '<?=$this->config->item('ck_editor_path')?>',
                                                            filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
                                                            filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
                                                        }, {width: 200});                                                       
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input">
                                                        Privacy Policy
                                                    </label>
                                                    <input id="privacy_policy_id" name="privacy_policy_id" type="hidden" value="<?php if(!empty($policy_privacy[0]['id'])){ echo $policy_privacy[0]['id']; }?>">
                                                    <textarea name="privacy_policy" id="privacy_policy" ><?=!empty($policy_privacy[0]['description'])?$policy_privacy[0]['description']:''; ?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('privacy_policy',
                                                        {
                                                            fullPage : false,
                                                            baseHref : '<?=$this->config->item('ck_editor_path')?>',
                                                            filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
                                                            filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
                                                        }, {width: 200});                                                       
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input">
                                                        Digital Millennium Copyright Act
                                                    </label>
                                                    <input id="dmca_id" name="dmca_id" type="hidden" value="<?php if(!empty($policy_dmca[0]['id'])){ echo $policy_dmca[0]['id']; }?>">
                                                    <textarea name="dmca" id="dmca" ><?=!empty($policy_dmca[0]['description'])?$policy_dmca[0]['description']:''; ?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('dmca',
                                                        {
                                                            fullPage : false,
                                                            baseHref : '<?=$this->config->item('ck_editor_path')?>',
                                                            filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
                                                            filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
                                                        }, {width: 200});                                                       
                                                    </script>
                                                </div>
                                            </div>

                                            <h5>MLS Disclaimer</h5>
                                            <div class="row">
                                                <div class="col-sm-5 form-group">
                                                  <label for="text-input">
                                                    MLS Logo
                                                  </label>
                                                  <div class="browse"> <span class="text"> </span>
                                                    <div class="browse_btn">
                                                      <div class="file_input_div">
                                                        <input type="button" value="Browse" class="file_input_button"  />
                                                        <input type="file" alt="1" name="mls_logo" id="mls_logo" onchange="showimagepreview(this)" class="file_input_hidden"/>
                                                      </div>
                                                    </div>
                                                    <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
                                                  </div>
                                                  <p> <span class="txt">&nbsp;</span>
                                                    <?php  if(!empty($editRecord[0]['mls_logo']) && file_exists($this->config->item('admin_big_img_path').$editRecord[0]['mls_logo'])){
                                                    ?>
                                                    <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('admin_upload_img_small')?>/<?=(!empty($editRecord[0]['mls_logo'])?$editRecord[0]['mls_logo']:'');?>"/> <a class="img_delete1" onclick="delete_image('mls_logo','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                                                    <? } else{
                                                    if(!empty($editRecord[0]['mls_logo']) && file_exists($this->config->item('admin_small_img_path').$editRecord[0]['mls_logo'])){
                                                    ?>
                                                                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('admin_small_img_path')?>/<?=(!empty($editRecord[0]['mls_logo'])?$editRecord[0]['mls_logo']:'');?>" /> <a class="img_delete1" onclick="delete_image('mls_logo','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
                                                                <?
                                                    }else{
                                                    ?>
                                                    <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                                                    <? } } ?>
                                                  </p>
                                                  <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
                                                </div>

                                                <div class="col-sm-7 form-group">
                                                    <label for="text-input"><?=$this->lang->line('child_mls_disclaimer')?></label>
                                                    <textarea id="mls_disclaimer" name="mls_disclaimer" class="form-control parsley-validated"  placeholder="MLS Disclaimer"><?php if(!empty($editRecord[0]['mls_disclaimer'])){ echo $editRecord[0]['mls_disclaimer'];}?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="7" />
                                            <input type="submit" title="Save and Finish" class="btn btn-secondary-green" value="Save and Finish" id="submit" onclick="showloading('<?=$viewname?>7');" name="submitbtn"/>
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
$(document).ready(function(){
    $("select#property_type").multiselect({
        multiple: true,
        header: "Property Type",
        noneSelectedText: "Property Type",
        selectedList: 1
    }).multiselectfilter();
    $("#div_msg").fadeOut(4000); 
    $("#div_msg1").fadeOut(4000); 
    $("#div_msg5").fadeOut(4000); 
    $("#div_msg6").fadeOut(4000); 
    
    <?php if(!empty($editRecord)) { ?> 
        active_theme('<?=$editRecord[0]['selected_theme']?>');
    <?php } else { ?> 
        active_theme('1');
    <?php } ?>
});

function showloading(formid)
{
   if ($('#'+formid).parsley().isValid()) {
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
            url: '<?=$this->config->item('superadmin_base_url').$viewname."/delete_image";?>',
            beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
            },
            success:function(msg){
                    if(msg == 'done')
                    {
                        if(divid == 'uploadPreview1')   
                            $('.img_delete1').hide();
                        else if(divid == 'uploadPreview2')  
                            $('.img_delete2').hide();
                        $('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
                  }
                  $.unblockUI();
                }//succsess
            });//ajax
            
            }},'No' : {'class'  : 'special'}}});
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
            $.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'   : {'class'  : 'btn_center alert_ok'}}});
            return false;
        }   
    }
    else
    {
        $.confirm({'title': 'Alert','message': " <strong> Maximum upload size 2 MB "+"<strong></strong>",'buttons': {'ok'   : {'class'  : 'btn_center alert_ok'}}});
            return false;
    }
}

/*
    @Description: Function for Check domain exist or not
    @Author: Sanjay Chabhadiya
    @Input: - domain_name
    @Output: - exist or not
    @Date: 29-04-2015
*/

function check_domain(nosubmit)
{
    <?php if(!empty($editRecord[0]['id'])) { ?>
    if($("#password").val() != $("#cpassword").val())
    {
        $.confirm({'title': 'Alert','message': " <strong> Password doesnt Match </strong>",'buttons': {'ok' : {'class'  : 'btn_center alert_ok'}}});
        return false;
    }
    <?php } ?>
    var domain = $("#domain").val();
    var slug = $("#slug").val();
    var flag = 0;
    //if($("#domain").val().trim() != '' || $("#slug").val().trim() != '')
    if ($('#<?=$viewname?>1').parsley().isValid())
    {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Checking for unique domain or slug...'})
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_domain';?>",
            //dataType: 'json',
            async: false,
            data: {'domain':domain,'slug':slug,'id':<?=!empty($editRecord)?$editRecord[0]['id']:'0'?>},
            success: function(data){
                if(data == '1')
                {
                    flag = 1;
                    $('#domain').focus();
                    $('#submit').attr('disabled','disabled');
                    $.confirm({'title': 'Alert','message': " <strong> This domain already exist! Please select other domain "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function()
                    {
                        $('#domain').focus();
                        $('#submit').removeAttr('disabled');
                        $.unblockUI();
                    }}}});
                    return false;
                }
                else if(data == '2')
                {
                    flag = 1;
                    $('#slug').focus();
                    $('#submit').attr('disabled','disabled');
                    $.confirm({'title': 'Alert','message': " <strong> This slug already exist! Please select other slug "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function()
                    {
                        $('#slug').focus();
                        $('#submit').removeAttr('disabled');
                        $.unblockUI();
                    }}}});
                    return false;
                }
            },error: function(jqXHR, textStatus, errorThrown) {
                $.unblockUI();
            }
        });
    }
    if(flag == 1 || nosubmit == 1){
        flag = 0;
        return false;
    }
    if ($('#<?php echo $viewname?>1').parsley().isValid()) {
       $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Checking for unique domain or slug...'})
    }
}

/*
    @Description: Function for get mls list
    @Author: Sanjay Chabhadiya
    @Input: - admin_id
    @Output: - mls list
    @Date: 29-04-2015
*/

function get_mls()
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/get_mls';?>",
        dataType: 'json',
        async: false,
        data: {'admin_id':$("#lw_admin_id").val()},
        beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
        },
        success: function(result){
            if(result.length)
            {
                $("#mls_id").html("<option value='' > -- Select MLS -- </option>");
                var selectedsubcat = 0;
                <?php if(!empty($editRecord[0]['mls_id'])) { ?>
                            selectedsubcat = '<?=$editRecord[0]['mls_id']?>';
                <?php } ?>
                $.each(result,function(i,item){ 
                    var option = $('<option />');
                    option.attr('value', item.id).text(this.mls_name);
                    if(selectedsubcat == item.id)				
                        option.attr("selected","selected");
                    $('#mls_id').append(option);
                });
            }
            else
                $("#mls_id").html("<option value='' > -- Select MLS -- </option>");
            $.unblockUI();
        }
    });
    return false;
}
<?php if(!empty($editRecord)) { ?> get_mls(); <?php } ?>


function contact_search(allflag)
{
    var uri_segment = $("#uri_segment").val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>superadmin/child_admin/carousels/"+uri_segment,
        data: {
            result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,edit_id:$("#edited_id").val()
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
        if (event.keyCode == 13) {
            contact_search('changesearch');
        }
    });
});
	
function clearfilter_contact()
{
    $("#searchtext").val("");
    contact_search('all');
}

function changepages()
{
    contact_search('');	
}

function applysortfilte_contact(sortfilter,sorttype)
{
    $("#sortfield").val(sortfilter);
    $("#sortby").val(sorttype);
    contact_search('changesorting');
}
	
$('body').on('click','#common_tb a.paginclass_A',function(e){
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),edit_id:$("#edited_id").val()
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div").html(html);
            $.unblockUI();
        }
    });
    return false;
});
		
$('body').on('click','#selecctall',function(e){
    if(this.checked) { // check select status
        $('.mycheckbox').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "mycheckbox"              
        });
    }else{
        $('.mycheckbox').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
        });        
    }
});
	
function delete_all(id,child_record_id)
{
    var myarray = new Array;
    var i=0;
    var boxes = $('input[name="check[]"]:checked');
    $(boxes).each(function(){
        myarray[i]=this.value;
        i++;
    });
    if(id != '0')
    {
        var single_remove_id = id;
    }
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('superadmin_base_url').'child_admin/carousels_delete_all';?>",
        dataType: 'json',
        //async: false,
        data: {'myarray':myarray,'single_remove_id':id,'child_record_id':child_record_id,edit_id:$("#edited_id").val()},
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(data){
            $.unblockUI();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>superadmin/child_admin/carousels/"+$("#edited_id").val()+"/"+data,
                data: {
                    result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),edit_id:$("#edited_id").val()
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
        },error: function(jqXHR, textStatus, errorThrown) {
                $.unblockUI();
        }
    });
}

function deletepopup1(id,name,child_record_id)
{      
    var boxes = $('input[name="check[]"]:checked');
    if(boxes.length == '0' && id== '0')
    {
        $.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        return false;
    }
    if(id == '0')
    {
        var msg = 'Are you sure want to delete record(s)';
    }
    else
    {
        if(name.length > 50)
            name = name.substr(0, 50)+'...';
        var msg = 'Are you sure want to delete '+unescape(name)+'';
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            delete_all(id,child_record_id);
        }},'No'	: {'class'	: 'special'}}
    });
} 

function status_change(status,id,edit_id,child_record_id)
{
    var path='';

    if(status == 0)
    {
        path = "<?= $this->config->item('superadmin_base_url').$viewname; ?>/unpublish_carousels/"+id;
        msg = 'Are you sure want to Unpublish Carousels ';
    }else
    {
        path = "<?= $this->config->item('superadmin_base_url').$viewname; ?>/publish_carousels/"+id;
        msg = 'Are you sure want to  Publish Carousels ';
    }

    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            $.ajax({
                type: "POST",
                url: path,
                dataType: 'json',
                data: {child_record_id:child_record_id,edit_id:edit_id },
                beforeSend: function() {
                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                },
                success: function(data){
                    $.unblockUI();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>superadmin/<?=$viewname?>/carousels/"+edit_id+"/"+data,
                        data: {
                            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),edit_id:edit_id
                        },
                        beforeSend: function() {
                            $('#common_div').block({ message: 'Loading...' }); 
                        },
                        success: function(html){
                            $("#common_div").html(html);
                            //$("#reload_td"+id).html(html);
                            $('#common_div').unblock(); 
                        }
                    });
                    return false;
                },error: function(jqXHR, textStatus, errorThrown) {
                    $.unblockUI();
                }
            });

        }},'No'	: {'class'	: 'special'}}
    });
}

/*For Nearby Area Tab*/
$(function() {
    $('#area_form_<?=$viewname?>').parsley().destroy();
    $('#area_form_<?=$viewname?>').parsley()
    var scntDiv = $('#p_scents');
    var i = $('#p_scents p').size();
    $('body').on('click', '#addarea', function(){
        i++;
        $('<div class="form-group row">\n\
                                <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="location_text[]" id="location_text[]" placeholder="e.g. New York"/></div>\n\
                                <div class="col-sm-4"><input type="text" class="form-control parsley-validated" name="location_url[]" id="location_url[]" data-type="map_url" data-parsley-type="map_url"  placeholder="e.g. http://xyz.com" /></div>\n\
                                <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="order_of_display[]" id="order_of_display[]" onkeypress="return isNumberKey(event);" placeholder="e.g. 1"/></div>\n\
                                <div class="col-sm-2"><a href="javascript:void(0);" class="btn btn-xs btn-primary" id="remarea"><i class="fa fa-times"></i> </a></div>\n\
                            </div>').appendTo(scntDiv);
        // i++;
        $('#area_form_<?=$viewname?>').parsley().destroy();
        $('#area_form_<?=$viewname?>').parsley();
        return false;
    });

    $('body').on('click', '#remarea', function(){
        if( i > 0 ) {
            $(this).closest('div .row').remove();
            $('#area_form_<?=$viewname?>').parsley().destroy();
            $('#area_form_<?=$viewname?>').parsley();
            //i--;
        }
        return false;
    });
});

// Delete popup for near by area
function deletepopup(id,name,child_record_id)
{      
    if(id == '0')
    {
        var msg = 'Are you sure want to delete record(s)';
    }
    else
    {
        if(name.length > 50)
            name = name.substr(0, 50)+'...';
        var msg = 'Are you sure want to delete '+unescape(name)+'';
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            delete_area(id,child_record_id);
        }},'No'	: {'class'	: 'special'}}
    });
}

function delete_area(id,child_record_id)
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('superadmin_base_url').'child_admin/nearby_area_delete';?>",
        //async: false,
        data: {'single_remove_id':id,child_record_id:child_record_id,edit_id:$("#editarea_id").val()},
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(data){
            $.unblockUI();
            var mytr = $('.remove_selected_area_'+id).closest("div .nearby_close_"+id);
            mytr.remove();
            /*$.ajax({
                type: "POST",
                url: "<?php echo base_url();?>superadmin/child_admin/nearbyarea/"+$("#editarea_id").val()+"/4",
                data: {
                    result_type:'ajax',edit_id:$("#editarea_id").val()
                },
                beforeSend: function() {
                    $('#common_area_div').block({ message: 'Loading...' }); 
                },
                success: function(html){
                    $("#common_area_div").html(html);
                    $("#flash").show();
                    $("#flash").fadeOut(3000).html('<span class="load">Record Deleted Successfully..</span>');
                    $('#common_area_div').unblock(); 
                }
            });*/
            return false;
        },error: function(jqXHR, textStatus, errorThrown) {
                $.unblockUI();
        }
    });
}

/*
    @Description: Function for update area
    @Author     : Sanjay Moghariya
    @Input      : Id
    @Output     : Update record
    @Date       : 29-04-2015
*/
function getsubmit(id)
{
    if ($('#area_form_<?=$viewname?>').parsley().isValid()) {
        var location_text = $('#loc_'+id).val();
        var location_url = $('#url_'+id).val();
        var order_of_display = $('#order_'+id).val();
        var child_record_id = $('#child_record_id_'+id).val();
        if(location_text == '' && id=='' && location_url == '')
        {
            alert("Enter all details..");
            $("#loc_"+id).focus();
        }
        else
        {
            //$("#flash").show();
            //$("#flash").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_nearbyarea',
                data: { location_text:location_text,location_url:location_url,order_of_display:order_of_display,id:id,edit_id:$("#edited_id").val(),child_record_id:child_record_id },
                cache: true,
                success: function(html)
                {
                    //$("#show").after(html);
                    //$("#email").focus();
                    $.unblockUI();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>superadmin/child_admin/nearbyarea/"+$("#editarea_id").val()+"/4",
                        data: {
                            result_type:'ajax',edit_id:$("#editarea_id").val()
                        },
                        beforeSend: function() {
                            $('#common_area_div').block({ message: 'Loading...' }); 
                        },
                        success: function(html){
                            $("#common_area_div").html(html);
                            $("#flash").show();
                            $("#flash").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
                            $('#common_area_div').unblock(); 
                        }
                    });
                }  
            });
        }
        return false;
    } else {
        var location_text = $('#loc_'+id).val();
        var location_url = $('#url_'+id).val();
        var order_of_display = $('#order_'+id).val();
        if(location_text.trim() == '')
            $.confirm({'title': 'Alert','message': " <strong> Please enter Location Text. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        else if(order_of_display.trim() == '')
            $.confirm({'title': 'Alert','message': " <strong> Please enter Order. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        else
            $.confirm({'title': 'Alert','message': " <strong> Please enter valid URL. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        return false;
    }
}

/*
    @Description: Function for Select theme
    @Author     : Sanjay Moghariya
    @Input      : domain id, theme id
    @Output     : 
    @Date       : 12-05-2015
*/
function active_theme(theme_id)
{
    $('#selected_theme').val(theme_id);
    for(i=1;i<=5;i++)
    {
        if(i == theme_id)
        {
            $(".active_class_"+theme_id).html('Activated');
            $(".active_class_"+theme_id).addClass('btn-warning');
        }
        else
        {
            $(".active_class_"+i).html('Activate');
            $(".active_class_"+i).removeClass('btn-warning');
        }
    }
    //$(".added_contacts_list").html(html);
    //$('.added_contacts_list').unblock(); 
            
}

function theme_preview(theme)
{
    if(theme == 'theme1')
        $('.modal-body').html('<img src="<?=base_url("images/theme1_full.png")?>" style="width:100%">');
    else
        $('.modal-body').html('<img src="<?=base_url("images/no_image.jpg")?>" style="width:100%">');
}

function create_slug(pagetitle)
{
    var string = pagetitle.toLowerCase();
    //Make alphanumeric (removes all other characters)
    string = string.replace(/[^a-zA-Z 0-9_\s-]+/g,'');
    //Clean up multiple dashes or whitespaces
    string = string.replace(/[\s-]+/g,' ');
    //Convert whitespaces and underscore to dash
    string = string.replace(/[\s_]+/g,'-');
    $('#slug').val(string);
}

</script>
