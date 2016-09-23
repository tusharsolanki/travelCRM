<?php 
    /*
        @Description: User DashBoard Notification
        @Author: Sanjay Chabhadiya
        @Date: 11-11-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user = $this->session->userdata($this->lang->line('common_user_session_label'));
?>
<input type="hidden" id="hidden_date" name="hidden_date"  value="<?php if(!empty($now_date)){ echo date($this->config->item('common_date_format'),strtotime($now_date));} ?>" />
<div class="row">
    <div class="col-md-12">
    <div class="row">
    
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="deshboard_main"><h3><?=!empty($time_message)?$time_message.' '.$this->user_session['name'].'!':''?></h3></div>
    </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
     <div class="deshboard_Wednesday">
     <div class="Wednesday_main">
     <p><span class="arrow_left"><a title="Previous" href="javascript:void(0);" onclick="next_and_pre(-1);" class="arrow_left"></a></span> <label id="now_date"><?php if(!empty($now_date)){ echo date('l F d, Y',strtotime($now_date));} ?> </label><span><a title="Next" href="javascript:void(0);" onclick="next_and_pre(1);" class="arrow_rigth"></a>
     </span></p>
     
     </div>
     	<div class="Wednesday_right"><?php
            if(!empty($now_date1)){ echo date('h:i A',strtotime($now_date1));} else { echo date('h:i A'); }
           ?></div>
    
     </div>
    </div>
    </div>
    
     <div class="row">
     <div id="content-container_margin_bottom">
     <div class="col-md-12">
     
     <div class="row">
     <div class="col-sm-2">
	  <div class="userimg row">
	  	<?php  if(!empty($prifile_pic[0]['contact_pic']) && file_exists($this->config->item('user_big_img_path').$prifile_pic[0]['contact_pic'])){
	  ?>
			<a href="#basicModal" class="text_size howler" id="basicModal" data-toggle="modal"><img class="img-responsive" src="<?=$this->config->item('user_upload_img_small')?>/<?=(!empty($prifile_pic[0]['contact_pic'])?$prifile_pic[0]['contact_pic']:'');?>"/> </a>
		<? } else { ?>
	  		<img class="img-responsive" src="<?=base_url('images/user.jpg') ?>">
	  <?php } ?> 
	  </div>
      
      </div>
      
      <div class="col-sm-10 counter_data_div">
        <div class="row">

          <div class="col-md-2 col-sm-4 ">
              <div class="new_listings"><p>New Contacts</p>
                 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>contacts">
                  <h1>
                    <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($contact_last_seen)?$contact_last_seen:'0000-00-00 00:00:00'?>"  />
                    <b><a href="#" onclick="return form_submit('<?=addslashes($viewname);?>');"><?=!empty($contact_count)?$contact_count:0?></a></b>
                  </h1>
                </form>
              </div>
          </div>
          <?php /* if(!empty($this->modules_unique_name) && in_array('lead_dashboard',$this->modules_unique_name)){?>
          <div class="col-md-2 col-sm-4 ">
              <div class="new_listings"><p>Live Wire Leads</p>
                 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>2" id="<?php echo $viewname;?>2" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>leads_dashboard">
                  <h1>
                    <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($joomla_lead_last_seen)?$joomla_lead_last_seen:'0000-00-00 00:00:00'?>"  />
                    
                    <b><a href="#" onclick="return form_submit2('<?=addslashes($viewname);?>2');"><?=!empty($joomla_lead_count)?$joomla_lead_count:0?></a></b>
                  </h1>
                </form>
              </div>
          </div>
          <? }*/ ?>
          <?php if(!empty($this->modules_unique_name) && in_array('form_builder',$this->modules_unique_name)){?>
          <div class="col-md-2 col-sm-4 ">
              <div class="new_listings"><p>Form Received</p>
                 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>3" id="<?php echo $viewname;?>3" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>dashboard/form_lead_list">
                  <h1>
                    <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($form_lead_last_seen)?$form_lead_last_seen:'0000-00-00 00:00:00'?>"  />
                    <b><a href="#" onclick="return form_submit3('<?=addslashes($viewname);?>3');"><?=!empty($form_lead_count)?$form_lead_count:0?></a></b>
                  </h1>
                </form>
              </div>
          </div>
          <? } /* ?>
          <div class="col-md-2 col-sm-4 ">
              <div class="new_listings"><p>Manual Leads</p>
                 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>1" id="<?php echo $viewname;?>1" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>contacts">
                  <h1>
                    <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($manual_contact_last_seen)?$manual_contact_last_seen:'0000-00-00 00:00:00'?>"  />
                    <input type="hidden" name="created_type" id="created_type" value="1"  />
                    <b><a href="#" onclick="return form_submit1('<?=addslashes($viewname);?>1');"><?=!empty($contact_manual_count)?$contact_manual_count:0?></a></b>
                  </h1>
                </form>
              </div>
          </div>
           <? if(in_array('listing_manager',$this->modules_unique_name)){ ?>
           <div class="col-md-2 col-sm-4">
          <div class="">
          <div class="new_listings"><p>New Listings</p>
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>4" id="<?php echo $viewname.'4';?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>listing_manager">
          <h1>
          <input type="hidden" name="new_list" id="new_list" value="<?=!empty($listing_last_seen)?$listing_last_seen:'0000-00-00 00:00:00'?>"  />
          <b><a href="#" onclick="return form_submit4('<?=addslashes($viewname).'4'?>');"><?=!empty($property_listing_count)?$property_listing_count:0?></a></b>
          </h1></form>
    		</div>
          </div>
          </div>
          <? } ?>
         <div class="col-md-2 col-sm-4">
              <div class="new_listings"><p>Error Alerts</p>
                <h1><b>
                 <a title="Error List" data-toggle="modal" class="view_error_btn" href="#basicModal1" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
                      <?=!empty($error_count)?$error_count:0?>
                 </a>
				</b> </h1>
                 <!-- <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>contacts">
                  <h1>
                    <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($contact_last_seen)?$contact_last_seen:'0000-00-00 00:00:00'?>"  />
                    <b><a href="#" onclick="return form_submit('<?=addslashes($viewname);?>');"><?=!empty($contact_count)?$contact_count:0?></a></b>
                  </h1>
                </form> -->
              </div>
          </div>
           <!-- <div class="col-sm-3">
          <div class="new_listings"><p>New Leads</p>
           <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?>contacts">
          <h1>
          <input type="hidden" name="new_contact" id="new_contact" value="<?=!empty($contact_last_seen)?$contact_last_seen:'0000-00-00 00:00:00'?>"  />
          <b><a href="#" onclick="return form_submit('<?=addslashes($viewname);?>');"><?=!empty($contact_count)?$contact_count:0?></a></b>
          </h1></form></div>
           </div> -->
            <!--<div class="col-sm-4">
           <div class="new_listings"><p>New Expired / Cancelled</p>
          <h1>0</h1></div>
            </div>-->
           <?php */ ?>
        </div>
      </div>
     
     </div>
     </div>
     </div>
    <?php /* ?>
    <div class="col-sm-12">
    <div class="row">
    <div id="content-container_margin_bottom">
    
    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
       <? if(in_array('communications',$this->modules_unique_name)){ ?>
       <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="Daily_box_mian">
        <!--<div class="Daily_box_mian_img"></div>-->
        <div class="Daily_box_mian_te">
           <p>  Mailings </p>
            <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/letter_label_envelope_task'; ?>" ><?=$task_lel_count?>/<span><?=$task_lel_overdue_count?></span></a></h2>
      </div>
      </div>
       
       
       </div>
       <? } ?>
        <? if(in_array('communications',$this->modules_unique_name)){ ?> 
        <div id="content-container_margin_bottom">
         <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
         <div class="Daily_box_mian phone">
        <!--<div class="Daily_box_phone_img"></div>-->
        <div class="Daily_box_mian_te">
           <p>  Calls </p>
            <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/telephone_task';?>" ><?=$call_count?>/<span><?=$call_overdue_count?></span> </a><span></span></h2>
      </div>
      </div>
         
         </div>
         </div>
         <? } ?>
         </div>
          <? if(in_array('communications',$this->modules_unique_name)){ ?>
         <div class="row">
          <div class="content-container_margin_bottom">
         <div class="col-md-12">
         <div class="row">
        
          <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
          <div class="Daily_box_mian_bottom">
          <div class="Daily_box_mian_bottom_te">
           <p>  Text </p>
            <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/sms_task';?>" ><?=$sms_count?>/<span><?=$sms_overdue_count?></span></a></h2>
      </div>
        <!--<div class="Daily_box_mian_bottom_img"></div>-->
        
      </div></div>
           <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
           <div class="Daily_box_mian Social">
           <div class="Daily_box_mian_bottom_te">
           <p>  Social Media </p>
            <h2>0</h2>
      </div>
           
       <!-- <div class="Daily_box_Social_img"></div>-->
        
      </div>
           </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="Daily_box_mian Schedule">
           <div class="Daily_box_mian_bottom_te">
           <p>  Tasks </p>
            <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/daily_task'; ?> " ><?=$task_count?>/<span><?=$task_overdue_count?></span></a></h2>
      </div>
           
       <!-- <div class="Daily_box_mian_Schedule_img"></div>-->
        
      </div>
            </div>
             <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
             <div class="Daily_box_mian email">
           <div class="Daily_box_mian_bottom_te">
           <p>  Emails </p>
            <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/email_task'; ?> " ><?=$email_count?>/<span><?=$email_overdue_count?></span></a></h2>
      </div>
           
        <!--<div class="Daily_box_mian_email_img"></div>-->
        
      </div>
             </div>
             </div>
             </div>
         </div>
         </div>
         <? } ?>
    </div>
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <div class="Daily_box">
      <!--<div class="Daily_box_img"></div>-->
      <div class="daily_task_count">
      <p> All Actions </p>
      <h2><a href="<?php echo $this->config->item('user_base_url').$viewname.'/to_do_task';?>" ><?=$to_do_task_count+$call_count+$sms_count+$task_count+$email_count+$task_lel_count?>/<span><?=$to_do_task_overdue_count+$call_overdue_count+$sms_overdue_count+$task_overdue_count+$email_overdue_count+$task_lel_overdue_count?></span></a></h2>
      </div>
      
      </div>
    </div>
    </div>
    
    
    </div>
    <?php */ ?>
    </div>
       </div>
      </div>
<script type="text/javascript">
function form_submit(viewname)
{
	$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
	$("#"+viewname).submit();
}
function form_submit1(viewname)
{
  $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
  $("#"+viewname).submit();
}
function form_submit2(viewname)
{
  $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
  $("#"+viewname).submit();
}
function form_submit3(viewname)
{
  $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
  $("#"+viewname).submit();
}
function form_submit4(viewname)
{
  $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
  $("#"+viewname).submit();
}
</script>