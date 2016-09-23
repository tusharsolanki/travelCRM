            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Valuation Report</title>
<style>
@page { margin: 0; }

</style> 
</head>

<body style=" margin:0; padding:0; font-size:12px; color:#000;">
<table width="720px" border="0" cellspacing="0" cellpadding="0" style="top:0; left:0%; border:solid 5px #000; border-top:none; margin:0 auto; background:#fff; font-family:Georgia, 'Times New Roman', Times, serif;">
	
	<tr>
    <td>
      <table width="720px" border="0" cellspacing="0" cellpadding="0" style="background:#fff; font-family:Georgia, 'Times New Roman', Times, serif;">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:250px; margin:0 auto; text-align:center;">
                    <tr>
                      <td><img src="<?=base_url()?>/images/valuation_pdf/img1.jpg" width="99" height="83" alt="" /></td>
                    </tr>
                    <tr>
                      <td style="font-size:20px; color:#00b050;">
                        <?=!empty($contact_name)?ucwords($contact_name):'-'?>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:17px; color:#879dc6; line-height:25px; padding-bottom:5px;">
                        <?=!empty($company_name)?ucwords($company_name):'-'?>
                      </td>
                    </tr>
                    <tr>
                      <td height="20" align="center" valign="middle" style="font-size:17px; color:#00affd;"><img src="<?=base_url()?>/images/valuation_pdf/skypeImg.png" width="19" height="19" alt="" />
                        <?=!empty($contact_phone)?$contact_phone:'-'?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <a href="#"  style="font-size:14px; color:#98806a; text-decoration:none;">
                          <?=!empty($contact_email)?$contact_email:'-'?>
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td> </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:545px; margin:0 auto; background:#f7f7f7; border:solid 1px #f1f1f1; border-radius:8px;">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="30" colspan="2" align="center" valign="middle" style="font-size:12px; color:#3f3f3f;">Home Price Estimate: <span style="color:#ff0053;"><?=!empty($normal_val)?'$'.$normal_val:'-'?></span></td>
                          </tr>
                          <tr>
                            <td height="30" align="center" valign="middle" style="font-size:17px; color:#3f3f3f;">Low Price Estimate: <span style="color:#ff0053;">
                              <?=!empty($lowvalue)?'$'.$lowvalue:'-'?>
                              </span></td>
                            <td height="30" align="center" valign="middle" style="font-size:17px; color:#3f3f3f;">High Price Estimate: <span style="color:#ff0053;">
                              <?=!empty($highvalue)?'$'.$highvalue:'-'?>
                              </span></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td> </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="30" align="left" valign="middle" style="width:100%; background:#fbfbfb; border-top:solid 1px #eaeaea; border-bottom:solid 1px #eaeaea; font-size:12px; color:#686464; text-transform:uppercase; padding-left:15px;">property deatils</td>
                    </tr>
                    <tr>
                      <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding:10px 15px;">
                          <tr>
                            <td style="font-size:14px; color:#0d5bb2;">Property Type : <span style="color:#00b050;">Single Family Home</span>
                            </td>
                            <td style="font-size:14px; color:#0d5bb2;">Bathrooms : <span style="color:#00b050;">
                              <?=!empty($bathroom)?$bathroom:'-'?>
                              </span></td>
                            <td style="font-size:14px; color:#0d5bb2;">Bedrooms : <span style="color:#00b050;">
                              <?=!empty($bedroom)?$bedroom:'-'?>
                              </span></td>
                          </tr>
                          <tr>
                            <td style="font-size:14px; color:#0d5bb2;">Home Condition : <span style="color:#00b050;">Perfect</span></td>
                            <td style="font-size:14px; color:#0d5bb2;">Square Feet : <span style="color:#00b050;">
                              <?=!empty($finished_sq_ft)?$finished_sq_ft:'-'?>
                              </span></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td style="font-size:14px; color:#0d5bb2;">Year built : <span style="color:#00b050;">
                              <?=!empty($year_built)?$year_built:'-'?>
                              </span></td>
                            <td style="font-size:14px; color:#0d5bb2;">Lot Size : <span style="color:#00b050;">
                              <?=!empty($lotsize_sq_ft)?$lotsize_sq_ft:'-'?>
                              </span></td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="30" align="left" valign="middle" style="width:100%; background:#fbfbfb; border-top:solid 1px #eaeaea; border-bottom:solid 1px #eaeaea; font-size:15px; color:#686464; text-transform:uppercase; padding-left:15px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>Property Address</td>
                            <td>Price History</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding:10px 15px;">
                          <tr>
                            <td style="font-size:14px; color:#0d5bb2;"><?=!empty($address)?$address:'-'?></td>
                            <td style="font-size:14px; color:#0d5bb2;">Last Sold Date: <span style="color:#00b050;">
                              <?=!empty($last_sold_date)?date($this->config->item('common_date_format'),strtotime($last_sold_date)):'-'?>
                              </span></td>
                            <td style="font-size:14px; color:#0d5bb2;">Last Sold Price: <span style="color:#00b050;">
                              <?=!empty($last_sold_price)?'$'.$last_sold_price:'-'?>
                              </span></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>        
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

    <tr>
          <td> </td>
        </tr>
    <tr>
          <td> </td>
        </tr>
    <tr>
              <td height="30" align="left" valign="middle">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:20px;border-top:solid 5px #000;padding:10px 0 10px 0;">

                  <tr>
                    <td width="18" style=" padding:10px 5px 0 15px;"><img src="D://xampp/htdocs/tops_libraries/uploads/valueation_report/homeicon.png" width="18" height="18" alt="" /></td>
                    <td style="font-size:12px; color:#000;">Nearby Sold Property Information For: <?=!empty($address)?$address:''?></td>
                  </tr>
                </table>
              </td>
            </tr>
    <tr>
      <td> </td>
    </tr>
    <?php
        if(!empty($nproperty_name) && count($nproperty_name) > 0)
        {
            $i = 0;
            $j = 1;
            //echo count($nproperty_name);exit;
            foreach($nproperty_name as $row)
            { ?>
    
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:715px; margin:0 auto;">
              <tr>
                <td width="30" height="20" align="left" valign="middle"  style="background:#000; text-align:center;"><img src="D://xampp/htdocs/tops_libraries/uploads/valueation_report/eyeIcon.png" width="16" height="10" alt="" /></td>
                <td height="20" align="left" valign="middle"  style="font-size:12px; background:#00b050; color:#fff; padding-left:10px;">8608 32nd Ave, Seattle, WA 98126</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td> </td>
        </tr>
        <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #eeeeee; margin:0 auto;">
                    <tr>
                      <td style="border-bottom:solid 1px #eeeeee; border-right:solid 1px #eeeeee;" width="260"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30" width="20" ><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td  align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Property Type: <span style="color:#73797b;">Residential - Single Family</span></td>
                          </tr>
                        </table></td>
                      <td style="border-bottom:solid 1px #eeeeee; border-right:solid 1px #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30"  width="20"><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Sold Price: <span style="color:#73797b;">
                              <?=!empty($nprice[$i])?'$'.$nprice[$i]:'-'?>
                              </span></td>
                          </tr>
                        </table></td>
                      <td style="border-bottom:solid 1px #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30"  width="20"><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Lot Size: <span style="color:#73797b;">
                              <?=!empty($nlot_size[$i])?$nlot_size[$i]:'-'?>
                              </span></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td style="border-right:solid 1px #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30"  width="20"><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Living Area Square feet: <span style="color:#73797b;">
                              <?=!empty($nsqft[$i])?$nsqft[$i]:'-'?>
                              </span></td>
                          </tr>
                        </table></td>
                      <td style="border-right:solid 1px #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30"  width="20"><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Sold Date: <span style="color:#73797b;">
                              <?=!empty($nsold_date[$i])?date($this->config->item('common_date_format'),strtotime($nsold_date[$i])):'-'?>
                              </span></td>
                          </tr>
                        </table></td>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" height="30"  width="20"><img src="<?=base_url()?>/images/valuation_pdf/liImg.png" width="9" height="9" alt="" /></td>
                            <td align="left" valign="middle" height="30" style="font-size:12px; color:#000;">Year Built: <span style="color:#73797b;">
                              <?=!empty($nbuild_year[$i])?$nbuild_year[$i]:'-'?>
                              </span></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <?php
             // if($i == 2)
                //  break;
              $i++;
          }
      }
      ?>
    
	    <tr>
    <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" background="#000;">
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style=" background:#000; font-size:25px; color:#fdd76d; line-height:30px;">Market Overview for <?php echo $city;?></td>
                  </tr>
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:8px;">
                        <tr>
                          <tr>
                            <td align="center"><img src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=average_listing_price&city=<?php echo $city;?>&state=<?php echo $state;?>" alt="Average Listing Price: Data not found" /></td>
                            <td align="center"><img src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=listing_volume&city=<?php echo $city;?>&state=<?php echo $state;?>" alt="Listing Volume: Data not found" /></td>
                            <td align="center"><img src="http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=qma_sales_volume&city=<?php echo $city;?>&state=<?php echo $state;?>" alt="Sales Volume: Data not found" /></td>
                          </tr>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="background:#fff; padding:10px; box-shadow:0px -3px 3px 0px #ccc;" ><?php
                     //echo $address;
                      echo $url = "https://maps.googleapis.com/maps/api/geocode/xml?address=".urldecode($address)."&key=AIzaSyCuN6g64vV-H_ydWJbj3zLlbRJflMJFdE0";
                      
                      $xml = simplexml_load_file($url);
                     
                      $latitude = $xml->result->geometry->location->lat;
                      $longitude = $xml->result->geometry->location->lng;

                      if(!empty($address))
                      {
                      ?>

                          <!--<img src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$address?>&zoom=19&size=300x300&maptype=roadmap" width="100% "height="289" />-->
                        
                          <img src="https://maps.googleapis.com/maps/api/staticmap?center=$latitude,$longitude&zoom=19&size=300x300&maptype=hybrid"/>
                          
                          <!--<img src="https://maps.googleapis.com/maps/api/streetview?size=200x200&location=40.720032,-73.988354&heading=235"/>
                          
                          <img src="http://upload.wikimedia.org/wikipedia/commons/a/a0/Google_favicon_2012.jpg" width="100% "height="289" />-->
                          
                      <?php } ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        
    </td>
  </tr>                 
	
</table>

</body>
</html>
          