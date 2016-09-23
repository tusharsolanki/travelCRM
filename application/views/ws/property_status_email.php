<?php 
/*
    @Description: Send email to user for status updates
    @Author     : Sanjay Moghariya
    @Date       : 05-11-2014
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
            Property Status Changed
            <?php /*<h1 id="site-logo"><img src="<?php echo $this->config->item('image_path');?>logo.png" alt="Site Logo"></h1> */ ?>
        </div ><!--close logo-->
    </div ><!--top head-->
    <div style="width:100%; height:auto; float:left; ;">
        <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
            <?php
            if(!empty($tabname))
            {
                if(!empty($email_temp))
                {
                    echo $email_temp;
                }
                else
                { ?>
                    <p>Hello <?=!empty($name)?$name:''?>,</p>
                    <?php if($tabname == 'F') { ?>
                      <p>One of your Favorite property <?=!empty($domain)?' on '.$domain:''?> status has been changed. Property details is as below:</p><p>&nbsp;</p>
                    <?php 
                    } else if($tabname == 'SS') {
                    ?>
                      <p>One of your Saved Searches preference property <?=!empty($domain)?' on '.$domain:''?> status has been changed. Property details is as below:</p><p>&nbsp;</p>
                    <?php
                    } else if($tabname == 'VR') {
                      ?>
                      <p>One of your Valuation Searches property <?=!empty($domain)?' on '.$domain:''?> status has been changed. Property details is as below:</p><p>&nbsp;</p>
                  <?php
                    }
                }
            }
            if(!empty($is_p_array))
            {
                if(!empty($property_name))
                {
                    $pc = 0;
                    foreach($property_name as $row)
                    { ?>
                        <p>Property Name: <b><?=!empty($row)?$row:'-'?></b></p>
                        <p>Price: <b><?=!empty($property_price[$pc])?'$'.number_format($property_price[$pc]):'-'?></b></p>
                        <p>New Status: <b><?=!empty($status[$pc])?$status[$pc]:'-'?></b></p>    
                        <p>&nbsp;</p>
                    <?php 
                    $pc++;
                    }
                }
            } else { ?>
                <p>Property Name: <b><?=!empty($property_name)?$property_name:'-'?></b></p>
                <p>Price: <b><?=!empty($property_price)?'$'.number_format($property_price):'-'?></b></p>
                <p>New Status: <b><?=!empty($status)?$status:'-'?></b></p>    
                <p>&nbsp;</p>
            <?php } ?>
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
