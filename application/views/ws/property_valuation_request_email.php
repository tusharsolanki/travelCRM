<?php 
/*
    @Description: Property Valuation request email template (Joomla contact form action=valuation)
    @Author     : Sanjay Moghariya
    @Date       : 15-05-2015
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=!empty($admin_data[0]['domain'])?$admin_data[0]['domain']:''?></title>
    </head>

    <body>
        <div style="width:90%; height:auto; float:left; border:1px solid #00b050;">

            <div style="width:100%; height:auto; float:left; border-bottom:#00b050 solid 2px;">
                <div style="width:100%; height:auto; float:left;margin:10px; color:#000; font-weight:bold;">
                    Property Valuation Request
                </div ><!--close logo-->
            </div ><!--top head-->

            <div style="width:100%; height:auto; float:left; ;">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                    <?php
                    if(!empty($msg_body)) {
                        echo $msg_body;
                    } else {
                    ?>
                        <p>Hello <?php if(!empty($agent_name)) echo ucwords($agent_name); else if(!empty($admin_name)) echo ucwords($admin_name); else echo '';?>,</p>
                        <p>Lead has requested a property valuation request from <b><?=!empty($domain)?$domain:''?></b>. Details is as below:</p>
                        <p>&nbsp;</p>
                        <p>Property Address: <b><?=!empty($property_name)?$property_name:''?></b></p>
                        <p>Name: <b><?=!empty($contact_name)?$contact_name:''?></b></p>
                        <p>Email: <b><?=!empty($contact_email)?$contact_email:''?></b></p>
                        <p>Phone no: <b><?=!empty($contact_phone)?$contact_phone:''?></b></p>
                        <p>&nbsp;</p>
                    <?php } ?>
                    <p>&nbsp;</p>
                </div><!--close peregraph content-->
            </div><!--close left side-->

            <div style="width:100%; height:auto; float:left; background:#fff; ">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:10.5px; color:#000; font-weight:bold; width:100%; height:auto; line-height:20px;margin:10px;">
                Thank you,<br />

                    <?php
                    if(!empty($admin_brokerage_pic) && file_exists($this->config->item('broker_small_img_path').$admin_brokerage_pic)) {
                    ?>
                        <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                                <h1 id="site-logo"><img src="<?php echo $this->config->item('base_url')."uploads/broker/small/".$admin_brokerage_pic;?>" height="150" width="150" alt="<?=!empty($domain)?$domain:''?>"></h1>
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