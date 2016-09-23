<?php
	/*
    @Description: Get Chat History of Contact
    @Author: Mohit Trivedi
    @Input: - User name and password
    @Output: - Details of Chat History and Messages
    @Date: 02-10-2014
    */

$viewname = $this->router->uri->segments[2];
$contact_id=$this->router->uri->segments[4];
include 'library.php';
$action = $_REQUEST["action"];

switch($action){
	case "login":
	include 'facebook.php';
	$appid 		= "728901530477590";
	$appsecret  = "450e78a3c48a4731e4d8c2592c2bbae1";
	$facebook   = new Facebook(array(
  		'appId' => $appid,
  		'secret' => $appsecret,
  		'cookie' => TRUE,
	));
	
    $user = $facebook->getUser();
	$access_token = $facebook->getAccessToken();
	$fbuser = $facebook->getUser();
	if ($fbuser) {
		try {
		    $user_profile = $facebook->api('/me');
			$frnd = $facebook->api('/me/friends');
			$conversation=$facebook->api( '/me/conversations');
			//echo "<pre>";
			//print_r($conversation);
			}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
			}
		
		$user_fbid = $fbuser;
		$friends = $facebook->api('/me/friends');
		$user_email =(!empty($user_profile["email"])) ? $user_profile["email"] : '-';
		$user_gender = (!empty($user_profile["gender"])) ? $user_profile["gender"] : '-';;
		$user_fnmae = (!empty($user_profile["first_name"])) ? $user_profile["first_name"] : '-';
		$user_lnmae = (!empty($user_profile["last_name"])) ? $user_profile["last_name"] : '-';
		$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
	}
	break;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login With Facebook</title>
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

function FBLogout(){
	//alert('hiii');
	FB.logout(function(response) {
		//alert('hiii');
		window.location.href = "<?= $this->config->item('admin_base_url').$viewname.'/view_record'.$contact_id?>";
	});
}
</script>
<style>
body{
	font-family:Arial;
	color:#333;
	font-size:14px;
}
.mytable{
	margin:0 auto;
	width:600px;
	border:2px dashed #17A3F7;
}
a{
	color:#0C92BE;
	cursor:pointer;
}
</style>
</head>
<body>
<table class="mytable">
<tr>
	<td align="left"><h2>Hi <?php echo $user_fnmae.' '.$user_lnmae;?></h2> ,</td>
	<td><a onClick="FBLogout">Logout</a></td>
</tr>
<tr>
	<td><b>Fb id:<?=(!empty($user_fbid)) ? $user_fbid : '-'; ?></b></td>
    <td valign="top" rowspan="2"><img src="<?=(!empty($user_image)) ? $user_image : 'Image Not Available'; ?> " height="100"/></td>
</tr>
<tr><td colspan="2"><?php //  if(!empty($conversation)){ echo"<pre>";pr($conversation);}else{echo"No Records Found.....";} ?></td>
<tr><td colspan="2"><?php if(!empty($conversation['data'])){
		for($i=0;$i<count($conversation['data']);$i++)
		{
			$data=$conversation['data'][$i];
			//pr($data);exit;
			$snippet=$data['snippet'];
			$message=$data['messages'];
			for($j=0;$j<count($message);$j++)
			{
				$messagedata=$message['data'][$j];
				$created_time=$messagedata['created_time'];
				$from=$messagedata['from'];
				//pr($from);
				foreach($from as $row)
				{
					pr($row);
				}
			}
		}
		//pr($data1['participants']);exit;
	}?>



</td></tr>

</table>


</body>
</html>
