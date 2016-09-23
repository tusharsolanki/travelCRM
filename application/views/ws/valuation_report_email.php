<?php 
/*
    @Description: Send email to user with property listing that matches saved searches criteria
    @Author     : Sanjay Moghariya
    @Date       : 07-11-2014
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
<div style="width:100%; height:auto; float:left;margin:10px; color:#000; font-weight:bold;">
    Property Valuation Report
<?php /*<h1 id="site-logo"><img src="<?php echo $this->config->item('base_path');?>images/logo.png" alt="Site Logo"></h1> */ ?>
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
        
    <p>Please find attached PDF file which showing property valuation for neighborhood data based on following criteria:</p>
    <?php } ?>
    <p>
        Address: 
        <?php
        $address = !empty($neighborhood)?$neighborhood:'';
        $address .= !empty($city)?', '.$city:'';
        $address .= !empty($zip_code)?', '.$zip_code:'';
        //$address .= !empty($state)?' '.$state:'';
        //$address .= !empty($country)?' '.$country:'';
        $addr =  trim($address);
        echo trim($addr,', ');
        ?>
    </p>
    <p>Radius: <?php echo !empty($radius)?$radius.' Miles':'100 Miles'; ?></p>
    <p>&nbsp;</p>
			
</div><!--close peregraph content-->

</div><!--close left side-->


<div style="width:100%; height:auto; float:left; background:#fff; ">
    <div style="font-family:Verdana, Geneva, sans-serif; font-size:10.5px; color:#000; font-weight:bold; width:100%; height:auto; line-height:20px;margin:10px;">
    Thank you,<br />
        <?php
        if(!empty($brokerage_pic) && file_exists($this->config->item('broker_small_img_path').$brokerage_pic)) {
        ?>
            <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                <h1 id="site-logo"><img src="<?php echo $this->config->item('base_url')."uploads/broker/small/".$brokerage_pic;?>" height="150" width="150" alt="<?=!empty($domain)?$domain:''?>"></h1>
            </div ><!--close logo-->
            <br />
        <?php } ?>
        <?=!empty($admin_name)?$admin_name:''?><br />
        <?php if(!empty($admin_address)) {echo $admin_address."<br />"; }?>
        <?php if(!empty($admin_phone)) {echo $admin_phone."<br />"; }?>

    </div><!--close title-->
</div ><!--top title-->


</div><!--close main div-->
</body>
</html>