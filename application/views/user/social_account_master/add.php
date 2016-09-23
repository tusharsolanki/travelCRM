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
        <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_insert?>" class="form parsley-form" >
          <div class="col-md-12">
            <div class="portlet">
              <div class="portlet-header">
                <h3> <?php echo $this->lang->line('common_label_socialacc')?> </h3>
                <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
              <div class="portlet-content"> 
                <!--Start linked login-->
                <div class="social_main">
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'user/social_account_master/add_linkedin'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/linkedin_login.jpg" alt="Linkedin Connect" title="Login with Linkedin" /></a> </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_linkedin">
                    <?  if(!empty($linkedin_data[0]['linkedin_access_token'])){?>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_linkedin();">Disconnect</a>
                    <? } else { ?>
                    <a class="btn btn-secondary" href="<?=base_url().'user/social_account_master/add_linkedin'?>" onclick="setdefaultdata();">Connect</a>
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
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><img src="<?=base_url()?>images/face_login.jpg" alt="Fb Connect" title="Login with facebook" onClick="FBLogin();"/></div>
                  
                </div>--> 
                <!--Start twitter login-->
                <div class="social_main">
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'user/social_account_master/add_twitter'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/twitter_login.jpg" alt="Linkedin Connect" title="Login with Linkedin" /></a> </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_twitter">
                    <?  if(!empty($linkedin_data[0]['twitter_access_token'])){?>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_twitter();">Disconnect</a>
                    <? } else { ?>
                    <a class="btn btn-secondary" href="<?=base_url().'user/social_account_master/add_twitter'?>" onclick="setdefaultdata();">Connect</a>
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
                
                <!--Start google login-->
                <div class="social_main">
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mrg23"><a href="<?=base_url().'user/social_account_master/google_connection'?>" onclick="setdefaultdata();"><img class="img-responsive imgradius" src="<?=base_url()?>images/google_login.jpg" alt="Google Connect" title="Login with Google" /></a> </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 disconnect text-center mrg23 dis_google">
                    <?  if(!empty($linkedin_data[0]['google_access_token'])){?>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="disconnect_google();">Disconnect</a>
                    <? } else { ?>
                    <a class="btn btn-secondary" href="<?=base_url().'user/social_account_master/google_connection'?>" onclick="setdefaultdata();">Connect</a>
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
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function disconnect_linkedin()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/social_account_master/disconnect_linkedin",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>user/social_account_master/add_linkedin">Connect</a>';
				$('.dis_linkedin').html(html);
				$.unblockUI();
			},
		});
}
function disconnect_twitter()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/social_account_master/disconnect_twitter",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>user/social_account_master/add_twitter">Connect</a>';
				$('.dis_twitter').html(html);
				$.unblockUI();
			}
		});
}
function disconnect_google()
{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/social_account_master/disconnect_google",
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
			success: function(data){
				var html='<a class="btn btn-secondary" href="<?=base_url()?>user/social_account_master/google_connection">Connect</a>';
				$('.dis_google').html(html);
				$.unblockUI();
			}
		});
}
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
			if(response.authResponse){
				window.location.href = "<?=base_url().'user/'.$path_insert?>";
			}
		}, {scope: 'email,user_likes,user_friends,read_stream, export_stream'});
	}
function setdefaultdata()
{
	$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		
}
</script> 
