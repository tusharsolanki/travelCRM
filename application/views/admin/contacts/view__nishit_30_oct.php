<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$contact_id = $this->router->uri->segments[4];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
$path_per_tou = $viewname.'/insert_personal_touches';
$path_comm = $viewname.'/insert_last_action_communication_plan';
$path_per_1 = $viewname.'/insert_conversations';
$path_per_2 = $viewname.'/update_conversations';
//Path for facebook chat history 
$loadcontroller='view_record/'.$contact_id.'?action=login';
$path_view = $viewname."/".$loadcontroller;
$fb_path=$viewname."/fb_conversation";
$loadcontroller1='view_record/'.$contact_id.'/7';
$path_view = $viewname."/".$loadcontroller;
$path_view1 = $viewname."/".$loadcontroller1;


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

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Send <span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body">
			<iframe src="" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('contact_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
      
      <div class="portlet">
      <div class="portlet-header">
        <h3><i class="fa fa-tasks"></i>
          <?=$this->lang->line('contact_view_table_head');?>
		  
        </h3>
		<span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      
      <div class="portlet-content"> 
      <div class="col-sm-12">
        <ul class="nav nav-tabs" id="myTab1">
          <li <?php if($tabid == '' || $tabid == 1 || $tabid == 4 || $tabid == 5 || $tabid == 6 || $tabid == 7){?> class="active" <?php } ?>> <a title="Contact Information" data-toggle="tab" href="#home">
            <?=$this->lang->line('contact_add_table_tab1_head');?>
            </a> </li>
          <?php if(!empty($editRecord[0]['id'])){ ?>
          <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Contact Photo and Documents" data-toggle="tab" href="#profile">
            <?=$this->lang->line('contact_add_table_tab2_head');?>
            </a> </li>
          <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Extra Information" data-toggle="tab" href="#profilenew">
            <?=$this->lang->line('contact_add_table_tab3_head');?>
            </a> </li>
          <li <?php if($tabid == 8){?> class="active" <?php } ?>> <a title="Buyer Preferences" data-toggle="tab" href="#buyer_preference">
            <?=$this->lang->line('contact_add_table_tab4_head');?>
            </a> </li>
			 <?php 
					if($joomla == "Yes"){
					?>
                <li <?php if($tabid == 9){?> class="active" <?php } ?>> <a title="Joomla Connection" data-toggle="tab" href="#joomla_connection">
                  <?=$this->lang->line('contact_add_table_tab5_head');?>
                  </a> </li>
                <?php } ?>
          <?php } ?>
          
        </ul>
        <div class="tab-content" id="myTab1Content">
          <div <?php if($tabid == '' || $tabid == 1 || $tabid == 4 || $tabid == 5 || $tabid == 6 || $tabid == 7){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" > 
          <div class="col-lg-7 col-xs-12">
            <div class="add_emailtype">
              <div class="row lftrgt">
                <div class="col-sm-3">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_prefix');?>
                  </label>
                </div>
                <div class="col-sm-3 form-group">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['prefix'])){echo $editRecord[0]['prefix'];}else{ echo "-"; }?>
                  </label>
                </div>
              </div>
              <div class="row lftrgt">
                <div class="col-sm-3 form-group">
                  <label for="text-input">
                    <?=$this->lang->line('common_label_name');?>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }else{ echo "-"; }?>
                  </label>
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
                <div class="col-sm-3 form-group">
                  <label for="text-input">
                    <?=$this->lang->line('common_label_spousename');?>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['spousefirst_name'])){ echo $editRecord[0]['spousefirst_name']; }else{ echo "-"; }?>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['spouselast_name'])){ echo $editRecord[0]['spouselast_name']; }else{ echo "-"; }?>
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
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['company_name'])){ echo $editRecord[0]['company_name']; }else{ echo "-"; }?>
                  </label>
                </div>
              </div>
              <div class="row lftrgt">
                <div class="col-sm-3">
                  <label for="text-input">
                    <?=$this->lang->line('contact_add_title1');?>
                  </label>
                </div>
                <div class="col-sm-5">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['company_post'])){ echo $editRecord[0]['company_post']; }else{ echo "-"; }?>
                  </label>
                </div>
              </div>
              <div class="row form-group lftrgt">
                <div class="col-sm-12 checkbox">
                  <label class="">
                  Is Contact Lead
                  <div class="float-left margin-left-15">
                    <input type="checkbox" value="1" class=""  id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'checked="checked"'; }?>>
                  </div>
                  </label>
                </div>
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
				  	$temp_email=0;
								foreach($email_type as $row){?>
                  <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){$temp_email=1; echo $row['name'];};?>
                  <?php }
				  	if($temp_email==0){ echo "-"; }  ?>
                  <?php }
				  else
				  {echo "-";}
				  ?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php if(!empty($rowtrans['email_address']) && !empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ ?>
				  <a href="#basicModal" class="text_size" id="basicModal" data-toggle="modal" onclick="add_email_campaign(<?=$editRecord[0]['id']?>)">
				  	<?=$rowtrans['email_address']?>
                  </a>
                  <?php
				  }
				  elseif(!empty($rowtrans['email_address']))
				  	echo $rowtrans['email_address']; 
				  else{ echo "-";}?>
                </div>
                <div class="col-sm-2 text-center text-center1 icheck-input-new">
                  <div class="form-group"> 
                    <!--<label><?=$this->lang->line('common_default');?></label>-->
                    <div class="">
                      <label class="">
                      <div class="">
                       <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ ?>
                        </a>
                       <?php } ?>
                        <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }else{echo "-";}?> data-required="true">
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
                </div>
              </div>
              <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>
              <div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                <div class="col-sm-4">
                  <?php if(!empty($phone_type)){
				  	$temp_phone=0;
									foreach($phone_type as $row){?>
                  <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){$temp_phone=1; echo $row['name']; }?>
                  <?php } if($temp_phone==0){ echo "-";}?>
                  <?php } ?>
                </div>
                <div class="col-sm-4">
                  <?php if(!empty($rowtrans['phone_no']) && !empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ ?>
                 <a href="#basicModal" class="text_size" id="basicModal" data-toggle="modal" onclick="add_sms_campaign(<?=$editRecord[0]['id']?>)">
				  	<?=$rowtrans['phone_no']?>
                  </a>
                  <?php
				  }
				  elseif(!empty($rowtrans['phone_no'])) 
				  	echo $rowtrans['phone_no'];
				  else{ echo "-";}?>
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
				  $temp_address=0;
									foreach($address_type as $row){?>
                  <?php if(!empty($rowtrans['address_type']) && $rowtrans['address_type'] == $row['id']){ $temp_address=1;echo $row['name']; }?>
                  <?php }
				  if($temp_address==0){echo "-";} 
				  	}?> 
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
              <?php } else {?>
			  <div class="col-sm-3 columns clear">

                 <?php echo "-";?>
			  </div>
		
			  <div class="col-sm-6 columns">
		
                 <?php echo "-";?>
			  </div>
		
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
              <div class="col-sm-6">
                <div class="addr">
                  <label> Contact Source: </label>
                  <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
                  <?php if(!empty($editRecord[0]['contact_source']) && $editRecord[0]['contact_source'] == $row['id']){ echo $row['name']; }?>
                  <?php } ?>
                  <?php } ?>
                </div>
                <div class="addr">
                  <label> Preferred Contact Method: </label>
                  <?php if(!empty($method_type)){
							foreach($method_type as $row){?>
                  <?php if(!empty($editRecord[0]['contact_method']) && $editRecord[0]['contact_method'] == $row['id']){ echo $row['name']; }?>
                  <?php } ?>
                  <?php } ?>
                </div>
                <div class="addr">
                  <label> Contact Type: </label>
                  <?php
						$selectedcontacttypes = array(); 
						if(!empty($contact_trans_data)){
								foreach($contact_trans_data as $row){ 
								
									$selectedcontacttypes[] = $row['contact_type_id'];
								
								} ?>
                  <?php } ?>
                  <?php if(!empty($contact_type)){
							foreach($contact_type as $row){?>
                  <div class="">
                    <div class="">
                      <?php if(in_array($row['id'],$selectedcontacttypes)){ echo $row['name'];}?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div>
                <div class="addr">
                  <label> Tags </label>
                  <?php if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
						foreach($tag_trans_data as $rowtrans){ ?>
                  <div class="">
                    <div class="">
                      <?php if(!empty($rowtrans['tag'])){ echo $rowtrans['tag']; }?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div>
                <div class="addr">
                  <label> Communication </label>
                  <?php if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
						foreach($communication_trans_data as $rowtrans){ ?>
                  <div class="">
                    <div class="">
                      <?php if(!empty($communication_plans)){
					  			$temp_communication=0;
												foreach($communication_plans as $row){?>
                      <?php if(!empty($rowtrans['communication_plan_id']) && $rowtrans['communication_plan_id'] == $row['id']){ $temp_communication=1; echo $row['description']; }?>
                      <?php } if($temp_communication==0){echo "-";} ?>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php }else{?>
                  <div class="">-</div>
                  <?php }  ?>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-5 col-xs-12">
          
          <div class="add_emailtype autooverflow">
            <div class="col-sm-12">
              <label for="text-input">
                <?=$this->lang->line('contact_add_contact_pic');?>
              </label>
              <p> <span class="txt">&nbsp;</span>
                <?php 	
						  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_big_img_path').$editRecord[0]['contact_pic'])){
							?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/>
                <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['contact_pic'])){
				?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" />
                <?
				}else{
				?>
                <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                <? } } ?>
              </p>
            </div>
          </div>
       
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
                  <?php
				  	
				    if(!empty($website_trans_data) && count($website_trans_data) > 0){
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
                  <?php }else { ?>
				  <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-5">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-5 form-group">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
				  
				  <?php }?>
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
                  <?php } else {?>
				  <div class="delete_social_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-5">
                      <?php echo "-"; ?>
                     
                    </div>
                    <div class="col-sm-5 form-group">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
				  <?php }?>
                </div>
              </div>
            </div>
            <div class="add_website add_emailtype1">
              <div class="socialnework">
                <h2><i class="fa fa-thumbs-o-up btnthumbs"></i>Social Media</h2>
                <ul>
                  <?php
					$flag='';
				  if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ 
						if($rowtrans['profile_type'] == '1')
						{$flag = '1';break;}
						}
					}
					//echo $flag." admin ".$this->admin_session['id'].'edit is'.$editRecord[0]['created_by'];
				if($flag == '1' && $editRecord[0]['created_type'] == 3 && $this->admin_session['id'] == $editRecord[0]['created_by'])
				{ ?>
					<i class="fa fa-facebook scl_btn btn-facebook"></i>You are Friends with <?php if(!empty($editRecord[0]['first_name'])){ echo ucwords($editRecord[0]['first_name']); }else{ echo "-"; }?> 
				 <?php }else{ ?>
					 <li><a href="javascript:void(0);" title="Facebook Profile" class="sendrequest" id="<?=!empty($editRecord[0]['fb_id'])?$editRecord[0]['fb_id']:'';?>"><i class="fa fa-facebook scl_btn btn-facebook"></i>Add <?php if(!empty($editRecord[0]['first_name'])){ echo ucwords($editRecord[0]['first_name']); }else{ echo "-"; }?> as a Friend </a></li>
				 <?php } ?> 
                  
                  
                  <li><a href="javascript:void(0);" title="Linkedin Profile"><i class="fa fa-linkedin scl_btn btn-linkedin"></i>Add a Linkedin Profile Link</a></li>
                  
                  <?php if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ 
						if($rowtrans['profile_type'] == '2')
						{$flag = 'twitter';break;}
						}
					}?>
                  
                  <?php if($flag == 'twitter'){ ?>
                  	
                  <?php }else{ ?>
                  <li><a href="javascript:void(0);" title="Twitter Profile"><i class="fa fa-twitter scl_btn btn-twitter"></i>Add a Twitter Profile Link</a></li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <div class="add_website add_emailtype1">
              <div class="row">
                <div class="col-sm-5"><b class="assign_title">Assign Contact to :</b></div>
                <div class="col-sm-7">
					<div class="margin-top-10px">
                 <?php if(!empty($user_name[0]['first_name'])){echo $user_name[0]['first_name']." ".$user_name[0]['middle_name']." ".$user_name[0]['last_name'];}else{echo '-';} ?>
				  	</div>
				  </div>
              </div>
            </div>
          </div>
        </div>
        <div <?php if($tabid == 2){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profile">
        <?php /*?><div class="col-sm-12">
          <div class="add_emailtype autooverflow">
            <div class="col-sm-7">
              <label for="text-input">
                <?=$this->lang->line('contact_add_contact_pic');?>
              </label>
              <p> <span class="txt">&nbsp;</span>
                <?php 	
						  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_big_img_path').$editRecord[0]['contact_pic'])){
							?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/>
                <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['contact_pic'])){
				?>
                <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" />
                <?
				}else{
				?>
                <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
                <? } } ?>
              </p>
            </div>
          </div>
        </div><?php */?>
        <div class="col-sm-12">
          <div class="add_emailtype autooverflow">
            <div class="col-sm-12 clear appendajaxdata toppadding">
              <?php $this->load->view('admin/contacts/contact_document_ajax'); ?>
            </div>
          </div>
        </div>
      </div>
      <div  <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profilenew" >
      <div class="col-sm-7">
      <div class="row">
        <div class="col-lg-3">
          <label for="text-input">
            <?=$this->lang->line('contact_add_birth_date');?>
          </label>
        </div>
        <div class="col-lg-3">
          <label for="text-input">
            <?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }else{ echo "-";}?>
          </label>
        </div>
      </div>
      <div class="row">
      <div class="col-lg-3">
        <label for="text-input">
          <?=$this->lang->line('contact_add_anniversary_date');?>
        </label>
      </div>
      <div class="col-sm-3">
      <label for="text-input">
      <?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }else{ echo "-";}?></label>
      </div>
      </div>
      <!----new code------>
