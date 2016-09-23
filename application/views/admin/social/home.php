<?php 
    /*
        @Description: Admin contact list
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
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
$callback_url         =   base_url().'admin/social/fbconnection/?fbTrue=true';
$facebook_api_key= !empty($fb_deatils[0]['fb_api_key'])?$fb_deatils[0]['fb_api_key']:$this->config->item('facebook_api_key');
$url='https://www.facebook.com/dialog/oauth?client_id='.$facebook_api_key.'&redirect_uri='.$callback_url.'&scope=email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages';

?>


<div id="content">
  <div id="content-header">
    <h1>Marketing Library</h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>Social</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                  <?php if(!empty($this->modules_unique_name) && in_array('facebook_post',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="#" onclick="return post_fb();"><img title="New Facebook Post" alt="New Facebook Post" src="<?=base_url()?>images/new-facebook-post-icon.jpg" class="img-responsive"><span>New Facebook Post</span></a></div>
                  </div>
                    <? } ?>
                    <?php if(!empty($this->modules_unique_name) && in_array('twitter',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/social/add_record/2');?>"><img title="New Tweet" alt="New Tweet" src="<?=base_url()?>images/new-tweet-icon.jpg" class="img-responsive"><span>New Tweet</span></a></div>
                  </div>
                   <? } ?>
                    <?php if(!empty($this->modules_unique_name) && in_array('linkedin',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/social/add_record/3');?>"><img title="New LinkedIn Post" alt="New LinkedIn Post" src="<?=base_url()?>images/new-linkedIn-post-icon.jpg" class="img-responsive"><span>New LinkedIn Post</span></a></div>
                  </div>
                   <? } ?>
                    <?php if(!empty($this->modules_unique_name) && in_array('all_channels',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center ">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/social/add_record/all');?>"><img title="New Post To All" alt="New Post To All" src="<?=base_url()?>images/new-post-to-all-icon.jpg" class="img-responsive"><span>New Post To All</span></a></div>
                  </div>
                   <? } ?>
                  <div class="col-lg-3 col-md-3 text-center clear">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/social');?>"><img title="Previous Social Posts" alt="Previous Social Posts" src="<?=base_url()?>images/previous-social-posts-icon.jpg" class="img-responsive"><span>Previous Social Posts</span></a></div>
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
</div>
<script>
var appid='<?=$facebook_api_key?>';
var callback_url='<?=$callback_url?>';
function post_fb()
{
window.open('https://www.facebook.com/dialog/oauth?client_id='+appid+'&redirect_uri='+callback_url+'&scope=email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages', "MsgWindow", "width=700, height=500");
	return false;	
}
</script>