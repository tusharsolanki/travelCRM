<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?
$image_path=base_url('flyer_image/flyer3')."/";
if(!empty($photos_trans_data[0]['photo']))
{
$back_image=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[0]['photo'];
}
else
{
	//$back_image=$image_path.'/banner-bg.jpg';
	$back_image = base_url().'images/no-img-banner-flyer.jpg';

}
 ?>
<body style="font-family:Arial, Helvetica, sans-serif; padding:0; margin:0;">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#2c3e50">&nbsp;</td>
    <td width="1300" align="center" valign="top" bgcolor="#2c3e50"><table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="333" align="center" valign="middle" bgcolor="#e9573f" style="font-size:40px; line-height:50px; color:#fff;">
		<?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?><br /><?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?><br /> 
			<?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> 
              <br /><?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?>
        </td>
        <td width="177">&nbsp;</td>
        <td align="left" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td height="50">&nbsp;</td>
          </tr>
          <tr>
            <td height="35" align="center" valign="top" style="font-size:16px; color:#fff; text-align:center; font-weight:bold; border-bottom:1px solid #808b96;">Property Description</td>
          </tr>
          <tr>
            <td >&nbsp;</td>
          </tr>
          <tr>
            <td  style="font-size:14px; color:#fff; line-height:22px; text-align:justify;">
            	<?=!empty($editRecord[0]['remarks'])?$editRecord[0]['remarks']:''?>
            </td>
          </tr>
          <tr>
            <td height="50" align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td bgcolor="#2c3e50">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#f1c40f">&nbsp;</td>
    <td width="1300" height="53" align="center" valign="top" bgcolor="#f1c40f"><table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="133" align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
    <td bgcolor="#f1c40f">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" valign="top" style="background-image:url(<?=$back_image?>); width:700px; height:730px; background-repeat:no-repeat; background-position:center;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td width="1300" align="left" valign="top"><table width="200" border="0" align="left" cellpadding="0" cellspacing="0" style="margin:-230px 0 0 -7px;">
          <tr>
            <td width="200" height="138" align="left" valign="top" style="background-image:url(<?=$image_path?>price-bg.png); background-repeat:no-repeat; background-position:top center;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" style="font-size:50px; color:#fff;">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" width="340" style="font-size:50px; color:#fff; text-align:center;"><?php
				if(!empty($editRecord[0]['price']))
					{
						$explode = explode('.',$editRecord[0]['price']);
						if(!empty($explode[1]) && $explode[1] != '00')
							echo '<b style="font-size:55px;font-weight:normal; margin:0 5px 0 0;">$</b>'.$editRecord[0]['price'];
						elseif(!empty($explode[0]))
							echo '<b style="font-size:55px;font-weight:normal; margin:0 5px 0 0;">$</b>'.$explode[0];
						else
							echo '<b style="font-size:55px;font-weight:normal; margin:0 5px 0 0;">$</b>'.'0';
							
					}
					else
						echo '<b style="font-size:35px; margin:5px 0 0 0;">$</b>'.'0';
				 ?></td>
              </tr>
            </table></td>
          </tr>
        </table>
          <table width="800" border="0" align="right" cellpadding="0" cellspacing="0" style="margin:-125px 0 0 0;">
          <tr >
            <?
		 //$i = 0;
		// pr($photos_trans_data);
		   if(!empty($photos_trans_data)) {
				for($i=0;$i<count($photos_trans_data);$i++)
				{
					if($i==3)
					{break;}
					if(!empty($photos_trans_data[$i]['photo']) && file_exists($this->config->item('listing_small_img_path').$photos_trans_data[$i]['photo']))
					{
                     
						?>
                        <td width="250" height="250" align="left" valign="top">
                        <img src="<?=base_url().$this->config->item('listing_small_upload_img_path').$photos_trans_data[$i]['photo']?>" width="250" height="250" /></td>
                        <?php if($i!=2){ ?>
                        <td width="25">&nbsp;</td>
                        <?php } ?>
            <?php	
                    } //$i++;
				}
				
				$totalpics = count($photos_trans_data);
				if($totalpics<2)
				{?>
					<td width="250" height="250" align="left" valign="top">&nbsp;</td>
					<td width="25">&nbsp;</td>
					<td width="250" height="250" align="left" valign="top">&nbsp;</td>
		<?php	}
				elseif($totalpics<3)
				{?>
					<td width="250" height="250" align="left" valign="top">&nbsp;</td>
					<td width="25">&nbsp;</td>
		<?php	}
			
			}?>
            
            </tr>
        </table></td>
        <td align="right">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="275" colspan="3" align="center" valign="top"  bgcolor="#2C3E50"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td width="1300" align="center" valign="top"><table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="730" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#fff;">
                  <tr>
                    <td width="42%" height="40" align="center">&nbsp;</td>
                    <td width="60" align="center">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td  width="200" height="30" align="left" style="border-right:1px solid #fff; margin-left:10PX; padding-left:10PX;"> &nbsp; &nbsp;<?php 
					if(!empty($editRecord[0]['total_area']) && $editRecord[0]['total_area'] != '0.00') 
						echo $editRecord[0]['total_area_name'].' : &nbsp;'.$editRecord[0]['total_area'];
					else 
						echo 'Total Area : 0';?></td>
                    <td>&nbsp;</td>
                    <td>BATHS :
                  		<?=!empty($editRecord[0]['bathrooms_count'])?$editRecord[0]['bathrooms_count']:'0'?>
                	</td>
                  </tr>
                  <tr>
                    <td height="30"  style="border-right:1px solid #fff;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="200" height="30" align="left" style="border-right:1px solid #fff; margin-left:10PX; padding-left:10PX;"> &nbsp; &nbsp;BEDS : 
                  		<?=!empty($editRecord[0]['bedrooms_count'])?$editRecord[0]['bedrooms_count']:'0'?>
                	</td>
                    <td>&nbsp;</td>
                    <td>KITCHEN : 
                  		<?=!empty($editRecord[0]['kitchen_count'])?$editRecord[0]['kitchen_count']:'0'?>
                	</td>
                  </tr>
                  <tr>
                    <td height="30"  style="border-right:1px solid #fff;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="200" height="30" align="left" style="border-right:1px solid #fff; margin-left:10PX; padding-left:10PX;"> &nbsp; &nbsp;FLOOR : 
                  		<?=!empty($editRecord[0]['floor_count'])?$editRecord[0]['floor_count']:'0'?>
                	</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
                <td width="100">&nbsp;</td>
                <td width="275" align="left" valign="top">
                	<?php if(!empty($editRecord[0]['admin_pic']) && file_exists($this->config->item('admin_small_img_path').$editRecord[0]['admin_pic'])) { ?>
						<img src="<?=$this->config->item('admin_upload_img_small')?>/<?=$editRecord[0]['admin_pic']?>" width="275" height="276">
			<?php } elseif(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['admin_pic'])) {?>
						<img src="<?=$this->config->item('user_upload_img_small')?>/<?=$editRecord[0]['contact_pic']?>" width="275" height="276">
            <?php } else { ?>
            			<img src="<?=base_url()?>images/no-img-banner-small.jpg" width="275" height="276">
            <?php } ?>
                </td>
              </tr>
            </table></td>
            <td width="570" align="left" valign="top" bgcolor="#f9d130"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td height="45">&nbsp;</td>
                </tr>
              <tr>
                <td height="60" align="center" width="560" valign="middle"><b style="font-size:36px;">
                	<?php
            		if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
						echo $editRecord[0]['admin_name'];
					else
						echo $editRecord[0]['user_name'];
				   ?>
                </b></td>
                </tr>
              <tr>
                <td height="40" align="center" valign="top" style="font-size:24px;">
                	<?php 
						if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
							echo $editRecord[0]['phone'];
						else
							echo $editRecord[0]['phone_no'];
					?>
                </td>
                </tr>
                
                <? if(!empty($editRecord[0]['email_id'])) {?>
                  <tr>
                    <td align="center" style="font-size:16px; line-height:26px;">
                      <?=!empty($editRecord[0]['email_id'])?$editRecord[0]['email_id']:''?>
                    </td>
                  </tr>
                  <? }?>
                  <? if(!empty($editRecord[0]['website_name'])) {?>
                  <tr>
                    <td align="center" style="font-size:16px; line-height:26px;">
                      <?=$editRecord[0]['website_name']?>
                    </td>
                  </tr>
                  <? }?>
                  <? if(!empty($editRecord[0]['user_license_no'])) {?>
                  <tr>
                    <td align="center" style="font-size:16px; line-height:26px;">
						<?=!empty($editRecord[0]['user_license_no'])?$editRecord[0]['user_license_no']:''?>
                    </td>
                  </tr>
                  <? }?>
              
                <td height="50">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td bgcolor="#f9d130">&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center" valign="top"><table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="730" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="50" align="left" valign="top" style=" font-size:20px; color:#fff;">Additional Amenities</td>
              </tr>
              
              <tr>
				<?php 
                  $i=0;
                  if(!empty($editRecord[0]['sewer_name'])) {
                  $i++;
                  if($i== 1){?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Sewer :
                      <?=$editRecord[0]['sewer_name']?></td>
                      </tr>
                    </table>
                </td>
                <?  } } ?>
                <? if(!empty($editRecord[0]['basement_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Basement :
                      <?=$editRecord[0]['basement_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['parking_type_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Parking Type :
                  <?=$editRecord[0]['parking_type_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['parking_spaces'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Air Conditioning :
                  <?=$editRecord[0]['parking_spaces']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['builder_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Builder :
                  <?=$editRecord[0]['builder_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['style_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Style :
                  <?=$editRecord[0]['style_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['exterior_finish_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Exterior Finish :
                  <?=$editRecord[0]['exterior_finish_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['foundation_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Foundation :
                  <?=$editRecord[0]['foundation_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['roof_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Roof :
                  <?=$editRecord[0]['roof_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['architecture_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Architecture :
                  <?=$editRecord[0]['architecture_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['green_certification_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Green Certification :
                  <?=$editRecord[0]['green_certification_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['fireplace_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Fireplace :
                  <?=$editRecord[0]['fireplace_name']?></td>
                      </tr>
                    </table>
               </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['energy_source_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Energy Source :
                  <?=$editRecord[0]['energy_source_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['heating_cooling_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Heating/Cooling :
                  <?=$editRecord[0]['heating_cooling_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['floor_covering_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Floor Covering :
                  <?=$editRecord[0]['floor_covering_name']?></td>
                      </tr>
                    </table>
               </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['interior_feature_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Interior Features :
                  <?=$editRecord[0]['interior_feature_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['water_company_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Water Company :
                  <?=$editRecord[0]['water_company_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['power_company_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Power Company :
                  <?=$editRecord[0]['power_company_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <? if(!empty($editRecord[0]['sewer_company_name'])) {
                    $i++;
                    if($i%2 == 1)
                    { ?>
              </tr>
              
              <tr>
                <? } ?>
                <td>
                	<table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                      <tr>
                        <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Sewer Company :
                  <?=$editRecord[0]['sewer_company_name']?></td>
                      </tr>
                    </table>
                </td>
                
                <? } ?>
                <?
                  if($i == 0) {
                            ?>
                <td><span><b>No Amenities</b></span></td>
                <?php } ?>
              </tr>
              
              <!--<tr>
                <td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Air Conditioning</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Heating</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Balcony</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Dishwasher</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Pool</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Microwave</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />SecurityCamera</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Fan</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Servants</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Furnished</td>
                  </tr>
                </table><br clear="all" /><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Gym</td>
                  </tr>
                </table><table border="0" cellspacing="0" cellpadding="0"  style="float:left; margin:0 10px 10px 0;">
                  <tr>
                    <td height="40" align="center" bgcolor="#1c2b3a" style="color:#fff; padding:0 10px; margin:0 0 10px 0; border-radius:4px;"><img src="<?=$image_path?>right-icon.png" width="17" height="13" />Fan</td>
                  </tr>
                </table></td>
              </tr>-->
            </table></td>
            <td width="570" height="150" align="center" valign="top" bgcolor="#f1c40f"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
                <td width="298">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td height="70" width="550" align="center" valign="middle" bgcolor="#f9d130"><img src="<?=$image_path?>logo.PNG" width="261" height="47" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td height="70" align="center" valign="middle" bgcolor="#FFFFFF" style="font-size:18px;">
                	<?php if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$editRecord[0]['brokerage_pic'])) {
        ?>
              <img src="<?=$this->config->item('broker_upload_img_small').$editRecord[0]['brokerage_pic']?>" />
              <?php } else { ?>
              <img src="<?=$this->config->item('listing_path')?>theme2/images/ban.png" />
              <?php } ?></td>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" align="center" style="font-weight:bold;"><?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?> <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></td>
                </tr>
              <tr>
                <td colspan="3" align="center">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td bgcolor="#f1c40f">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>