<div class="add_website add_emailtype1">
              <div>
                <div class="row add_website_div">
                  <div class="col-sm-5">
                    <label for="text-input">
                      <?=$this->lang->line('common_label_field_type');?>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <label for="text-input">
                      Value
                    </label>
                  </div>
                  <div class="col-sm-1 text-center icheck-input-new">
                    <div class=""> </div>
                  </div>
                  <?php
				  	
				    if(!empty($field_trans_data) && count($field_trans_data) > 0){
			 		foreach($field_trans_data as $rowtrans){ ?>
                  <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-5">
                      <?php if(!empty($rowtrans['name'])){ echo $rowtrans['name']; }else{ echo "-";}?>
                    </div>
                    <div class="col-sm-5 form-group">
                      <?php if(!empty($rowtrans['field_name'])){ echo $rowtrans['field_name']; }else{ echo "-";}?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
                  <?php } ?>
                  <?php }else { ?>
				  <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-5">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-5 form-group">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
				  
				  <?php }?>
                </div>
              </div>
              <div>
                
              </div>
            </div>


      
      <!----new code------>      
      </div>
      </div>
      
      
      <div  <?php if($tabid == 8){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="buyer_preference" >
      
			
            <div class="col-sm-12">
            
               <div class="row">
                 <div class="col-sm-4 form-group">
                     <label for="text-input"><?=$this->lang->line('contact_add_price_range_from');?></label>
                      </div>
                      <div class="col-sm-4 form-group">
                      <label for="text-input">
                     <?php if(!empty($editRecord[0]['price_range_from'])){ echo $editRecord[0]['price_range_from']; }?>                   </label>
                     </div>
                 </div>
                 <div class="row">
                 <div class="col-sm-4">
                     <label for="text-input"><?=$this->lang->line('contact_add_price_range_to');?></label>
                      </div>
                 <div class="col-sm-4">
                 <label for="text-input">
              <?php if(!empty($editRecord[0]['price_range_to'])){ echo $editRecord[0]['price_range_to']; }?>     
                 </label>     
              </div>
             </div>
            <div class="row">
             <div class="col-sm-4">
              <label for="text-input"><?=$this->lang->line('contact_add_house_style');?></label>
              </div>
              <div class="col-sm-8">
              <label for="text-input">
				  <?php if(!empty($editRecord[0]['house_style'])){ echo $editRecord[0]['house_style']; }?>
             </label>
             </div>
            </div> 
				<div class="row">
				 <div class="col-sm-4">
				  <label for="text-input"><?=$this->lang->line('contact_add_area_of_interest');?></label>
				  </div>
                  <div class="col-sm-8">
                  <label for="text-input">
                  <?php if(!empty($editRecord[0]['area_of_interest'])){ echo $editRecord[0]['area_of_interest']; }?>
				 </label>
                 </div>
				</div>
                <div class="row">
				 <div class="col-sm-4">
				  <label for="text-input"><?=$this->lang->line('contact_add_square_footage');?></label>
				  </div>
                  <div class="col-sm-8">
                  <?php if(!empty($editRecord[0]['square_footage'])){ echo $editRecord[0]['square_footage']; }?>
				 </div>
				</div>
                
                 <div class="row">
				 <div class="col-sm-4">
				  <label for="text-input"><?=$this->lang->line('contact_add_no_of_bedrooms');?></label>
				  </div>
                  <div class="col-sm-8">
                  <?php if(!empty($editRecord[0]['no_of_bedrooms'])){ echo $editRecord[0]['no_of_bedrooms']; }?>
				 </div>
				</div>
                
                 <div class="row">
				 <div class="col-sm-4">
				  <label for="text-input"><?=$this->lang->line('contact_add_no_of_bathrooms');?></label>
				  </div>
                  <div class="col-sm-8">
                  <?php if(!empty($editRecord[0]['no_of_bathrooms'])){ echo $editRecord[0]['no_of_bathrooms']; }?>
				 </div>
				</div>
                
                  <div class="row">
				 <div class="col-sm-4">
				  <label for="text-input"><?=$this->lang->line('contact_add_buyer_preferences_notes');?></label>
				    </div>
                    <div class="col-sm-8">
                    <?php if(!empty($editRecord[0]['buyer_preferences_notes'])){ echo $editRecord[0]['buyer_preferences_notes']; }?>
				 </div>
				</div>
            </div>
     
      </div>
	  
	  <?php 
		 if($joomla == "Yes"){
	  ?>
            <input type="hidden" name="selected_contact_id" id="selected_contact_id" value="<?= !empty($selected_contact_id)?$selected_contact_id:''?>">
      <div  <?php if($tabid == 9){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="joomla_connection" >
        <div class="col-lg-12">
          <ul class="nav nav-tabs" id="myTab2">
            <li <?php if($tabid == '' || $tabid == 101 || $tabid == 102 || $tabid == 103 || $tabid == 104){?> class="active" <?php } ?>> <a title="Contact Register" data-toggle="tab" href="#contact_register" onclick="load_view('104');"> Contact Register </a> </li>
            <li <?php if($tabid == 105){?> class="active" <?php } ?>> <a title="Saved Searches" data-toggle="tab" href="#saved_searches" onclick="load_view('105');"> Saved Searches </a> </li>
            <li <?php if($tabid == 106){?> class="active" <?php } ?>> <a title="Favorite" data-toggle="tab" href="#favorite" onclick="load_view('106');"> Favorite </a> </li>
            <li <?php if($tabid == 107){?> class="active" <?php } ?>> <a title="Properties Viewed" data-toggle="tab" href="#properties_viewed" onclick="load_view('107');"> Properties Viewed </a> </li>
            <li <?php if($tabid == 108){?> class="active" <?php } ?>> <a title="Last Login" data-toggle="tab" href="#last_login" onclick="load_view('108');"> Last Login </a> </li>
          </ul>
          <div class="tab-content" id="myTab2Content">
            <div <?php  if($tabid == '' || $tabid == 101 || $tabid == 102 || $tabid == 103 || $tabid == 104){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="contact_register">
                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="col-lg-12">
                      <div class="table-responsive">
                        <div class="table-in-responsive">
                          <!-- table code start-->
                          <div class="row dt-rt">
                                        <?php if(!empty($msg)){?>
                                                <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>

                                                <div class="col-lg-12 col-sm-12 col-xs-12">
                               <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                <label>
                                    <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                    <input class="" type="text" name="searchtext_cr" id="searchtext_cr" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>" />
                                    <button class="btn howler" data-type="danger" onclick="contact_search('changesearch');" title="Search Contacts">Search</button>
                                    <button class="btn howler" data-type="danger" onclick="clearfilter_contact();" title="View All Contacts">View All</button>
                                </label>
                               </div>
                              </div>          
                                        </div>
                          <div id="common_div">
                            <?=$this->load->view('admin/'.$viewname.'/view_contact_register')?>
                          </div>
                       <!-- table code end-->
                        </div>
                      </div>
                    </div>
                </div>
          </div>
          <div <?php   if($tabid == 105){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="saved_searches">
              <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <div class="table-in-responsive">
                         <!-- table code start-->
                         <div class="row dt-rt">
                              <?php /* if(!empty($msg)){?>
                                      <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                      $newdata = array('msg'  => '');
                                      $this->session->set_userdata('message_session', $newdata);?> </div><?php } */ ?>

                              <div class="col-lg-12 col-sm-12 col-xs-12">
                                  <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                      <label>
                                          <input class="" type="hidden" name="uri_segment1" id="uri_segment1" value="<?=!empty($uri_segment1)?$uri_segment1:'0'?>">
                                          <input class="" type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext1)?$searchtext1:''?>" />
                                          <button class="btn howler" data-type="danger" onclick="contact_search1('changesearch');" title="Search Contacts">Search</button>
                                          <button class="btn howler" data-type="danger" onclick="clearfilter_contact1();" title="View All Contacts">View All</button>
                                      </label>
                                 </div>
                              </div>          
                          </div>
                                              <div id="common_div_ss">                 
                                       <?=$this->load->view('admin/'.$viewname.'/view_saved_searches')?>
                          </div>
                        <!-- table code end-->

                    </div>
                  </div>
                </div>
            </div>
        </div>
         <div  <?php if($tabid == 106){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="favorite" >
             <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <div class="table-in-responsive">
                          <!-- table code start-->
                        <div class="row dt-rt">
                            <?php /*if(!empty($msg)){?>
                                    <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                    $newdata = array('msg'  => '');
                                    $this->session->set_userdata('message_session', $newdata);?> </div><?php } */?>

                              <div class="col-lg-12 col-sm-12 col-xs-12">
                                  <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                      <label>
                                          <input class="" type="hidden" name="uri_segment2" id="uri_segment2" value="<?=!empty($uri_segment2)?$uri_segment2:'0'?>">
                                          <input class="" type="text" name="searchtext2" id="searchtext2" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext2)?$searchtext2:''?>" />
                                          <button class="btn howler" data-type="danger" onclick="contact_search2('changesearch');" title="Search Contacts">Search</button>
                                          <button class="btn howler" data-type="danger" onclick="clearfilter_contact2();" title="View All Contacts">View All</button>
                                      </label>
                                  </div>
                              </div>          
                          </div>
                          <div id="common_div_fav">
                            <?=$this->load->view('admin/'.$viewname.'/view_favorite')?>
                          </div>
                          <!-- table code end-->

                    </div>
                  </div>
                </div>
            </div>
      </div>
      <div  <?php  if($tabid == 107){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="properties_viewed" >
          <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                         <!-- table code start-->
                         <div class="row dt-rt">
                                      <?php /*if(!empty($msg)){?>
                                              <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                              $newdata = array('msg'  => '');
                                              $this->session->set_userdata('message_session', $newdata);?> </div><?php } */?>

                                              <div class="col-lg-12 col-sm-12 col-xs-12">
                             <div class="dataTables_filter" id="DataTables_Table_0_filter">
                              <label>
                                  <input class="" type="hidden" name="uri_segment3" id="uri_segment3" value="<?=!empty($uri_segment3)?$uri_segment3:'0'?>">
                                  <input class="" type="text" name="searchtext3" id="searchtext3" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext3)?$searchtext3:''?>" />
                                  <button class="btn howler" data-type="danger" onclick="contact_search3('changesearch');" title="Search Contacts">Search</button>
                                  <button class="btn howler" data-type="danger" onclick="clearfilter_contact3();" title="View All Contacts">View All</button>
                              </label>
                             </div>
                            </div>          
                                      </div>
                        <div id="common_div_pv">
                          <?=$this->load->view('admin/'.$viewname.'/view_properties_viewed')?>
                         </div>
                        <!-- table code end-->
                </div>
              </div>
            </div>
        </div>

    </div>
    <div  <?php  if($tabid == 108){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="last_login" >
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                         <!-- table code start-->
                         <div class="row dt-rt">
                          <?php /*if(!empty($msg)){?>
                                  <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                  $newdata = array('msg'  => '');
                                  $this->session->set_userdata('message_session', $newdata);?> </div><?php } */?>

                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        <input class="" type="hidden" name="uri_segment4" id="uri_segment4" value="<?=!empty($uri_segment4)?$uri_segment4:'0'?>">
                                        <input class="" type="text" name="searchtext4" id="searchtext4" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext4)?$searchtext4:''?>" />
                                        <button class="btn howler" data-type="danger" onclick="contact_search4('changesearch');" title="Search Contacts">Search</button>
                                        <button class="btn howler" data-type="danger" onclick="clearfilter_contact4();" title="View All Contacts">View All</button>
                                    </label>
                                </div>
                            </div>          
                        </div>
                        <div id="common_div_ll">
                         <?=$this->load->view('admin/'.$viewname.'/view_last_login')?>
                        </div>

                        <!-- table code end-->
                </div>
              </div>
            </div>
        </div>

    </div>
  </div>
</div>
</div>
      <?php } ?>
      </div>
      </div>
      <div class="col-lg-12">
        <ul class="nav nav-tabs" id="myTab2">
          <li <?php if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4){?> class="active" <?php } ?>> <a title="Conversations" data-toggle="tab" href="#conversations">
            <?=$this->lang->line('contact_add_table2_tab1_head');?>
            </a> </li>
          <li <?php if($tabid == 5){?> class="active" <?php } ?>> <a title="Personal Touches" data-toggle="tab" href="#personaltouches">
            <?=$this->lang->line('contact_add_table2_tab2_head');?>
            </a> </li>
          <li <?php if($tabid == 6){?> class="active" <?php } ?>> <a title="Communication" data-toggle="tab" href="#communication_plan">
            <?=$this->lang->line('contact_add_table2_tab3_head');?>
            </a> </li>
          <li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="Social Media" data-toggle="tab" href="#social_media">
            <?=$this->lang->line('contact_add_table2_tab4_head');?>
            </a> </li>
        </ul>
     
        <div class="tab-content" id="myTab2Content">
          <div <?php if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="conversations">
            <div class="col-lg-12">
              <div class="activity_top"> <a title="Add Conversations Log" href="#basicModal"  data-toggle="modal" class="btn btn-secondary pull-right mrg21">Add Conversations Log</a></div>
           <div class="table-responsive">
            <div class="table-in-responsive">
              <table width="100%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
			  	<thead>
                  <tr role="row">
                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Activity</th>
                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Action</th>
                  </tr>
                </thead>
                <?php  if(!empty($conversations)){
					for($i=0;$i<count($conversations);$i++) { 
					if($conversations[$i]['status'] != '0' || $conversations[$i]['is_completed_task'] != '0')
					{
				if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='1'){?>
				<tr class="load_conversations">
				 <td width="50%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                        <td width="77%"><?php if(!empty($conversations[$i]['interaction_type_name'])){ echo $conversations[$i]['interaction_type_name'];}?> </td>
                      </tr>
                      <tr>
                        <td><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td>
                        <td><?php if(!empty($conversations[$i]['disposition_name'])){ echo $conversations[$i]['disposition_name'];}else{echo "Not Available";}?></td>
                      </tr>
                      
					  <tr>
                        <td colspan="3"><?php if(!empty($conversations[$i]['description'])){ echo $conversations[$i]['description'];}?></td>
                      </tr>
					</table>
				</td>
               <td width="13%" valign="middle" class="text-center">
				  <a class="btn btn-xs btn-success" title="Edit Contact" onClick="editconversation(<?php echo $conversations[$i]['id'];?>);"  data-toggle="modal" data-target="#basicModal"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button class="btn btn-xs btn-primary" title="Delete Contact" onClick="deletepopup1('<?php echo  $conversations[$i]['id'] ?>','<?php echo $conversations[$i]['contact_name'] ?>');"><i class="fa fa-times"></i></button>
				  
				  </td>
                </tr>
				<?php }elseif(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='11'){ ?>
				
				<tr class="load_conversations">
				 <td width="50%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b>Task</b></td>
                        <td ><?php if(!empty($conversations[$i]['task_name'])){ echo $conversations[$i]['task_name'];}?>
                      </tr>
                    
                      <tr>
                        <td width="30%"><b>Task Date</b></td>
                        <td ><?php if(!empty($conversations[$i]['task_date'])){ echo date($this->config->item('common_date_format'),strtotime($conversations[$i]['task_date']));}?>
                      </tr>
                      <tr>
                        <td><?php if(!empty($conversations[$i]['user_name'])){  echo $conversations[$i]['user_name'];}?></td>
                        
                        <td ><?php if(!empty($conversations[$i]['desc'])){ echo $conversations[$i]['desc'];}?></td>
					</table>
				</td>
               <td width="13%" valign="middle" class="text-center">
				<?php if($conversations[$i]['is_completed_task'] == '0')
					{ ?>
                    
				   		<input type="checkbox" id="is_completed_task_<?php echo $conversations[$i]['id']; ?>" onClick="is_completed_task('<?=$conversations[$i]['id']?>');" />
					<?php } ?>
                   	<span class="is_completed_<?php echo $conversations[$i]['id']; ?>"><?php if(!empty($conversations[$i]['is_completed_task']) && $conversations[$i]['is_completed_task'] == '1') { ?><label title="Completed"  class="btn_done btn_success_done pcbtn reload_class">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?>
				   <a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a></span>
				  </td>
                </tr>
                <?php } else {?>
				<tr class="load_conversations">
				 <td width="50%" colspan="2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                        <td width="77%">
						
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='2'){ echo "Assign Communication";}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='3'){ echo "Assign to";}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='4'){ echo "Re-assign to";}?>
						<b><?php if(!empty($conversations[$i]['user_name1'])){echo ":".$conversations[$i]['user_name1'];}; ?></b>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='5')
							{	
								 echo "Email Sent From";
								if(!empty($conversations[$i]['interaction_id']))
									{
										if(!empty($conversations[$i]['inte_to_plan_name']))
										{
										echo " >> ".$conversations[$i]['inte_to_plan_name'];
										}
									}
									else
									{
										if(!empty($conversations[$i]['email_template_name']))
										{
											echo $conversations[$i]['email_template_name'];
										}
										if(!empty($conversations[$i]['email_campaing_template_name']))
										{
											echo $conversations[$i]['email_campaing_template_name'];
										}
									}
									 
							}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='6'){ echo "Email Sent From  Campaign ";}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='7')
									{ 	echo "SMS Sent From";
										if(!empty($conversations[$i]['inte_to_plan_name']))
										{echo " >> ".$conversations[$i]['inte_to_plan_name'];}
									}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='8'){ echo "SMS Sent From Campaign";}?>
						
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='9'){ if(!empty($conversations[$i]['mail_out_type'])){echo $conversations[$i]['mail_out_type'];}}?>
						<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='10'){ echo "Remove From Communication"; /*if(!empty($conversations[$i]['plan_name'])){echo " >> ".$conversations[$i]['plan_name'];}*/}?>
						
						 </td>
                      </tr>
                      <tr>
                        <td>
						<?php if(empty($conversations[$i]['plan_name']))
								{
									if(!empty($conversations[$i]['created_date']))
									{ 
									echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));
									}
								}else{
									if(!empty($conversations[$i]['created_date']))
									{ 
									echo date($this->config->item('common_date_format'),strtotime($conversations[$i]['created_date']));
									}
								}?>
						</td>
						<td><?php if(!empty($conversations[$i]['plan_name']))
									{ 
										echo $conversations[$i]['plan_name'];
									}
									else if(!empty($conversations[$i]['inte_to_plan_name']))
									{
										echo " >> ".$conversations[$i]['inte_to_plan_name'];
									}
									if(!empty($conversations[$i]['interaction_name']))
									{ 
										echo " >> ".$conversations[$i]['interaction_name'];
									}?>
						</td>
                      </tr>
					  <tr>
					  	<td>
								<?php if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Letter')
								{
									if(!empty($conversations[$i]['letter_template_name']))
									{	
									 echo $conversations[$i]['letter_template_name'];
									}
								}
								else
								if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Envelope')
								{
									if(!empty($conversations[$i]['envelope_template_name']))
									{	
										echo $conversations[$i]['envelope_template_name'];
									}
								}
								else if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Label')
								{
									if(!empty($conversations[$i]['label_template_name']))
									{	
										echo $conversations[$i]['label_template_name'];
									}
								}
								if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='8')
								{
									 if(!empty($conversations[$i]['sms_campaing_template_name']))
									{	
										echo $conversations[$i]['sms_campaing_template_name'];
									}
								}
								if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='7')
									{ 
										if(!empty($conversations[$i]['sms_template_name']))
										{
											echo $conversations[$i]['sms_template_name'];
										}
										if(!empty($conversations[$i]['sms_campaing_template_name']))
										{
											echo $conversations[$i]['sms_campaing_template_name'];
										}	
										 
									}
								if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='6')
									{ 
										if(!empty($conversations[$i]['email_campaing_template_name']))
										{
											echo $conversations[$i]['email_campaing_template_name'];
										}
										
									}?>
								
						</td>
					  </tr>
					  
					</table>
				</td>
               
                </tr>
				<?php } ?>
				<?php }}} else {?>
				
				<tr>
					<th width="10%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"> No Record Found!</th>
				</tr>
				
			<?php 	}?>	
              </table>
              </div>
              </div>
            </div>
          </div>
          <div <?php if($tabid == 5){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="personaltouches"> 
            <div class="col-lg-12">
              <div class="activity_top"> <a title="Add Personal Touches" href="#basicModal_2"  data-toggle="modal" class="btn btn-secondary pull-right mrg21">Add Personal Touches</a></div>
            </div>
            <div class="col-lg-12">
                 <div class="table-responsive">
            <div class="table-in-responsive">
              <table width="100%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                <thead>
                  <tr role="row">
                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Task</th>
                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Done</th>
                  </tr>
                </thead>
				<?php 
				if(!empty($personale_touches)){				
				 for($i=0;$i<count($personale_touches);$i++) { ?>
                <tr>
                  <td width="50%">
                    <table width="100%" class="personaltouches" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b><?php if(!empty($personale_touches[$i]['name'])){echo $personale_touches[$i]['name'];}; ?></b></td>
                        <td width="77%"><?php if(!empty($personale_touches[$i]['followup_date'])){ echo date($this->config->item('common_date_format'),strtotime($personale_touches[$i]['followup_date']));}?></td>
                      </tr>
					     <tr>
                        <td><?php if(!empty($personale_touches[$i]['task'])){ echo $personale_touches[$i]['task'];}?></td>
                       
                      </tr>
                      <tr>
                      <!--   <td class="reload_class_<?php echo $personale_touches[$i]['id']; ?>"></td>-->
                      </tr>
                    </table>
                  </td>
                  <td width="13%" align="center" valign="middle" v>
				  <?php if($personale_touches[$i]['is_done'] == '0'){	?>
                    <input type="checkbox" id="selectall1_<?php echo $personale_touches[$i]['id']; ?>" value="<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo '1';}?>" class="selecctall"<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo "checked=checked";}?> onClick="is_done_p(this.value,<?php echo $personale_touches[$i]['id']; ?>);">
                <?php } ?>
                <span class="reload_class_<?php echo $personale_touches[$i]['id']; ?>"> <?php if(!empty($personale_touches[$i]['is_done'])) { ?><label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?></span>
                 
						</td>
                </tr><?php } 
				}
				else {?>
				<tr>
					<th width="10%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"> No Record Found!</th>
				</tr>
				
			<?php 	}?>
              </table>
            </div>
          </div>
           </div>
          </div>
          <div <?php if($tabid == 6){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="communication_plan" >
            <div class="col-lg-12">
             <div class="table-responsive">
            <div class="table-in-responsive">
              <table width="100%%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                <thead>
                  <tr role="row">
                    <th width="50%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Task</th>
                    <th width="10%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Done</th>
                    
                  </tr>
                </thead>
				
				<?php
					if(!empty($interation_plan_communication_plan)){

				 for($i=0;$i<count($interation_plan_communication_plan);$i++) {
				 if($interation_plan_communication_plan[$i]['status'] != '0' || $interation_plan_communication_plan[$i]['is_done'] != '0')
				 {
				 // pr($interation_plan_communication_plan); ?>
                <tr>
                  <td width="50%">
                    <table width="100%" class="personaltouches" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b><?php if(!empty($interation_plan_communication_plan[$i]['name'])){echo $interation_plan_communication_plan[$i]['name'];}; ?></b></td>
                        <td width="77%"><?php if(!empty($interation_plan_communication_plan[$i]['task_date'])){ echo date($this->config->item('common_date_format'),strtotime($interation_plan_communication_plan[$i]['task_date']));}?></td>
                      </tr>
                      <tr>
                      	<td> Assign By : </td>
                        <td><?=$interation_plan_communication_plan[$i]['assign_name']?$interation_plan_communication_plan[$i]['assign_name']:''?></td>
                      </tr>
                      <tr>
                        <td><?php if(!empty($interation_plan_communication_plan[$i]['interaction_plan_name'])){ echo $interation_plan_communication_plan[$i]['interaction_plan_name'];}?></td>
                        <td><?php if(!empty($interation_plan_communication_plan[$i]['interaction_name'])){ echo $interation_plan_communication_plan[$i]['interaction_name'];}?></td>
                      </tr>
                      <tr>
                       <!-- <td class="reload_class_<?php echo $interation_plan_communication_plan[$i]['id']; ?>"></td>-->
						</tr>
                    </table>
                  </td>
                  <td width="13%" align="center" valign="middle" class="">
					<?php if($interation_plan_communication_plan[$i]['is_done'] == '0' && $interation_plan_communication_plan[$i]['assign_to'] == $this->admin_session['id'])
					{ ?>
				   <input type="checkbox" id="selecctall_<?php echo $interation_plan_communication_plan[$i]['id']; ?>" value="<?php if(!empty($interation_plan_communication_plan[$i]['is_done']) && $interation_plan_communication_plan[$i]['is_done'] == '1'){ echo '1';}?>" class="selecctall_comm_plan<?=$interation_plan_communication_plan[$i]['interaction_plan_id']?>" <?php if(!empty($interation_plan_communication_plan[$i]['is_done']) && $interation_plan_communication_plan[$i]['is_done'] == '1'){ echo "checked=checked";}?> onClick="is_done(this.value,<?php echo $interation_plan_communication_plan[$i]['id'];?>);" data-group="<?=$interation_plan_communication_plan[$i]['interaction_plan_id']?>">
					<?php } ?>
                   <span class="reload_class_<?php echo $interation_plan_communication_plan[$i]['id']; ?>"><?php if(!empty($interation_plan_communication_plan[$i]['is_done'])) { ?>
                   <?=$interation_plan_communication_plan[$i]['completed_by_name']?$interation_plan_communication_plan[$i]['completed_by_name']:''?> <br/>
                   <?=date($this->config->item('common_date_format'),strtotime($interation_plan_communication_plan[$i]['task_completed_date']))?>
                   <label title="Completed"  class="btn_done btn_success_done pcbtn reload_class">Completed</a><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</a><?php } ?>
				   <a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a></span>
						</td>
                        
                        
                </tr><?php } 
				}}
				else {?>
				<tr>
					<th width="10%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"> No Record Found!</th>
				</tr>
				
			<?php 	}?>
              </table>
            </div>
          </div>
          </div>
          </div>
          <div <?php  if($tabid == 7){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="social_media" >
            <div class="col-lg-12">
              <div class="activity_top"><b>Facebook Chat:</b> <a title="Follow-Up" href="#basicModal_3"  data-toggle="modal" class="btn btn-secondary pull-right mrg21">Follow-up</a></div>
                 <div class="table-responsive">
                
            <div class="table-in-responsive">
            
            <div class="facebook_chat_history" id="facebook_chat_history">
            <?php if($editRecord[0]['fb_id']){?>
              <img src="<?php echo $this->config->item('base_url');?>images/facebook-connect.png" alt="Fb Connect" title="Login with facebook" onClick="FBLogin();"/>
            <?php } else
			{
				echo "Please Insert Contact Facebook Link"; } ?>
            </div>
            
          <?php if(!empty($chat)){?>  
          <table width="100%%" class="table1 table-striped2 table-striped2 table-bordered2 table-hover1 table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
               <?php for($i=0;$i<count($chat);$i++){ $data_date=$chat[$i]['msg_date_time'];?> 
                <tr>
                  <td width="5%" align="center" valign="middle"><i class="fa fa-facebook scl_btn btn-facebook mrg12"></i></td>
                  <td width="95%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><b><?=$chat[$i]['from_fb_name'] ?>:</b></td>
                      </tr>
                      <tr>
                        <td><?=$chat[$i]['msg'] ?></td>
                      </tr>
                      <tr>
                        <td><?=date($this->config->item('common_datetime_format'),strtotime($data_date))?></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <?php }?>
              </table>
            <!-----Chat History------>
            
            
			<?php } 
			?>
            <!-----chat history---->

            </div>
   
          </div>
            </div>
          </div>
        </div>
     
    </div>
    <!-- /.portlet-content --> 
    
  </div>
</div>
</div>
</div>
</div>
</div>
<!-- #content-header --> 

<!-- /#content-container -->


<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add Conversations Log</h3>
      </div>
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Action Type:
			  </td>
              <td>
               <select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>
                <textarea class="form-control" name="description" id="description"></textarea>
              </td>
            </tr>
            <tr>
              <td>Disposition:</td>
              <td>
                 <select id="disposition_type" name="disposition_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
				 <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
              </td>
            </tr>
          </table>
		  
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="submit" id="activitylog" class="btn btn-secondary">Add Conversation Log</button>
        <!--<button type="button" class="btn btn-primary">Cancel</button>-->
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<div aria-hidden="true" style="display: none;" id="basicModal_2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add Personal Touches</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_tou;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Action Type:</td>
              <td>
                <select id="interaction_type" name="interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Task:</td>
              <td>
                <textarea class="form-control" name="task" id="task"></textarea>
              </td>
            </tr>
            <tr>
              <td>Follow-up Date:</td>
              <td>
			  	 <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
                 <input id="followup_date" name="followup_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['followup_date']) && $editRecord[0]['followup_date'] != '0000-00-00' && $editRecord[0]['followup_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['followup_date'])); }?>" readonly>
              </td>
            </tr>
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <input type="submit" value="Add Personal Touches" class="btn btn-secondary" title="Add Personal Touches">
        
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<div aria-hidden="true" style="display: none;" id="basicModal_for" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Last Action</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="last_action_popup" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_comm;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		<input type="hidden" value="" id="is_done_hidd_tab" name="is_done_hidd_tab" />
		<input type="hidden" value="<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:0?>" id="hid_contact_id" name="hid_contact_id" />
		<input type="hidden" value="" id="hid_current_plan_id" name="hid_current_plan_id" />
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
		  	<tr>
               <td>
                 <input type="radio" value="1" name="rd_start_interaction_plan" checked="checked" data-required="true" onClick="display_plan_list(this.value);" >
              </td>
			   <td>Do not take any action</td>
            </tr>
            <tr>
			  <td width="10%">
                 <input type="radio" value="2" name="rd_start_interaction_plan" data-required="true" onClick="display_plan_list(this.value);" >
              </td>
              <td>Restart Current Communication</td>
            </tr>
			
			<tr style="display:none;" class="tr_res_interaction_plan" >
              <td></td>
              <td class="form-group">
			   Next Action Start Date:
               <input id="r_next_interaction_start_date" name="r_next_interaction_start_date" class="form-control parsley-validated" readonly type="text" value="">
              </td>
            </tr>
			
            <tr>
				<td>
                 <input type="radio" value="3" name="rd_start_interaction_plan" <?php if(!empty($rowtrans['rd_start_interaction_plan']) && $rowtrans['rd_start_interaction_plan'] == '1'){ echo 'checked="checked"'; }?> data-required="true" onClick="display_plan_list(this.value);" >
              </td>
               <td>Start New Communication</td>
            </tr>
			
			<tr style="display:none;" class="tr_interaction_plan" >
              <td></td>
              <td class="form-group">
			   Next Action Start Date:
               <input id="next_interaction_start_date" name="next_interaction_start_date" readonly class="form-control parsley-validated" type="text" value="">
              </td>
            </tr>
			
			<tr style="display:none;" class="tr_interaction_plan" >
				<td></td>
               	<td class="form-group">
				<select class="form-control parsley-validated" name="slt_interaction_plan" id="slt_interaction_plan">
					<!--<option value="">Select Communication</option>-->
				   <?php if(!empty($interaction_plan_list)){
							foreach($interaction_plan_list as $row){?>
								<option value="<?=$row['id']?>"><?=$row['plan_name']?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				</td>
            </tr>
			
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <input type="submit" value="Save" class="btn btn-secondary" onclick="return checkforplanlist();">
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<div aria-hidden="true" style="display: none;" id="basicModal_3" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Follow-up Contact</h3>
      </div>
	 <div class="loding_win">
      <div class="modal-body">
        <div class="col-sm-12">
		 <div class="row">
		 <div class="col-sm-1">
          <label for="text-input">To:</label>
		 </div>
       	 <div class="col-sm-11">
              <select class="selectBox" name='platform' id='platform'>
              <option value="1" selected="selected">Facebook</option>
              <option value="2">Twitter</option>
			  <option value="3">Linkedin</option>
              </select>
         </div>
        </div>
      </div>
	  </div>
	 
      <div class="col-sm-12 text-center mrgb4" id="facebook">

	    <button type="submit" class="btn btn-secondary" onclick="FBLogin_send();">Send Massage on Facebook</button>
      </div>
	 </div>
      <div class="col-sm-12 mrgb4" id="twitter" style="display:none">
		<div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">

            <div class="col-sm-12 form-group">
			<div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_category' id='category' onChange="selectsubcategory(this.value)" >
              <option value="-1">Category</option>
                 <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     
              <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>
            </div>
			</div>	
           <div class="col-sm-12">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			   <select class="selectBox" name='template_name' id='template_name'>
              </select>
             </div>
            </div>
      
	           <!--<div class="col-sm-12 pull-left text-center margin-top-10">
               
              	<input type="radio" name="rad_tweet" id="tweet" value="1" checked="checked" >Public Tweet
                <input type="radio" name="rad_tweet" id="tweet"  value="2" > Direct Message 
	           </div>-->
         
         
          <div class="form-group clear">
                  <label for="select-multi-input">
                  <?=$this->lang->line('label_emailmessage');?><span class="val">*</span>
                  </label>
                  <textarea name="email_message" class="form-control parsley-validated" id="email_message" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('email_message',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                </div>
          
            </div>
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" class="btn btn-secondary" value="Send On Twitter" id="send_on_twitter"  title="Send On Twitter" name="submitbtn" />
<a href="https://twitter.com/share" class="my_tweet_a btn btn-secondary" data-via="demotops" data-count="none" onclick="user_post();" >Public Tweet</a>
 <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
         </div>
         
          </form>
         </div>
         </div>
        </div>
       </div>
      </div>

      <div class="col-sm-12 mrgb4" id="linkedin" style="display:none">
			<div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">

            <div class="col-sm-12 form-group">
			<div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_category' id='category1' onChange="selectsubcategory1(this.value)" >
              <option value="-1">Category</option>
                 <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     
              <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory1'>
              </select>
              <span id="category_loader"></span>
              </div>
            </div>
			</div>


           <div class="col-sm-12">
            <div class="row">
             <div class="col-sm-12 form-group">
               <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			   <select class="selectBox" name='template_name' id='template_name1'>
              </select>
            </div>
            </div>
			
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?><span class="val">*</span></label>
         <input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo $editRecord[0]['template_subject']; }?>" data-required="true">
          </div>
          </div>
          
          <div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('label_message');?><span class="val">*</span>
                  </label>
                  <textarea name="email_message" class="form-control parsley-validated" id="email_message1" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('email_message1',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                </div>
          
            </div>
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="button" class="btn btn-secondary" value="Send On linkedin"  title="Send On linkedin"onclick="return setdefaultdata();" name="submitbtn1" />
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

<!-- Contact Register Popup -->
<div aria-hidden="true" style="display: none;" id="contact_register_popup" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_contact_register_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Contact Register</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 contact_register_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Saved Searches Popup -->
<div aria-hidden="true" style="display: none;" id="saved_searches_popup" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_saved_searches_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Saved Searches</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 saved_searches_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Favorite Popup -->
<div aria-hidden="true" style="display: none;" id="favorite_popup" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_favorite_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Favorite</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 favorite_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Properties Viewed Popup -->
<div aria-hidden="true" style="display: none;" id="properties_viewed_popup" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_properties_viewed_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Properties Viewed</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 properties_viewed_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Last Login Popup -->
<div aria-hidden="true" style="display: none;" id="last_login_popup" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_last_login_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Last Login</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 last_login_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(document).ready(function(){
    $('#platform').on('change', function() {
      if (this.value == '2')
      {
		$("#facebook").hide();
        $("#linkedin").hide();
        $("#twitter").show();
      }
      else if (this.value == '3')
      {
		$("#facebook").hide();
        $("#twitter").hide();
        $("#linkedin").show();
      }
      else
      {
        $("#twitter").hide();
        $("#linkedin").hide();
		$("#facebook").show();		
      }
    });
});
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
         load_view(<?=$tabid?>);
    });
    function load_view(id)
    {
        if(id == '104') {
            //$("#contact_register").hide();
            //$("#favorite").hide();
        }
        else if(id == '105') {
            //$("#my_plan").hide();
        }
        if(id == '106') {
            //$("#premium_plan").hide();
        }
        else if(id == '107') {
            //$("#my_plan").hide();
        }
        else if(id == '108') {
            //$("#my_plan").hide();
        }
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:id},
            success: function(html){
                if(id == '108')
                {
                    //$("#premium_plan").show();
                    $("#div_msg1").fadeOut(4000);
                }    
                else {
                    //$("#my_plan").show();
                    $("#div_msg").fadeOut(4000); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }
	function is_done(id,is_done_hidd)
	{
		
		try
		{
			var plangroup = $("#selecctall_"+is_done_hidd).attr('data-group');
			//alert(plangroup);
			
			var notcheckedchk = $('.selecctall_comm_plan'+plangroup+':not(:checked)').length;
			
			if(notcheckedchk == 0)
			{
				$('#is_done_hidd_tab').val(is_done_hidd);
				$('#hid_current_plan_id').val(plangroup);
				
				$('#complate_interaction_plan_a').trigger('click');
				$("#selecctall_"+is_done_hidd).attr('checked',false);
				return false;
			}
		}
		catch(e){}
		
			var msg = 'Are you sure want to complete communication ';
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
		   'action': function(){
		   
			if($('#selecctall_'+is_done_hidd).prop('checked') == true)
				var id = 1;
			else
				var id = 0;
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/interaction_id_done';?>",
				dataType: 'json',
				async: false,
				data: {'id':id,'is_done_hidd':is_done_hidd},
				success: function(data){
				
						if(id == 1)
						{
							html='<label title="Completed" class="btn temp_success pcbtn reload_class">Completed</label>';
						}
						else
						{
							html='<label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label>';
						}	
						$(".reload_class_"+is_done_hidd).html(html);
						$("#selecctall_"+is_done_hidd).hide(data);
						}
			});
							
			}},'No'	: {'class'	: 'special',
			'action': function(){
				$("#selecctall_"+is_done_hidd).attr('checked',false);
			}
			}}});
		
	}
	
	function is_completed_task(conversation_id)
	{
			var msg = 'Are you sure want to complete task ';
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
		   'action': function(){
		   
			if($('#is_completed_task_'+conversation_id).prop('checked') == true)
				var id = 1;
			else
				var id = 0;
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/is_completed_task';?>",
				dataType: 'json',
				async: false,
				data: {'conversation_id':conversation_id},
				success: function(data){
						if(id == 1)
						{
							html='<label title="Completed" class="btn temp_success pcbtn reload_class">Completed</label>';
						}
						else
						{
							html='<label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label>';
						}	
						$(".is_completed_"+conversation_id).html(html);
						$("#is_completed_task_"+conversation_id).hide();
						}
			});
							
			}},'No'	: {'class'	: 'special',
			'action': function(){
				$("#is_completed_task_"+conversation_id).attr('checked',false);
			}
			}}});
	}
	
	function is_done_p(id,is_done_hidd)
	{	
	
		if($('#selectall1_'+is_done_hidd).prop('checked') == true)
			var id = 1;
		else
			var id = 0;
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/personal_id_done';?>",
			dataType: 'json',
			async: false,
			data: {'id':id,'is_done_hidd':is_done_hidd},
			success: function(data){
					
					if(id == 1)
					{
						html='<label title="Completed" class="btn temp_success pcbtn" >Completed</label>';
					}
					else
					{
						html='<label title="Pending" class="btn_danger_pending btn_done pcbtn" >Pending</label>';
					}	
					$(".reload_class_"+is_done_hidd).html(html);
					$("#selectall1_"+is_done_hidd).hide(data);
					$('.personaltouches').unblock(); 

			
					}
		});
	}	
	function deletepopup1(id,name)
	{      
			
		var msg = 'Are you sure want to delete '+name+'?';
		$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 
	function delete_all(id)
		{
			
			var contact_id = $('#contact_id').val();
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_conversations';?>",
			dataType: 'json',
			async: false,
			data: {'id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/contacts/view_record/"+contact_id,
					
				
				beforeSend: function() {
							$('#load_conversations').block({ message: 'Loading...' }); 
						  },
				success: function(html){
						$("#load_conversations").html(html);
						$('#load_conversations').unblock(); 
					}
				});
						
				return false;
			}
		});
	}
	function editconversation(id)
	{
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('admin_base_url').$viewname."/get_conversation_data";?>',
			success:function(data){
				
				document.forms['<?php echo $viewname;?>'].elements['sl_interaction_type'].value=data.plan_type_id;
				document.forms['<?php echo $viewname;?>'].elements['disposition_type'].value=data.dis_id;
				$("#description").html(data.description);
				$("#activitylog").html('Update Activity Long');
				 $('#<?php echo $viewname;?>').attr('action', "<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_2."/";?>"+data.id);
		
				}
			});//ajax
	}
