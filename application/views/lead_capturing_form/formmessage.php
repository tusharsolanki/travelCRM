<?php
if(!empty($form_data_message))
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=!empty($form_data_message[0]['form_title'])?$form_data_message[0]['form_title']:'';?></title>
</head>
<body>

<div id="message" align="center">
<form style="background-color:<?=!empty($form_data_message[0]['bg_color'])?$form_data_message[0]['bg_color']:'#FFFFFF';?>; width:<?=!empty($form_data_message[0]['form_width'])?$form_data_message[0]['form_width']:'700';?>; height:<?=!empty($form_data_message[0]['form_height'])?$form_data_message[0]['form_height']:'700';?>;" >
<h2><?php echo $form_data_message[0]['success_msg'];?></h2>
</form>
</div>
</body>
</html>
<?php
}
?>