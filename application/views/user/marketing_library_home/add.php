<?php 
    /*
        @Description: user contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>

<div id="content">
  <div id="content-header">
    <h1><?=$this->lang->line('marketing_title');?></h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i><?=$this->lang->line('marketing_title');?></h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                  
                   <?php if(!empty($this->modules_unique_name) && in_array('email_library',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/email_library');?>"><img title="Email_Library" alt="Email_Library" src="<?=base_url()?>images/email_library.png" class="img-responsive"><span>Email Library</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('auto_responder',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/auto_responder');?>"><img title="Auto Responder" alt="Auto Responder" src="<?=base_url()?>images/auto_responder.png" class="img-responsive"><span>Auto Responder</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('envelope_library',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/envelope_library');?>"><img title="Envelope Library" alt="Envelope Library" src="<?=base_url()?>images/envelope_library.png" class="img-responsive"><span>Envelope Library</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('social_media_posts',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center ">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/socialmedia_post');?>"><img title="Social Media Posts" alt="Social Media Posts" src="<?=base_url()?>images/social_media_posts.png" class="img-responsive"><span>Social Media Posts</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('phone_call_scripts',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center clear">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/phonecall_script');?>"><img title="Phone Call Scripts" alt="Phone Call Scripts" src="<?=base_url()?>images/phone_call_scripts.png" class="img-responsive"><span>Phone Call Scripts</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('sms_texts',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/sms_texts');?>"><img title="SMS Texts" alt="SMS Texts" src="<?=base_url()?>images/smstexts.png" class="img-responsive"><span>SMS Texts</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('label_library',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center ">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/label_library');?>"><img title="Label Library" alt="Label Library" src="<?=base_url()?>images/label_library.png" class="img-responsive"><span>Label Library</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('letter_library',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center ">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/letter_library');?>"><img title="Letter Library" alt="Letter Library" src="<?=base_url()?>images/letter_library.png" class="img-responsive"><span>Letter Library</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($result[0]['bombbomb_username']) && !empty($result[0]['bombbomb_password']) && !empty($this->modules_unique_name) && in_array('bomb_bomb_library',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center ">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/bomb_library');?>"><img title="Bomb Bomb Emails Library" alt="Bomb Bomb Emails Library" src="<?=base_url()?>images/bomb-bomb-library.jpg" class="img-responsive"><span>Bomb Bomb Library</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('sms_auto_responder',$this->modules_unique_name)){ ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/sms_texts_response');?>"><img title="SMS Auto Response" alt="SMS Auto Response" src="<?=base_url()?>images/sms-auto-responder.png" class="img-responsive"><span>SMS Auto Responder</span></a></div>
                  </div>
                  <?  } ?>
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
</div>