</script>
<script type="text/javascript">
$(function() {
	$( "#followup_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	$( "#next_interaction_start_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	$( "#r_next_interaction_start_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	});
	
function display_plan_list(value)
{
	if(value == 3)
	{
		$('.tr_interaction_plan').css('display','');
		$('.tr_res_interaction_plan').css('display','none');
	}
	else if(value == 2)
	{
		$('.tr_res_interaction_plan').css('display','');
		$('.tr_interaction_plan').css('display','none');
	}
	else
	{
		$('.tr_res_interaction_plan').css('display','none');
		$('.tr_interaction_plan').css('display','none');
	}
}	
	
</script>
<script type="text/javascript">
	$("select#platform").multiselect({
		 multiple: false,
		 selectedList: 1
	}).multiselectfilter();
</script>
<script type="text/javascript">
	$("select#category").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();

	$("select#subcategory").multiselect({
		 multiple: false,
		 header: "Sub-Category",
		 noneSelectedText: "Sub-Category",
		 selectedList: 1
	}).multiselectfilter();

	$("select#template_name").multiselect({
		 multiple: false,
		 header: "Template Name",
		 noneSelectedText: "Template Name",
		 selectedList: 1
	}).multiselectfilter();

	$("select#category1").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();

	$("select#subcategory1").multiselect({
		 multiple: false,
		 header: "Sub-Category",
		 noneSelectedText: "Sub-Category",
		 selectedList: 1
	}).multiselectfilter();

	$("select#template_name1").multiselect({
		 multiple: false,
		 header: "Template Name",
		 noneSelectedText: "Template Name",
		 selectedList: 1
	}).multiselectfilter();


	 function setdefaultdata()
	 {
	 	
		var ck_edit = CKEDITOR.instances.post_content.getData();
		if(ck_edit == "")
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
	 }

 
  
function selectsubcategory(id){
 if(id!="-1"){
   	$("#subcategory").html("<option value='-1'>Sub-Category</option>");
   	loadData('category',id);
  	setTimeout(function(){$('#subcategory').change()},1000);
 }else{
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $("select#subcategory").multiselect('refresh').multiselectfilter();
   setTimeout(function(){$('#subcategory').change()},1000);
 }
}

function loadData(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory_id']?>';
			
			<?php } ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#subcategory').append(option);
			});
		$("select#subcategory").multiselect('refresh').multiselectfilter();				
     }
   });
}

