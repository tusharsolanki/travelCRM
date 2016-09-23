<?php
/*
    @Description: Joomla Dashboard view
    @Author     : Sanjay Moghairya
    @Date       : 01-12-2014

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
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('contact_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3><i class="fa fa-tasks"></i><?=$this->lang->line('contact_view_table_head');?></h3>
                        <span class="pull-right"><a title="Back" class="btn btn-secondary  margin-left-5px" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
                        <span class="pull-right"><a title="Edit" class="btn btn-secondary " href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>"><?php echo $this->lang->line('common_edit_title')?></a> </span>
                    </div>
      
                    <div class="portlet-content"> 
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs" id="myTab1">
                                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="Contact Information" onclick="load_view('1');" data-toggle="tab" href="#home">
                                    <?=$this->lang->line('leads_dashboard_summary_head');?>
                                </a> </li>

                                <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Properties" data-toggle="tab" onclick="load_view('2');" href="#properties">
                                    <?=$this->lang->line('leads_dashboard_properties_head');?>
                                </a> </li>
                                <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Extra Information" data-toggle="tab" onclick="load_view('3');" href="#searches">
                                  <?=$this->lang->line('leads_dashboard_searches_head');?>
                                </a> </li>
                                <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="Buyer Preferences" data-toggle="tab" onclick="load_view('8');" href="#edit_profile">
                                  <?=$this->lang->line('leads_dashboard_edit_profile_head');?>
                                </a> </li>
                            </ul>
                            <div class="tab-content" id="myTab1Content">
                                <div <?php if($tabid == '' || $tabid == 1){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" > 
                                    <div class="col-lg-7 col-xs-12">
                                        <div class="add_emailtype">
                                            <div class="row lftrgt">
                                                <div class="col-sm-3">
                                                    <label for="text-input"><?=$this->lang->line('contact_add_prefix');?></label>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                  <label for="text-input"><?php if(!empty($editRecord[0]['prefix'])){echo $editRecord[0]['prefix'];}else{ echo "-"; }?></label>
                                                </div>
                                            </div>
                                            <div class="row lftrgt">
                                                <div class="col-sm-3 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_name');?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }else{ echo "-"; }?></label>
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['middle_name'])){ echo $editRecord[0]['middle_name']; }else{ echo ""; }?></label>
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name']; }else{ echo ""; }?></label>
                                                </div>
                                            </div>
                                            <div class="row lftrgt">
                                                <div class="col-sm-3 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_spousename');?></label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['spousefirst_name'])){ echo $editRecord[0]['spousefirst_name']; }else{ echo "-"; }?></label>
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['spouselast_name'])){ echo $editRecord[0]['spouselast_name']; }else{ echo "-"; }?></label>
                                                </div>
                                            </div>
                                            <div class="row lftrgt">
                                                <div class="col-sm-3">
                                                    <label for="text-input"><?=$this->lang->line('contact_add_company');?></label>
                                                </div>
                                                <div class="col-sm-3">
                                                  <label for="text-input"><?php if(!empty($editRecord[0]['company_name'])){ echo $editRecord[0]['company_name']; }else{ echo "-"; }?></label>
                                                </div>
                                            </div>
                                            <div class="row lftrgt">
                                                <div class="col-sm-3">
                                                    <label for="text-input"><?=$this->lang->line('contact_add_title1');?></label>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['company_post'])){ echo $editRecord[0]['company_post']; }else{ echo "-"; }?>s</label>
                                                </div>
                                            </div>
                                            <div class="row lftrgt">
                                                <div class="col-sm-4">
                                                    <label for="text-input">Is this Contact a Lead</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="text-input"><?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'Yes';}else{ echo 'No';}?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add_email_address_div add_emailtype">
                                            <?php if(!empty($editRecord[0]['is_subscribe'])){ ?>
                                                <div class="col-sm-9"></div>
                                                <div class="col-sm-3 text-center new_red_class">Unsubscribed</div>
                                            <?php } ?>
                                            <div class="col-sm-4">
                                                <label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>
                                            </div>
                                            <div class="col-sm-2 text-center text-center1 icheck-input-new">
                                                <div class=""><label><?=$this->lang->line('common_default');?></label></div>
                                            </div>
                                            <div class="col-sm-1 text-center icheck-input-new">
                                                <div class=""><label>&nbsp;</label></div>
                                            </div>
              
                                            <?php
                                            if(!empty($email_trans_data) && count($email_trans_data) > 0){
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
                                                        </div>
                                                        <div class="col-sm-2 text-center text-center1 icheck-input-new">
                                                            <div class=""> 
                                                                <div class="">
                                                                    <label class="">
                                                                        <div class="">
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
                                                <label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>
                                            </div>
                                            <div class="col-sm-2 text-center text-center1 icheck-input-new">
                                                <div class="">
                                                    <label><?=$this->lang->line('common_default');?></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 text-center  icheck-input-new">
                                                <div class=""><label>&nbsp;</label></div>
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
                                                            <?php if(!empty($rowtrans['phone_no'])){ ?>
                                                                <a href="#basicModal" class="text_size normal_a_css" id="basicModal" data-toggle="modal" onclick="add_sms_campaign('<?=$editRecord[0]['id']?>','<?=$rowtrans['id']?>')">
                                                                    <?=$rowtrans['phone_no']?>
                                                                </a>
                                                            <?php
                                                            }
                                                            else{ echo "-"; }?>
                                                        </div>
                                                        <div class="col-sm-2 text-center text-center1 icheck-input-new">
                                                            <div class=""> 
                                                                <div class="">
                                                                    <label class="">
                                                                        <div class="">
                                                                            <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'Yes';}else{ echo 'No';}?>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1 text-center icheck-input-new">
                                                            <div class=""></div>
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
                                                    <label><?=$this->lang->line('contact_add_notes');?></label>
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
                                                    <label> Communication Plan </label>
                                                    <?php 
                                                    $plan_list_array = array();
                                                    if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
                                                        foreach($communication_trans_data as $rowtrans){
                                                            $plan_list_array[] = $rowtrans['interaction_plan_id'];
                                                        ?>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if(!empty($communication_plans)){
                                                        foreach($communication_plans as $row){?>
                                                            <?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo $row['plan_name']."<br>";} ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-12">
                                        <div class="add_emailtype autooverflow">
                                            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-4">
                                                <label for="text-input" class="cont"><?=$this->lang->line('contact_add_contact_pic');?></label>
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
                                            </div>
                                        </div>
       
                                        <div class="add_website add_emailtype1">
                                            <div>
                                                <div class="row add_website_div">
                                                    <div class="col-sm-3">
                                                        <label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <label for="text-input"><?=$this->lang->line('contact_add_website');?></label>
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
                                                                        <a class="normal_a_css" href="<?php echo $rowtrans['website_name'];?>" target="_blank">
                                                                            <?php echo $rowtrans['website_name'];?>
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
                                                            <div class="col-sm-3"><?php echo "-";?>s</div>
                                                            <div class="col-sm-7 form-group"><?php echo "-";?></div>
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
                                                        <label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <label for="text-input"><?=$this->lang->line('contact_add_website');?></label>
                                                    </div>
                                                    <div class="col-sm-1 text-center icheck-input-new">
                                                        <div class=""></div>
                                                    </div>
                                                    <?php if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
                                                        foreach($profile_trans_data as $rowtrans){?>

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
                                                            <div class="col-sm-3"><?php echo "-"; ?></div>
                                                            <div class="col-sm-7 form-group"><?php echo "-";?></div>
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
                                                    <div class="col-sm-6"><b class="assign_title">Contact Assigned to</b></div>
                                                    <div class="col-sm-6 col-lg-6">
                                                        <div class="margin-top-10px">
                                                            <?php echo $user_name[0]['first_name']." ".$user_name[0]['middle_name']." ".$user_name[0]['last_name']; ?>
                                                        </div>
                                                    </div>
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
                                                        <table width="100%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
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
                                                                            <tr class="load_conversations manual_conversations">
                                                                                <td width="50%">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td width="63%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                                                                                            <td width="27%"><?php if(!empty($conversations[$i]['interaction_type_name'])){ echo $conversations[$i]['interaction_type_name'];}?> </td>
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
                                                                                    <a class="btn btn-xs btn-success" title="Edit Contact" onClick="editconversation(<?php echo $conversations[$i]['id'];?>);"  data-toggle="modal" data-target="#basicModal_conversation"><i class="fa fa-pencil"></i></a> &nbsp; 
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
                                                                                        </tr>
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
                                                                                                else if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Envelope')
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
                                                                    <?php }
                                                                }
                                                            } else {?>
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
                                <div <?php if($tabid == 2){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="properties">
                                    <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-in-responsive">
                                                    <!-- table code start-->
                                                    <div class="row dt-rt">
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
                                <div  <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="searches" >
                                    <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-in-responsive">
                                                   <!-- table code start-->
                                                    <div class="row dt-rt">
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
                                                    <div class="row dt-rt">
                                                        <div class="col-sm-12">
                                                            <?php
                                                                $sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                                            ?>
                                                            <a class="btn btn-secondary pull-right btn-success howler" title="Add Saved Searches" href="<?=base_url('admin/'.$viewname.'/add_saved_searches/'.$sel_contact_id);?>">Add Saved Searches</a>
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
                                <div  <?php if($tabid == 4){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="edit_profile" >
                                    Edit profile
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

<script type="text/javascript">
    $(document).ready(function(){
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
					url: "<?php echo base_url();?>admin/leads_dashboard/view_record/"+contact_id,
					
				
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
				$("#activitylog").html('Update Activity Log');
				 $('#<?php echo $viewname;?>').attr('action', "<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_2."/";?>"+data.id);
		
				}
			});//ajax
	}
	function addconversation()
	{				
		document.forms['<?php echo $viewname;?>'].elements['sl_interaction_type'].value=4;
		document.forms['<?php echo $viewname;?>'].elements['disposition_type'].value=2;
		$("#description").html('');
		$("#activitylog").html('Add Activity Log');
		$('#<?php echo $viewname;?>').attr('action', "<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_1;?>");
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