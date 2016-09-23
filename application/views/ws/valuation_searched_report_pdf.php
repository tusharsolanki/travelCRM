<?php 
/*
    @Description: Send email to user with property valuation that matches cron setting
    @Author     : Sanjay Moghariya
    @Date       : 03-12-2014
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$this->config->item('sitename');?></title>
    </head>
    <body>
        <div style="width:100%; height:auto; float:left; border:1px solid #00b050;">
            <div style="width:100%; height:auto; float:left; border-bottom:#00b050 solid 2px;">
                <div style="width:100%; height:auto; float:left;margin:10px; color:#fff; font-weight:bold;">
                    <h1 id="site-logo"><img src="<?php echo $this->config->item('image_path');?>logo.png" alt="Site Logo"></h1>
                </div ><!--close logo-->
            </div ><!--top head-->
            <div style="width:100%; height:auto; float:left; ;">
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                    <p>
                        <?php
                        if(!empty($nproperty_name) && count($nproperty_name) > 0)
                        {
                            $i = 0;
                            $j = 1;
                            foreach($nproperty_name as $row)
                            {
                                if(!empty($row))
                                { ?>
                                    <div style="width:100%; height:auto; float:left; border-bottom:#00b050 solid 2px;">
                                        <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#333; line-height:15px; text-align:justify; margin:10px;">
                                            <table>
                                                <tr>
                                                    <td colspan="2"><b><?=$j?></b></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%"><b>Property Name:</b></td>
                                                    <td><?= !empty($row) ? $row : '-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Description:</b></td>
                                                    <td><?= !empty($nproperty_description[$i]) ? $nproperty_description[$i] : '-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Price:</b></td>
                                                    <td><?= !empty($nprice[$i]) ? $nprice[$i].' USD' : '-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Build Year:</b></td>
                                                    <td><?= !empty($nbuild_year[$i]) ? $nbuild_year[$i]: '-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bedrooms:</b></td>
                                                    <td><?= !empty($nbedrooms[$i]) ? $nbedrooms[$i]:'-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bathrooms:</b></td>
                                                    <td><?= !empty($nbathrooms[$i]) ? $nbathrooms[$i]:'-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Living Area Square Feet:</b></td>
                                                    <td><?= !empty($nsqft[$i]) ? $nsqft[$i]:'-'?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Lot Size:</b></td>
                                                    <td><?= !empty($nlot_size[$i]) ? $nlot_size[$i]:'-'?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                    $j++;
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </p>
                    <p>&nbsp;</p>
                </div><!--close peregraph content-->
            </div><!--close left side-->
            <div id="avglisting"> <img id="avglistingimg" class="chartdiv1 lazy" alt="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=780&height=300&type=average_listing_price&city=<?=$city?>&state=<?=$state?>" src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=780&height=300&type=average_listing_price&city=<?php echo $city;?>&state=<?php echo $state;?>" /> </div>
            <div id="numlisting"> <img id="numlistingimag" class="chartdiv1 lazy" alt="Number of Listings Graph not found" src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=780&height=300&type=listing_volume&city=<?php echo $city;?>&state=<?php echo $state;?>" /> </div>
            <div id="numsales"> <img id="numsalesimg" class="chartdiv1 lazy" alt="Number of Sales Graph not found" src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=780&height=300&type=qma_sales_volume&city=<?php echo $city;?>&state=<?php echo $state;?>" /> </div>
        </div><!--close main div-->
    </body>
</html>