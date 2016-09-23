<?php

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];


/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

/*echo "<pre>", print_r($content, true), "</pre>";
exit;*/
/*echo "<b>Hi </b>" . $content->name . "</br>";
echo "<b>Your Current Status </b>" . $content->status->text . "</br>";
echo "<b>Your Posted This On: </b>" . $content->created_at . "</br>";
echo "<b>Is This You? </b></br>";
echo "<img src = " . $content->profile_image_url . ">";

echo "</br><a href = 'clearsessions.php'>Logout</a>";*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Twitter OAuth in PHP</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style type="text/css">
      img {border-width: 0}
      * {font-family:'Lucida Grande', sans-serif;}
    </style>
  </head>
  <body>
    <div>
      <h2>Welcome to a Twitter OAuth PHP example.</h2>
		<table width="500px"   style='border: 1px solid black'>
			<tr>
				<td><?php echo "<img src = " . $content->profile_image_url . ">"; ?></td> 
				<td> <?php echo "</br><a href = 'clearsessions.php'>Logout</a>"; ?></td>
			</tr>
			<tr>
				<td>Name : </td>
				<td><?php echo $content->name; ?></td>
			</tr>
			<tr>
				<td>id_str : </td>
				<td><?php echo $content->id_str; ?></td>
			</tr>
			<tr>
				<td>Screen Name : </td>
				<td><?php echo $content->screen_name; ?></td>
			</tr>
			<tr>
				<td>Location : </td>
				<td><?php echo $content->location; ?></td>
			</tr>
			<tr>
				<td>Followers Count : </td>
				<td><?php echo $content->followers_count; ?></td>
			</tr>
			<tr>
				<td>Friends Count : </td>
				<td><?php echo $content->friends_count; ?></td>
			</tr>
			<tr>
				<td>Account Created Date : </td>
				<td><?php echo $content->created_at; ?></td>
			</tr>			
		</table>
  </body>
</html>
