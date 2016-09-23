<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?
$image_path=base_url('flyer_image/flyer2');
if(!empty($photos_trans_data[0]['photo']))
{
$back_image=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[0]['photo'];
}
else
{
	//$back_image=$image_path.'/banner-bg.jpg';
	$back_image = base_url().'images/no-img-banner-flyer2.jpg';

}
 ?>
 
<body style="margin:0; padding:0; font-family:Arial, Helvetica, sans-serif;">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top" style="background:url(<?=$back_image?>)center center fixed; height:540px; width:700px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1078">&nbsp;</td>
        <td width="261">&nbsp;</td>
      </tr>
      <tr>
        <td height="149">&nbsp;</td>
        
        <td width="262" height="149" align="right" valign="top" style="background-image:url(<?=$image_path?>/top-price.png) top right no-repeat; width:160px;">
        <table width="232" height="119" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="4" height="60">&nbsp;</td>
            <td width="200">&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center" valign="top" style="font-size:27px; color:#fff; font-weight:bold;">
              
              <?php
				if(!empty($editRecord[0]['price']))
					{
						$explode = explode('.',$editRecord[0]['price']);
						if(!empty($explode[1]) && $explode[1] != '00')
							echo '<b style="font-size:35px; margin:5px 0 0 0;">$</b>'.$editRecord[0]['price'];
						elseif(!empty($explode[0]))
							echo '<b style="font-size:35px; margin:5px 0 0 0;">$</b>'.$explode[0];
						else
							echo '<b style="font-size:35px; margin:5px 0 0 0;">$</b>'.'0';
							
					}
					else
						echo '<b style="font-size:35px; margin:5px 0 0 0;">$</b>'.'0';
				 ?></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td height="183">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="150" align="left" valign="top">
        <table width="362" height="90" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="8%" height="90" align="left" valign="top" bgcolor="#967adc">&nbsp;</td>
            <td width="92%" height="18" align="left" valign="middle" bgcolor="#967adc"><span style="font-size:35px; color:#fff; line-height:55px; font-weight:bold;">
            <?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?><br /> 
			<?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> 
              <br /><?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></h1>
            </span></td>
          </tr>
          </table></td>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;">
    <table width="1300" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:-130px auto 0" >
      <tr>
        <td align="center" valign="top"><table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="36" align="left" valign="top">&nbsp;</td>
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
            <td width="298" align="left" valign="top" ><img src="<?=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[$i]['photo']?>" width="268" height="268" style="border:15px solid #fff;" /></td>
            <td width="36" align="left" valign="top">&nbsp;</td>
            <?	
                    
                    } //$i++;
				}
				
			}?>
            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30" align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto; width:1200px; padding:0 40px " >
      <tr>
        <td height="25" align="left" valign="top" style="background:#2a2a2a"><table width="900" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25">&nbsp;</td>
            <td height="25" align="left" valign="middle" style="font-size:18px; color:#fff; font-weight:bold; background-image:url(<?=$image_path?>title-right-bg.png) top center no-repeat ;" ><span style="font-size:18px; color:#fff; font-weight:bold;">Property Description</span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;">
    <table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto; width:1200px; padding:0 40px" >
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:20px; font-family:Arial, Helvetica, sans-serif;"><?=!empty($editRecord[0]['remarks'])?$editRecord[0]['remarks']:''?></td>
      </tr>
      <tr>
        <td height="275" align="right" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;width:750px;" >
      <tr>
        <td align="center" valign="top" style="color:#3a3a3c; font-size:17px;"><table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;width:750px;" >
          <tr>
            <td height="49" bgcolor="#efefef"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:15px;">
              <tr>
                <td width="200" align="center" style="border-right:1px solid #e0e4e4; color:#999999; font-size:20x"><?php 
					if(!empty($editRecord[0]['total_area']) && $editRecord[0]['total_area'] != '0.00') 
						echo $editRecord[0]['total_area_name'].'<strong style="color:#3a3a3c">&nbsp;'.$editRecord[0]['total_area'].'</strong>';
					else 
						echo '0';?></td>
                <td width="200" align="center" style="text-transform:uppercase; border-right:1px solid #e0e4e4; color:#999999; font-size:20x">BATHS <strong style="color:#3a3a3c; ">
                  <?=!empty($editRecord[0]['bathrooms_count'])?$editRecord[0]['bathrooms_count']:'0'?>
                </strong></td>
                <td width="200" align="center" style="text-transform:uppercase; border-right:1px solid #e0e4e4; color:#999999; font-size:20x">BEDS <strong style="color:#3a3a3c">
                  <?=!empty($editRecord[0]['bedrooms_count'])?$editRecord[0]['bedrooms_count']:'0'?>
                </strong></td>
                <td width="200" align="center" style="text-transform:uppercase; border-right:1px solid #e0e4e4; color:#999999; font-size:20x">KITCHEN<strong style="color:#3a3a3c">
                  <?=!empty($editRecord[0]['kitchen_count'])?$editRecord[0]['kitchen_count']:'0'?>
                </strong></td>
                <td width="200" align="center" style="text-transform:uppercase; border-right:1px solid #e0e4e4; color:#999999; font-size:20x">FLOOR<strong style="color:#3a3a3c">
                  <?=!empty($editRecord[0]['floor_count'])?$editRecord[0]['floor_count']:'0'?>
                </strong></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="47" align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="18" align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto; width:1200px; padding:0 40px " >
      <tr>
        <td height="25" align="left" valign="top" style="background:#2a2a2a"><table width="900" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25">&nbsp;</td>
            <td height="25" align="left" valign="middle" style="font-size:18px; color:#fff; font-weight:bold; background-image:url(<?=$image_path?>title-right-bg.png) top center no-repeat ;" >Additional Amenities</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;">&nbsp;</td>
  </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <?php 
		  $i=0;
		  if(!empty($editRecord[0]['sewer_name'])) {
		  $i++;
		  if($i== 1){?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Sewer :
          <?=$editRecord[0]['sewer_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <?  } } ?>
        <? if(!empty($editRecord[0]['basement_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Basement :
          <?=$editRecord[0]['basement_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['parking_type_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Parking Type :
          <?=$editRecord[0]['parking_type_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['parking_spaces'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Air Conditioning :
          <?=$editRecord[0]['parking_spaces']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['builder_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Builder :
          <?=$editRecord[0]['builder_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['style_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Style :
          <?=$editRecord[0]['style_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['exterior_finish_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Exterior Finish :
          <?=$editRecord[0]['exterior_finish_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['foundation_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Foundation :
          <?=$editRecord[0]['foundation_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['roof_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Roof :
          <?=$editRecord[0]['roof_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['architecture_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Architecture :
          <?=$editRecord[0]['architecture_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['green_certification_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Green Certification :
          <?=$editRecord[0]['green_certification_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['fireplace_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Fireplace :
          <?=$editRecord[0]['fireplace_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['energy_source_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Energy Source :
          <?=$editRecord[0]['energy_source_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['heating_cooling_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Heating/Cooling :
          <?=$editRecord[0]['heating_cooling_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['floor_covering_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Floor Covering :
          <?=$editRecord[0]['floor_covering_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['interior_feature_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Interior Features :
          <?=$editRecord[0]['interior_feature_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['water_company_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Water Company :
          <?=$editRecord[0]['water_company_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['power_company_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Power Company :
          <?=$editRecord[0]['power_company_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <? if(!empty($editRecord[0]['sewer_company_name'])) {
		    $i++;
			if($i%3 == 1)
			{ ?>
      </tr>
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
      <tr>
        <? } ?>
        <td width="300" height="44" align="left" valign="middle" bgcolor="#FFFFFF" style="border-radius:4px; color:#999999;font-size:18px">&nbsp;&nbsp;<img src="<?=$image_path?>/check-box.png" width="14" height="14" style="margin:0 12px 0 0;" />Sewer Company :
          <?=$editRecord[0]['sewer_company_name']?></td>
        <td width="14" align="left" valign="top">&nbsp;</td>
        <? } ?>
        <?
		  if($i == 0) {
					?>
        <td><span><b>No Amenities</b></span></td>
        <?php } ?>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="100%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td width="50" align="left" valign="top">&nbsp;</td>
        <td width="350" height="255" align="left" valign="top" style="background:#967adc;"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="30" height="30">&nbsp;</td>
            <td>&nbsp;</td>
            <td width="30">&nbsp;</td>
            <td>&nbsp;</td>
            <td width="30">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td width="230" height="133" align="center" valign="middle" bgcolor="#FFFFFF"><img src="<?=$image_path?>/logo.png" width="175" height="31" /></td>
            <td>&nbsp;</td>
            <td width="230" align="center" valign="middle" bgcolor="#FFFFFF"><?php if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$editRecord[0]['brokerage_pic'])) {
        ?>
              <img src="<?=$this->config->item('broker_upload_img_small').$editRecord[0]['brokerage_pic']?>" />
              <?php } else { ?>
              <img src="<?=$this->config->item('listing_path')?>theme2/images/ban.png" />
              <?php } ?></td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="27">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="3" align="center" valign="top" style="font-size:22px; color:#fff;"><?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?>
              <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?>
              <br />
              <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?>
              ,
              <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?>
              <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?>
              </h1>
              <br />
              <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td width="200">&nbsp;</td>
        <td align="left" valign="top" bgcolor="#efefef"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td height="25" align="left" valign="top" style="background:#2a2a2a"><table width="500" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td width="25">&nbsp;</td>
                <td height="25" align="left" valign="middle" style="font-size:18px; color:#fff; font-weight:bold;"><?php
            		if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
						echo $editRecord[0]['admin_name'];
					else
						echo $editRecord[0]['user_name'];
				   ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="37">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:20px; color:#3a3a3c">
              <tr>
                <td width="160">&nbsp;</td>
                <td align="center" valign="middle" bgcolor="#967adc" style="height:48px; color:#fff; border-radius:4px; font-size:20px;"><?php 
						if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
							echo $editRecord[0]['phone'];
						else
							echo $editRecord[0]['phone_no'];
					?></td>
                <td width="160">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle" >&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <? if(!empty($editRecord[0]['email_id'])) {?>
              <tr>
                <td>&nbsp;</td>
                <td height="30" align="center" valign="middle" ><a href="#" style="color:#3a3a3c;">
                  <?=!empty($editRecord[0]['email_id'])?$editRecord[0]['email_id']:''?>
                </a></td>
                <td>&nbsp;</td>
              </tr>
              <? }?>
              <? if(!empty($editRecord[0]['website_name'])) {?>
              <tr>
                <td>&nbsp;</td>
                <td height="30" align="center" valign="middle" ><a href="#" style="color:#3a3a3c;">
                  <?=$editRecord[0]['website_name']?>
                </a></td>
                <td>&nbsp;</td>
              </tr>
              <? }?>
              <? if(!empty($editRecord[0]['user_license_no'])) {?>
              <tr>
                <td>&nbsp;</td>
                <td height="30" align="center" valign="middle" ><?=!empty($editRecord[0]['user_license_no'])?$editRecord[0]['user_license_no']:''?></td>
                <td>&nbsp;</td>
              </tr>
              <? }?>
            </table></td>
          </tr>
        </table></td>
        <td width="50" align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td align="center" valign="top" style="background-image:url(<?=$image_path?>/con-bg.jpg) top center no-repeat #86b6cd; background-size:100% 100%;"><table width="1500" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;width:1500px;" >
      <tr>
        <td align="left" valign="top" style="color:#3a3a3c; font-size:17px;">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