$("#subcategory").change(function() {
	var subcategory_id = $("#subcategory").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category").val();
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}
});
function loadtemplateData(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,loadsubcategoryId:loadsubcategoryId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name_id']?>';
			
			<?php }  ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.template_name);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#template_name').append(option);
			});
		$("select#template_name").multiselect('refresh').multiselectfilter();
						
     }
   });
}

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>

$("#template_name").change(function() {
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			$("#txt_template_subject").val(item.template_subject);
			CKEDITOR.instances.email_message.setData(item.email_message);
			});
		 }
		});
});

///new code for linkedin


function selectsubcategory1(id){
 if(id!="-1"){
   	$("#subcategory1").html("<option value='-1'>Sub-Category</option>");
   	loadData1('category',id);
  	setTimeout(function(){$('#subcategory1').change()},1000);
 }else{
   $("#subcategory1").html("<option value='-1'>Sub-Category</option>");
   $("select#subcategory1").multiselect('refresh').multiselectfilter();
   setTimeout(function(){$('#subcategory1').change()},1000);
 }
}

function loadData1(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory_id']?>';
			
			<?php } ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#subcategory1').append(option);
			});
		$("select#subcategory1").multiselect('refresh').multiselectfilter();				
     }
   });
}

$("#subcategory1").change(function() {
	var subcategory_id = $("#subcategory1").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category1").val();
	   	$("#template_name1").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData1('template',category_id,subcategory_id);
	 }else{
	   $("#template_name1").html("<option value='-1'>Template Name</option>");
	   $("select#template_name1").multiselect('refresh').multiselectfilter();
 	}
});
function loadtemplateData1(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,loadsubcategoryId:loadsubcategoryId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name_id']?>';
			
			<?php }  ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.template_name);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#template_name1').append(option);
			});
		$("select#template_name1").multiselect('refresh').multiselectfilter();
						
     }
   });
}

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory1('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>

$("#template_name1").change(function() {
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name1").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			$("#txt_template_subject").val(item.template_subject);
			CKEDITOR.instances.email_message1.setData(item.email_message);
			});
		 }
		});
});
	</script>
