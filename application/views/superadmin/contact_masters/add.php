  <!--/*
        @Description: View for add Contact
        @Author: Mohit Trivedi
        @Input: -
        @Output: -
        @Date: 01-09-14
        */
-->
<?php
	
	$viewname = $this->router->uri->segments[2]; 
  	isset($editRecord) ? $loadcontroller='update_email' : $loadcontroller='insert_email';
  		$path_email = $viewname."/".$loadcontroller;
	isset($editPhoneRecord) ? $loadcontroller='update_phone' : $loadcontroller='insert_phone';
  		$path_phone = $viewname."/".$loadcontroller;
	isset($editAddressRecord) ? $loadcontroller='update_address' : $loadcontroller='insert_address';
  		$path_address = $viewname."/".$loadcontroller;
	isset($editWebsiteRecord) ? $loadcontroller='update_website' : $loadcontroller='insert_website';
  		$path_website = $viewname."/".$loadcontroller;
	isset($editStatusRecord) ? $loadcontroller='update_status' : $loadcontroller='insert_status';
  		$path_status = $viewname."/".$loadcontroller;
	isset($editDispositionRecord) ? $loadcontroller='update_disposition' : $loadcontroller='insert_disposition';
  		$path_disposition = $viewname."/".$loadcontroller;
	isset($editProfileRecord) ? $loadcontroller='update_profile' : $loadcontroller='insert_profile';
  		$path_profile = $viewname."/".$loadcontroller;
	isset($editContactRecord) ? $loadcontroller='update_contact' : $loadcontroller='insert_contact';
  		$path_contact = $viewname."/".$loadcontroller;
	isset($editDocumentRecord) ? $loadcontroller='update_document' : $loadcontroller='insert_document';
  		$path_document = $viewname."/".$loadcontroller;
	isset($editSourceRecord) ? $loadcontroller='update_source' : $loadcontroller='insert_source';
  		$path_source = $viewname."/".$loadcontroller;
  	isset($editMethodRecord) ? $loadcontroller='update_method' : $loadcontroller='insert_method';
  		$path_method = $viewname."/".$loadcontroller;
	isset($editFieldRecord) ? $loadcontroller='update_field' : $loadcontroller='insert_field';
  		$path_field = $viewname."/".$loadcontroller;
	//pr($editPhoneRecord);
	//$path = $viewname."/".$loadcontroller;
