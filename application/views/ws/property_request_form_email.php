<?php 
/*
    @Description: Property Showing request form email template (Joomla contact form action=property)
    @Author     : Sanjay Moghariya
    @Date       : 18-03-2015
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
                    Property Showing Request
                </div ><!--close logo-->
            </div ><!--top head-->

            <div style="width:100%; height:auto; float:left; ;">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                    <?php 
                    if(!empty($msg_body)) {
                        echo $msg_body;
                    } else {
                    ?>
                        <p>Hello <?php if(!empty($agent_name)) echo ucwords($agent_name);
                        else if(!empty($admin_data[0]['admin_name'])) echo ucwords($admin_data[0]['admin_name']);
                        else echo ''; ?>,</p>
                        <p>Lead has requested a property showing request from <b><?=!empty($admin_data[0]['domain'])?$admin_data[0]['domain']:''?></b>. Following are details for requested lead.</p>

                        <p>Property Name: <b><?=!empty($lead_data['property_name'])?$lead_data['property_name']:''?></b></p>
                        <p>Name: <b><?=!empty($lead_data['name'])?$lead_data['name']:''?></b></p>
                        <p>Email: <b><?=!empty($lead_data['email'])?$lead_data['email']:''?></b></p>
                        <p>Phone no: <b><?=!empty($lead_data['phone'])?$lead_data['phone']:''?></b></p>
                        <p>Comments: <b><?=!empty($lead_data['comments'])? stripslashes($lead_data['comments']):''?></b></p>
                    <?php } ?>
                    <p>&nbsp;</p>
                </div><!--close peregraph content-->
            </div><!--close left side-->

            <div style="width:100%; height:auto; float:left; background:#fff; ">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:10.5px; color:#000; font-weight:bold; width:100%; height:auto; line-height:20px;margin:10px;">
                    Thank you,<br />

                    <?php
                    if(!empty($admin_data[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$admin_data[0]['brokerage_pic'])) {
                    ?>
                        <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                                <h1 id="site-logo"><img src="<?php echo $this->config->item('broker_upload_img_small').$admin_data[0]['brokerage_pic'];?>" alt="<?=!empty($admin_data[0]['domain'])?$admin_data[0]['domain']:''?>"></h1>
                        </div ><!--close logo-->
                        <br />
                    <?php } ?>
                    <?=!empty($admin_data[0]['admin_name'])?$admin_data[0]['admin_name']:''?><br />
                    <?php if(!empty($admin_data[0]['address'])) {echo $admin_data[0]['address']."<br />"; }?>
                    <?php if(!empty($admin_data[0]['phone'])) {echo $admin_data[0]['phone']."<br />"; }?>
                </div><!--close title-->
            </div ><!--top title-->
        </div><!--close main div-->
    </body>
</html>