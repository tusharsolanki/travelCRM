<?php
/*
        @Description: Social Account master Add page
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Social Account master Add 
        @Date       : 05-09-2014
*/
	
    $viewname = $this->router->uri->segments[2]; 
    $loadcontroller='add_record?action=login';
    $path_insert = $viewname."/".$loadcontroller;

?>

<div id="content">
  <div id="content-header">
    <h1><?php echo $this->lang->line('common_label_socialacc')?></h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="chart_bg1 tbl_border"> 
        <!-- <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_insert?>" class="form parsley-form" >-->
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3> <?php echo $this->lang->line('common_label_socialacc')?> </h3>
              <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
            <div class="portlet-content">
              <div class="social_main">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'admin/social_account_master/add_linkedin'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/linkedin_login.jpg" alt="Linkedin Connect" title="Login with Linkedin" /></a> </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_linkedin">
                  <?  if(!empty($linkedin_data[0]['linkedin_access_token'])){?>
                  <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_linkedin();">Disconnect</a>
                  <? } else { ?>
                  <a class="btn btn-secondary" href="<?=base_url().'admin/social_account_master/add_linkedin'?>" onclick="setdefaultdata();">Connect</a>
                  <? } ?>
                  <?  if(!empty($linkedin_data[0]['linkedin_access_token'])){?>
                  <span id="linkedin_connect">
                  <p>Connected as</p>
                  <b>
                  <?=!empty($linkedin_data[0]['linkedin_username'])?$linkedin_data[0]['linkedin_username']:'';?>
                  </b> </span>
                  <? }?>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 mrg23">
                  <ul class="list">
                    <li>Your LinkedIn contacts will be synchronized.</li>
                    <li>Connect and Follow Up with contacts on LinkedIn.</li>
                  </ul>
                </div>
              </div>
              <!--<div class="social_main">
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><img class="img-responsive imgradius" src="<?=base_url()?>images/face_login.jpg" alt="Fb Connect" title="Login with facebook" onClick="FBLogin();"/></div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_linkedin">
                  <?php /*?> <?  if(!empty($linkedin_data[0]['fb_api_key'])){?>
                  		 <a class="btn btn-primary" href="javascript:void(0);" onclick="FBLogin();">Disconnect</a>
                   <? } else { ?><?php */?>
					   <a class="btn btn-secondary" href="#" onClick="FBLogin();">Connect</a>
					<?php /*?>   <? } ?><?php */?>
                    <?  if(!empty($linkedin_data[0]['fb_secret_key'])){?>
                    <span id="linkedin_connect">
                        <p>Manually Connected </p>
                        <?php /*?><b><?=!empty($linkedin_data[0]['fb_api_key'])?$linkedin_data[0]['fb_api_key']:'';?></b><?php */?>
                    </span>
                    <? }?>
                    </div>
                  <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 mrg23">
                    <ul class="list">
                      <li>Your Facebook contacts will be synchronized.</li>
                     
                    </ul>
                  </div>
                  
                </div>-->
              
              <div class="social_main">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'admin/social_account_master/add_twitter'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/twitter_login.jpg" alt="Linkedin Connect" title="Login with Linkedin" /></a> </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_twitter">
                  <?  if(!empty($linkedin_data[0]['twitter_access_token'])){?>
                  <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_twitter();">Disconnect</a>
                  <? } else { ?>
                  <a class="btn btn-secondary" href="<?=base_url().'admin/social_account_master/add_twitter'?>" onclick="setdefaultdata();">Connect</a>
                  <? } ?>
                  <?  if(!empty($linkedin_data[0]['twitter_access_token'])){?>
                  <span id="facebook_connect">
                  <p>Connected as</p>
                  <b>
                  <?=!empty($linkedin_data[0]['twitter_username'])?$linkedin_data[0]['twitter_username']:'';?>
                  </b> </span>
                  <? }?>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 mrg23">
                  <ul class="list">
                    <li>Connect and Follow Up with contacts on Twitter</li>
                  </ul>
                </div>
              </div>
              <!--Google login-->
              <div class="social_main">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'admin/social_account_master/google_connection'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/google_login.jpg" alt="Google Connect" title="Login with Google" /></a> </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_google">
                  <?  if(!empty($linkedin_data[0]['google_access_token'])){?>
                  <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_google();">Disconnect</a>
                  <? } else { ?>
                  <a class="btn btn-secondary" href="<?=base_url().'admin/social_account_master/google_connection'?>" onclick="setdefaultdata();">Connect</a>
                  <? } ?>
                  <?  if(!empty($linkedin_data[0]['google_access_token'])){?>
                  <span id="facebook_connect">
                  <p>Connected as</p>
                  <b>
                  <?=!empty($linkedin_data[0]['google_user_name'])?$linkedin_data[0]['google_user_name']:'';?>
                  </b> </span>
                  <? }?>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 mrg23">
                  <ul class="list">
                    <li>Connect and Follow Up with contacts on Google</li>
                  </ul>
                </div>
              </div>
              <!--Bombbomb login-->
              <div class="social_main">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><img class="img-responsive imgradius" src="<?=base_url()?>images/bomb_image.jpg" alt="Bomb Bomb Connect" title="Bomb Bomb Connect" /> </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_bomb">
                  <?php
					 $message = $this->session->userdata('message_session');
					 //pr($message);
					 if(!empty($msg) && !empty($message['connect'])){ ?>
                  <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata); ?> </div>
                  <?php } ?>
                  <div class="">
                    <?  if(!empty($linkedin_data[0]['bombbomb_username'])){$cls1='style="display:block"';$cls2='style="display:none"'; } else {$cls1='style="display:none"';$cls2='style="display:block"';}?>
                    <span id="bomb_disconnect" <?=$cls1?>> <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_bombbomb();">Disconnect</a>
                    <p>Connected as</p>
                    <b>
                    <?=!empty($linkedin_data[0]['bombbomb_username'])?$linkedin_data[0]['bombbomb_username']:'';?>
                    </b> </span>
                    <div id="bomb_connect" <?=$cls2?>>
                      <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?=base_url().'admin/social_account_master/bombbomb_connection'?>" data-validate="parsley" novalidate >
                        <div class="form-group" style="float:left;width:49%;">
                          <input class="form-group form-control parsley-validated charval" data-required="true" placeholder="User Name"  type="text" id="bombbomb_username" name="bomb_username" value="" />
                        </div>
                        <div class="form-group" style="float:left;width:49%;margin-left:2px;" >
                          <input class="form-group form-control parsley-validated charval" data-required="true" placeholder="Password" type="password" id="bombbomb_password" name="bomb_password" value="" />
                        </div>
                        <input class="btn btn-secondary" type="submit" id="bomb_submit" style="margin-top:3px;" name="bomb_submit" value="Connect" />
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 mrg23">
                  <ul class="list">
                    <li>Connect with bomb bomb.</li>
                  </ul>
                </div>
                </div>
              <!-- End bombbomb --> 
            </div>
          </div>
        </div>
        <!-- </form>--> 
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
});
//fonction for disconnect social credential
function disconnect_linkedin()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/social_account_master/disconnect_linkedin",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>admin/social_account_master/add_linkedin">Connect</a>';
				$('.dis_linkedin').html(html);
				$.unblockUI();
			}
		});
}
function disconnect_twitter()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/social_account_master/disconnect_twitter",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>admin/social_account_master/add_twitter">Connect</a>';
				$('.dis_twitter').html(html);
				$.unblockUI();
			}
		});
}
function disconnect_google()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/social_account_master/disconnect_google",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>admin/social_account_master/google_connection">Connect</a>';
				$('.dis_google').html(html);
				$.unblockUI();
			}
		});
}
function disconnect_bombbomb()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/social_account_master/disconnect_bombbomb",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				$('#bomb_disconnect').hide();
				$('#bomb_connect').show();
				$.unblockUI();
			}
		});
}
	window.fbAsyncInit = function() {
		FB.init({
		appId      : '<?=!empty($linkedin_data[0]['fb_api_key'])?$linkedin_data[0]['fb_api_key']:$this->config->item('facebook_api_key')?>', // replace your app id here
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
			if(response.authResponse){
				window.location.href = "<?=base_url().'admin/'.$path_insert?>";
			}
		}, {scope: 'email,user_likes,user_friends,read_stream, export_stream'});
	}

function setdefaultdata()
{
	$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		
}
</script> 
