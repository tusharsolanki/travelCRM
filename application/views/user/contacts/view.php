<?php
/*
    @Description: Contact add
    @Author: Kaushik Valiya
    @Date: 30-09-2014

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
if(!empty($joomla_tabid)) {
	$joomla_tabid = $joomla_tabid;
        $tabid = 9;
}
else {
	$joomla_tabid = 1;
    //$tabid = 1;
}
?>
<style>
/*.ui-multiselect{width:100% !important;}*/
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
      <div class="modal-body holds-the-iframe">
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
		<span class="pull-right"><a title="Back" class="btn btn-secondary  margin-left-5px" href="<?php echo $this->config->item('base_url').'user/'?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
    <?php if(!empty($this->modules_unique_name) && in_array('contact_edit',$this->modules_unique_name)){?>
     <?php  
	 	 if(!empty($data_match[0]['uct_id']) || (!empty($data_match[0]['createdby_id']) && (is_array($user_session['agent_id_array']) && in_array($data_match[0]['createdby_id'],$this->user_session['agent_id_array'])))){
	
			 ?>
             
      			<span class="pull-right"><a title="Edit" class="btn btn-secondary" href="<?php echo $this->config->item('base_url').'user/'?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>"><?php echo $this->lang->line('common_edit_title')?></a> </span>
	<?php }?>
     <?php }?>
      </div>
      
      <div class="portlet-content"> 
      <div class="col-sm-12">
        <ul class="nav nav-tabs" id="myTab1">
          <li <?php if($tabid == '' || $tabid == 1 || $tabid == 4 || $tabid == 5 || $tabid == 6){?> class="active" <?php } ?>> <a title="Contact Information" data-toggle="tab" href="#home">
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
          <li <?php if($tabid == 8){?> class="active" <?php } ?>> <a title="Buyer Preferences" data-toggle="tab" href="#buyer_preference">
            <?=$this->lang->line('contact_add_table_tab4_head');?>
            </a> </li>
          <?php }  ?>
          <?php } */  ?>
           <?php 
				if($joomla == "Yes" && $right_buyer[0]['lead_dashboard_tab'] == '1'){
					?>
                <li <?php if($tabid == 9  || $joomla_tabid == 105 || $joomla_tabid == 109 || $joomla_tabid == 106 || $joomla_tabid == 108){?> class="active" <?php } ?>> <a title="Joomla Connection" data-toggle="tab" onclick="load_view('9');" href="#joomla_connection">
                  <?=$this->lang->line('contact_add_table_tab5_head');?>
                  </a> </li>
                <?php } } ?>
          <?php /*  ?>
           
          <li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="Social Media" data-toggle="tab" href="#social_media">
            <?=$this->lang->line('contact_add_table2_tab4_head');?>
            </a> </li>
           <?php */ ?>
             <?php if(!empty($this->modules_unique_name) && in_array('listing_manager',$this->modules_unique_name)){?>
          <?php 
			if(!empty($datalist))
			{
		 ?>
          <li <?php if($tabid == 10){?> class="active" <?php } ?>> <a title="Property Information" data-toggle="tab" href="#propertyinformation"><?=$this->lang->line('contact_add_table_tab6_head');?></a> </li>
          <? } ?>
           <?php } ?>
        </ul>
        
        <div class="tab-content" id="myTab1Content">
          <div <?php if($tabid == '' || $tabid == 1 || $tabid == 4 || $tabid == 5 || $tabid == 6){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" > 
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
                <div class="col-sm-9">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }else{ echo "-"; }?>
                  </label>
                <!--</div>
                <div class="col-sm-3">-->
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['middle_name'])){ echo $editRecord[0]['middle_name']; }else{ echo ""; }?>
                  </label>
                <!--</div>
                <div class="col-sm-3">-->
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name']; }else{ echo ""; }?>
                  </label>
                </div>
              </div>
              
              <div class="row lftrgt">
                <div class="col-sm-3 form-group">
                  <label for="text-input">
                    <?=$this->lang->line('common_label_spousename');?>
                  </label>
                </div>
                <div class="col-sm-6">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['spousefirst_name'])){ echo $editRecord[0]['spousefirst_name']; }else{ echo "-"; }?>
                  </label>
                <!--</div>
                <div class="col-sm-3">-->
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
              <!--<div class="row form-group lftrgt">
                <div class="col-sm-12 checkbox">
                  <label class="">
                  Is this Contact a Lead
                  <div class="float-left margin-left-15">
                    <input type="checkbox" value="1" class=""  id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'checked="checked"'; }?>>
                  </div>
                  </label>
                </div>
              </div>-->
              
              <div class="row lftrgt">
                <div class="col-sm-4">
                  <label for="text-input">
                    Is this Contact a Lead
                  </label>
                </div>
                <div class="col-sm-4">
                  <label for="text-input">
                    <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'Yes';}else{ echo 'No';}?>
                  </label>
                </div>
              </div>
              
              <div class="row lftrgt">
                <div class="col-sm-3">
                  <label for="text-input">
                    <?=$this->lang->line('leads_dashboard_lead_type');?>
                  </label>
                </div>
                <div class="col-sm-5">
                  <label for="text-input">
                        <?php if(!empty($editRecord[0]['created_type']) && $editRecord[0]['created_type'] == '6') {
                          if(!empty($editRecord[0]['joomla_contact_type'])){ echo $editRecord[0]['joomla_contact_type']; }else{ echo "-"; }
                        } else { echo "-"; } ?>
                  </label>
                </div>
            </div>
              
            </div>
            <div class="add_email_address_div add_emailtype">
            
              <?php if(!empty($editRecord[0]['is_subscribe'])){ ?>
              <div class="col-sm-9">
              
              </div>
              <div class="col-sm-3 text-center new_red_class">
                	Unsubscribed
              </div>
              <?php } ?>
            
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
                <div class="col-sm-4">
                <?php if(!empty($rowtrans['email_address'])){ ?>
				  <a href="#basicModal" class="text_size normal_a_css" id="basicModal" data-toggle="modal" onclick="add_email_campaign('<?=$editRecord[0]['id']?>','<?=$rowtrans['id']?>')">
                  	<?=$rowtrans['email_address']?>
                  </a>
                  <?php
				  }
				  else{ echo "-";}?>

                  <?php //if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }else{ echo "-";}?>
                </div>
                <div class="col-sm-2 text-center text-center1 icheck-input-new">
                  <div class=""> 
                    <!--<label><?=$this->lang->line('common_default');?></label>-->
                    <div class="">
                      <label class="">
                      <div class="">
                        <!--<input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }else{echo "-";}?> data-required="true">-->
                         <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'Yes'; }else{echo "No";}?>
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
                <?php /*if(!empty($rowtrans['phone_no']) && !empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ ?>
                 <a href="#basicModal" class="text_size" id="basicModal" data-toggle="modal" onclick="add_sms_campaign(<?=$editRecord[0]['id']?>)">
				  	<?=$rowtrans['phone_no']?>
                  </a>
                  <?php
				  }
				  elseif(!empty($rowtrans['phone_no'])) 
				  	echo $rowtrans['phone_no'];
				  else{ echo "-";} */ ?>
                  
                   <?php if(!empty($rowtrans['phone_no'])){ ?>
                 <a href="#basicModal" class="text_size normal_a_css" id="basicModal" data-toggle="modal" onclick="add_sms_campaign('<?=$editRecord[0]['id']?>','<?=$rowtrans['id']?>')">
				  	<?=$rowtrans['phone_no']?>
                  </a>
                  <?php
				  }
				  else{ echo "-";}  ?>
                  <?php //if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }else{ echo "-";}?>
                </div>
                <div class="col-sm-2 text-center text-center1 icheck-input-new">
                  <div class=""> 
                    <!--<label><?=$this->lang->line('common_default');?></label>-->
                    <div class="">
                      <label class="">
                      <div class="">
                        <!--<input type="radio" class=""   name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true" >-->
                        <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'Yes';}else{ echo 'No';}?>
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
                 <?php /* if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                <div class="addr">
                  <label> Communication Plan </label>
                  
                  <?php 
				 $plan_list_array = array();
				 if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
						foreach($communication_trans_data as $rowtrans){
							$plan_list_array[] = $rowtrans['interaction_plan_id'];
							 ?>
					  <?php } ?>
                <?php }else{echo "-";} ?>
                
                  <?php if(!empty($communication_plans)){
							foreach($communication_plans as $row){?>
								<?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo $row['plan_name']."<br>";} ?>
							<?php } ?>
				   <?php } ?>
                </div>
                <?php } */ ?>
              </div>
            </div>
          </div>
          <div class="col-lg-5 col-xs-12">
          
          	<div class="add_emailtype autooverflow">
            <div class="col-sm-4">
              <label for="text-input">
                <?=$this->lang->line('contact_add_contact_pic');?>
              </label>
              <p>                 <?php 	
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
          
          
          
       		<div class="col-xs-8">
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
					//echo $flag." user ".$this->user_session['id'].'edit is'.$editRecord[0]['created_by'];
				if($flag == '1' && $editRecord[0]['created_type'] == 3 && (is_array($user_session['agent_id_array']) && in_array($editRecord[0]['created_by'],$this->user_session['agent_id_array'])))
				{ ?>
					<i class="fa fa-facebook scl_btn btn-facebook"></i>You are Friends with <?php if(!empty($editRecord[0]['first_name'])){ echo ucwords($editRecord[0]['first_name']); }else{ echo "-"; }?> 
				 <?php }else{
					 
					 if(!empty($editRecord[0]['fb_id']))
					 {
					  ?>
					 <li><a href="javascript:void(0);" title="Facebook Profile" class="sendrequest" id="<?=!empty($editRecord[0]['fb_id'])?$editRecord[0]['fb_id']:'';?>"><i class="fa fa-facebook scl_btn btn-facebook"></i>Add <?php if(!empty($editRecord[0]['first_name'])){ echo ucwords($editRecord[0]['first_name']); }else{ echo "-"; }?> as a Friend </a></li>
				 <?php }else{ ?> 
                 	 <li><i class="fa fa-facebook scl_btn btn-facebook"></i>
                     		<?php if(!empty($data_match[0]['uct_id']) || (!empty($data_match[0]['createdby_id']) && (is_array($user_session['agent_id_array']) && in_array($data_match[0]['createdby_id'],$this->user_session['agent_id_array'])))){ ?>
							<a href="<?php echo $this->config->item('base_url').'user/'?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>">Add Facebook Profile Link</a>
							<?php }else{ ?>
                            Add Facebook Profile Link
                            <?php } ?>
                        </li>
                 <?php } } ?>
                  
                  
                  <?php 
				  if(!empty($already_connected)){ ?>
                  <li><i class="fa fa-linkedin scl_btn btn-linkedin"></i>Already Connected.</li>
                  <?php } else if(!empty($contact_invitation_trans)){?>
                  <li><i class="fa fa-linkedin scl_btn btn-linkedin"></i>Invitation has been sent on <?php if(!empty($contact_invitation_trans[0]['create_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($contact_invitation_trans[0]['create_date'])); }else{ echo "-"; }?></li>
				  <?php }else if(!empty($email_trans_data)){?>
                  <li><i class="fa fa-linkedin scl_btn btn-linkedin"></i><a href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>/sendlinked_invitation/<?php echo $contact_id;?>">Invite <?php if(!empty($editRecord[0]['first_name'])){ echo ucfirst($editRecord[0]['first_name']); }else{ echo "-"; }?> to contact</a></li>
                  <?php } else{?>
                  <li><i class="fa fa-linkedin scl_btn btn-linkedin"></i><a href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>">Add Linkedin Profile Link</a></li>
                  <?php }?>
               
				  <?php 
				
				  if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ 
						if($rowtrans['profile_type'] == '2')
						{$flag = 'twitter';break;}
						}
					}?>
                  
                  <?php if($flag == 'twitter'){ ?>
                  	<?php foreach($profile_trans_data as $rowtrans){ 
								if($rowtrans['profile_type'] == '2')
								{
									if(!empty($twitter_screen_name))
									{
										if(in_array($rowtrans['website_name'],$twitter_screen_name))
										{
										?>
										 <li><li><i class="fa fa-twitter scl_btn btn-twitter"></i>@<?=$rowtrans['website_name'] ?> already following.</li>
										<?
										}
										else
										{
										?>
										 <li><i class="fa fa-twitter scl_btn btn-twitter"></i><a class="twitter-follow-button" href="https://twitter.com/<?=$rowtrans['website_name']?>" data-show-count="false" data-lang="en">Follow @<?=$rowtrans['website_name']?></a></li>
									<?php
										}
									}
									else
									{
									?>
									 <li><i class="fa fa-twitter scl_btn btn-twitter"></i><a class="twitter-follow-button" href="https://twitter.com/<?=$rowtrans['website_name']?>" data-show-count="false" data-lang="en">Follow @<?=$rowtrans['website_name']?></a></li>
									<?php
									}								
								}
							}
						?>
                  <?php }else{ ?>
                  <li><i class="fa fa-twitter scl_btn btn-twitter"></i><a href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>">Add Twitter Profile Link</a></li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            
            
            
          	</div>
          
            <div class="add_website add_emailtype1">
              <div>
                <div class="row add_website_div">
                  <div class="col-sm-3">
                    <label for="text-input">
                      <?=$this->lang->line('common_label_website_type');?>
                    </label>
                  </div>
                  <div class="col-sm-7">
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
                    <div class="col-sm-3">
                      <?php if(!empty($rowtrans['name'])){ echo $rowtrans['name']; }else{ echo "-";}?>
                    </div>
                    <div class="col-sm-7">
                      <?php if(!empty($rowtrans['website_name'])){ ?>
					  			<a class="normal_a_css" href="<?php echo addhttp($rowtrans['website_name']);?>" target="_blank">
									<?php echo addhttp($rowtrans['website_name']);?>
								</a>	
								<?php } else{ echo "-";}?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
                  <?php } ?>
                  <?php }else { ?>
				  <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-3">
                      <?php echo "-";?>
                    </div>
                    <div class="col-sm-7 form-group">
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
                  <div class="col-sm-3">
                    <label for="text-input">
                      <?=$this->lang->line('contact_add_profile_type');?>
                    </label>
                  </div>
                  <div class="col-sm-7">
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
                    <div class="col-sm-3">
                      <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
                      <?php if(!empty($rowtrans['profile_type']) && $rowtrans['profile_type'] == $row['id']){ echo $row['name']; }?>
                      <?php } ?>
                      <?php } ?>
                    </div>
                    <div class="col-sm-7">
                      <?php if(!empty($rowtrans['website_name'])){ 
					 
					  			if($rowtrans['profile_type'] == '2')
								{?>
                                <a class="normal_a_css" href="<?php echo "http://www.twitter.com/".$rowtrans['website_name'];?>" target="_blank">
								<?php echo "@".$rowtrans['website_name']; ?>
								</a>
								<?php }else{ ?>
                                <a class="normal_a_css" href="<?php echo $rowtrans['website_name'];?>" target="_blank">
									<?php echo $rowtrans['website_name']; ?>
                                </a>
								<?php } }else{echo "-";}?>
                    </div>
                    <div class="col-sm-1 text-center icheck-input-new">
                      <div class=""> </div>
                    </div>
                  </div>
                  <?php } ?>
                  <?php } else {?>
				  <div class="delete_social_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-3">
                      <?php echo "-"; ?>
                     
                    </div>
                    <div class="col-sm-7 form-group">
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
            
            <?php if(!empty($user_name[0]['first_name'])){?>
            <div class="add_website add_emailtype1">
              <div class="row">
                <div class="col-sm-5"><b class="assign_title">Contact Assigned to</b></div>
                <?php foreach($user_name as $row) { ?>
                <div class="col-sm-6 col-lg-6">
					<div class="margin-top-10px">
                 <?php echo trim($row['first_name'])." ".trim($row['middle_name'])." ".trim($row['last_name']); ?><?=!empty($row['agent_type'])?'('.$row['agent_type'].')':''?>
				  	</div>
				  </div>
                 <div class="col-sm-6"></div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            
            <div class="add_website add_emailtype1">
             
             <div class="row">
              <div class="col-lg-6"><b class="assign_title">Conversations</b></div>
             <div class="col-lg-6">
              <div class="activity_top"> <a title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" class="btn btn-secondary pull-right mrg21" onclick="addconversation();" >Add Conversations Log</a></div>
              </div>
             
             </div>
             
            <div class="row">
            
            <div class="col-lg-12">
              
           		<div class="conversations_table">
				<div class="">
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
						//pr($conversations);
					if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='1'){?>
					<tr class="load_conversations manual_conversations">
					 <td width="50%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="53%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
							<td width="47%">
							<?php /*?><?php if(!empty($conversations[$i]['interaction_type_name'])){ echo $conversations[$i]['interaction_type_name'];}?><?php */?>
                            <?php if(!empty($conversations[$i]['plan_name'])){ echo $conversations[$i]['plan_name'];}?>
                            </td>
						  </tr>
						  <tr>
							<td><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td>
							<!--<td><?php if(!empty($conversations[$i]['disposition_name'])){ echo $conversations[$i]['disposition_name'];}else{echo "Not Available";}?></td>-->
                            <td><?php if(!empty($conversations[$i]['created_by_admin'])){ echo "By : ".$conversations[$i]['created_by_admin'];}else{if(!empty($conversations[$i]['created_by_user'])){ echo "By : ".$conversations[$i]['created_by_user'];}else{echo "";}}?></td>
						  </tr>
						  <tr>
							<td colspan="3"><?php if(!empty($conversations[$i]['description'])){ echo $conversations[$i]['description'];}?></td>
						  </tr>
						</table>
					</td>
				   <td width="13%" valign="middle" class="text-center">
                   	
					  <?php 
					  if(is_array($user_session['agent_id_array']) && in_array($conversations[$i]['created_by'],$this->user_session['agent_id_array'])){?>
                      <a class="btn btn-xs btn-success" title="Edit Contact" onclick="editconversation(<?php echo $conversations[$i]['id'];?>);"  data-toggle="modal" data-target="#basicModal_conversation"><i class="fa fa-pencil"></i></a> &nbsp; 
											<!--<button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup1('<?php echo  $conversations[$i]['id'] ?>','<?php echo $conversations[$i]['contact_name'] ?>');"><i class="fa fa-times"></i></button>-->
					  <?php } ?>
                      
					  </td>
					</tr>
					<?php }elseif(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='12'){?>
				<tr class="load_conversations manual_conversations">
				 <td colspan="2" width="100%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                      	<td><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                        <td><b>Notes</b></td>
                      </tr>
                      <tr>
                      	<td width="53%"><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td>
                        <td width="47%">
						<?php if(!empty($conversations[$i]['created_by_admin'])){ echo "By : ".$conversations[$i]['created_by_admin'];}else{if(!empty($conversations[$i]['created_by_user'])){ echo "By : ".$conversations[$i]['created_by_user'];}else{echo "";}}?>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php if(!empty($conversations[$i]['description'])){ echo $conversations[$i]['description'];}?></td>
                      </tr>
					</table>
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
							<?php /*?><td><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td><?php */?>
							 <td><?php if(!empty($conversations[$i]['user_name'])){  echo $conversations[$i]['user_name'];}?></td>
							<td ><?php if(!empty($conversations[$i]['desc'])){ echo $conversations[$i]['desc'];}?></td>
						</table>
					</td>
				   <td width="13%" valign="middle" class="text-center">
					<?php if($conversations[$i]['is_completed_task'] == '0')
						{ ?>
						
							<input type="checkbox" id="is_completed_task_<?php echo $conversations[$i]['id']; ?>" onclick="is_completed_task('<?=$conversations[$i]['id']?>');" />
						<?php } ?>
						<span class="is_completed_<?php echo $conversations[$i]['id']; ?>"><?php if(!empty($conversations[$i]['is_completed_task']) && $conversations[$i]['is_completed_task'] == '1') { ?><label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?>
					   <a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a></span>
					  </td>
					</tr>
					<?php } else {?>
					<tr class="load_conversations">
					 <td width="50%" colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="40%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
							<td width="60%">
							
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
							<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='6'){
							
                            if(!empty($conversations[$i]['ecr_id']) && !empty($conversations[$i]['created_by_user']))
							{ ?>
							
								<a class="normal_a_css" href="<?=$this->config->item('user_base_url').'emails';?>/view_data/<?=!empty($conversations[$i]['campaign_id'])?$conversations[$i]['campaign_id']:'';?>/<?=!empty($conversations[$i]['ecr_id'])?$conversations[$i]['ecr_id']:'';?>" title="View">Email Sent From  Campaign </a>
							<? } else {?>
                            <?
							 echo "Email Sent From  Campaign ";} }?>
							<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='7')
										{ 	echo "SMS Sent From";
											if(!empty($conversations[$i]['inte_to_plan_name']))
											{echo " >> ".$conversations[$i]['inte_to_plan_name'];}
										}?>
							<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='8'){
							if(!empty($conversations[$i]['scr_id']) && !empty($conversations[$i]['created_by_user']))
							{
							?>
							<a class="normal_a_css" href="<?=$this->config->item('user_base_url').'sms';?>/view_data/<?=!empty($conversations[$i]['campaign_id'])?$conversations[$i]['campaign_id']:'';?>/<?=!empty($conversations[$i]['scr_id'])?$conversations[$i]['scr_id']:'';?>" title="View"> SMS Sent From Campaign </a>
							<?
							}else
							{
							 echo "SMS Sent From Campaign";} }?>
							
							<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='9'){ if(!empty($conversations[$i]['mail_out_type'])){echo $conversations[$i]['mail_out_type'];}}?>
							<?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='10'){ echo "Remove From Communication"; /*if(!empty($conversations[$i]['plan_name'])){echo " >> ".$conversations[$i]['plan_name'];}*/}?>
							
							 </td>
						  </tr>
                          <tr>
                      	<td colspan="2"><?php if(!empty($conversations[$i]['created_by_admin'])){ echo "By : ".$conversations[$i]['created_by_admin'];}else{if(!empty($conversations[$i]['created_by_user'])){ echo "By : ".$conversations[$i]['created_by_user'];}else{echo "";}}?></td>
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
            
            </div>
            
          </div>
          
          
          
          <div class="clear">
          	<div class="col-lg-12">
         <ul class="nav nav-tabs" id="myTab2">
          <!--<li <?php if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4){?> class="active" <?php } ?>> <a title="Conversations" data-toggle="tab" href="#conversations">
            <?=$this->lang->line('contact_add_table2_tab1_head');?>
            </a> </li>-->
             <?php /* ?>
          <li <?php if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4 || $tabid == 7 || $tabid == 8){?> class="active" <?php } ?>> <a title="Communication" data-toggle="tab" href="#communication_plan">
            <?=$this->lang->line('contact_add_table2_tab3_head');?>
            </a> </li>
             <?php */ ?>
          <li <?php if($tabid == '' || $tabid == 1 || $tabid == 5){?> class="active" <?php } ?>> <a title="Personal Touches" data-toggle="tab" href="#personaltouches">
            <?=$this->lang->line('contact_add_table2_tab2_head');?>
            </a> </li>
          <!--<li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="Social Media" data-toggle="tab" href="#social_media">
            <?=$this->lang->line('contact_add_table2_tab4_head');?>
            </a> </li>-->
        </ul>
     
        <div class="tab-content" id="myTab2Content">
          <!--<div <?php if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="conversations">
            <div class="col-lg-12">
              <div class="activity_top"> <a title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" class="btn btn-secondary pull-right mrg21" onclick="addconversation();">Add Conversations Log</a></div>
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
						//pr($conversations);
					if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='1'){?>
					<tr class="load_conversations manual_conversations">
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
                   	
					  <?php 
					  if($conversations[$i]['created_by'] == $this->user_session['id']){?>
                      <a class="btn btn-xs btn-success" title="Edit Contact" onclick="editconversation(<?php echo $conversations[$i]['id'];?>);"  data-toggle="modal" data-target="#basicModal_conversation"><i class="fa fa-pencil"></i></a> &nbsp; 
											<button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup1('<?php echo  $conversations[$i]['id'] ?>','<?php echo $conversations[$i]['contact_name'] ?>');"><i class="fa fa-times"></i></button>
					  <?php } ?>
                      
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
							<?php /*?><td><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td><?php */?>
							 <td><?php if(!empty($conversations[$i]['user_name'])){  echo $conversations[$i]['user_name'];}?></td>
							<td ><?php if(!empty($conversations[$i]['desc'])){ echo $conversations[$i]['desc'];}?></td>
						</table>
					</td>
				   <td width="13%" valign="middle" class="text-center">
					<?php if($conversations[$i]['is_completed_task'] == '0')
						{ ?>
						
							<input type="checkbox" id="is_completed_task_<?php echo $conversations[$i]['id']; ?>" onclick="is_completed_task('<?=$conversations[$i]['id']?>');" />
						<?php } ?>
						<span class="is_completed_<?php echo $conversations[$i]['id']; ?>"><?php if(!empty($conversations[$i]['is_completed_task']) && $conversations[$i]['is_completed_task'] == '1') { ?><label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?>
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
          </div>-->
          
          <div <?php /*if($tabid == '' || $tabid == 1 || $tabid == 2 || $tabid == 3 || $tabid == 4 || $tabid == 7 || $tabid == 8){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php }*/ ?> class="row tab-pane fade in" id="communication_plan" >
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
				//pr($interation_plan_communication_plan);
					if(!empty($interation_plan_communication_plan)){
					
				for($i=0;$i<count($interation_plan_communication_plan);$i++) {
				 
				  if($interation_plan_communication_plan[$i]['status'] != '0' || $interation_plan_communication_plan[$i]['is_done'] != '0')
				 {
				 ?>
                <tr>
                  <td width="50%">
                    <table width="100%" class="personaltouches" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="30%"><b><?php if(!empty($interation_plan_communication_plan[$i]['name'])){echo $interation_plan_communication_plan[$i]['name'];}; ?></b> (<?php if(!empty($interation_plan_communication_plan[$i]['task_date'])){ echo date($this->config->item('common_date_format'),strtotime($interation_plan_communication_plan[$i]['task_date']));}?>)</td>
                        <td width="77%"> Assigned To : <?php if(!empty($interation_plan_communication_plan[$i]['assign_name'])) { echo ucwords($interation_plan_communication_plan[$i]['assign_name']);}elseif(!empty($interation_plan_communication_plan[$i]['assign_name1'])) { echo ucwords($interation_plan_communication_plan[$i]['assign_name1']);}?></td>
                      </tr>
                      <tr>
                      	<td colspan="2">
                         <?php if(!empty($interation_plan_communication_plan[$i]['interaction_plan_name'])){ 
							if(!empty($interation_plan_communication_plan[$i]['interaction_plan_id']))
							{
							?>
							<a class="normal_a_css" href="<?=$this->config->item('user_base_url').'interaction_plans';?>/edit_record/<?=!empty($interation_plan_communication_plan[$i]['interaction_plan_id'])?$interation_plan_communication_plan[$i]['interaction_plan_id']:'';?>" title="View"><?= $interation_plan_communication_plan[$i]['interaction_plan_name']?></a>
							<?
							}else
							{
							echo $interation_plan_communication_plan[$i]['interaction_plan_name']; }}?>
		
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
						<?php if(!empty($interation_plan_communication_plan[$i]['interaction_name'])){
							if(!empty($interation_plan_communication_plan[$i]['interaction_plan_interaction_id']))
							{
							?>
							<a class="normal_a_css" href="<?=$this->config->item('user_base_url').'interaction';?>/view_record/<?=!empty($interation_plan_communication_plan[$i]['interaction_plan_interaction_id'])?$interation_plan_communication_plan[$i]['interaction_plan_interaction_id']:'';?>" title="View"><?= $interation_plan_communication_plan[$i]['interaction_name']?></a>
							<?
							}else
							{
							 echo $interation_plan_communication_plan[$i]['interaction_name'];} }?>
                        </td>
                      </tr>
                      <tr>
                       <!-- <td class="reload_class_<?php echo $interation_plan_communication_plan[$i]['id']; ?>"></td>-->
						</tr>
                    </table>
                  </td>
                  <td width="13%" align="center" valign="middle" class="">
					<?php if($interation_plan_communication_plan[$i]['is_done'] == '0')
					{ 
					$disabled = 0;
						if($interation_plan_communication_plan[$i]['start_type'] == 2)
						{
							$other_interaction_id = $interation_plan_communication_plan[$i]['interaction_id'];
							foreach($interation_plan_communication_plan as $row)
							{
								//echo $row['interaction_plan_interaction_id']."-".$other_interaction_id."-".$row['is_done'];
								if($row['interaction_plan_interaction_id'] == $other_interaction_id && $row['is_done'] == '0')
								{
									$disabled = 1;
									break;
								}
							}
						}
					?>
				   <input type="checkbox" id="selecctall_<?php echo $interation_plan_communication_plan[$i]['id']; ?>" value="<?php if(!empty($interation_plan_communication_plan[$i]['is_done']) && $interation_plan_communication_plan[$i]['is_done'] == '1'){ echo '1';}?>" class="selecctall_comm_plan<?=$interation_plan_communication_plan[$i]['interaction_plan_id']?>" <?php if(!empty($interation_plan_communication_plan[$i]['is_done']) && $interation_plan_communication_plan[$i]['is_done'] == '1'){ echo "checked=checked";}?> <?php if((is_array($user_session['agent_id_array']) && !in_array($interation_plan_communication_plan[$i]['assign_to'],$this->user_session['agent_id_array']))  || ($disabled == 1)){ echo "style=display:none;";} ?> onclick="is_done(this.value,<?php echo $interation_plan_communication_plan[$i]['id'];?>);" data-group="<?=$interation_plan_communication_plan[$i]['interaction_plan_id']?>">
					<?php } ?>
                   <span class="reload_class_<?php echo $interation_plan_communication_plan[$i]['id']; ?>"><?php if(!empty($interation_plan_communication_plan[$i]['is_done'])) { ?>
<?php /*?><?=$interation_plan_communication_plan[$i]['completed_by_name']?$interation_plan_communication_plan[$i]['completed_by_name']:''?><?php */?> 
                   
                   <?php if(!empty($interation_plan_communication_plan[$i]['completed_by_name'])) { echo ucwords($interation_plan_communication_plan[$i]['completed_by_name']);}elseif(!empty($interation_plan_communication_plan[$i]['completed_by_name1'])) { echo ucwords($interation_plan_communication_plan[$i]['completed_by_name1']);}?>
                   
                   <br/>
                   <?=date($this->config->item('common_date_format'),strtotime($interation_plan_communication_plan[$i]['task_completed_date']))?>
                   <label title="Completed"  class="btn_done btn_success_done pcbtn reload_class">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?>
				   <a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a></span>
                  
						</td>
                        
                        
                </tr><?php } }
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
          
          <div  <?php if($tabid == '' || $tabid == 1 || $tabid == 5){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="personaltouches"> 
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
                  <td width="13%" align="center" valign="middle" >
				  <?php if($personale_touches[$i]['is_done'] == '0' && (is_array($user_session['agent_id_array']) && in_array($personale_touches[$i]['created_by'],$this->user_session['agent_id_array']))){	?>
                    <input type="checkbox" id="selectall1_<?php echo $personale_touches[$i]['id']; ?>" value="<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo '1';}?>" class="selecctall"<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo "checked=checked";}?> onclick="is_done_p(this.value,<?php echo $personale_touches[$i]['id']; ?>);">
                <?php } ?>
                <span class="reload_class_<?php echo $personale_touches[$i]['id']; ?>"> <?php if(!empty($personale_touches[$i]['is_done'])) { ?><label title="Completed" class="btn_done btn_success_done pcbtn">Completed</label><?php }else {?><label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label><?php } ?></span>
                 
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
          
          <?php /*?><div <?php  if($tabid == 7){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="social_media" >
            <div class="col-lg-12">
              <div class="activity_top"><b>Chat:</b>
              <?php if(!empty($profile_trans_data) || !empty($editRecord[0]['linkedin_id'])){?>
               <a title="Follow-Up" href="#basicModal_3"  data-toggle="modal" class="follow btn btn-secondary pull-right mrg21">Follow-up</a><?php } ?>
               </div>
                 <div class="table-responsive">
                
            <div class="table-in-responsive">
            
            <div class="facebook_chat_history" id="facebook_chat_history">
            <?php if($editRecord[0]['fb_id']){?>
              <img src="<?php echo $this->config->item('base_url');?>images/facebook-connect.png" alt="Fb Connect" title="Login with facebook" onClick="FBLogin();"/>
            <?php } else
			{
				echo ""; } ?>
            </div>
            
          <?php if(!empty($chat)){?>  
          <table width="100%%" class="table1 table-striped2 table-striped2 table-bordered2 table-hover1 table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
               <?php for($i=0;$i<count($chat);$i++){ $data_date=$chat[$i]['msg_date_time'];?> 
                <tr>
                  <td width="5%" align="center" valign="middle">
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '1') {?>
                  <i class="fa fa-facebook scl_btn btn-facebook mrg12"></i>
                  <? } ?>
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '2') {
					 					  ?>
                 	<i class="fa fa-twitter scl_btn btn-twitter"></i>
                  <?  } ?>
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '3') {?>
                  <i class="fa fa-linkedin scl_btn btn-linkedin"></i>
                  <?  }?>
                  </td>
                  <td width="95%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <? if(!empty($chat[$i]['from_fb_name'])) {?>
                      <tr>
                        <td><b><?=$chat[$i]['from_fb_name'] ?>:</b></td>
                      </tr>
                      <? } ?>
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
          </div><?php */?>
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
              <?php $this->load->view('user/contacts/contact_document_ajax'); ?>
            </div>
          </div>
        </div>
      </div>
      <div  <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profilenew" >
      <div class="col-sm-12 col-lg-7">
      <div class="row">
        <div class="col-lg-3 col-sm-4">
          <label for="text-input">
            <?=$this->lang->line('contact_add_birth_date');?>
          </label>
        </div>
        <div class="col-lg-3 col-sm-8">
          <label for="text-input">
            <?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }else{ echo "-";}?>
          </label>
        </div>
      </div>
      <div class="row">
      <div class="col-lg-3 col-sm-4">
        <label for="text-input">
          <?=$this->lang->line('contact_add_anniversary_date');?>
        </label>
      </div>
      <div class="col-lg-3 col-sm-8">
      <label for="text-input">
      <?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }else{ echo "-";}?>
      </div>
      </div>
      <div class="add_website add_emailtype1">
              <div>
                <div class="row add_website_div">
                  <!--<div class="col-sm-5">
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
                  </div>-->
                  <?php
				  	
				    if(!empty($field_trans_data) && count($field_trans_data) > 0){
			 		foreach($field_trans_data as $rowtrans){ ?>
                  <div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
                    <div class="col-sm-3">
                    <label>
                      <?php if(!empty($rowtrans['name'])){ echo $rowtrans['name']; }else{ echo "-";}?>
                    </label>
                    </div>
                    <div class="col-sm-3 form-group">
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
      </div>
      </div>
       <?php if(!empty($this->modules_unique_name) && in_array('buyer_preferences',$this->modules_unique_name)){?>
      <div  <?php if($tabid == 8){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="buyer_preference" >
      
			
            <div class="col-sm-12">
            
               <div class="row">
                 <div class="col-sm-4">
                     <label for="text-input"><?=$this->lang->line('contact_add_price_range_from')." (In ".$this->lang->line('currency').")";?></label>
                      </div>
                      <div class="col-sm-4">
                      <label for="text-input">
                        <?php if(!empty($editRecord[0]['price_range_from'])){ echo number_format($editRecord[0]['price_range_from']); }?> </label>
                     </div>
                 </div>
                 <div class="row">
                 <div class="col-sm-4">
                     <label for="text-input"><?=$this->lang->line('contact_add_price_range_to')." (In ".$this->lang->line('currency').")";?></label>
                      </div>
                 <div class="col-sm-4">
                 <label for="text-input">
                    <?php if(!empty($editRecord[0]['price_range_to'])){ echo number_format($editRecord[0]['price_range_to']); }?>
                 </label>     
              </div>
             </div>
                <div class="row">
                 <div class="col-sm-4">
                     <label for="text-input"><?=$this->lang->line('agent_rr_weightage_label_area_range')." (In ".$this->lang->line('area_units').")";?></label>
                      </div>
                 <div class="col-sm-4">
                 <label for="text-input">
                     <?=!empty($editRecord[0]['min_area'])?$editRecord[0]['min_area'].' - ':''?><?= !empty($editRecord[0]['max_area'])?$editRecord[0]['max_area']:'';?>
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
      <? } ?>
       <?php 
		 if($joomla == "Yes" && $right_buyer[0]['lead_dashboard_tab'] == '1'){
	  ?>
            <input type="hidden" name="selected_contact_id" id="selected_contact_id" value="<?= !empty($selected_contact_id)?$selected_contact_id:''?>">
      <div  <?php if($tabid == 9 || $joomla_tabid == 105 || $joomla_tabid == 109 || $joomla_tabid == 106 || $joomla_tabid == 108){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="joomla_connection" >
        <div class="col-lg-12">
          <ul class="nav nav-tabs" id="myTab2">
            <li <?php if($tabid == '' || $tabid == 101 || $tabid == 102 || $tabid == 103 || $tabid == 104 || $joomla_tabid == 1){?> class="active" <?php } ?>> <a title="Contact Register" data-toggle="tab" href="#contact_register" onclick="load_view('104');"> Contact Register </a> </li>
            <li <?php if($tabid == 105 || $joomla_tabid == 105){?> class="active" <?php } ?>> <a title="Saved Searches" data-toggle="tab" href="#saved_searches" onclick="load_view('105');"> Saved Searches </a> </li>
            <li <?php if($tabid == 106 || $joomla_tabid == 106){?> class="active" <?php } ?>> <a title="Favorite" data-toggle="tab" href="#favorite" onclick="load_view('106');"> Favorite </a> </li>
            <li <?php if($tabid == 107){?> class="active" <?php } ?>> <a title="Properties Viewed" data-toggle="tab" href="#properties_viewed" onclick="load_view('107');"> Properties Viewed </a> </li>
            <li <?php if($tabid == 108 || $joomla_tabid == 108){?> class="active" <?php } ?>> <a title="Last Login" data-toggle="tab" href="#last_login" onclick="load_view('108');"> Last Login </a> </li>
            <li <?php if($tabid == 109 || $joomla_tabid == 109){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('contact_joomla_tab_val_searched')?>" data-toggle="tab" href="#valuation_searched" onclick="load_view('109');"><?=$this->lang->line('contact_joomla_tab_val_searched')?></a> </li>
            <?php if(!empty($cform_tab) && $cform_tab == '1') { ?>
            <li <?php if($tabid == 110 || $joomla_tabid == 110){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('contact_joomla_tab_val_contact')?>" data-toggle="tab" href="#valuation_contact" onclick="load_view('110');"><?=$this->lang->line('contact_joomla_tab_val_contact')?></a> </li>
            <li <?php if($tabid == 111 || $joomla_tabid == 111){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('contact_joomla_tab_property_contact')?>" data-toggle="tab" href="#property_contact" onclick="load_view('111');"><?=$this->lang->line('contact_joomla_tab_property_contact')?></a> </li>
            <?php } ?>
          </ul>
          <div class="tab-content" id="myTab2Content">
                 <div <?php  if($tabid == '' || $tabid == 101 || $tabid == 102 || $tabid == 103 || $tabid == 104 || $joomla_tabid == 1){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="contact_register">
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
                                    <input class="" type="text" name="searchtext_cr" id="searchtext_cr" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>" />
                                    <button class="btn howler" data-type="danger" onclick="contact_search('changesearch');" title="Search Contacts">Search</button>
                                    <button class="btn howler" data-type="danger" onclick="clearfilter_contact();" title="View All Contacts">View All</button>
                                </label>
                               </div>
                              </div>          
                                        </div>
                          <div id="common_div">
                            <?=$this->load->view('user/'.$viewname.'/view_contact_register')?>
                          </div>
                       <!-- table code end-->
                        </div>
                      </div>
                    </div>
                </div>
          </div>
          <div <?php   if($tabid == 105 || $joomla_tabid == 105){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="saved_searches">
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
                                          <input class="" type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext1)?htmlentities($searchtext1):''?>" />
                                          <button class="btn howler" data-type="danger" onclick="contact_search1('changesearch');" title="Search Contacts">Search</button>
                                          <button class="btn howler" data-type="danger" onclick="clearfilter_contact1();" title="View All Contacts">View All</button>
                                      </label>
                                 </div>
                              </div>          
                          </div>
                         <div class="row dt-rt">
                            <div class="col-sm-12">
                                <?php
                                $sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                ?>
                                <a class="btn btn-secondary pull-right btn-success howler" title="Add Saved Searches" href="<?=base_url('user/'.$viewname.'/add_saved_searches/'.$sel_contact_id);?>">Add Saved Searches</a>
                            </div>
                           </div>
                                              <div id="common_div_ss">                 
                                       <?=$this->load->view('user/'.$viewname.'/view_saved_searches')?>
                          </div>
                        <!-- table code end-->

                    </div>
                  </div>
                </div>
            </div>
        </div>
         <div  <?php if($tabid == 106 || $joomla_tabid == 106){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="favorite" >
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
                                          <input class="" type="text" name="searchtext2" id="searchtext2" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext2)?htmlentities($searchtext2):''?>" />
                                          <button class="btn howler" data-type="danger" onclick="contact_search2('changesearch');" title="Search Contacts">Search</button>
                                          <button class="btn howler" data-type="danger" onclick="clearfilter_contact2();" title="View All Contacts">View All</button>
                                      </label>
                                  </div>
                              </div>          
                          </div>
                          <div id="common_div_fav">
                            <?=$this->load->view('user/'.$viewname.'/view_favorite')?>
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
                                  <input class="" type="text" name="searchtext3" id="searchtext3" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext3)?htmlentities($searchtext3):''?>" />
                                  <button class="btn howler" data-type="danger" onclick="contact_search3('changesearch');" title="Search Contacts">Search</button>
                                  <button class="btn howler" data-type="danger" onclick="clearfilter_contact3();" title="View All Contacts">View All</button>
                              </label>
                             </div>
                            </div>          
                                      </div>
                        <div id="common_div_pv">
                          <?=$this->load->view('user/'.$viewname.'/view_properties_viewed')?>
                         </div>
                        <!-- table code end-->
                </div>
              </div>
            </div>
        </div>

    </div>
    <div  <?php  if($tabid == 108 || $joomla_tabid == 108){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="last_login" >
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
                                        <input class="" type="text" name="searchtext4" id="searchtext4" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext4)?htmlentities($searchtext4):''?>" />
                                        <button class="btn howler" data-type="danger" onclick="contact_search4('changesearch');" title="Search Contacts">Search</button>
                                        <button class="btn howler" data-type="danger" onclick="clearfilter_contact4();" title="View All Contacts">View All</button>
                                    </label>
                                </div>
                            </div>          
                        </div>
                        <div id="common_div_ll">
                         <?=$this->load->view('user/'.$viewname.'/view_last_login')?>
                        </div>

                        <!-- table code end-->
                </div>
              </div>
            </div>
        </div>

    </div>
    <div  <?php  if($tabid == 109 || $joomla_tabid == 109){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="valuation_searched" >
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
                                   <input class="" type="hidden" name="uri_segment5" id="uri_segment5" value="<?=!empty($uri_segment5)?$uri_segment5:'0'?>">
                                   <input class="" type="text" name="searchtext5" id="searchtext5" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext5)?htmlentities($searchtext5):''?>" />
                                   <button class="btn howler" data-type="danger" onclick="contact_search5('changesearch');" title="Search Contacts">Search</button>
                                   <button class="btn howler" data-type="danger" onclick="clearfilter_contact5();" title="View All Contacts">View All</button>
                               </label>
                           </div>
                       </div>          
                   </div>
                   <div id="common_div_vs">
                    <?=$this->load->view('user/'.$viewname.'/view_valuation_searched')?>
                   </div>
                   <!-- table code end-->
                </div>
              </div>
            </div>
        </div>

    </div>
              
    <div  <?php  if($tabid == 110 || $joomla_tabid == 110){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="valuation_contact" >
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                    <!-- table code start-->
                    <div class="row dt-rt">
                       <div class="col-lg-12 col-sm-12 col-xs-12">
                           <div class="dataTables_filter" id="DataTables_Table_0_filter">
                               <label>
                                   <input class="" type="hidden" name="uri_segment110" id="uri_segment110" value="<?=!empty($uri_segment110)?$uri_segment110:'0'?>">
                                   <input class="" type="text" name="searchtext110" id="searchtext110" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext110)?$searchtext110:''?>" />
                                   <button class="btn howler" data-type="danger" onclick="contact_search110('changesearch');" title="Search Contacts">Search</button>
                                   <button class="btn howler" data-type="danger" onclick="clearfilter_contact110();" title="View All Contacts">View All</button>
                               </label>
                           </div>
                       </div>          
                   </div>
                   <div id="common_div_vc">
                    <?=$this->load->view('user/'.$viewname.'/view_valuation_contact')?>
                   </div>
                   <!-- table code end-->
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- Property contact form -->    
    <div  <?php  if($tabid == 111 || $joomla_tabid == 111){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="property_contact" >
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                    <!-- table code start-->
                    <div class="row dt-rt">
                       <div class="col-lg-12 col-sm-12 col-xs-12">
                           <div class="dataTables_filter" id="DataTables_Table_0_filter">
                               <label>
                                   <input class="" type="hidden" name="uri_segment111" id="uri_segment111" value="<?=!empty($uri_segment111)?$uri_segment111:'0'?>">
                                   <input class="" type="text" name="searchtext111" id="searchtext111" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext111)?$searchtext111:''?>" />
                                   <button class="btn howler" data-type="danger" onclick="contact_search111('changesearch');" title="Search Contacts">Search</button>
                                   <button class="btn howler" data-type="danger" onclick="clearfilter_contact111();" title="View All Contacts">View All</button>
                               </label>
                           </div>
                       </div>          
                   </div>
                   <div id="common_div_pc">
                    <?=$this->load->view('user/'.$viewname.'/view_property_contact')?>
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
      
      <div <?php  if($tabid == 7){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="social_media" >
            <div class="col-lg-12">
              <div class="activity_top"><!--<b>Chat:</b>-->
              <?php if(!empty($profile_trans_data) || !empty($editRecord[0]['linkedin_id'])){?>
               <a title="Follow-Up" href="#basicModal_3"  data-toggle="modal" class="follow btn btn-secondary pull-right mrg21">Follow-up</a><?php } ?>
               </div>
                 <div class="table-responsive">
                
            <div class="table-in-responsive">
            
            <div class="facebook_chat_history" id="facebook_chat_history">
            <?php if($editRecord[0]['fb_id']){?>
              <img src="<?php echo $this->config->item('base_url');?>images/facebook-connect.png" alt="Fb Connect" title="Login with facebook" onClick="FBLogin();"/>
            <?php } else
			{
				echo ""; } ?>
            </div>
            <table width="100%%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
            	<thead>
                  <tr role="row">
                    <th width="5%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Type</th>
                    <th width="95%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Chat</th>
                  </tr>
                </thead>
          <?php if(!empty($chat)){?>  
               <?php for($i=0;$i<count($chat);$i++){ $data_date=$chat[$i]['msg_date_time'];?> 
                <tr>
                  <td width="5%" align="center" valign="middle">
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '1') {?>
                  <i class="fa fa-facebook scl_btn btn-facebook mrg12"></i>
                  <? } ?>
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '2') {
					 					  ?>
                 	<i class="fa fa-twitter scl_btn btn-twitter"></i>
                  <?  } ?>
                  <?php if(!empty($chat[$i]['type']) && $chat[$i]['type'] == '3') {?>
                  <i class="fa fa-linkedin scl_btn btn-linkedin"></i>
                  <?  }?>
                  </td>
                  <td width="95%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <? if(!empty($chat[$i]['from_fb_name'])) {?>
                      <tr>
                        <td><b><?=$chat[$i]['from_fb_name'] ?>:</b></td>
                      </tr>
                      <? } ?>
                      <tr>
                        <td><?=$chat[$i]['msg'] ?></td>
                      </tr>
                      <tr>
                        <td><?=date($this->config->item('common_datetime_format'),strtotime($data_date))?></td>
                      </tr>
                    </table>
                  </td>
                <?php }?>
              
            <!-----Chat History------>
            
            
			<?php } else{ ?>
			<tr>
				<th width="100%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"> No Record Found!</th>
				</tr>
			<?php }
			?>
            </table>
            <!-----chat history---->

            </div>
   
          </div>
            </div>
          </div>
           <?php if(!empty($this->modules_unique_name) && in_array('listing_manager',$this->modules_unique_name)){?>
       <?php 
		 if(!empty($datalist))
		 {
		 ?>
         <div <?php if($tabid == 10){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="propertyinformation">
		  	<input class="" type="hidden" name="uri_segment6" id="uri_segment6" value="<?=!empty($uri_segment6)?$uri_segment6:'0'?>">
            <div id="common_div6">
             <?=$this->load->view('user/'.$viewname.'/property_list');?>
          </div>
		  
         </div>
         <?php } ?>
         <?php } ?>
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


<div aria-hidden="true" style="display: none;" id="basicModal_conversation" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add Conversations Log</h3>
      </div>
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url').'user/';?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Action Type:
			  </td>
              <td>
               <!--<select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>-->
                
                <input id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" type="text" value="">
                
              </td>
            </tr>
            <tr>
              <td>Details:</td>
              <td>
                <textarea class="form-control" name="description" id="description"></textarea>
              </td>
            </tr>
            <!--<tr>
              <td>Disposition:</td>
              <td>
                 <select id="disposition_type" name="disposition_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>-->
          </table>
		  
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
      	<input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
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
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url').'user/';?><?php echo $path_per_tou;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Interaction Type:</td>
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
                 <input id="followup_date" name="followup_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['followup_date']) && $editRecord[0]['followup_date'] != '0000-00-00' && $editRecord[0]['followup_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['followup_date'])); }?>" readonly="readonly">
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
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="last_action_popup" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url').'user/';?><?php echo $path_comm;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		<input type="hidden" value="" id="is_done_hidd_tab" name="is_done_hidd_tab" />
		<input type="hidden" value="<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:0?>" id="hid_contact_id" name="hid_contact_id" />
		<input type="hidden" value="" id="hid_current_plan_id" name="hid_current_plan_id" />
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
		  	<tr>
               <td>
                 <input type="radio" value="1" name="rd_start_interaction_plan" checked="checked" data-required="true" onclick="display_plan_list(this.value);" >
              </td>
			   <td>Do not take any action</td>
            </tr>
            <tr>
			  <td width="10%">
                 <input type="radio" value="2" name="rd_start_interaction_plan" data-required="true" onclick="display_plan_list(this.value);" >
              </td>
              <td>Restart Current Communication</td>
            </tr>
			
			<tr style="display:none;" class="tr_res_interaction_plan" >
              <td></td>
              <td class="form-group">
			   Next Action Start Date:
               <input id="r_next_interaction_start_date" name="r_next_interaction_start_date" class="form-control parsley-validated" readonly="readonly" type="text" value="">
              </td>
            </tr>
			
            <tr>
				<td>
                 <input type="radio" value="3" name="rd_start_interaction_plan" <?php if(!empty($rowtrans['rd_start_interaction_plan']) && $rowtrans['rd_start_interaction_plan'] == '1'){ echo 'checked="checked"'; }?> data-required="true" onclick="display_plan_list(this.value);" >
              </td>
               <td>Start New Communication</td>
            </tr>
			
			<tr style="display:none;" class="tr_interaction_plan" >
              <td></td>
              <td class="form-group">
			   Next Action Start Date:
               <input id="next_interaction_start_date" name="next_interaction_start_date" readonly="readonly" class="form-control parsley-validated" type="text" value="">
              </td>
            </tr>
			
			<tr style="display:none;" class="tr_interaction_plan" >
				<td></td>
               	<td class="form-group">
				<select class="form-control parsley-validated" name="slt_interaction_plan" id="slt_interaction_plan">
					<option value="">Select Communication</option>
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
      <div id="row_data">
        <div class="col-sm-12">
        <div class="row">
        <div class="col-sm-12">
              <label for="text-input">Platform Type</label>
		</div>
		
       	 <div class="col-sm-11">
         <?php //pr($profile_trans_data);?>
              <select class="selectBox" name='platform' id='platform'>
              <?php 
			  $f=0;
			$t=0;
			  for($i=0;$i<count($profile_trans_data);$i++){
				  
              if($profile_trans_data[$i]['profile_type']=='1' && $profile_trans_data[$i]['website_name']!='' && $f == 0){$f=1;?>
              <option value="1" selected="selected">Facebook</option>
              <?php } if($profile_trans_data[$i]['profile_type']=='2' && $profile_trans_data[$i]['website_name']!='' && $t == 0){$t=1; ?>
              <option value="2">Twitter</option>
             <? }?>
              <?php } if(!empty($editRecord[0]['linkedin_id'])){?>
			  <option value="3">Linkedin</option>
              <?php } ?>
              </select>
         </div>
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
          
          <form class="form parsley-form" enctype="multipart/form-data" name="twitter_form" id="twitter_form" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').$viewname.'/send_twitter_message'?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
<div class="col-sm-12 form-group">
			<div class="row">
             <div class="col-sm-12">
              <label for="text-input">Twitter Handle</label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='screen_name' id='screen_name'  >
                 <?php if(isset($profile_trans_data) && count($profile_trans_data) > 0){
							foreach($profile_trans_data as $row1){
								if(!empty($row1['website_name']) && $row1['profile_type'] == '2'){?>
                <option value="<?php echo $row1['website_name'];?>"><?php echo $row1['website_name'];?></option>
                <?php 		}
							}
							 
						} ?>
              </select>
              </div>
     
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
            </div>
			</div>
            <div class="col-sm-12 form-group">
			<div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_category' id='category2' onChange="selectsubcategory2(this.value)" >
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
     
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
            </div>
			</div>	
           <div class="col-sm-12">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			   <select class="selectBox" name='template_name' id='template_name2'>
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
                  <textarea name="email_message" class="form-control parsley-validated" id="twitter_email_message" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('twitter_email_message',
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
<input id="id" name="platform" type="hidden" value="2">
<!--<input type="submit" class="btn btn-secondary" value="Send On Twitter" id="send_on_twitter"  title="Send On Twitter" name="submitbtn" />
--><!--<a href="https://twitter.com/share" class="my_tweet_a btn btn-secondary" data-via="demotops" data-count="none" onclick="user_post();" >Public Tweet</a>-->
<input type="submit" class="btn btn-secondary" value="Direct Message"  title="Direct Message" onclick="return senddirectdata();" name="submit_direct" />
<input type="submit" class="btn btn-secondary" value="Tweet"  title="Tweet" onclick="return senddirectdata();" name="submit_twit" />
<!--<a href="https://twitter.com/share?text=text goes here&url=a" class="my_tweet_a btn btn-secondary" data-via="demotops" data-count="none">Tweet</a>-->
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
          
          <form class="form parsley-form" enctype="multipart/form-data" name="linkedin_form" id="linkedin_form" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').$viewname.'/sendlinked_message'?>" novalidate>
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
     
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory1'>
              </select>
              <span id="category_loader"></span>
              </div>-->
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
         <input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo htmlentities($editRecord[0]['template_subject']); }?>" data-required="true">
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
                <input id="id" name="platform" type="hidden" value="3">
		      </div>
             </div>
            </div>
               
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="button" class="btn btn-secondary" value="Send On linkedin"  title="Send On linkedin" onclick="return setdefaultdata();" name="submitbtn1" />
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
    <div class="modal-dialog modal-dialog_lg">
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
    <div class="modal-dialog modal-dialog_lg">
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
    <div class="modal-dialog modal-dialog_lg">
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
    <div class="modal-dialog modal-dialog_lg">
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
    <div class="modal-dialog modal-dialog_lg">
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


<!-- Valuation Searched Popup -->
<div aria-hidden="true" style="display: none;" id="valuation_searched_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_valuation_searched_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title"><?=$this->lang->line('contact_joomla_tab_val_searched')?></h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 valuation_searched_popup">
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

<!-- Valuation Contact Popup -->
<div aria-hidden="true" style="display: none;" id="valuation_contact_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_valuation_contact_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title"><?=$this->lang->line('contact_joomla_tab_val_contact')?></h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 valuation_contact_popup">
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

<!-- Property Contact form Popup -->
<div aria-hidden="true" style="display: none;" id="property_contact_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_property_contact_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title"><?=$this->lang->line('contact_joomla_tab_property_contact')?></h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 property_contact_popup">
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
function setdefaultdata()
 {
	
	var ck_edit = CKEDITOR.instances.email_message1.getData();
	if(ck_edit == "")
	{
		$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
	else
	{
		var html=CKEDITOR.instances.email_message1.getSnapshot();
		var dom=document.createElement("DIV");
		dom.innerHTML=html;
		var plain_text=(dom.textContent || dom.innerText);
		 CKEDITOR.instances.email_message1.setData(plain_text);
		
		//create and set a 128 char snippet to the hidden form field
		//var snippet=plain_text.substr(0,140);
		$('#linkedin_form').submit();
	}
 }
 function senddirectdata()
 {
	
	var ck_edit = CKEDITOR.instances.twitter_email_message.getData();
	if(ck_edit == "")
	{
		$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
	else
	{
		var html=CKEDITOR.instances.twitter_email_message.getSnapshot();
		var dom=document.createElement("DIV");
		dom.innerHTML=html;
		var plain_text=(dom.textContent || dom.innerText);
		CKEDITOR.instances.twitter_email_message.setData(plain_text);
		
		//create and set a 128 char snippet to the hidden form field
		//var snippet=plain_text.substr(0,140);
		$('#twitter_form').submit();
	}
 }
$(document).ready(function(){
        <?php if($joomla_tabid == '105') { ?>
            contact_search1('');
        <?php } ?>
	<?php 
		if(!empty($msg1))
		{
			?>
				$.confirm({'title': 'Alert','message': " <strong> <?=$msg1?></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			<?	
			$newdata = array('msg'  => '');
           $this->session->set_userdata('message_session1', $newdata);
		}
		
		?>
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

	function is_completed_task(conversation_id)
	{
			var msg = 'Are you sure want to complete Task ';
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
		   'action': function(){
		   
			if($('#is_completed_task_'+conversation_id).prop('checked') == true)
				var id = 1;
			else
				var id = 0;
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('base_url')."user/".$viewname.'/is_completed_task';?>",
				dataType: 'json',
				async: false,
				data: {'conversation_id':conversation_id},
				success: function(data){
						if(id == 1)
						{
							html='<label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label>';
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
				url: "<?php echo $this->config->item('base_url')."user/".$viewname.'/interaction_id_done';?>",
				dataType: 'json',
				async: false,
				data: {'id':id,'is_done_hidd':is_done_hidd},
				success: function(data){
				
						/*if(id == 1)
						{
							html='<label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label>';
						}
						else
						{
							html='<label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label>';
						}	
						$(".reload_class_"+is_done_hidd).html(html);
						$("#selecctall_"+is_done_hidd).hide(data);*/
						
						window.location.reload();
						
						}
			});
							
			}},'No'	: {'class'	: 'special',
			'action': function(){
				$("#selecctall_"+is_done_hidd).attr('checked',false);
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
			url: "<?php echo $this->config->item('base_url').'user/'.$viewname.'/personal_id_done';?>",
			dataType: 'json',
			async: false,
			data: {'id':id,'is_done_hidd':is_done_hidd},
			success: function(data){
					
					if(id == 1)
					{
						html='<label title="Completed" class="btn_done btn_success_done pcbtn reload_class">Completed</label>';
					}
					else
					{
						html='<label title="Pending" class="btn_danger_pending btn_done pcbtn">Pending</label>';
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
			url: "<?php echo $this->config->item('base_url').'user/'.$viewname.'/ajax_delete_conversations';?>",
			dataType: 'json',
			async: false,
			data: {'id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/contacts/view_record/"+contact_id,
					
				
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
			url: '<?=$this->config->item('base_url')."user/".$viewname."/get_conversation_data";?>',
			success:function(data){
				
				//document.forms['<?php echo $viewname;?>'].elements['sl_interaction_type'].value=data.plan_type_id;
				//document.forms['<?php echo $viewname;?>'].elements['disposition_type'].value=data.dis_id;
				$("#sl_interaction_type").val(data.plan_name);
				$("#description").html(data.description);
				$("#activitylog").html('Update Activity log');
				 $('#<?php echo $viewname;?>').attr('action', "<?php echo $this->config->item('base_url').'user/'?><?php echo $path_per_2."/";?>"+data.id);
		
				}
			});//ajax
	}
	function addconversation()
	{				
		//document.forms['<?php echo $viewname;?>'].elements['sl_interaction_type'].value=4;
		//document.forms['<?php echo $viewname;?>'].elements['disposition_type'].value=2;
		$("#sl_interaction_type").val('');
		$("#description").html('');
		$("#activitylog").html('Add Conversation');
		$('#<?php echo $viewname;?>').attr('action', "<?php echo $this->config->item('user_base_url')?><?php echo $path_per_1;?>");
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
	$("select#platform").multiselect({
		 multiple: false,
		 selectedList: 1
	}).multiselectfilter();
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
$("select#template_name2").multiselect({
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
	$("select#category2").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();
	$("select#screen_name").multiselect({
		 multiple: false,
		 header: "Screen Name",
		 noneSelectedText: "Screen Name",
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


	

 
  
/*function selectsubcategory(id){
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
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_subcategory';?>",
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
*/
function selectsubcategory(id)
{
		var category_id = $("#category").val();
		if(category_id!="-1"){
			$("#template_name").html("<option value='-1'>Template Name</option>");
			loadtemplateData('template',category_id);  
		}else{
		   $("#template_name").html("<option value='-1'>Template Name</option>");
		   $("select#template_name").multiselect('refresh').multiselectfilter();
 	} 
}
function loadtemplateData(loadType,loadcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId},
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
/*$("#subcategory").change(function() {
	var subcategory_id = $("#subcategory").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category").val();
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}
});*/
/*function loadtemplateData(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
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
}*/

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>
$("#template_name2").change(function() {
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name2").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			$("#txt_template_subject").val(item.template_subject);
			
			var full_msg = item.post_content;
			
			CKEDITOR.instances.twitter_email_message.setData(full_msg);
			
			var html=full_msg;
			var dom=document.createElement("DIV");
			dom.innerHTML=html;
			var plain_text=(dom.textContent || dom.innerText);
		
			//create and set a 128 char snippet to the hidden form field
			var snippet=plain_text.substr(0,140);
			var test = '';
			<?php 
				if(!empty($profile_trans_data))
				{
					foreach($profile_trans_data as $rowtrans) { 
						if($rowtrans['profile_type']== '2' ){
				?>
					 test = '@<?=$rowtrans['website_name']?>';
				<?php
						}
					}
				}
			?>		
								
			$("a.my_tweet_a").attr("href", "https://twitter.com/share?text="+test+ " " + snippet +"&url=a");
			});
		 }
		});
});

///new code for linkedin

function selectsubcategory1(id)
{
		var category_id = $("#category1").val();
		if(category_id!="-1"){
			$("#template_name1").html("<option value='-1'>Template Name</option>");
	   		loadtemplateData1('template',category_id);
		}else{
		   $("#template_name1").html("<option value='-1'>Template Name</option>");
	  	   $("select#template_name1").multiselect('refresh').multiselectfilter();
 	} 
}


function loadtemplateData1(loadType,loadcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId},
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
//twitter cat
function selectsubcategory2(id)
{
		var category_id = $("#category2").val();
		if(category_id!="-1"){
			$("#template_name2").html("<option value='-1'>Template Name</option>");
	   		loadtemplateData2('template',category_id);
		}else{
		   $("#template_name2").html("<option value='-1'>Template Name</option>");
	  	   $("select#template_name2").multiselect('refresh').multiselectfilter();
 	} 
}


function loadtemplateData2(loadType,loadcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId},
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
					
					$('#template_name2').append(option);
			});
		$("select#template_name2").multiselect('refresh').multiselectfilter();
						
     }
   });
}

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory2('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>

$("#template_name1").change(function() {
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name1").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			$("#txt_template_subject").val(item.template_subject);
			CKEDITOR.instances.email_message1.setData(item.post_content);
			});
		 }
		});
});
/*$("#template_name2").change(function() {
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name2").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			//$("#txt_template_subject").val(item.template_subject);
			CKEDITOR.instances.twitter_email_message.setData(item.post_content);
			});
		 }
		});
});*/

	</script>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '<?=$this->config->item('facebook_api_key')?>', // replace your app id here
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
					 url: "<?=base_url().'user/'.$path_view?>",
					 data: {login:'login'},
					 cache: false,
	 				 beforeSend: function() {
					$('#facebook_chat_history').block({ message: 'Loading...' }); 
					  },
					 success: function(data){
						window.location.href = "<?=base_url().'user/'.$path_view1?>";
						
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
					 url: "<?=base_url().'user/'.$fb_path?>",
					 data: {login:'login',contact:contact},
					 cache: false,
	 				 beforeSend: function() {
							$('.loding_win').block({ message: 'Loading...' }); 
						  },
					 success: function(data){
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
					$( ".close" ).trigger( "click" );
					$('.loding_win').unblock(); 
					}
					});

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

function add_email_campaign(id,email_trans_id){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>emails/add_record/'+id+'/'+email_trans_id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
function add_sms_campaign(id,phone_trans_id){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>sms/add_record/'+id+'/'+phone_trans_id;
	$('.popup_heading_h3').html('SMS');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
	
</script>
<script>

  !function(d,s,id)
  {

	//var message = CKEDITOR.instances.email_message.getData();
	
	/*var html=CKEDITOR.instances.email_message.getSnapshot();
    var dom=document.createElement("DIV");
    dom.innerHTML=html;
    var plain_text=(dom.textContent || dom.innerText);

    //create and set a 128 char snippet to the hidden form field
    var snippet=plain_text.substr(0,140);*/
    //document.getElementById("hidden_snippet").value=snippet;
	
	//alert(snippet);
	
	//$("a.twitter-share-button").attr("href", "https://twitter.com/share?text="+message+"&url=a");

    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
	
    if(!d.getElementById(id))
    {
      js=d.createElement(s);
      js.id=id;
      js.src=p+'://platform.twitter.com/widgets.js';
      fjs.parentNode.insertBefore(js,fjs);
    }
  }(document, 'script', 'twitter-wjs');
  
  //try{
CKEDITOR.instances.twitter_email_message.on('change', function() { 
	
	var html=CKEDITOR.instances.twitter_email_message.getSnapshot();
    var dom=document.createElement("DIV");
    dom.innerHTML=html;
    var plain_text=(dom.textContent || dom.innerText);

    //create and set a 128 char snippet to the hidden form field
    var snippet=plain_text.substr(0,140);
	var test = '';
	<?php 
		if(!empty($profile_trans_data))
		{
			foreach($profile_trans_data as $rowtrans) { 
				if($rowtrans['profile_type']== '2' ){
		?>
			 test = '@<?=$rowtrans['website_name']?>';
		<?php
				}
			}
		}
	?>		
	
	$("a.my_tweet_a").attr("href", "https://twitter.com/share?text="+test+" "+snippet+"&url=a");
	
});
//}
//catch(e){}
  function load_view(id)
    {
        if(id == '104') {
            //$("#contact_register").hide();
            //$("#favorite").hide();
        }
        else if(id == '105') { // Saved Searches
            contact_search1('');
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
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/selectedview_session';?>",
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
/*Contact Register*/

$('body').on('click','.contact_register_popup_btn',function(e){
    $(".contact_register_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/contact_register_popup';?>",
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
            url: "<?php echo base_url();?>user/contacts/view_record_index/"+id,
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
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_record_index_savser';?>",
        data: {'search_id':search_id,'result_type':'ajax'},
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
        url: "<?php echo base_url();?>user/contacts/view_record_index_savser/"+id,
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
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/favorite_popup';?>",
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
        url: "<?php echo base_url();?>user/contacts/view_record_index_fav/"+id,
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
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/properties_viewed_popup';?>",
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
        url: "<?php echo base_url();?>user/contacts/view_record_index_prop_view/"+id,
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
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/last_login_popup';?>",
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
        url: "<?php echo base_url();?>user/contacts/view_record_index_lastlog/"+id,
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

$('body').on('click','.valuation_searched_popup_btn',function(e){
    $(".valuation_searched_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/valuation_searched_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
            $(".valuation_searched_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //console.log(textStatus, errorThrown);
            $(".valuation_searched_popup").html('Something went wrong.');
        }
    });
});

function contact_search5(allflag)
{
    var uri_segment = $("#uri_segment5").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/contacts/view_record_index_valuation_searched/"+id,
        data: {
        result_type:'ajax',perpage:$("#perpage5").val(),searchtext:$("#searchtext5").val(),sortfield:$("#sortfield5").val(),sortby:$("#sortby5").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_vs').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_vs").html(html);
            //$('#common_div_ll').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext5').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search5('changesearch');
        }
    });
});

function clearfilter_contact5()
{
    $("#searchtext5").val("");
    contact_search5('all');
}

function changepages5()
{
    contact_search5('');	
}

function applysortfilte_contact5(sortfilter,sorttype)
{
    $("#sortfield5").val(sortfilter);
    $("#sortby5").val(sorttype);
    contact_search5('changesorting');
}

$('body').on('click','#common_tb5 a.paginclass_A',function(e){

    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport5").val(),perpage:$("#perpage5").val(),searchtext:$("#searchtext5").val(),sortfield:$("#sortfield5").val(),sortby:$("#sortby5").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_vs").html(html);
            $.unblockUI();
        }
    });
    return false;
});
	
</script> 
<script>

function contact_search6(allflag6)
	{
            var uri_segment6 = $("#uri_segment6").val();
			//alert(uri_segment);
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/<?=$viewname?>/property_listing/"+uri_segment6,
			data: {
			result_type:'ajax',perpage6:$("#perpage6").val(),searchtext6:$("#searchtext6").val(),sortfield6:$("#sortfield6").val(),sortby6:$("#sortby6").val(),allflag6:allflag6,id:<?=$contact_id?>
		},
		beforeSend: function() {
					$('#common_div6').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div6").html(html);
				$('#common_div6').unblock(); 
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('#searchtext6').keyup(function(event) 
		  {
				
				if (event.keyCode == 13) {
						contact_search6('changesearch');
				}
		  });
	});
	
	function clearfilter_contact6()
	{
		$("#searchtext6").val("");
		contact_search6('all');
	}
	
	function changepages6()
	{
		contact_search6('');	
	}
	
  	function applysortfilte_contact6(sortfilter,sorttype)
	{
		$("#sortfield6").val(sortfilter);
		$("#sortby6").val(sorttype);
		contact_search6('changesorting');
	}
	
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage6:$("#perpage6").val(),searchtext6:$("#searchtext6").val(),sortfield6:$("#sortfield6").val(),sortby6:$("#sortby6").val(),id:<?=$contact_id?>
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div6").html(html);
					$.unblockUI();
                }
            });
            return false;
        });

function reload_tab()
{
	window.location.reload(); 
}
</script>
<script>
    $('body').on('click','.valuation_contact_popup_btn',function(e){
        $(".valuation_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
        var search_id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/valuation_contact_popup';?>",
            data: {'search_id':search_id},
            success: function(html){
                $(".valuation_contact_popup").html(html);	
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".valuation_cotnact_popup").html('Something went wrong.');
            }
        });
    });

    function contact_search110(allflag)
    {
        var uri_segment = $("#uri_segment110").val();
        var id = '<?php echo $this->router->uri->segments[4]; ?>';
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>user/contacts/view_record_index_valuation_contact/"+id,
            data: {
            result_type:'ajax',perpage:$("#perpage110").val(),searchtext:$("#searchtext110").val(),sortfield:$("#sortfield110").val(),sortby:$("#sortby110").val(),allflag:allflag,id:id
            },
            beforeSend: function() {
                $('#common_div_vc').block({ message: 'Loading...' }); 
            },
            success: function(html){
                $("#common_div_vc").html(html);
                //$('#common_div_ll').unblock(); 
            }
        });
        return false;
    }

    $(document).ready(function(){
        $('#searchtext110').keyup(function(event) 
        {
            if (event.keyCode == 13) {
                contact_search110('changesearch');
            }
        });
    });

    function clearfilter_contact110()
    {
        $("#searchtext110").val("");
        contact_search110('all');
    }

    function changepages110()
    {
        contact_search110('');	
    }

    function applysortfilte_contact110(sortfilter,sorttype)
    {
        $("#sortfield110").val(sortfilter);
        $("#sortby110").val(sorttype);
        contact_search110('changesorting');
    }

    $('body').on('click','#common_tb110 a.paginclass_A',function(e){
        var id = '<?php echo $this->router->uri->segments[4]; ?>';
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type:'ajax',perpage:$("#perpage110").val(),searchtext:$("#searchtext110").val(),sortfield:$("#sortfield110").val(),sortby:$("#sortby110").val(),id:id
            },
            beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            },
            success: function(html){
                $("#common_div_vc").html(html);
                $.unblockUI();
            }
        });
        return false;
    });