?>
<div id="content" class="contact-masters">
  <div id="content-header">
   <h1>Contact Masters</h1>
  </div>
  <div id="content-container">
  	
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
      
       <h3> <i class="fa fa-tasks"></i>Contact Masters</h3>       
       	 <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>  
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
		
		<div class="">
		  <div class="chart_bg1 tbl_border">
			<!-- EMAIL TYPE-->
			<form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="email_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_email?>" class="form parsley-form" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_email_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <!--Not original but correct things-->
              <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($email_type) && count($email_type)>0){
					foreach($email_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space"></div>
					<div id="flash"></div>
					<div id="show"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="email_update[]" id="email_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="email_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
					
						<a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_email_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="email_id" name="email_id" class="email_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents" class="form-group">
					 <?php if(empty($email_type) || count($email_type) == 0){?>
					  <p>
						<label for="p_scnts">
					<input type="text" class="form-control parsley-validated" data-required="required" name="email_type[0]" id="email_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt" title="Add Email Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Email Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type" onclick="return setdefaultdata('email_form_<?php echo $viewname;?>');">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
              
              
			  </div>
			  </div>
			  </div>
			</form>
			
			<!-- PHONE TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="phone_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_phone?>" >
			<div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_phone_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
				<table width="100%" class="iconment_title_in" >
				<tr>
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($phone_type) && count($phone_type)>0){
					foreach($phone_type as $row)
					{   
				?>
				<tr>
				  <td colspan="2">
				  	<div class="space_phone"></div>
					<div id="flash_phone"></div>
					<div id="show_phone"></div>
				  </td>
				</tr>
				<tr>
					<td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="phone_update[]" id="phone_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="phone_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_phone('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_phone_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
					</td>
				</tr>
				<?php }}?>
				</table>
                <table width="100%">
					<tr>
						<td colspan="2">
                        <input type="hidden" id="phone_id" name="phone_id" class="phone_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                        <div id="p_scents_phone" class="form-group">
                         <?php if(empty($phone_type) || count($phone_type) == 0){?>
							<label for="p_scnts_phone">
							<input type="text" class="form-control parsley-validated" data-required="required" name="phone_type[0]" id="phone_type[0]" value="<?=isset($editPhoneRecord) ? $editPhoneRecord[0]['name'] : ''?>" />
							</label>
                             <?php } ?>
							</div>
                            </td>
					</tr>
                    
                    
                    <tr>
				<td>
					  	
                       <a href="#" id="addScnt_phone" title="Add Phone Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Phone Type</a>
                       
					</td>
					<td>&nbsp;</td>
				</tr>
                    
					<tr>
						<td>
							<input type="submit" style="width:auto;" onclick="return setdefaultdata('phone_form_<?php echo $viewname;?>');" class="btn btn-primary" value="Save" name="type">
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>
			</div>
			</div>
			</form>
			
			<!-- ADDRESS TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="address_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_address?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_address_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($address_type) && count($address_type)>0){
					foreach($address_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_address"></div>
					<div id="flash_address"></div>
					<div id="show_address"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
						<input type="text" class="form-control parsley-validated" name="address_update[]" id="address_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                         <input type="hidden" class="form-control parsley-validated" name="address_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_address('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_address_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="address_id" name="address_id" class="address_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_address" class="form-group">
					
                    <?php if(empty($address_type) || count($address_type) == 0){?>
						<label for="p_scnts_address">
						<input type="text" class="form-control parsley-validated" data-required="required" name="address_type[0]" id="address_type[0]" value="<?=isset($editAddressRecord) ? $editAddressRecord[0]['name'] : ''?>" />
						</label>
                        
                        <?php } ?>
                        
						<!-- <a id="addScnt_address" class="btn btn-xs btn-success icons3 iconsd3" href="#"><i class="fa fa-plus"></i></a>-->
					 
					</div>
                    </td>
				</tr>
                
                 <tr>
                <td>
                <a href="#" id="addScnt_address" title="Add Address Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Address Type</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('address_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
			
			<!-- WEBSITE TYPE-->
			<!--<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_website?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<?php echo $this->lang->line('common_label_website_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($website_type) && count($website_type)>0){
					foreach($website_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_website"></div>
					<div id="flash_website"></div>
					<div id="show_website"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize">
					
						<input type="text" class="form-control parsley-validated" name="website" id="website_<?=$row['id']?>" value="<?php echo  $row['name'] ?>" />
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_website('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=addslashes($row['name'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_website_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <td width="30%" height="35px"><input type="hidden" name="id" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['id']:''?>" />
					<?php echo $this->lang->line('common_label_website_type')?> <span style="color:#F00">*</span></td>
				  <td width="70%"  height="45px"><div id="p_scents_website" class="form-group">
					  <p>
						<label for="p_scnts_website">
						<input type="text" class="form-control parsley-validated" data-required="required" name="website_type[0]" id="website_type[0]" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['name'] : ''?>" />
						<a href="#" id="addScnt_website" style="color:#999900;font-size:10px;"><img src="<?=base_url('images/add_icon.jpg') ?>"/></a> </label>
					  </p>
					</div></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" value="Save" name="type">
				  </td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>-->
			
            <!-- WEBSITE TYPE-->
			<!--<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="website_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_website?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<?php echo $this->lang->line('common_label_website_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($website_type) && count($website_type)>0){
					foreach($website_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_website"></div>
					<div id="flash_website"></div>
					<div id="show_website"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="website" id="website_<?=$row['id']?>" value="<?php echo  $row['name'] ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
					</td>
					<td>
                    <?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_website('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=addslashes($row['name'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_website_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  <?php }?>
                </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				 <input type="hidden" name="website_id" class="website_id" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_websitetype" class="">
					  <?php if(empty($website_type) || count($website_type) == 0){?>
                      <p>
						<label for="p_scnts_website">
						<input type="text" class="form-control parsley-validated" data-required="required" name="website_type[0]" id="website_type[0]" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['name'] : ''?>" /></label>
					  </p>
					  <?php }?>
					</div>
                    </td>
				</tr>
				<tr>
                <td>
 					<a href="javascript:void(0)" id="addScnt_websitetype" title="Add Address Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Website Type</a>

                </td>
                	
				  <td>&nbsp;</td>
                  </tr>
                  <tr>
				  <td><input type="submit" onclick="return setdefaultdata('website_form_<?php echo $viewname;?>');"  style="width:auto;"  class="btn btn-primary" value="Save" name="type">
				  </td>
                  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>-->
            
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="website_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_website?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_website_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($website_type) && count($website_type)>0){
					foreach($website_type as $row)
					{ 
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_website"></div>
					<div id="flash_website"></div>
					<div id="show_website"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
						<input type="text" class="form-control parsley-validated" name="website_update[]" id="website_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="website_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_website('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_website_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" name="website_id" class="website_id" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scnts_websitetype" class="form-group">
					
                    <?php if(empty($website_type) || count($website_type) == 0){?>
						<label for="p_scnts_websitetype">
						<input type="text" class="form-control parsley-validated" data-required="required" name="website_type[0]" id="website_type[0]" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['name'] : ''?>" />
						</label>
                        <?php } ?>
					 
					</div>
                    </td>
				</tr>
                
                
                
                 <tr>
                    <td>
                    <a href="#" id="addScnt_websitetype" title="Add Website Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Website Type</a>
                    </td>
                    <td>&nbsp;</td>
                    </tr>
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('website_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
            
            
            
			<!-- STATUS TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="status_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_status?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_status_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($status_type) && count($status_type)>0){
					foreach($status_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_status"></div>
					<div id="flash_status"></div>
					<div id="show_status"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="status_update[]" id="status_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                         <input type="hidden" class="form-control parsley-validated" name="status_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_status('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_status_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_status" class="form-group">
					
                    <?php if(empty($status_type) || count($status_type) == 0){?>
						<label for="p_scnts_status">
						<input type="text" class="form-control parsley-validated" data-required="required" name="status_type[0]" id="status_type[0]" value="<?=isset($editWebsiteRecord) ? $editWebsiteRecord[0]['name'] : ''?>" />
						</label>
						
                      <?php } ?>
				
					</div>
                    </td>
				</tr>
                
                <tr>
                <td>
                <a href="#" id="addScnt_status" title="Add Status Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Status Type</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('status_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
			
			<!-- PROFILE TYPE-->
			<?php /*?><form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_profile?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_profile_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($profile_type) && count($profile_type)>0){
					foreach($profile_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_profile"></div>
					<div id="flash_profile"></div>
					<div id="show_profile"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="profile_update[]" id="profile_<?=$row['id']?>" value="<?php echo  $row['name'] ?>" />
						
						<input type="hidden" class="form-control parsley-validated" name="profile_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_profile('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=addslashes($row['name'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_profile_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editProfileRecord) ? $editProfileRecord[0]['id']:''?>" />
				  <td colspan="2">
					<div id="p_scents_profile" class="form-group">
			
						<label for="p_scnts_profile">
						<input type="text" class="form-control parsley-validated" data-required="required" name="profile_type[0]" id="profile_type[0]" value="<?=isset($editProfileRecord) ? $editProfileRecord[0]['name'] : ''?>" />
						</label>
						
				
                <a id="addScnt_profile" class="btn btn-xs btn-success icons3 iconsd3" href="#"><i class="fa fa-plus"></i></a>
					</div></td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form><?php */?>
			
			<!-- Contact TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="contact_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_contact?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_contact_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($contact_type) && count($contact_type)>0){
					foreach($contact_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_contact"></div>
					<div id="flash_contact"></div>
					<div id="show_contact"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="contact_update[]" id="contact_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        <input type="hidden" class="form-control parsley-validated" name="contact_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
				</td>
				<td>
						<a href="javascript:void(0);" onclick="get_submit_contact('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_contact_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editContactRecord) ? $editContactRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_contact" class="form-group">
					 
                     <?php if(empty($contact_type) || count($contact_type) == 0){?>
						<label for="p_scnts_contact">
						<input type="text" class="form-control parsley-validated" data-required="required" name="contact_type[0]" id="contact_type[0]" value="<?=isset($editContactRecord) ? $editContactRecord[0]['name'] : ''?>" />
						</label>
						<?php } ?>
                       
					</div>
                    </td>
				</tr>
                
                
                <tr>
                <td>
                <a href="#" id="addScnt_contact" title="Add Contact Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Contact Type</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('contact_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
			
			 <!-- DOCUMENT TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="documents_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_document?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_document_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($document_type) && count($document_type)>0){
					foreach($document_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_document"></div>
					<div id="flash_document"></div>
					<div id="show_document"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="document_update[]" id="document_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                         <input type="hidden" class="form-control parsley-validated" name="document_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
				</td>
				<td>
						<a href="javascript:void(0);" onclick="get_submit_document('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_document_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editDocumentRecord) ? $editDocumentRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_document" class="form-group">
					
                    <?php if(empty($document_type) || count($document_type) == 0){?>
						<label for="p_scnts_document">
						<input type="text" class="form-control parsley-validated" data-required="required" name="document_type[0]" id="document_type[0]" value="<?=isset($editDocumentRecord) ? $editDocumentRecord[0]['name'] : ''?>" />
						</label>
						<?php } ?>
                        
					</div>
                    </td>
				</tr>
                
                
                 <tr>
                <td>
                <a href="#" id="addScnt_document" title="Add Document Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Document Type</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
                
				<tr>
				  <td><input type="submit" style="width:auto;" onclick="return setdefaultdata('documents_form_<?php echo $viewname;?>');" class="btn btn-primary" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
			
			<!-- SOURCE TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="source_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_source?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_source_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($source_type) && count($source_type)>0){
					foreach($source_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_source"></div>
					<div id="flash_source"></div>
					<div id="show_source"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="source_update[]" id="source_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="source_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_source('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_source_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editSourceRecord) ? $editSourceRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_source" class="form-group">
					 
                     <?php if(empty($source_type) || count($source_type) == 0){?>
						<label for="p_scnts_source">
						<input type="text" class="form-control parsley-validated" data-required="required" name="source_type[0]" id="source_type[0]" value="<?=isset($editSourceRecord) ? $editSourceRecord[0]['name'] : ''?>" />
						</label>
                       <?php } ?> 
                        
					</div>
                    </td>
				</tr>
                
                 <tr>
                <td>
                <a href="#" id="addScnt_source" title="Add Source Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Source Type</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('source_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
            
			<!-- DISPOSITION TYPE-->
			<?php /*?><form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_disposition?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_disposition_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($disposition_type) && count($disposition_type)>0){
					foreach($disposition_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_disposition"></div>
					<div id="flash_disposition"></div>
					<div id="show_disposition"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="disposition_update[]" id="disposition_<?=$row['id']?>" value="<?php echo  $row['name'] ?>" />
						
						
						<input type="hidden" class="form-control parsley-validated" name="disposition_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
						
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_disposition('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=addslashes($row['name'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_disposition_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editdispositionRecord) ? $editdispositionRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_disposition" class="form-group">
					  
						<label for="p_scnts_disposition">
						<input type="text" class="form-control parsley-validated" data-required="required" name="disposition_type[0]" id="disposition_type[0]" value="<?=isset($editdispositionRecord) ? $editdispositionRecord[0]['name'] : ''?>" />
						</label>
										  
                      <a href="#" class="btn btn-xs btn-success icons icons3 iconsd3" id="addScnt_disposition"><i class="fa fa-plus"></i></a>
                      
                      
					</div></td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form><?php */?>
            
            <!-- METHOD TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="source_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_method?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_method_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th class="title_inp1"><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($method_type) && count($method_type)>0){
					foreach($method_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_method"></div>
					<div id="flash_method"></div>
					<div id="show_method"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="method_update[]" id="method_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="method_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<a href="javascript:void(0);" onclick="get_submit_method('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_method_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editmethodRecord) ? $editmethodRecord[0]['id']:''?>" />
				  <td colspan="2">
                  <div id="p_scents_method" class="form-group">
					 <?php if(empty($method_type) || count($method_type) == 0){?>
						<label for="p_scents_method">
						<input type="text" class="form-control parsley-validated" data-required="required" name="method_type[0]" id="method_type[0]" value="<?=isset($editmethodRecord) ? $editmethodRecord[0]['name'] : ''?>" />
						</label>
                        <?php } ?>
                      
					</div>
                    </td>
				</tr>
                
                 <tr>
                <td>
                <a href="#" id="addScnt_method" title="Add Contact Method" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Contact Method</a>
                </td>
                <td>&nbsp;</td>
                </tr>
                
                
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('source_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
            
            <!-- ADDITIONAL FIELD-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="additional_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_field?>" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('common_label_field_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr>
				  <th class=""><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($field_type) && count($field_type)>0){
					foreach($field_type as $row)
					{   
					?>
				<tr>
				  <td colspan="3">
				  	<div class="space_field"></div>
					<div id="flash_field"></div>
					<div id="show_field"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="60%">
					
						<input type="text" class="form-control parsley-validated" name="field_name_edit[]" id="field_name_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                        
                        <input type="hidden" class="form-control parsley-validated" name="field_name_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                        
					</td>
                    <td class="text_capitalize" width="20%">
               <select class="form-control parsley-validated" name="field_type_edit_action[]" id="field_type_<?=$row['id']?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?>>
               <option <?php if(!empty($row['field_type']) && $row['field_type'] == '1'){ echo "selected"; }?> value="1">Text</option>
			   <option <?php if(!empty($row['field_type']) && $row['field_type'] == '2'){ echo "selected"; }?> value="2">Date</option>
		              </select>
					</td>
                    
					<td width="20%">
						<a href="javascript:void(0);" onclick="get_submit_field('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_field_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
                <tr>
				  <input type="hidden" name="id" value="<?=isset($editfieldRecord) ? $editfieldRecord[0]['id']:''?>" />
                 <td colspan="3">
                 <div id="p_scents_field" class="form-group">
                 
                 <?php if(empty($field_type) || count($field_type) == 0){?>
                 <table width="100%" id="">
                        <tr>
                            <td width="60%">
                                	<input type="text" class="form-control parsley-validated" data-required="required" name="field_name[0]" id="field_name[0]" value="<?=isset($editfieldRecord) ? $editfieldRecord[0]['name'] : ''?>" />
                            </td>
                            <td width="20%">
                            	<select class="form-control parsley-validated" name="field_type[0]" id="field_type[0]">
               <option <?php if(!empty($editfieldRecord['field_type']) && $editfieldRecord['field_type'] == '1'){ echo "selected"; }?> value="1">Text</option>
			   <option <?php if(!empty($editfieldRecord['field_type']) && $editfieldRecord['field_type'] == '2'){ echo "selected"; }?> value="2">Date</option>
		              </select>
                            </td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                     </table>
                  <?php } ?>
                     </div>
                     </td>
                   </tr>
                   
                    <tr>
                    <td>
                    <a href="#" id="addScnt_field" title="Add Field Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Field Type</a>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                   
                   
                   
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary" onclick="return setdefaultdata('additional_form_<?php echo $viewname;?>');" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
            
			</div>
			</div>
            </div>
			
		  </div>
		</div>
		
	
  </div>
<script type="text/javascript">
    $(document).ready(function (){
            //$('#<?php echo $viewname;?>').bValidator();
    });
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function getemail()
    {
        var emailid = $("#email").val();
        $.ajax({
            type: "post",
            data: {'email':emailid,
            },
            url: '<?php echo $this->config->item('superadmin_base_url')?>/user/getemail', 
            success: function(msg1) 
            {
                if(msg1 != '')
                {
                    $("#emailexist").val(msg1);
                    $("#email").focus();
                }
                else
                    $("#emailexist").val(msg1);
            }
        });	
    return false;
    }
</script>

<!-- ================== START Multipal Input Box Script ================== -->
<!-- ==== Email INPUT ADD ===== -->
<script type="text/javascript">

$(function() {

        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt', function(){
			i++;
        $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="email_type[' + i +']" id="email_type[' + i +']"/></label>&nbsp;<a href="#" id="remScnt" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
               // i++;
                return false;
        });
        
		$('body').on('click', '#remScnt', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== PHONE INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_phone');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_phone', function(){
			i++;
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="phone_type[' + i +']" id="phone_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_phone" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_phone', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== Address INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_address');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_address', function(){
			i++;
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="address_type[' + i +']" id="address_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_address" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_address', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== Website INPUT ADD ===== -->

<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scnts_websitetype');
        var i = $('#p_scents_websitetype p').size();
		$('body').on('click', '#addScnt_websitetype', function(){
			i++;
        $('<p><label for="p_scnts_websitetype"><input type="text" class="form-control parsley-validated" data-required="required" name="website_type[' + i +']" id="website_type[' + i +']"/> </label>&nbsp;<a href="javascript:void(0)" id="remScnt_websitetype" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
				//i++;
                return false;
        });      
		$('body').on('click', '#remScnt_websitetype', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>


<!-- ==== Status INPUT ADD ===== -->
<script type="text/javascript">
/*$(function() {
        var scntDiv = $('#p_scents_website');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_website', function(){
        $('<p><label for="p_scnts_website"><input type="text" class="form-control parsley-validated" data-required="required" name="website_type[' + i +']" id="website_type[' + i +']"/> </label><a href="#" id="remScnt_website" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_website', function(){ 
                if( i > 1 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});*/

$(function() {
        var scntDiv = $('#p_scents_website');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_website', function(){
			i++;
        $('<p><label for="p_scnts_website"><input type="text" class="form-control parsley-validated" data-required="required" name="website_type[' + i +']" id="website_type[' + i +']"/> </label><a href="javascript:void(0)" id="remScnt_website" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
               // i++;
                return false;
        });      
		$('body').on('click', '#remScnt_website', function(){ 
                if( i > 0 ) {
                        $(this).parents('p').remove();
                       // i--;
                }
                return false;
        });
});

$(function() {
        var scntDiv = $('#p_scents_status');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_status', function(){
			i++;
        $('<p><label for="p_scnts_status"><input type="text" class="form-control parsley-validated" data-required="required" name="status_type[' + i +']" id="status_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_status" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_status', function(){ 
                if( i > 0 ) {
                        $(this).parents('p').remove();
                       // i--;
                }
                return false;
        });
});

</script>

<!-- ==== Profile INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_profile');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_profile', function(){
			i++;
        $('<p><label for="p_scnts_profile"><input type="text" class="form-control parsley-validated" data-required="required" name="profile_type[' + i +']" id="profile_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_profile" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_profile', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== Contact INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_contact');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_contact', function(){
			i++;
        $('<p><label for="p_scnts_contact"><input type="text" class="form-control parsley-validated" data-required="required" name="contact_type[' + i +']" id="contact_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_contact" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
               // i++;
                return false;
        });      
		$('body').on('click', '#remScnt_contact', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== Document INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_document');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_document', function(){
			i++;
        $('<p><label for="p_scnts_document"><input type="text" class="form-control parsley-validated" data-required="required" name="document_type[' + i +']" id="document_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_document" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a><p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_document', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== Source INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_source');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_source', function(){
			i++;
        $('<p><label for="p_scnts_source"><input type="text" class="form-control parsley-validated" data-required="required" name="source_type[' + i +']" id="source_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_source" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                //i++;
                return false;
        });      
		$('body').on('click', '#remScnt_source', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== DISPOSITION INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_disposition');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt_disposition', function(){
			i++;
        $('<p><label for="p_scnts_disposition"><input type="text" class="form-control parsley-validated" data-required="required" name="disposition_type[' + i +']" id="disposition_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_disposition" class="btn btn-xs btn-primary icons3 iconsd3"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_disposition', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>

<!-- ==== CONTACT METHOD INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_method');
        var i = $('#p_scents_method p').size();
		$('body').on('click', '#addScnt_method', function(){
			i++;
        $('<p><label for="p_scnts_method"><input type="text" class="form-control parsley-validated" data-required="required" name="method_type[' + i +']" id="method_type[' + i +']"/> </label>&nbsp;<a href="javascript:void(0)" id="remScnt_method" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                return false;
        });      
		$('body').on('click', '#remScnt_method', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                       // i--;
                }
                return false;
        });
});
</script>

<!-- ==== CONTACT ADDITIONAL FIELD INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_field');
        var i = $('#p_scents_field').size();
		$('body').on('click', '#addScnt_field', function(){
        $('<tr><td width="60%"><input type="text" class="form-control parsley-validated" data-required="required" name="field_name[' + i +']" id="field_name[' + i +']"/></td><td width="20%"><select class="form-control parsley-validated contact_module" name="field_type[' + i +']" id="field_type[' + i +']"><option value="1">Text</option><option value="2">Date</option></select></td><td width="20%"><a href="javascript:void(0)" id="remScnt_field" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></td></tr>').appendTo(scntDiv);
				i++;
                return false;
        });      
		$('body').on('click', '#remScnt_field', function(){
			//alert('hiii');
                if( i > 0 ) {
                        $(this).closest('tr').remove();
                       // i--;
                }
                return false;
        });
});
</script>

<!-- ================== END Multipal Input Script ================== -->

<!-- ================== START Ajax Script ================== -->
<!-- ==== Email UPDATE AJAX ===== -->
<script type="text/javascript">
function getsubmit(id)
{
	var email = $("#email_"+id).val();
	if(email=='' && id=='')
	{
		alert("Enter text..");
		$("#email").focus();
	}
	else
	{
		//alert(id);
		$("#flash").show();
		$("#flash").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_email',
		data: { email_type:email,email_id:id },
		cache: true,
		success: function(html)
		{
			$("#show").after(html);
			//$("#flash").hide();
			$("#email").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== PHONE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_phone(id)
{
	//alert(id);exit;
	var phone = $("#phone_"+id).val();
	//alert(phone);
	if(phone=='' && id=='')
	{
		alert("Enter text..");
		$("#phone").focus();
	}
	else
	{
		$("#flash_phone").show();
		$("#flash_phone").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_phone',
		data: { phone_type:phone,phone_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_phone").after(html);
			//$("#flash_phone").hide();
			$("#phone").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== ADDRESS UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_address(id)
{
	//alert(id);exit;
	var address = $("#address_"+id).val();
	//alert(phone);
	if(address=='' && id=='')
	{
		alert("Enter text..");
		$("#address").focus();
	}
	else
	{
		$("#flash_address").show();
		$("#flash_address").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_address',
		data: { address_type:address,address_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_address").after(html);
			//$("#flash_address").hide();
			$("#address").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== WEBSITE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_website(id)
{
	//alert(id);exit;
	var website = $("#website_"+id).val();
	//alert(address);
	if(website=='' && id=='')
	{
		alert("Enter text..");
		$("#website").focus();
	}
	else
	{
		$("#flash_website").show();
		$("#flash_website").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_website',
		data: { website_type:website,website_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_website").after(html);
			//$("#flash_website").hide();
			$("#website").focus();
		}  
		});
	}
	return false;
}

function get_submit_status(id)
{
	//alert(id);exit;
	var status = $("#status_"+id).val();
	//alert(address);
	if(status=='' && id=='')
	{
		alert("Enter text..");
		$("#status").focus();
	}
	else
	{
		$("#flash_status").show();
		$("#flash_status").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_status',
		data: { status_type:status,status_id:id },
		cache: true,
		success: function(html)
		{
			$("#flash_status").after(html);
			//$("#flash_status").hide();
			$("#status").focus();
		}  
		});
	}
	return false;
}

</script>

<!-- ==== PROFILE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_profile(id)
{
	//alert(id);exit;
	var profile = $("#profile_"+id).val();
	//alert(address);
	if(profile=='' && id=='')
	{
		alert("Enter text..");
		$("#profile").focus();
	}
	else
	{
		$("#flash_profile").show();
		$("#flash_profile").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_profile',
		data: { profile_type:profile,profile_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_profile").after(html);
			//$("#flash_profile").hide();
			$("#profile").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== CONACT UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_contact(id)
{
	//alert(id);exit;
	var contact = $("#contact_"+id).val();
	//alert(address);
	if(contact=='' && id=='')
	{
		alert("Enter text..");
		$("#contact").focus();
	}
	else
	{
		$("#flash_contact").show();
		$("#flash_contact").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_contact',
		data: { contact_type:contact,contact_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_contact").after(html);
			//$("#flash_contact").hide();
			$("#contact").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== DOCUMENT UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_document(id)
{
	//alert(id);exit;
	var document = $("#document_"+id).val();
	//alert(address);
	if(document=='' && id=='')
	{
		alert("Enter text..");
		$("#document").focus();
	}
	else
	{
		$("#flash_document").show();
		$("#flash_document").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_document',
		data: { document_type:document,document_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_document").after(html);
			//$("#flash_document").hide();
			$("#document").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== SOURCE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_source(id)
{
	//alert(id);exit;
	var source = $("#source_"+id).val();
	//alert(address);
	if(source=='' && id=='')
	{
		alert("Enter text..");
		$("#source").focus();
	}
	else
	{
		$("#flash_source").show();
		$("#flash_source").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_source',
		data: { source_type:source,source_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_source").after(html);
			//$("#flash_source").hide();
			$("#source").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== DISPOSITION UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_disposition(id)
{
	//alert(id);exit;
	var disposition = $("#disposition_"+id).val();
	//alert(address);
	if(disposition=='' && id=='')
	{
		alert("Enter text..");
		$("#disposition").focus();
	}
	else
	{
		$("#flash_disposition").show();
		$("#flash_disposition").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_disposition',
		data: { disposition_type:disposition,disposition_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_disposition").after(html);
			//$("#flash_disposition").hide();
			$("#disposition").focus();
		}  
		});
	}
	return false;
}
function setdefaultdata(id)
	{
		 if ($('#'+id).parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
	}

</script>

<script type="text/javascript">
function get_submit_method(id)
{
	var method = $("#method_"+id).val();
	if(method=='' && id=='')
	{
		alert("Enter text..");
		$("#method").focus();
	}
	else
	{
		$("#flash_method").show();
		$("#flash_method").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_method',
		data: { method_type:method,method_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_method").after(html);
			$("#method").focus();
		}  
		});
	}
	return false;
}
</script>

<script type="text/javascript">
function get_submit_field(id)
{
	var field_name = $("#field_name_"+id).val();
	var field_type = $("#field_type_"+id).val();
	if(field_name=='' && field_type=='')
	{
		alert("Enter text..");
		$("#field_name_"+id).focus();
	}
	else
	{
		$("#flash_field").show();
		$("#flash_field").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_field',
		data: { field_type:field_type,field_name:field_name,field_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_field").after(html);
			$("#field").focus();
		}  
		});
	}
	return false;
}
function setdefaultdata(id)
{
	 if ($('#'+id).parsley().isValid()) {
		$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
	
	}
}
</script>

<!-- ================== END Ajax Script ================== -->
