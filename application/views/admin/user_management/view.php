<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 

$viewname = $this->router->uri->segments[2];

if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;

$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
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
            <h3><i class="fa fa-tasks"></i>
              <?= $this->lang->line('user_view_table_head');?></h3>
			  <span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span> 
            
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
                <div <?php if($tabid == '' || $tabid == 1){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home">
                <div class="col-lg-7 col-xs-12">
                 <div class="add_emailtype">
                  <div class="row lftrgt">
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?=$this->lang->line('contact_add_prefix');?>
                      </label>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label for="text-input"><?php if(!empty($editRecord[0]['prefix'])){echo $editRecord[0]['prefix'];}else{ echo "-"; }?></label>
                    </div>
                  </div>
                  <div class="row lftrgt">
                    <div class="col-sm-3 form-group">
                      <label for="text-input">
                        <?=$this->lang->line('contact_add_fname');?>
                      </label>
                    </div>
                    <div class="col-sm-3">
                      <label for="text-input"><?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }else{ echo "-"; }?></label>
                    </div>
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?php if(!empty($editRecord[0]['middle_name'])){ echo $editRecord[0]['middle_name']; }else{ echo "-"; }?>
                      </label>
                    </div>
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name']; }else{ echo "-"; }?>
                      </label>
                    </div>
                  </div>
                  <div class="row lftrgt">
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?=$this->lang->line('contact_add_company');?>
                      </label>
                      </div>
                    <div class="col-sm-3">
                      <label for="text-input"><?php if(!empty($editRecord[0]['company_name'])){ echo $editRecord[0]['company_name']; }else{ echo "-"; }?></label>
                    </div>
                  </div>
                  <div class="row lftrgt">
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?=$this->lang->line('contact_add_title1');?>
                      </label>
                    </div>
                    <div class="col-sm-5">
                      <label for="text-input"><?php if(!empty($editRecord[0]['company_post'])){ echo $editRecord[0]['company_post']; }else{ echo "-"; }?></label>
                    </div>
                  </div>
                  
                  <div class="row lftrgt">
                    <div class="col-sm-3">
                      <label for="text-input">
                        <?=$this->lang->line('user_add_user_roles');?>
                      </label>
                    </div>
                    <div class="col-sm-5">
                  
                      <label for="text-input"><?php if(!empty($user_type[0]['name'])){ echo $user_type[0]['name']; }else{ echo "-"; }?></label>
                    </div>
                  </div>
                  
                  
                  <div class="row form-group lftrgt">
                    </div>
                  </div>
                  
                  <div class="add_email_address_div add_emailtype">
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
                    <div class="col-sm-2 text-center text-center1 icheck-input-new">
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
                      <div class="col-sm-4">
                        <?php if(!empty($email_type)){
								foreach($email_type as $row){?>
                        <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){ echo $row['name'];}?>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      <div class="col-sm-4 form-group">
                        <?php if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }else{ echo "-";}?>
                      </div>
                      <div class="col-sm-2 text-center text-center1 icheck-input-new">
                        <div class="form-group"> 
                          <!--<label><?=$this->lang->line('common_default');?></label>-->
                          <div class="">
                            <label class="">
                            <div class="">
							 <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true">
							</div>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class=""> </div>
                      </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
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
                        <?=$this->lang->line('contact_add_phone_no');?>
                      </label>
                    </div>
                    <div class="col-sm-2 text-center text-center1 icheck-input-new">
                      <div class="">
                        <label>
                          <?=$this->lang->line('common_default');?>
                        </label>
                      </div>
                    </div>
                    <div class="col-sm-1 text-center  icheck-input-new">
                      <div class="">
                        <label>&nbsp;</label>
                        <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>--> 
                      </div>
                    </div>
                    <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>
                    <div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                      <div class="col-sm-4">
                        <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
                        <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){ echo $row['name']; }?>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      <div class="col-sm-4">
                        <?php if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }else{ echo "-";}?>
                      </div>
                      <div class="col-sm-2 text-center text-center1 icheck-input-new">
                        <div class=""> 
                          <!--<label><?=$this->lang->line('common_default');?></label>-->
                          <div class="">
                            <label class="">
                            <div class="">
							 <input type="radio" class=""   name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true" >
							</div>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-1 text-center icheck-input-new">
                        <div class=""> 
                          <!--<label>&nbsp;</label>--> 
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                  <div class="add_address_div add_emailtype">
                    <div class="col-sm-3">
                        <label for="validateSelect">Address Type</label>
                        </div>
                        <div class="col-sm-6">
                        <label for="validateSelect">Address</label>
                    </div>
                    <?php if(!empty($address_trans_data) && count($address_trans_data) > 0){
			 		foreach($address_trans_data as $rowtrans){ ?>
                    <div class="delete_address_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    
                      <div class="col-sm-3 columns">
                      
                     
                        <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
                        <?php if(!empty($rowtrans['address_type']) && $rowtrans['address_type'] == $row['id']){ echo $row['name']; }?>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      <div class="col-sm-6 columns">
                        <div class="row">
                          <?php if(!empty($rowtrans['address_line1'])){ echo $rowtrans['address_line1']; }else{ echo "-";}?>
                        </div>
                        <div class="row">
                          <?php if(!empty($rowtrans['address_line2'])){ echo $rowtrans['address_line2']; }else{ echo "-";}?>
                        </div>
                        <div class="row">
                          <div class="col-sm-5 nopadding">
                            <?php if(!empty($rowtrans['city'])){ echo $rowtrans['city']; }else{ echo "-";}?>
                          </div>
                          <div class="col-sm-3 nopadding">
                            <?php if(!empty($rowtrans['state'])){ echo $rowtrans['state']; }else{ echo "-";}?>
                          </div>
                          <div class="col-sm-4 nopadding">
                            <?php if(!empty($rowtrans['zip_code'])){ echo $rowtrans['zip_code']; }else{ echo "-";}?>
                          </div>
                        </div>
                        <div class="row">
                          <?php if(!empty($rowtrans['country'])){ echo $rowtrans['country']; }else{ echo "-";}?>
                        </div>
                      </div>
                      <div class="col-sm-2"> </div>
                      <div> </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                  <div class="add_emailtype">
                    <div class="col-sm-6">
                    <div class="addr">
                      <label>
                        <?=$this->lang->line('contact_add_notes');?>
                      </label>
                      <?php if(!empty($editRecord[0]['notes'])){ echo $editRecord[0]['notes']; }else{ echo "-";}?>
                    </div>
                     </div>
                    
                  </div>
                </div>
                <div class="col-lg-5 col-xs-12">
                  <div class="add_website add_emailtype1">
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
                            <?php if(!empty($rowtrans['name'])){ echo $rowtrans['name']; }else{ echo "-";}?>
                          </div>
                          <div class="col-sm-5 form-group">
                            <?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }else{ echo "-";}?>
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> </div>
                          </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
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
                            <?=$this->lang->line('contact_add_website');?>
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
                            <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
                            <?php if(!empty($rowtrans['profile_type']) && $rowtrans['profile_type'] == $row['id']){ echo $row['name']; }?>
                            <?php } ?>
                            <?php } ?>
                          </div>
                          <div class="col-sm-5 form-group">
                            <?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }else{echo "-";}?>
                          </div>
                          <div class="col-sm-1 text-center icheck-input-new">
                            <div class=""> </div>
                          </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="add_website add_emailtype1">
              <div class="socialnework">
              <div class="col-sm-5">
                <label for="text-input">
                <?=$this->lang->line('contact_add_contact_pic');?>
              </label>
              <p> <span class="txt">&nbsp;</span>
                <?php 	
						  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('user_big_img_path').$editRecord[0]['contact_pic'])){
							?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('user_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/>
                <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('user_small_img_path').$editRecord[0]['contact_pic'])){
				?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('user_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" />
                <?
				}else{
				?>
                <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                <? } } ?>
              </p>
          
              </div>
              </div>
              </div>
        <div class="socialnework">
        <div class="col-sm-5">
          <label for="text-input">
            <?=$this->lang->line('contact_add_birth_date');?>
          </label>
        </div>
        <div class="col-sm-5">
          <label for="text-input">
            <?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }else{ echo "-";}?>
          </label>
        </div>
      </div>
      
      <div class="col-sm-5">
        <label for="text-input">
          <?=$this->lang->line('contact_add_anniversary_date');?>
        </label>
      </div>
      <div class="col-sm-5">
      <label for="text-input">
      <?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }else{ echo "-";}?>
      </label>
      </div>
      <?php /* ?>
      <div class="add_emailtype1">
        <div class="col-sm-5">
          <label for="text-input">
            <?=$this->lang->line('fb_key_id');?>
          </label>
        </div>
        <div class="col-sm-5">
          <label for="text-input">
            <?=!empty($user_info[0]['fb_api_key'])?$user_info[0]['fb_api_key']:'-'?>
          </label>
        </div>
     
      
      <div class="col-sm-5">
        <label for="text-input">
          <?=$this->lang->line('fb_secret_key');?>
        </label>
      </div>
      <div class="col-sm-5">
      <label for="text-input">
                  <?=!empty($user_info[0]['fb_secret_key'])?$user_info[0]['fb_secret_key']:'-'?>
      </label>
      </div>
      
       </div>
       <?php */ ?>
      </div>
              
          
              
              </div></div></div></div></div></div></div></div></div></div></div>