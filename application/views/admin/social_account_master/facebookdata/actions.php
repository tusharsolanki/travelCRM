<?php
	$viewname = $this->router->uri->segments[2];
	include 'facebook.php';
	/*$appid 		= "728901530477590";
	$appsecret  = "450e78a3c48a4731e4d8c2592c2bbae1";*/
	$appid 		= !empty($fb_details[0]['fb_api_key'])?$fb_details[0]['fb_api_key']:$this->config->item('facebook_api_key');
	$appsecret  = !empty($fb_details[0]['fb_secret_key'])?$fb_details[0]['fb_secret_key']:$this->config->item('facebook_secret_key');
	$facebook   = new Facebook(array(
  		'appId' => $appid,
  		'secret' => $appsecret,
  		'cookie' => TRUE,
	));
	
    $user       = $facebook->getUser();
	$access_token = $facebook->getAccessToken();
	$fbuser = $facebook->getUser();
	if ($fbuser) {
		try {
		    $user_profile = $facebook->api('/me');
            $frnd = $facebook->api('/me/friends?fields=birthday,gender,hometown,picture.width(150).height(150),cover,first_name,email,id,location,last_name,about,bio,address,middle_name,name,interests,interested_in,relationship_status,id');
		}
		catch (Exception $e) {
			$errormsg = $e->getMessage();
			//exit();
		}
		
		$user_fbid = $fbuser;
		//echo "<pre>";print_r($user_profile);
		$user_email =(!empty($user_profile["email"])) ? $user_profile["email"] : '-';
		$user_gender = (!empty($user_profile["gender"])) ? $user_profile["gender"] : '-';;
		$user_user_name = (!empty($user_profile["username"])) ? $user_profile["username"] : '-';
		$user_fnmae = (!empty($user_profile["first_name"])) ? $user_profile["first_name"] : '-';
		$user_lnmae = (!empty($user_profile["last_name"])) ? $user_profile["last_name"] : '-';
		$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
		//$check_select = mysql_num_rows(mysql_query("SELECT * FROM `fblogin` WHERE email = '$user_email'"));
		//if($check_select > 0){
		//	mysql_query("INSERT INTO `fblogin` (fb_id, name,gender, email, image, postdate) VALUES ('$user_fbid', '$user_fnmae',$user_gender, '$user_email', '$user_image', '$now')");
		//}
		
	}
?>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '<?=!empty($fb_details[0]['fb_api_key'])?$fb_details[0]['fb_api_key']:$this->config->item('facebook_api_key');?>', // replace your app id here
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

function FBLogout(){
	FB.logout(function(response) {
		window.location.href = "index.php";
	});
}
</script>
<div id="content">
    <div id="content-header">
        <h1><?php echo $this->lang->line('common_label_socialacc')?></h1>
    </div>
    <div id="content-container">
        <div class="content_right_part">
            <div class="chart_bg1 tbl_border">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-header">
                                <h3>
                                    <?php echo $this->lang->line('common_label_socialacc')?>
                                </h3>
                            </div>
                            <div class="portlet-content" id="fb_div">

								<?php if(!empty($errormsg)){ ?>
									<div>
										<?php /*?><?=$errormsg;?><?php */?>
									</div>
								<?php } ?>

								<table class="mytable">
								<tr>
									<td align="left"><h4>Hi <?=!empty($user_fnmae)?$user_fnmae:'';!empty($user_lnmae)?$user_lnmae:'';?> ,</h4></td>
									<td><a href="javascript:void(0);" class="btn btn-success howler" title="Add Friends to Contact" onclick="addfriendslist();">Add Friends to Contact</a> <?php /*?> <a onClick="FBLogout();" href="#" title="Logout" >Logout</a><?php */?></td>
								</tr>
								<tr>

									<td><img src="<?=(!empty($user_image)) ? $user_image : base_url('images/no_image.jpg');?> " height="100"/></td>
								
									<td><b class="margin-left-5px">Gender : </b><?=(!empty($user_gender)) ? $user_gender : '-'; ?></br>
                                    <b class="margin-left-5px">User Name : </b><?=(!empty($user_user_name)) ? $user_user_name : '-'; ?></td>
								</tr>
								
								</table>
								
								<table class="mytable margin-top-5px">
								<tr>
									<td align='center' colspan='2' ><h5><b>Facebook Friends List</b></h5></td>
								   
								</tr>
									<?php
									$i=0;
									
									if(!empty($frnd))
									{
										for($i=0;$i < count($frnd['data']); $i++)
										{ 
											//pr($frnd['data'][$i]);
											?>
											<tr>
												<td><?=$frnd['data'][$i]['name'];?></td>
												<td><!--<a href="https://graph.facebook.com/<?=$frnd['data'][$i]['id'];?>"><?=$frnd['data'][$i]['id'];?></a>--></td>
											</tr>
											
									   <?php
										}
									}
									else
									{
									?>

										<tr>
												<td>No Record Found!</td>
											
											</tr>
								<?php } ?>	
																	
								</table>
							</div>
                        
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    </div>
	
<script type="text/javascript">

function addfriendslist()
{
	<?php if(!empty($frnd)){ ?>
	var js_array = <?=!empty($frnd)?json_encode($frnd):''?> ;
	<?php }else{ ?>
	var js_array = '';
	<?php } ?>
	var user_profile = <?=!empty($user_profile)?json_encode($user_profile):''?> ;
	$.ajax({
		type: "post",
		url: '<?php echo $this->config->item('admin_base_url')?><?=$viewname.'/insert_data';?>',
		data: {'frnd':js_array,'user_profile':user_profile},
		beforeSend: function() {
							$('#fb_div').block({ message: 'Loading...' }); 
		
		},
		success: function(msg) 
		{
			//alert(msg);
			window.location.href='<?php echo $this->config->item('admin_base_url').'contacts';?>';
		}
	});	
}	
</script>