<!----------------------Login With Facebook Account and Get facebook chat history..--------------------->

<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '728901530477590', // replace your app id here
	channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', 
	status     : true, 
	cookie     : true, 
	xfbml      : true  
		});
	};
	
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
	}(document));
	
function FBLogin(){
	FB.login(function(response){
		if(response.authResponse)
		{
				 $.ajax({
					 type: "POST",
					 //dataType: 'json',
					 url: "<?=base_url().'admin/'.$path_view?>",
					 data: {login:'login'},
					 cache: false,
	 				 beforeSend: function() {
					$('#facebook_chat_history').block({ message: 'Loading...' }); 
					  },
					 success: function(data){
					 //alert(data);
					window.location.href = "<?=base_url().'admin/'.$path_view1?>";
						
					 }
					});
		}
	}, {scope: 'email,user_likes,user_friends,read_stream, export_stream, read_mailbox'});
}

function FBLogin_send(){
	FB.login(function(response){
		if(response.authResponse)
		{
				var contact = "<?php echo $this->router->uri->segments[4] ?>"; 
				
				 $.ajax({
					 type: "POST",
					 url: "<?=base_url().'admin/'.$fb_path?>",
					 data: {login:'login',contact:contact},
					 cache: false,
	 				 beforeSend: function() {
							$('.loding_win').block({ message: 'Loading...' }); 
						  },
					 success: function(data){
					//alert(data);
					if(data != '' && data != 0)
					{	
						FB.ui({
						  method: 'send',
						  to:data,
						  link: 'http://topsdemo.in/',
						});
					}
					else
					{
						$.confirm({'title': 'Alert','message': " <strong> This contact is not your friend "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
						   
					}
					//$("#basicModal_3").dialog("close");
					$( ".close" ).trigger( "click" );
					$('.loding_win').unblock(); 
					}
					});

		   // window.location.href = "<?=base_url().'admin/'.$path_view?>";
		}
	}, {scope: 'email,user_likes,user_friends,read_stream, export_stream, read_mailbox'});
}
///// send Request from contact
$('.sendrequest').on('click',function()
{
var iid = $(this).attr('id');
 FB.ui(
     { 
      method: 'friends', 
      id: iid // assuming you set this variable previously...
     });

});	

function checkforplanlist()
{
	var radioval = $('input[name=rd_start_interaction_plan]:checked', '#last_action_popup').val();
	//alert(radioval);
	
	if(radioval == 3)
	{
		var plan_val = $('#slt_interaction_plan').val();
		if(plan_val == '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select a communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
	}
}

/*Contact Register*/

$('body').on('click','.contact_register_popup_btn',function(e){
    $(".contact_register_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/contact_register_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".contact_register_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".contact_register_popup").html('Something went wrong.');
        }
    });
});
function contact_search(allflag)
{
    var uri_segment = $("#uri_segment").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/contacts/view_record_index/"+id,
            data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext_cr").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,id:id
    },
    beforeSend: function() {
                            $('#common_div').block({ message: 'Loading...' }); 
                      },
            success: function(html){
                    $("#common_div").html(html);
                    //$('#common_div').unblock(); 
            }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext_cr').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search('changesearch');
        }
    });
});

