<?php 
/*
    @Description: Send email to user with property listing that matches saved valuation searched criteria
    @Author     : Sanjay Moghariya
    @Date       : 08-12-2014
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
<h1 id="site-logo"><img src="<?php echo $this->config->item('image_path');?>logo.png" alt="Site Logo"></h1>
</div ><!--close logo-->
</div ><!--top head-->



<div style="width:100%; height:auto; float:left; ;">
<div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
    <?php
    if(!empty($temp_msg))
    {
        echo $temp_msg;
    } else { ?>
    <p>Hello <?=!empty($contact_name)?$contact_name:''?>,</p>
        
    <p>Please find attached PDF file which showing property valuation for based on following valuation searched criteria:</p>
    <?php } ?>
    <p>
        Address: 
        <?php
        $address = !empty($search_address)?$search_address:'';
        $address .= !empty($city)?', '.$city:'';
        $address .= !empty($state)?' '.$state:'';
        $address .= !empty($zip_code)?' '.$zip_code:'';
        $addr =  trim($address);
        echo trim($addr,', ');
        ?>
    </p>
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