<?php 
/*
    @Description: User Registration Email Template
    @Author     : Sanjay Moghariya
    @Date       : 30-10-2014
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$this->config->item('sitename');?></title>
</head>

<body>
<div style="width:90%; height:auto; float:left; border:1px solid #00b050;">

<div style="width:100%; height:auto; float:left; border-bottom:#00b050 solid 2px;">
<div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
<h1 id="site-logo"><img src="<?php echo $this->config->item('base_url');?>images/logo.png" alt="Site Logo" /></h1>
</div ><!--close logo-->
</div ><!--top head-->



<div style="width:100%; height:auto; float:left; ;">
<div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                <?php
                
                    if(!empty($admin_temp_msg))
                    {
                        echo $admin_temp_msg;
                    } else {
                    ?>
                        <p>Hello <?=!empty($name)?$name:''?>,</p>
                        <p>Thank you for registering in Livewire CRM. Your contact details is as below:</p>

                      <p>Name: <b><?=!empty($name)?$name:''?></b></p>
                      <p>Email: <b><?=!empty($email)?$email:''?></b></p>
                <?php  } ?>
                    <p>&nbsp;</p>
			
</div><!--close peregraph content-->

</div><!--close left side-->


<div style="width:100%; height:auto; float:left; background:#00b050; ">
<div style="font-family:Verdana, Geneva, sans-serif; font-size:10.5px; color:#fff; font-weight:bold; width:100%; height:auto; line-height:20px;margin:10px;">
Thank you,<br />
<?=$this->config->item('sitename');?><br />

</div><!--close title-->
</div ><!--top title-->


</div><!--close main div-->
</body>
</html>