function clearfilter_contact()
{
    $("#searchtext_cr").val("");
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
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
            $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext_cr").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),id:id
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

/*Saved Searches*/

$('body').on('click','.saved_searches_popup_btn',function(e){
    $(".saved_searches_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_saved_searches_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".saved_searches_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".saved_searches_popup").html('Something went wrong.');
        }
    });
});

function contact_search1(allflag)
{
    var uri_segment = $("#uri_segment1").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/contacts/view_record_index_savser/"+id,
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_ss').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_ss").html(html);
            //$('#common_div_ss').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext1').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search1('changesearch');
        }
    });
});

function clearfilter_contact1()
{
    $("#searchtext1").val("");
    contact_search1('all');
}

function changepages1()
{
    contact_search1('');	
}

function applysortfilte_contact1(sortfilter,sorttype)
{
    $("#sortfield1").val(sortfilter);
    $("#sortby1").val(sorttype);
    contact_search1('changesorting');
}

$('body').on('click','#common_tb1 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_ss").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/* Favorite */

$('body').on('click','.favorite_popup_btn',function(e){
    $(".favorite_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/favorite_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".favorite_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".favorite_popup").html('Something went wrong.');
        }
    });
});

function contact_search2(allflag)
{
    var uri_segment = $("#uri_segment2").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/contacts/view_record_index_fav/"+id,
        data: {
        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage2").val(),searchtext:$("#searchtext2").val(),sortfield:$("#sortfield2").val(),sortby:$("#sortby2").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_fav').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_fav").html(html);
            //$('#common_div_fav').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext2').keyup(function(event)
    {
        if (event.keyCode == 13) {
            contact_search2('changesearch');
        }
    });
});

