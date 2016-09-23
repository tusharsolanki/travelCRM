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
<title><?=!empty($domain)?$domain:''?></title>
</head>

<body>
<div style="width:90%; height:auto; float:left; border:1px solid #00b050;">

<div style="width:100%; height:auto; float:left; border-bottom:#00b050 solid 2px;">
    <div style="width:100%; height:auto; float:left;margin:10px; color:#000; font-weight:bold;">
        <?php if(!empty($is_admin)) {
            if(!empty($contact_data['is_valuation_contact']) && $contact_data['is_valuation_contact'] == 'Yes') { 
                echo "New Property Valuation Lead Registered";
            } else {
                echo "New Lead Registered";
            }
        }
        else { echo "Welcome"; }?>
    </div ><!--close logo-->
</div ><!--top head-->


<div style="width:100%; height:auto; float:left; ;">
<div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                <?php
                if(!empty($is_admin)) { 
                    if(!empty($admin_temp_msg)) {
                        echo $admin_temp_msg;
                    } else {
                    ?>
                        <p>Hello <?=!empty($admin_name)?  ucwords($admin_name):''?>,</p>
                        <?php if(!empty($contact_data['is_valuation_contact']) && $contact_data['is_valuation_contact'] == 'Yes') { ?>
                            <p>New property valuation lead is added on <b><?=!empty($domain)?$domain:''?></b>. Details is as below:</p>
                        <?php } else { ?>
                            <p>New lead is added on <b><?=!empty($domain)?$domain:''?></b>. Details is as below:</p>
                        <?php } ?>
                        <p>&nbsp;</p>
                        <p>Lead Type: <b><?=!empty($contact_data['joomla_contact_type'])?$contact_data['joomla_contact_type']:''?></b></p>
                        <p>&nbsp;</p>
                        <p><b>Contact Information</b></p>
                        <p>Name: <b><?=!empty($name)?$name:''?></b></p>
                        <p>Email: <b><?=!empty($email)?$email:''?></b></p>
                        <p>Phone no: <b><?=!empty($contact_data['phone_no'])?$contact_data['phone_no']:''?></b></p>
                        <p>Address: <b><?=!empty($contact_data['joomla_address'])?$contact_data['joomla_address']:''?></b></p>
                        <p>Timeframe: <b><?=!empty($contact_data['joomla_timeframe'])?$contact_data['joomla_timeframe']:''?></b></p>
                        <p>&nbsp;</p>
                        <?php
                        if(!empty($contact_data['house_style']) || !empty($contact_data['price_range_from']) || !empty($contact_data['price_range_to']) || !empty($contact_data['no_of_bedrooms']) || !empty($contact_data['no_of_bathrooms']) || !empty($contact_data['area_of_interest']))
                        {
                        ?>
                            <p><b>Search Criteria</b></p>
                            <?php if(!empty($contact_data['house_style'])) { echo '<p>Property Type: <b>'.$contact_data['house_style'].'</b></p>'; } ?>
                            <?php if(!empty($contact_data['price_range_from']) || !empty($contact_data['price_range_to'])) { echo '<p>Price Range: <b>'.!empty($contact_data['price_range_from'])?'$'.number_format($contact_data['price_range_from']):0 .'-'. !empty($contact_data['price_range_to'])?'$'.number_format($contact_data['price_range_to']):0 .'</b></p>'; } ?>
                            <?php if(!empty($contact_data['no_of_bedrooms'])) { echo '<p>Bedrooms: <b>'. !empty($contact_data['no_of_bedrooms'])?$contact_data['no_of_bedrooms'].'+':'' .'</b></p>'; } ?>
                            <?php if(!empty($contact_data['no_of_bathrooms'])) { echo '<p>Bathrooms: <b>'. !empty($contact_data['no_of_bathrooms'])?$contact_data['no_of_bathrooms'].'+':'' .'</b></p>'; } ?>
                            <?php if(!empty($contact_data['area_of_interest'])) { echo '<p>City/Area: <b>'. !empty($contact_data['area_of_interest'])?$contact_data['area_of_interest']:'' .'</b></p>'; } ?>
                        <?php } ?>
                <?php }
                } 
                else { 
                    if(!empty($temp_msg))
                    {
                        echo $temp_msg;
                    } else {
                    ?>
                        <p>Hello <?=!empty($name)?  ucwords($name):''?>,</p>
                        <p>Thank you for registering on <b><?=!empty($domain)?$domain:''?></b> for our property search. If you would like to view any of the properties you see or have any questions, please do not hesitate to contact us….Happy Searching!</p>
                        <?php /*
                        <p>Name: <b><?=!empty($name)?$name:''?></b></p>
                        <p>Email: <b><?=!empty($email)?$email:''?></b></p>
                        <p>Phone no: <b><?=!empty($contact_data['phone_no'])?$contact_data['phone_no']:''?></b></p>
                        <p>Address: <b><?=!empty($contact_data['joomla_address'])?$contact_data['joomla_address']:''?></b></p>
                        <p>Timeframe: <b><?=!empty($contact_data['joomla_timeframe'])?$contact_data['joomla_timeframe']:''?></b></p>
                         * */ ?>
                <?php } } ?>
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
    <?=!empty($admin_all_details['admin_name'])?$admin_all_details['admin_name']:''?><br />
    <?php if(!empty($admin_all_details['address'])) {echo $admin_all_details['address']."<br />"; }?>
    <?php if(!empty($admin_all_details['phone'])) {echo $admin_all_details['phone']."<br />"; }?>

</div><!--close title-->
</div ><!--top title-->


</div><!--close main div-->
</body>
</html>
