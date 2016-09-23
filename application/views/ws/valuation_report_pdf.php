<?php 
/*
    @Description: Send email to user with property listing that matches cron setting
    @Author     : Sanjay Moghariya
    @Date       : 28-11-2014
*/
?>
<?php
if(!empty($agent_data) && !empty($agent_data[0]['email_id']) && !empty($agent_data[0]['admin_name']) && !empty($agent_data[0]['assigned_agent_id']))
{
    $agent_name = !empty($agent_data[0]['admin_name'])? ucwords(strtolower($agent_data[0]['admin_name'])):'';
    $agent_email = !empty($agent_data[0]['email_id'])?$agent_data[0]['email_id']:'';
    $agent_phone = !empty($agent_data[0]['phone'])?$agent_data[0]['phone']:'';
    $address = '';
    $address .= !empty($agent_data[0]['address_line1'])?$agent_data[0]['address_line1']:'';
    $address .= !empty($agent_data[0]['address_line2'])?', '.$agent_data[0]['address_line2']:'';
    $address .= !empty($agent_data[0]['city'])?', '.$agent_data[0]['city']:'';
    $address .= !empty($agent_data[0]['state'])?', '.$agent_data[0]['state']:'';
    $address .= !empty($agent_data[0]['zip_code'])?' '.$agent_data[0]['zip_code']:'';
    $agent_address = trim($address);
    $brokerage_pic = !empty($agent_data[0]['brokerage_pic'])?$agent_data[0]['brokerage_pic']:'';
}
else
{
    $agent_name = !empty($admin_data['admin_name'])?ucwords(strtolower($admin_data['admin_name'])):'';
    $agent_email = !empty($admin_data['email_id'])?$admin_data['email_id']:'';
    $agent_phone = !empty($admin_data['phone'])?$admin_data['phone']:'';
    $agent_address = !empty($admin_data['address'])?trim($admin_data['address']):'';
    $brokerage_pic = !empty($admin_data['brokerage_pic'])?$admin_data['brokerage_pic']:'';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$this->config->item('sitename');?></title>
    </head>
    <body>
        <div style="width:100%; height:auto; float:left; border:none;">
            <div style="width:100%; height:auto; float:left;">
                <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                    <h1 id="site-logo" style="text-align: center">
                        <?php if(!empty($brokerage_pic) && file_exists($this->config->item('broker_small_img_path').$brokerage_pic)) { ?>
                            <img src="<?php echo $this->config->item('broker_upload_img_small').$brokerage_pic;?>" alt="Site Logo">
                        <?php } else { ?>
                                <img src="<?php echo $this->config->item('image_path').'logo.png';?>" alt="Site Logo">
                        <?php } ?>
                    </h1>
                </div ><!--close logo-->
            </div ><!--top head-->
            <div style="width:100%; height:auto; float:left; text-align: center;font-weight: normal">
                <div style="font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                    <p style="text-align: center">
                        <div style="width:100%; height:auto;text-align: center;">
                            <div style="font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                                <?php
                                $total_property = !empty($property_data)?count($property_data):0;
                                ?>
                                This property valuation is based on the neighborhood around <span style="color:#0489B1;font-weight: bold"><?=!empty($neighbor_address)?$neighbor_address:'-'?></span>. These are the last <?php echo $total_property;?> properties that were sold in your neighborhood.
                                This report is intended only to show you the activity in your neighborhood. If you would like a detailed valuation of your home, or have any other questions that we can help you with, please contact us at <span style="font-weight: bold;"><?php echo $agent_email?></span> or <span style="font-weight: bold;"><?php echo $agent_phone?></span>.
                            </div>
                        </div>
                    </p>
                    <p style="text-align: center">
                        <?php
                        if(!empty($property_data) && count($property_data) > 0)
                        {
                            foreach($property_data as $row)
                            {
                                if(!empty($row['name']))
                                { ?>
                                    <div style="width:80%; height:auto; float:left">
                                        <div style="font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                                            <table width="100%" align="center" border="0" style="font-size:12px;">
                                                <tr>
                                                    <?php
                                                    $image_url = '';
                                                    if(!empty($mls_id) && $mls_id == 1 && !empty($row['LN']) && !empty($image_list) && array_key_exists($row['LN'],$image_list))
                                                       $image_url = image_url($this->config->item('NWMLS').$row['LN'].'/medium/'.$image_list[$row['LN']]);
                                                    elseif(!empty($mls_id) && $mls_id == 2 && !empty($row['LN']) && !empty($row['PIC']))
                                                       $image_url = image_url($this->config->item('Asheville').$row['LN'].'_1.jpg');
                                                    elseif(!empty($mls_id) && $mls_id == 3 && !empty($row['LN']) && !empty($row['PIC']))
                                                       $image_url = image_url($this->config->item('Portland').$row['LN'].'_1.jpg');
                                                    elseif(!empty($mls_id) && $mls_id == 4 && !empty($row['Internal_MLS_ID']) && !empty($row['PIC']))
                                                       $image_url = image_url($this->config->item('Florida').$row['Internal_MLS_ID'].'_1.jpg');
                                                    ?>
                                                    <?php if(!empty($image_url)) { ?>
                                                    <td rowspan="9" width="35%" ><img src="<?=$row['image']?>" width="180" height="150"></img></td>
                                                    <?php } else { ?>
                                                    <td rowspan="9" width="35%" ><img src="<?=$this->config->item('base_url')?>images/no-img-banner-flyer2.jpg" width="180" height="150"></img></td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align: center"><h4><?= !empty($row['name']) ? $row['name'] : '-'?></h4></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Beds:</td>
                                                    <td><b><?= !empty($row['bedrooms']) ? $row['bedrooms'] : 0?></b></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Baths:</td>
                                                    <td><b><?= !empty($row['bathrooms']) ? $row['bathrooms'] : 0?></b></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Sq. Feet:</td>
                                                    <td><b><?= !empty($row['sqft']) ? number_format($row['sqft']) : 0?></b></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Lot Size:</td>
                                                    <td><b><?= !empty($row['lot_size']) ? number_format(($row['lot_size'] / 43560),3).' ac' : 0?></b></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Original List Price:</td>
                                                    <td><b><?= !empty($row['LP']) ? '$'.number_format($row['LP']) : 0.00?></b></td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#0489B1;font-weight: bold">Sold Price:</td>
                                                    <td><b><?= !empty($row['SP']) ? '$'.number_format($row['SP']) : 0.00?></b></td>
                                                </tr>
                                                <tr>
                                                    <?php $lp = !empty($row['LP'])?$row['LP']:0;
                                                    $sp = !empty($row['SP'])?$row['SP']:0;
                                                    ?>
                                                    <td colspan="2">This property sold for <span style="color:#f00;"><?php echo number_format(($sp*100)/$lp,2).'%'; ?></span> of the listing price in <span style="color: #f00;"><?= !empty($row['CDOM']) ? $row['CDOM'] : 0?> Days</span> on the market.</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center"><b><?php echo "MLS# ".$row['LN'];?></b></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        }
                        ?>
                    </p>
                    <p style="text-align: center">
                        <div style="width:100%; height:auto; float:left;margin:10px; color:#fff;">
                            <div style="font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                                This report was prepared for you on <b><?=date('m/d/Y')?></b> by the <b><?php echo $agent_name?></b> <?php if(!empty($agent_address)) { ?>a member of <b><?php echo $agent_address?></b> <?php } ?>. If you have any questions, or would like a detailed report on the value of your home, please email us at <b><?php echo $agent_email?></b> or call us at <b><?php echo $agent_phone?></b>.
                            </div>
                        </div>
                    </p>
                    <p style="text-align: center">
                        <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                            <h1 id="site-logo" style="text-align: center">
                                <?php if(!empty($brokerage_pic) && file_exists($this->config->item('broker_small_img_path').$brokerage_pic)) { ?>
                                    <img src="<?php echo $this->config->item('broker_upload_img_small').$brokerage_pic;?>" alt="Site Logo" />
                                <?php } else { ?>
                                        <img src="<?php echo $this->config->item('image_path').'logo.png';?>" alt="Site Logo" />
                                <?php } ?>
                            </h1>
                        </div ><!--close logo-->
                    </p>
                </div><!--close peregraph content-->
            </div><!--close left side-->
            <?php /*
            <div style="width:100%; height:auto; float:left; background:#00b050; ">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:10.5px; color:#fff; font-weight:bold; width:100%; height:auto; line-height:20px;margin:10px;">
                    Thank you,<br />
                    <?=$this->config->item('sitename');?><br />
                </div><!--close title-->
            </div ><!--top title-->
            */?>
        </div><!--close main div-->
    </body>
</html>