function clearfilter_contact2()
{
    $("#searchtext2").val("");
    contact_search2('all');
}

function changepages2()
{
    contact_search2('');	
}

function applysortfilte_contact2(sortfilter,sorttype)
{
    $("#sortfield2").val(sortfilter);
    $("#sortby2").val(sorttype);
    contact_search2('changesorting');
}

$('body').on('click','#common_tb2 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage2").val(),searchtext:$("#searchtext2").val(),sortfield:$("#sortfield2").val(),sortby:$("#sortby2").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_fav").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/* Property Viewed */

$('body').on('click','.properties_viewed_popup_btn',function(e){
    $(".properties_viewed_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/properties_viewed_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".properties_viewed_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".properties_viewed_popup").html('Something went wrong.');
        }
    });
});

function contact_search3(allflag)
{
    var uri_segment = $("#uri_segment3").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/contacts/view_record_index_prop_view/"+id,
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage3").val(),searchtext:$("#searchtext3").val(),sortfield:$("#sortfield3").val(),sortby:$("#sortby3").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_pv').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_pv").html(html);
            //$('#common_div_pv').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext3').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search3('changesearch');
        }
    });
});

function clearfilter_contact3()
{
    $("#searchtext3").val("");
    contact_search3('all');
}

function changepages3()
{
    contact_search3('');	
}