</script>
<script>
    $('body').on('click','.property_contact_popup_btn',function(e){
        $(".property_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
        var search_id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/property_contact_popup';?>",
            data: {'search_id':search_id},
            success: function(html){
                $(".property_contact_popup").html(html);	
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".property_cotnact_popup").html('Something went wrong.');
            }
        });
    });

    function contact_search111(allflag)
    {
        var uri_segment = $("#uri_segment111").val();
        var id = '<?php echo $this->router->uri->segments[4]; ?>';
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>user/contacts/view_record_index_property_contact/"+id,
            data: {
            result_type:'ajax',perpage:$("#perpage111").val(),searchtext:$("#searchtext111").val(),sortfield:$("#sortfield111").val(),sortby:$("#sortby111").val(),allflag:allflag,id:id
            },
            beforeSend: function() {
                $('#common_div_pc').block({ message: 'Loading...' }); 
            },
            success: function(html){
                $("#common_div_pc").html(html);
                //$('#common_div_ll').unblock(); 
            }
        });
        return false;
    }

    $(document).ready(function(){
        $('#searchtext111').keyup(function(event) 
        {
            if (event.keyCode == 13) {
                contact_search111('changesearch');
            }
        });
    });

    function clearfilter_contact111()
    {
        $("#searchtext111").val("");
        contact_search111('all');
    }

    function changepages111()
    {
        contact_search111('');	
    }

    function applysortfilte_contact111(sortfilter,sorttype)
    {
        $("#sortfield111").val(sortfilter);
        $("#sortby111").val(sorttype);
        contact_search111('changesorting');
    }

    $('body').on('click','#common_tb111 a.paginclass_A',function(e){
        var id = '<?php echo $this->router->uri->segments[4]; ?>';
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type:'ajax',perpage:$("#perpage111").val(),searchtext:$("#searchtext111").val(),sortfield:$("#sortfield111").val(),sortby:$("#sortby111").val(),id:id
            },
            beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            },
            success: function(html){
                $("#common_div_pc").html(html);
                $.unblockUI();
            }
        });
        return false;
    });
</script>