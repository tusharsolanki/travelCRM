<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<style>
body {
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, sans-serif;
}
</style>
<?
$image_path=base_url('flyer_image/flyer1');
if(!empty($photos_trans_data[0]['photo']))
{
$back_image=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[0]['photo'];
}
else
{	
	//$back_image = $image_path.'/banner-bg.jpg';
	$back_image = base_url().'images/no-img-banner-flyer.jpg';
}
 ?>
 
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" style="background-image:url(<?=$image_path?>/mainbg.jpg)center center fixed; width:100%; height:auto; ">
    <!--<table width="1300" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto; width:1300px; background-image:url(<?=$image_path?>/mainbg.jpg) repeat left;">-->
    <table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30" style="width:100%; height:20px;" >&nbsp;</td>
        </tr>
        <tr>
          <td height="400" align="center" valign="top"  ><table width="100%" height="400px" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="50">&nbsp;</td>
              <td height="400px" align="center" valign="top"><table width="1200" border="0" align="center" style="width:1200px; height:100%;  text-align:center; background-image:url(<?=$back_image?>) top center no-repeat; ">
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td width="152" height="152" align="center" valign="middle" style=" background-image:url(<?=$image_path?>/roundWhiteBg.png) top center no-repeat; border-radius:99px; font-size:24px; color:#00b050; font-weight:bold; z-index:999999; position:relative; margin:2px 0 0 0; line-height:60px;">&nbsp;&nbsp;<?php
					if(!empty($editRecord[0]['price']))
					{
						$explode = explode('.',$editRecord[0]['price']);
						if(!empty($explode[1]) && $explode[1] != '00')
							echo $editRecord[0]['price'];
						elseif(!empty($explode[0]))
							echo $explode[0];
						else
							echo '0';
							
					}
					else
						echo '0';
				 ?></td>
                </tr>
                <tr>
                  <td height="320" valign="top">&nbsp;</td>
                  <td height="200" align="center" valign="middle" >&nbsp;</td>
                </tr>
              </table></td>
              <td width="50">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#00B050" ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td align="center" valign="top" bgcolor="#00B050" style="background:#00b050; padding:0 0;"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto; width:1200px;">
          <tr>
            <td align="center" valign="middle" style="display:inline-block;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:22px; font-weight:bold; color:#fff; line-height:35px;"><?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?>
                  <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?>
                  <br />
                  <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?>
                  ,
                  <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?>
                  <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?>
                  </h1>
                  <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></td>
                </tr>
              </table></td>
            <td style="width:260px;"><table align="right" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:23px; font-weight:bold; color:#fff; line-height:48px;"><?php
            		if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
						echo $editRecord[0]['admin_name'];
					else
						echo $editRecord[0]['user_name'];
				   ?></td>
                </tr>
              <tr>
                <td height="35" align="left" valign="middle" style="font-size:18px; color:#fff; line-height:22px;"><?php 
						if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
							echo $editRecord[0]['phone'];
						else
							echo $editRecord[0]['phone_no'];
					?></td>
                </tr>
              <? if(!empty($editRecord[0]['email_id'])) {?>
              <tr>
                <td height="20" align="left" valign="middle"><a href="#" style="font-size:13px; color:#fff; line-height:15px;">
                  <?=!empty($editRecord[0]['email_id'])?$editRecord[0]['email_id']:''?>
                  </a></td>
                </tr>
              <? }?>
              <? if(!empty($editRecord[0]['website_name'])) {?>
              <tr>
                <td height="25" align="left" valign="middle"><a href="#" style="font-size:13px; color:#fff; line-height:15px;">
                  <?=!empty($editRecord[0]['website_name'])?$editRecord[0]['website_name']:''?>
                  </a></td>
                </tr>
              <? }?>
              <? if(!empty($editRecord[0]['user_license_no'])) {?>
              <tr>
                <td height="20" align="left" valign="middle" style="font-size:13px; color:#fff; line-height:15px;"><?=!empty($editRecord[0]['user_license_no'])?$editRecord[0]['user_license_no']:''?></td>
                </tr>
              <? }?>
              </table></td>
            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" >&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
       <table border="0" cellspacing="0" cellpadding="0" style="width:750px; margin:0 auto;">
        <tr>
          <td height="45">&nbsp;</td>
        </tr>
        <tr>
          <td height="38" align="left" valign="middle" style="font-size:15px; color:#fff; font-weight:bold; padding-left:20px; background:#2a2a2a;">&nbsp;&nbsp;Property Description</td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td height="75" align="left" valign="middle" style="font-size:13px; color:#3a3a3c; line-height:20px;">
          <?=!empty($editRecord[0]['remarks'])?$editRecord[0]['remarks']:''?>
           </td>
        </tr>
        <tr>
          <td height="20"></td>
        </tr>
        </table>
       
    </td>
  </tr>
  </table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="309" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:750px; margin:0 auto;">
      <tr>
        <td style="border-right:solid 1px #999;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="72" height="45" align="left" valign="top" style="font-size:16px; color:#999;"><?=!empty($editRecord[0]['total_area_name'])?$editRecord[0]['total_area_name']:'';?></td>
            <td height="45" align="left" valign="top" style="font-size:16px; color:#3a3a3c; font-weight:bold;"><?php 
							if(!empty($editRecord[0]['total_area']) && $editRecord[0]['total_area'] != '0.00') 
								echo $editRecord[0]['total_area'];
							else 
								echo '0';?></td>
          </tr>
          <tr>
            <td width="72" height="45" align="left" valign="top" style="font-size:16px; color:#999;">BATHS	: </td>
            <td height="45" align="left" valign="top" style="font-size:16px; color:#3a3a3c; font-weight:bold;"><?=!empty($editRecord[0]['bathrooms_count'])?$editRecord[0]['bathrooms_count']:'0'?></td>
          </tr>
          <tr>
            <td width="72" height="45" align="left" valign="top" style="font-size:16px; color:#999;">BEDS	: </td>
            <td height="45" align="left" valign="top" style="font-size:16px; color:#3a3a3c; font-weight:bold;"><?=!empty($editRecord[0]['bedrooms_count'])?$editRecord[0]['bedrooms_count']:'0'?></td>
          </tr>
        </table></td>
        <td width="26" align="center"></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="85" height="45" align="left" valign="top" style="font-size:16px; color:#999; text-transform:uppercase;">KITCHEN	: </td>
            <td height="45" align="left" valign="top" style="font-size:16px; color:#3a3a3c; font-weight:bold;"><?=!empty($editRecord[0]['kitchen_count'])?$editRecord[0]['kitchen_count']:'0'?></td>
          </tr>
          <tr>
            <td width="85" height="45" align="left" valign="top" style="font-size:16px; color:#999; text-transform:uppercase;">FLOORS	: </td>
            <td height="45" align="left" valign="top" style="font-size:16px; color:#3a3a3c; font-weight:bold;"><?=!empty($editRecord[0]['floor_count'])?$editRecord[0]['floor_count']:'0'?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="70">&nbsp;</td>
    <td width="795" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td style="font-size:18px; color:#2a2a2a; font-weight:bold;">Additional Amenities</td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <?php 
							  $i=0;
							  if(!empty($editRecord[0]['sewer_name'])) {
							  $i++;
							  if($i== 1){?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:18px; color:#999;">Sewer :
                  <?=$editRecord[0]['sewer_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <?  } } ?>
            <? if(!empty($editRecord[0]['basement_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Basement :
                  <?=$editRecord[0]['basement_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['parking_type_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Parking Type :
                  <?=$editRecord[0]['parking_type_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['parking_spaces'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Parking Spaces :
                  <?=$editRecord[0]['parking_spaces']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['builder_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Builder :
                  <?=$editRecord[0]['builder_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['style_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Style :
                  <?=$editRecord[0]['style_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['exterior_finish_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Exterior Finish :
                  <?=$editRecord[0]['exterior_finish_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['foundation_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Foundation :
                  <?=$editRecord[0]['foundation_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['roof_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Roof :
                  <?=$editRecord[0]['roof_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['architecture_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Architecture :
                  <?=$editRecord[0]['architecture_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['green_certification_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Green Certification :
                  <?=$editRecord[0]['green_certification_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['fireplace_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Fireplace :
                  <?=$editRecord[0]['fireplace_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['energy_source_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Energy Source :
                  <?=$editRecord[0]['energy_source_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['heating_cooling_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Heating/Cooling :
                  <?=$editRecord[0]['heating_cooling_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['floor_covering_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Floor Covering :
                  <?=$editRecord[0]['floor_covering_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['interior_feature_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Interior Features :
                  <?=$editRecord[0]['interior_feature_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['water_company_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Water Company :
                  <?=$editRecord[0]['water_company_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['power_company_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Power Company :
                  <?=$editRecord[0]['power_company_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <? if(!empty($editRecord[0]['sewer_company_name'])) {
							$i++;
							if($i%3 == 1)
							{ ?>
          </tr>
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <? } ?>
            <td style="background:#f8f8f8;" height="41" width="200"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14">&nbsp;</td>
                <td><img src="<?=$image_path?>/chkboximg.png" width="12" height="12" alt="" /></td>
                <td width="7">&nbsp;</td>
                <td align="left" style="font-size:16px; color:#999;">Sewer Company :
                  <?=$editRecord[0]['sewer_company_name']?></td>
              </tr>
            </table></td>
            <td width="15">&nbsp;</td>
            <? } ?>
            <?
							  if($i == 0) {
										?>
            <td><span><b>No Amenities</b></span></td>
            <?php } ?>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="110">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td valign="top">
       <table border="0" cellspacing="0" cellpadding="0" >
       <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="30"></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:950px; margin:0 auto;">
              <tr>
               <td width="60">&nbsp;</td>
               <?
		 //$i = 0;
		// pr($photos_trans_data);
		   if(!empty($photos_trans_data)) {
                    
						for($i=0;$i<count($photos_trans_data);$i++)
						{
							if($i==4)
							{break;}
						if(!empty($photos_trans_data[$i]['photo']) && file_exists($this->config->item('listing_small_img_path').$photos_trans_data[$i]['photo'])) {
                     
						?>
               
                		<td width="280"><img width="246" height="246" src="<?=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[$i]['photo']?>" alt="" /></td>
                		<td>&nbsp;</td>
                 <?	
                    
                    } //$i++;
				}
				
			}?>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="33">&nbsp;</td>
        </tr>
    </table>
    </td>
  </tr>
</table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" style=" background:#00b050;  padding:10px 0;">
    <table  border="0" cellspacing="0" cellpadding="0" style="width:750px; margin:0 auto;">
         <tr height="2"><td>&nbsp;</td></tr>
        <tr valign="top">
          <td width="250" align="center" valign="middle" style="background:#fff;"><img src="<?=$image_path?>/footerlogo1.png" width="230" height="47" alt="" /></td>
          <td width="15"></td>
          <td width="220" align="center" valign="middle" style="font-weight:bold; background:#fff;">
           <?php if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$editRecord[0]['brokerage_pic'])) {
        ?>
        		<img width="210" height="47" src="<?=$this->config->item('broker_upload_img_small').$editRecord[0]['brokerage_pic']?>">
        <?php } else { ?>
          		<img width="210" height="47" src="<?=$this->config->item('listing_path')?>theme2/images/ban.png">
        <?php } ?>
          </td>
          <td width="17"></td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" style="font-size:18px; color:#fff; font-weight:bold; line-height:22px;">
                 <?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?><br /> <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> </h1>
             <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?>
                </td>
              </tr>
             
            </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