function applysortfilte_contact3(sortfilter,sorttype)
{
    $("#sortfield3").val(sortfilter);
    $("#sortby3").val(sorttype);
    contact_search3('changesorting');
}

$('body').on('click','#common_tb3 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage3").val(),searchtext:$("#searchtext3").val(),sortfield:$("#sortfield3").val(),sortby:$("#sortby3").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_pv").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/*Last Login*/

$('body').on('click','.last_login_popup_btn',function(e){
    $(".last_login_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/last_login_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
            $(".last_login_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //console.log(textStatus, errorThrown);
            $(".last_login_popup").html('Something went wrong.');
        }
    });
});

function contact_search4(allflag,per_page)
{
    var uri_segment = $("#uri_segment4").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/contacts/view_record_index_lastlog/"+id,
        data: {
        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:per_page,searchtext:$("#searchtext4").val(),sortfield:$("#sortfield4").val(),sortby:$("#sortby4").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_ll').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_ll").html(html);
            //$('#common_div_ll').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext4').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search4('changesearch');
        }
    });
});

function clearfilter_contact4()
{
    $("#searchtext4").val("");
    contact_search4('all');
}

function changepages4()
{
    contact_search4('');	
}

function applysortfilte_contact4(sortfilter,sorttype)
{
    $("#sortfield4").val(sortfilter);
    $("#sortby4").val(sorttype);
    contact_search4('changesorting');
}

$('body').on('click','#common_tb4 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage4").val(),searchtext:$("#searchtext4").val(),sortfield:$("#sortfield4").val(),sortby:$("#sortby4").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_ll").html(html);
            $.unblockUI();
        }
    });
    return false;
});
</script>
<script>
$("#send_on_twitter").click(function(e) {
	var val = ($("input[name=rad_tweet]:checked").val());
	//alert(val);
		 if(val == 1)
		 {
			 	//alert('Public');
				$(".my_tweet_a").trigger('click');
		 }
		 else if(val == 2)
		 {
				alert('Coming Soon...');
		 }
		 
	return false;
		 
});
</script>
<!-- Twitter Script START --> 
<script>

  /*!function(d,s,id)
  {

	var html=CKEDITOR.instances.email_message.getSnapshot();
    var dom=document.createElement("DIV");
    dom.innerHTML=html;
    var plain_text=(dom.textContent || dom.innerText);

    //create and set a 128 char snippet to the hidden form field
    var snippet=plain_text.substr(0,140);
	
	$("a.my_tweet_a").attr("href", "https://twitter.com/share?text="+snippet+"&url=a");
    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
	
    if(!d.getElementById(id))
    {
      js=d.createElement(s);
      js.id=id;
      js.src=p+'://platform.twitter.com/widgets.js';
      fjs.parentNode.insertBefore(js,fjs);
    }
  }(document, 'script', 'twitter-wjs');*/

  function user_post(d,s,id)
  {
	//var message = CKEDITOR.instances.email_message.getData();
	
	var html=CKEDITOR.instances.email_message.getSnapshot();
    var dom=document.createElement("DIV");
    dom.innerHTML=html;
    var plain_text=(dom.textContent || dom.innerText);

    //create and set a 128 char snippet to the hidden form field
    var snippet=plain_text.substr(0,140);
    //document.getElementById("hidden_snippet").value=snippet;
	
	//alert(snippet);
	
	$("a.my_tweet_a").attr("href", "https://twitter.com/share?text="+snippet+"&url=a");
	
    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
	if(!d.getElementById(id))
    {
      js=d.createElement(s);
      js.id=id;
      js.src=p+'://platform.twitter.com/widgets.js';
      fjs.parentNode.insertBefore(js,fjs);
    }
  }(document, 'script', 'twitter-wjs');

  
function add_email_campaign(id){
	var frameSrc = '<?= $this->config->item('admin_base_url'); ?>emails/add_record/'+id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	$('iframe').attr("src",frameSrc);
	$(".modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
function add_sms_campaign(id){
	var frameSrc = '<?= $this->config->item('admin_base_url'); ?>sms/add_record/'+id;
	$('.popup_heading_h3').html('SMS');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	$('iframe').attr("src",frameSrc);
	$(".modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
</script> 
<!-- Twitter Script END -->
