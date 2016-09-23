<!--/*
        @Description: View for add Contact
        @Author: Mohit Trivedi
        @Input: -
        @Output: -
        @Date: 25-09-14
        */
-->
<?php
	
	$message = $this->session->userdata('message_session');
	//pr($message['msg']);
	$viewname = $this->router->uri->segments[2]; 
  	isset($editRecord) ? $loadcontroller='update_property_list' : $loadcontroller='insert_property_list';
  		$path_property_list = $viewname."/".$loadcontroller;
	isset($editdocument_listRecord) ? $loadcontroller='update_document_list' : $loadcontroller='insert_document_list';
  		$path_document_list = $viewname."/".$loadcontroller;
	isset($editLotTypeRecord) ? $loadcontroller='update_lot_type' : $loadcontroller='insert_lot_type_list';
  		$path_lot_type = $viewname."/".$loadcontroller;
	isset($editTransactionRecord) ? $loadcontroller='update_trasaction' : $loadcontroller='insert_trasaction';
  		$path_trasaction = $viewname."/".$loadcontroller;
	isset($editLockBoxRecord) ? $loadcontroller='update_lockbox' : $loadcontroller='insert_lockbox';
  		$path_lockbox = $viewname."/".$loadcontroller;
	isset($editSewerRecord) ? $loadcontroller='update_sewer' : $loadcontroller='insert_sewer';
  		$path_sewer = $viewname."/".$loadcontroller;
	isset($editBasementRecord) ? $loadcontroller='update_basement' : $loadcontroller='insert_basement';
  		$path_basement = $viewname."/".$loadcontroller;
	isset($editArchitectureRecord) ? $loadcontroller='update_architecture' : $loadcontroller='insert_architecture';
  		$path_architecture = $viewname."/".$loadcontroller;
	isset($editEnergySourceRecord) ? $loadcontroller='update_energy_source' : $loadcontroller='insert_energy_source';
  		$path_energySource = $viewname."/".$loadcontroller;
	isset($editExteriorFinishRecord) ? $loadcontroller='update_exterior_finish' : $loadcontroller='insert_exterior_finish';
  		$path_exterior_finish = $viewname."/".$loadcontroller;
	isset($editFireplaceRecord) ? $loadcontroller='update_fireplace' : $loadcontroller='insert_fireplace';
  		$path_fireplace = $viewname."/".$loadcontroller;
	isset($editFloorCoverRecord) ? $loadcontroller='update_floor_covering' : $loadcontroller='insert_floor_covering';
  		$path_floorcover = $viewname."/".$loadcontroller;
	isset($editFoundationRecord) ? $loadcontroller='update_foundation' : $loadcontroller='insert_foundation';
  		$path_foundation = $viewname."/".$loadcontroller;
	isset($editGreenCertificationRecord) ? $loadcontroller='update_green_certification' : $loadcontroller='insert_green_certification';
  		$path_green_certification = $viewname."/".$loadcontroller;
	isset($editHeatingCoolingRecord) ? $loadcontroller='update_heating_cooling' : $loadcontroller='insert_heating_cooling';
  		$path_heating_cooling = $viewname."/".$loadcontroller;
	isset($editInteriorFeatureRecord) ? $loadcontroller='update_interior_feature' : $loadcontroller='insert_interior_feature';
  		$path_interior_feature = $viewname."/".$loadcontroller;
	isset($editParkingRecord) ? $loadcontroller='update_parking_type' : $loadcontroller='insert_parking_type';
  		$path_parking = $viewname."/".$loadcontroller;
	isset($editPowerCompanyRecord) ? $loadcontroller='update_power_company' : $loadcontroller='insert_power_company';
  		$path_power_company = $viewname."/".$loadcontroller;
	isset($editRoofMasterRecord) ? $loadcontroller='update_roof_master' : $loadcontroller='insert_roof_master';
  		$path_roof_master = $viewname."/".$loadcontroller;
	isset($editSewerCompanyRecord) ? $loadcontroller='update_sewer_company' : $loadcontroller='insert_sewer_company';
  		$path_sewer_company = $viewname."/".$loadcontroller;
	isset($editStyleMasterRecord) ? $loadcontroller='update_style_master' : $loadcontroller='insert_style_master';
  		$path_style_master = $viewname."/".$loadcontroller;
	isset($editWaterCompanyRecord) ? $loadcontroller='update_water_company' : $loadcontroller='insert_water_company';
  		$path_water_company = $viewname."/".$loadcontroller;
  	
	//pr($editdocument_listRecord);
	//$path = $viewname."/".$loadcontroller;
?>
<script>
<?php if(!empty($message['msg'])) {
	$this->session->set_userdata('message_session'); ?>
jQuery(document).ready(function(){
	try{
		parent.insert_data();
	 }
	 catch(err) {
        // Handle error(s) here
    }
	parent.parent.$('.close_contact_select_popup').trigger('click');
});
<?php } ?>
</script>
<style>
<?php if(!empty($property_listing_iframe) && $property_listing_iframe == 'iframe'){ ?>
#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back{ display:none !important;}
#content{ margin-left:0;}
<?php } ?>
</style>
<div id="content" class="contact-masters">
<div id="content-header">
  <h1>Contact Masters</h1>
</div>
<div id="content-container">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-header">
        <h3> <i class="fa fa-tasks"></i>Property List Masters</h3>
        <span class="float-right margin-top--15"><a class="btn btn-secondary" title="Back" href="<?=base_url('admin/livewire_configuration');?>" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
        <div class="">
          <div class="chart_bg1 tbl_border"> 
         <?php 
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'property_type') || $property_listing_iframe == 'all')) { 
		 ?>
            <!-- Property TYPE-->
            <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="property_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_property_list?>" class="form parsley-form" onsubmit="return setdefaultdata('property_type_<?php echo $viewname;?>');" >
              <div class="col-md-6">
                <div class="mrg-bottom-40">
                  <div class="portlet-header">
                    <h3> 
                      <!--<i class="fa fa-tasks"></i>--> 
                      <?php echo $this->lang->line('common_label_property_type')?> </h3>
                  </div>
                  <div class="portlet-content">
                    <table width="100%" class="iconment_title_in" >
                      <tr >
                        <th><?php echo $this->lang->line('common_label_title')?></th>
                        <th><?php echo $this->lang->line('common_label_action')?></th>
                      </tr>
                      <?php
					if(!empty($property_type) && count($property_type)>0){
					foreach($property_type as $row)
					{   
					?>
                      <tr>
                        <td colspan="2"><div class="space"></div>
                          <div id="flash"></div>
                          <div id="show"></div></td>
                      </tr>
                      <tr>
                        <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="property_list_update[]" id="property_list_<?=$row['id']?>" value="<?php echo htmlentities($row['name']);?>" <?php if($row['user_type']=='1'){ ?> readonly <?php }?>/><input type="hidden" class="form-control parsley-validated" name="property_idd[]" id="" value="<?php echo  $row['id'] ?>"/> </td>
                        <td><?php if($row['user_type']!='1'){ ?>
                          <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_property_list_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                          <?php }?></td>
                      </tr>
                      <?php }}?>
                      <input type="hidden" id="property_list_id" name="property_list_id" class="property_list_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                      <tr>
                        <td colspan="2"><div id="p_scents" class="form-group">
                            <?php if(empty($property_type) || count($property_type) == 0){?>
                            <p>
                              <label for="p_scnts">
                                <input type="text" class="form-control parsley-validated" data-required="required" name="property_list_type[0]" id="property_list_type[0]" value="<?=isset($editRecord) ? htmlentities($editRecord[0]['name']) : ''?>" />
                              </label>
                            </p>
                            <?php } ?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><a href="#" id="addScnt" title="Add Email Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Property Type</a></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'slt_doc_type') || $property_listing_iframe == 'all')) { 
		 ?>
            <!-- document_list TYPE-->
            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="document_list_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_document_list?>" onsubmit="return setdefaultdata('document_list_<?php echo $viewname;?>');" >
              <div class="col-md-6">
                <div class="mrg-bottom-40">
                  <div class="portlet-header">
                    <h3><!-- <i class="fa fa-tasks"></i>--> <?php echo $this->lang->line('label_document_type')?> </h3>
                  </div>
                  <div class="portlet-content">
                    <table width="100%" class="iconment_title_in" >
                      <tr>
                        <th><?php echo $this->lang->line('common_label_title')?></th>
                        <th><?php echo $this->lang->line('common_label_action')?></th>
                      </tr>
                      <?php
					if(!empty($document_list_type) && count($document_list_type)>0){
					foreach($document_list_type as $row)
					{   
				?>
                      <tr>
                        <td colspan="2"><div class="space_document_list"></div>
                          <div id="flash_document_list"></div>
                          <div id="show_document_list"></div></td>
                      </tr>
                      <tr>
                        <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="document_list_update[]" id="document_list_<?=$row['id']?>" value="<?php echo htmlentities($row['name']); ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?>/><input type="hidden" class="form-control parsley-validated" name="document_idd[]" id="" value="<?php echo  $row['id'] ?>"/></td>
                        <td><?php if($row['user_type']!='1'){ ?>
                          <a href="javascript:void(0);" onclick="get_submit_document_list('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_document_list_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                          <?php }?></td>
                      </tr>
                      <?php }}?>
                    </table>
                    <input type="hidden" id="document_list_id" name="document_list_id" class="document_list_id" value="<?=isset($editdocument_listRecord) ? $editdocument_listRecord[0]['id']:''?>" />
                    <table width="100%">
                      <tr>
                        <td colspan="2"><div id="p_scents_document_list" class="form-group">
                            <?php if(empty($document_list_type) || count($document_list_type) == 0){?>
                            <p>
                              <label for="p_scnts_document_list">
                                <input type="text" class="form-control parsley-validated" data-required="required" name="document_list_type[0]" id="document_list_type[0]" value="<?=isset($editdocument_listRecord) ? $editdocument_listRecord[0]['name'] : ''?>" />
                              </label>
                              <?php /*?><a href="#" id="addScnt_document_list" title="Add More" style="color:#999900;font-size:10px;"><img src="<?=base_url('images/add_icon.jpg') ?>"/></a><?php */?>
                            </p>
                            <?php } ?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><a href="#" id="addScnt_document_list" title="Add document_list Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Document Type</a></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'lot_type') || $property_listing_iframe == 'all')) { 
		 ?>
            <!-- LOT TYPE-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="lot_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_lot_type?>" onsubmit="return setdefaultdata('lot_type_<?php echo $viewname;?>');">
              <div class="col-md-6">
                <div class="mrg-bottom-40">
                  <div class="portlet-header">
                    <h3> 
                      <!--<i class="fa fa-tasks"></i>--> 
                      <?php echo $this->lang->line('label_lot_type')?> </h3>
                  </div>
                  <div class="portlet-content">
                    <table width="100%" class="iconment_title_in" >
                      <tr >
                        <th><?php echo $this->lang->line('common_label_title')?></th>
                        <th><?php echo $this->lang->line('common_label_action')?></th>
                      </tr>
                      <?php
					if(!empty($lot_type) && count($lot_type)>0){
					foreach($lot_type as $row)
					{   
					?>
                      <tr>
                        <td colspan="2"><div class="space_lot_type"></div>
                          <div id="flash_lot_type"></div>
                          <div id="show_lot_type"></div></td>
                      </tr>
                      <tr>
                        <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="lot_type_update[]" id="lot_type_<?=$row['id']?>" value="<?php echo htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> /><input type="hidden" class="form-control parsley-validated" name="lot_idd[]" id="" value="<?php echo  $row['id'] ?>"/></td>
                        <td><?php if($row['user_type']!='1'){ ?>
                          <a href="javascript:void(0);" onclick="get_submit_lot_type('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_lot_type_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                          <?php }?></td>
                      </tr>
                      <?php }}?>
                    </table>
                    <table width="100%">
                      <tr>
                        <input type="hidden" id="lot_type_id" name="lot_type_id" class="lot_type_id" value="<?=isset($editLotTypeRecord) ? $editLotTypeRecord[0]['id']:''?>" />
                        <td colspan="2"><div id="p_scents_lot_type" class="form-group">
                            <?php if(empty($lot_type) || count($lot_type) == 0){?>
                            <p>
                              <label for="p_scnts_lot_type">
                                <input type="text" class="form-control parsley-validated" data-required="required" name="lot_type[0]" id="lot_type[0]" value="<?=isset($editLotTypeRecord) ? $editLotTypeRecord[0]['name'] : ''?>" />
                              </label>
                            </p>
                            <?php } ?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><a href="#" id="addScnt_lot_type" title="Add Lot Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Lot Type</a></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'transaction_type') || $property_listing_iframe == 'all')) { 
		 ?>  
            <!-- Transaction TYPE-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="transaction_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_trasaction?>" onsubmit="return setdefaultdata('transaction_type_<?php echo $viewname;?>');">
              <div class="col-md-6">
                <div class="mrg-bottom-40">
                  <div class="portlet-header">
                    <h3> <?php echo $this->lang->line('label_transaction_type')?> </h3>
                  </div>
                  <div class="portlet-content">
                    <table width="100%" class="iconment_title_in" >
                      <tr >
                        <th><?php echo $this->lang->line('common_label_title')?></th>
                        <th><?php echo $this->lang->line('common_label_action')?></th>
                      </tr>
                      <?php
					if(!empty($transaction_type) && count($transaction_type)>0){
					foreach($transaction_type as $row)
					{   
					?>
                      <tr>
                        <td colspan="2"><div class="space_transaction"></div>
                          <div id="flash_transaction"></div>
                          <div id="show_transaction"></div></td>
                      </tr>
                      <tr>
                        <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="transaction_update[]" id="transaction_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> /><input type="hidden" class="form-control parsley-validated" name="transaction_idd[]" id="" value="<?php echo  $row['id'] ?>"/></td>
                        <td><?php if($row['user_type']!='1'){ ?>
                          <a href="javascript:void(0);" onclick="get_submit_transaction('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_transaction_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                          <?php }?></td>
                      </tr>
                      <?php }}?>
                    </table>
                    <table width="100%">
                      <tr>
                        <input type="hidden" name="transaction_id" class="transaction_id" value="<?=isset($editTransactionRecord) ? $editTransactionRecord[0]['id']:''?>" />
                        <td colspan="2"><div id="p_scents_transactiontype" class="form-group">
                            <?php if(empty($transaction_type) || count($transaction_type) == 0){?>
                            <p>
                              <label for="p_scnts_transaction">
                                <input type="text" class="form-control parsley-validated" data-required="required" name="transaction_type[0]" id="transaction_type[0]" value="<?=isset($editTransactionRecord) ? $editTransactionRecord[0]['name'] : ''?>" />
                              </label>
                            </p>
                            <?php }?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><a href="#" id="addScnt_transactiontype" title="Add Transaction Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Transaction Type</a></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" style="width:auto;" class="btn btn-primary" value="Save" name="type"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'lockbox_type_id') || $property_listing_iframe == 'all')) { 
		 ?> 
            <!-- lockbox TYPE-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="lockbox_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_lockbox?>" onsubmit="return setdefaultdata('lockbox_type_<?php echo $viewname;?>');">
              <div class="col-md-6">
                <div class="mrg-bottom-40">
                  <div class="portlet-header">
                    <h3> 
                      <!--<i class="fa fa-tasks"></i>--> 
                      <?php echo $this->lang->line('label_lockbox_type')?> </h3>
                  </div>
                  <div class="portlet-content">
                    <table width="100%" class="iconment_title_in" >
                      <tr >
                        <th><?php echo $this->lang->line('common_label_title')?></th>
                        <th><?php echo $this->lang->line('common_label_action')?></th>
                      </tr>
                      <?php
					if(!empty($lockbox_type) && count($lockbox_type)>0){
					foreach($lockbox_type as $row)
					{   
					?>
                      <tr>
                        <td colspan="2"><div class="space_lockbox"></div>
                          <div id="flash_lockbox"></div>
                          <div id="show_lockbox"></div></td>
                      </tr>
                      <tr>
                        <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="lockbox_update[]" id="lockbox_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?> /><input type="hidden" class="form-control parsley-validated" name="lock_idd[]" id="" value="<?php echo  $row['id'] ?>"/></td>
                        <td><?php if($row['user_type']!='1'){ ?>
                          <a href="javascript:void(0);" onclick="get_submit_lockbox('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_lockbox_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                          <?php } ?></td>
                      </tr>
                      <?php }}?>
                    </table>
                    <table width="100%">
                      <tr>
                        <input type="hidden" name="id" value="<?=isset($editLockBoxRecord) ? $editLockBoxRecord[0]['id']:''?>" />
                        <td colspan="2"><div id="p_scents_lockbox" class="form-group">
                            <?php if(empty($lockbox_type) || count($lockbox_type) == 0){?>
                            <p>
                              <label for="p_scnts_lockbox">
                                <input type="text" class="form-control parsley-validated" data-required="required" name="lockbox_type[0]" id="lockbox_type[0]" value="<?=isset($editLockBoxRecord) ? $editLockBoxRecord[0]['name'] : ''?>" />
                              </label>
                            </p>
                            <?php } ?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><a href="#" id="addScnt_lockbox" title="Add lockbox Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add lockbox Type</a></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'sewer_id') || $property_listing_iframe == 'all')) { 
		 ?>  
            <!-- SEWER TYPE-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="sewer_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_sewer?>" onsubmit="return setdefaultdata('sewer_type_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_sewer_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($sewer_type) && count($sewer_type)>0){
					foreach($sewer_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_sewer"></div>
					<div id="flash_sewer"></div>
					<div id="show_sewer"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="sewer_update[]" id="sewer_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        <input type="hidden" class="form-control parsley-validated" name="sewer_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_sewer('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_sewer_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  <?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="sewer_id" value="<?=isset($editSewerRecord) ? $editSewerRecord[0]['id']:''?>" />
				  <td colspan="2">
					<div id="p_scents_sewer" class="form-group">
					  <?php if(empty($sewer_type) || count($sewer_type)== 0){?>
					  <p>
						<label for="p_scnts_sewer">
						<input type="text" class="form-control parsley-validated" data-required="required" name="sewer_type[0]" id="sewer_type[0]" value="<?=isset($editSewerRecord) ? $editSewerRecord[0]['name'] : ''?>" />
						</label>
						
						</p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
					<td>
					<a href="#" id="addScnt_sewer" title="Add sewer Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Sewer Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'basement_id') || $property_listing_iframe == 'all')) { 
		 ?>
            <!-- BASEMENT TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="basement_type_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_basement?>" onsubmit="return setdefaultdata('basement_type_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_basement_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($basement_type) && count($basement_type)>0){
					foreach($basement_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_basement"></div>
					<div id="flash_basement"></div>
					<div id="show_basement"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="basement_update[]" id="basement_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="basement_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
				</td>
				<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_basement('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('basement_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_basement_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  		<?php } ?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editBasementRecord) ? $editBasementRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_basement" class="form-group">
				   <?php if(empty($basement_type) || count($basement_type) == 0){?>
					  <p>
						<label for="p_scnts_basement">
						<input type="text" class="form-control parsley-validated" data-required="required" name="basement_type[0]" id="basement_type[0]" value="<?=isset($editBasementRecord) ? $editBasementRecord[0]['name'] : ''?>" />
						</label>

					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
					<td>
					<a href="#" id="addScnt_basement" title="Add basement Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Basement Type</a>
						
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
         <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'architecture_id') || $property_listing_iframe == 'all')) { 
		 ?>
            <!-- ARCHITECTURE TYPE-->
            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="architecture_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_architecture?>" onsubmit="return setdefaultdata('architecture_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_architecture_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($architecture_type) && count($architecture_type)>0){
					foreach($architecture_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_architecture"></div>
					<div id="flash_architecture"></div>
					<div id="show_architecture"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="architecture_update[]" id="architecture_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?>/>
                        
                        <input type="hidden" class="form-control parsley-validated" name="architecture_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_architecture('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_architecture_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
				  		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editArchitectureRecord) ? $editArchitectureRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_architecture" class="form-group">
				   <?php if(empty($architecture_type) || count($architecture_type) == 0){?>
					  <p>
						<label for="p_scnts_architecture">
						<input type="text" class="form-control parsley-validated" data-required="required" name="architecture_type[0]" id="architecture_type[0]" value="<?=isset($editArchitectureRecord) ? $editArchitectureRecord[0]['name'] : ''?>" />
						</label>
						
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
					<td>
					<a href="#" id="addScnt_architecture" title="Add architecture Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Architecture Type</a>
					
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'energy_source_id') || $property_listing_iframe == 'all')) { 
		?>    
            <!-- ENERGY SOURCE TYPE-->
			<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="energy_source_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_energySource?>" onsubmit="return setdefaultdata('energy_source_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_energy_source_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($energy_source_type) && count($energy_source_type)>0){
					foreach($energy_source_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_energy_source"></div>
					<div id="flash_energy_source"></div>
					<div id="show_energy_source"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="energy_source_update[]" id="energy_source_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?>/>
                        <input type="hidden" class="form-control parsley-validated" name="energy_source_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_energy_source('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_energy_source_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
							<?php }?>
				  </td>
				</tr>
				<?php }} ?>
			  </table>
			  <table width="100%">
			  
				<tr>
				  <input type="hidden" name="energy_source_id" value="<?=isset($editEnergySourceRecord) ? $editEnergySourceRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_energy_source" class="form-group">
				  <?php if(empty($energy_source_type) || count($energy_source_type) == 0){?>
					  <p>
						<label for="p_scnts_energy_source">
						<input type="text" class="form-control parsley-validated" data-required="required" name="energy_source_type[0]" id="energy_source_type[0]" value="<?=isset($editEnergySourceRecord) ? $editEnergySourceRecord[0]['name'] : ''?>" />
						</label>
					 </p>
				<?php } ?>
					</div></td>
				</tr>
				
				<tr>
					<td>
					<a style="margin-bottom:10px;" href="#" id="addScnt_energy_source"  title="Add energy_source Type" class="text_color_red text_size add_new_ta" ><i class="fa fa-plus-square"></i> Add Energy Source Type</a></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
				
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
		<?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'exterior_finish_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- EXTERIOR FINISH TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="exterior_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_exterior_finish?>" onsubmit="return setdefaultdata('exterior_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_exterior_finish_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($exterior_finish_type) && count($exterior_finish_type)>0){
					foreach($exterior_finish_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_exterior_finish"></div>
					<div id="flash_exterior_finish"></div>
					<div id="show_exterior_finish"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="exterior_finish_update[]" id="exterior_finish_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?>/>
                         <input type="hidden" class="form-control parsley-validated" name="exterior_finish_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_exterior_finish('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_exterior_finish_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
							<?php }?>
				  </td>
				</tr>
				<?php }} ?>
			  </table>
			  <table width="100%">
			  
				<tr>
				  <input type="hidden" name="exterior_finish_id" value="<?=isset($editExteriorFinishRecord) ? $editExteriorFinishRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_exterior_finish" class="form-group">
				  <?php if(empty($exterior_finish_type) || count($exterior_finish_type) == 0){?>
					  <p>
						<label for="p_scnts_exterior_finish">
						<input type="text" class="form-control parsley-validated" data-required="required" name="exterior_finish_type[0]" id="exterior_finish_type[0]" value="<?=isset($editExteriorFinishRecord) ? $editExteriorFinishRecord[0]['name'] : ''?>" />
						</label>
					 </p>
				<?php } ?>
					</div></td>
				</tr>
				
				<tr>
					<td>
					<a style="margin-bottom:10px;" href="#" id="addScnt_exterior_finish"  title="Add exterior_finish Type" class="text_color_red text_size add_new_ta" ><i class="fa fa-plus-square"></i> Add Exterior Finish Type</a></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
				
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'fireplace_id') || $property_listing_iframe == 'all')) { 
		?>
            
            <!-- FIREPLACE TYPE-->
            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="fireplace_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_fireplace?>" onsubmit="return setdefaultdata('fireplace_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_fireplace_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($fireplace_type) && count($fireplace_type)>0){
					foreach($fireplace_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_fireplace"></div>
					<div id="flash_fireplace"></div>
					<div id="show_fireplace"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="fireplace_update[]" id="fireplace_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php } ?>/>
                        <input type="hidden" class="form-control parsley-validated" name="fireplace_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_fireplace('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_fireplace_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
							<?php }?>
				  </td>
				</tr>
				<?php }} ?>
			  </table>
			  <table width="100%">
			  
				<tr>
				  <input type="hidden" name="id" value="<?=isset($editFireplaceRecord) ? $editFireplaceRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_fireplace" class="form-group">
				  <?php if(empty($fireplace_type) || count($fireplace_type) == 0){?>
					  <p>
						<label for="p_scnts_fireplace">
						<input type="text" class="form-control parsley-validated" data-required="required" name="fireplace_type[0]" id="fireplace_type[0]" value="<?=isset($editFireplaceRecord) ? $editFireplaceRecord[0]['name'] : ''?>" />
						</label>
					 </p>
				<?php } ?>
					</div></td>
				</tr>
				
				<tr>
					<td>
					<a style="margin-bottom:10px;" href="#" id="addScnt_fireplace"  title="Add fireplace Type" class="text_color_red text_size add_new_ta" ><i class="fa fa-plus-square"></i> Add Fireplace Type</a></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
				
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'floor_covering_id') || $property_listing_iframe == 'all')) { 
		?>
            	<!-- FLOOR COVERING TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="floor_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_floorcover?>" onsubmit="return setdefaultdata('floor_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<?php echo $this->lang->line('label_floor_cover_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($floor_covering_type) && count($floor_covering_type)>0){
					foreach($floor_covering_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_floor_covering"></div>
					<div id="flash_floor_covering"></div>
					<div id="show_floor_covering"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="floor_covering_update[]" id="floor_covering_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                         <input type="hidden" class="form-control parsley-validated" name="floor_covering_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
                    <?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_floor_covering('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_floor_covering_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				  <?php }?>
                </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				 <input type="hidden" name="floor_covering_id" class="floor_covering_id" value="<?=isset($editFloorCoverRecord) ? $editFloorCoverRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_floor_coveringtype" class="form-group">
					  <?php if(empty($floor_covering_type) || count($floor_covering_type) == 0){?>
                      <p>
						<label for="p_scnts_floor_covering">
						<input type="text" class="form-control parsley-validated" data-required="required" name="floor_covering_type[0]" id="floor_covering_type[0]" value="<?=isset($editFloorCoverRecord) ? $editFloorCoverRecord[0]['name'] : ''?>" /></label>
					  </p>
					  <?php }?>
					</div>
                    </td>
				</tr>
				<tr>
                <td>
 					<a href="#" id="addScnt_floor_coveringtype" title="Add Floor Covering Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Floor Covering Type</a>

                </td>
                	
				  <td>&nbsp;</td>
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
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'foundation_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- FOUNDATION TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="foundation_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_foundation?>" onsubmit="return setdefaultdata('foundation_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_foundation_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($foundation_type) && count($foundation_type)>0){
					foreach($foundation_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_foundation"></div>
					<div id="flash_foundation"></div>
					<div id="show_foundation"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="foundation_update[]" id="foundation_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="foundation_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_foundation('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_foundation_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="foundation_id" name="foundation_id" class="foundation_id" value="<?=isset($editFoundationRecord) ? $editFoundationRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_foundation" class="form-group">
					 <?php if(empty($foundation_type) || count($foundation_type) == 0){?>
					  <p>
						<label for="p_scnts_foundation">
						<input type="text" class="form-control parsley-validated" data-required="required" name="foundation_type[0]" id="foundation_type[0]" value="<?=isset($editFoundationRecord) ? $editFoundationRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_foundation" title="Add Foundation Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Foundation Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'green_certification_id') || $property_listing_iframe == 'all')) { 
		?>
            
            <!-- GREEN CERTIFICATION TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="green_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_green_certification?>" onsubmit="return setdefaultdata('green_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_green_certification_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($green_certification_type) && count($green_certification_type)>0){
					foreach($green_certification_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_green_certification"></div>
					<div id="flash_green_certification"></div>
					<div id="show_green_certification"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="green_certification_update[]" id="green_certification_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                         <input type="hidden" class="form-control parsley-validated" name="green_certification_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_green_certification('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_green_certification_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="green_certification_id" name="green_certification_id" class="green_certification_id" value="<?=isset($editGreenCertificationRecord) ? $editGreenCertificationRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_green_certification" class="form-group">
					 <?php if(empty($green_certification_type) || count($green_certification_type) == 0){?>
					  <p>
						<label for="p_scnts_green_certification">
						<input type="text" class="form-control parsley-validated" data-required="required" name="green_certification_type[0]" id="green_certification_type[0]" value="<?=isset($editGreenCertificationRecord) ? $editGreenCertificationRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_green_certification" title="Add Green Certification Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Green Certification Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'heating_cooling_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- HEATING COOLING TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="heating_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_heating_cooling?>" onsubmit="return setdefaultdata('heating_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_heating_cooling_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($heating_cooling_type) && count($heating_cooling_type)>0){
					foreach($heating_cooling_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_heating_cooling"></div>
					<div id="flash_heating_cooling"></div>
					<div id="show_heating_cooling"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="heating_cooling_update[]" id="heating_cooling_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                         <input type="hidden" class="form-control parsley-validated" name="heating_cooling_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_heating_cooling('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_heating_cooling_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="heating_cooling_id" name="heating_cooling_id" class="heating_cooling_id" value="<?=isset($editHeatingCoolingRecord) ? $editHeatingCoolingRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_heating_cooling" class="form-group">
					 <?php if(empty($heating_cooling_type) || count($heating_cooling_type) == 0){?>
					  <p>
						<label for="p_scnts_heating_cooling">
						<input type="text" class="form-control parsley-validated" data-required="required" name="heating_cooling_type[0]" id="heating_cooling_type[0]" value="<?=isset($editHeatingCoolingRecord) ? $editHeatingCoolingRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_heating_cooling" title="Add Heating Cooling Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Heating Cooling Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'interior_feature_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- INTERIOR FEATURE TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="interior_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_interior_feature?>" onsubmit="return setdefaultdata('interior_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_interior_feature_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($interior_feature_type) && count($interior_feature_type)>0){
					foreach($interior_feature_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_interior_feature"></div>
					<div id="flash_interior_feature"></div>
					<div id="show_interior_feature"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="interior_feature_update[]" id="interior_feature_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        <input type="hidden" class="form-control parsley-validated" name="interior_feature_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_interior_feature('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_interior_feature_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="interior_feature_id" name="interior_feature_id" class="interior_feature_id" value="<?=isset($editInteriorFeatureRecord) ? $editInteriorFeatureRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_interior_feature" class="form-group">
					 <?php if(empty($interior_feature_type) || count($interior_feature_type) == 0){?>
					  <p>
						<label for="p_scnts_interior_feature">
						<input type="text" class="form-control parsley-validated" data-required="required" name="interior_feature_type[0]" id="interior_feature_type[0]" value="<?=isset($editInteriorFeatureRecord) ? $editInteriorFeatureRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_interior_feature" title="Add interior_feature Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Interior Feature Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'parking_type_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- PARKING TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="parking_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_parking?>" onsubmit="return setdefaultdata('parking_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_parking_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($parking_type) && count($parking_type)>0){
					foreach($parking_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_parking"></div>
					<div id="flash_parking"></div>
					<div id="show_parking"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="parking_update[]" id="parking_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="parking_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_parking('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_parking_type_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="parking_id" name="parking_id" class="parking_id" value="<?=isset($editParkingRecord) ? $editParkingRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_parking" class="form-group">
					 <?php if(empty($parking_type) || count($parking_type) == 0){?>
					  <p>
						<label for="p_scnts_parking">
						<input type="text" class="form-control parsley-validated" data-required="required" name="parking_type[0]" id="parking_type[0]" value="<?=isset($editParkingRecord) ? $editParkingRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_parking" title="Add Parking Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Parking Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'power_company_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- POWER COMPANY TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="power_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_power_company?>" onsubmit="return setdefaultdata('power_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_power_company_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($power_company_type) && count($power_company_type)>0){
					foreach($power_company_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_power_company"></div>
					<div id="flash_power_company"></div>
					<div id="show_power_company"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="power_company_update[]" id="power_company_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="power_company_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_power_company('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_power_company_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="power_company_id" name="power_company_id" class="power_company_id" value="<?=isset($editPowerCompanyRecord) ? $editPowerCompanyRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_power_company" class="form-group">
					 <?php if(empty($power_company_type) || count($power_company_type) == 0){?>
					  <p>
						<label for="p_scnts_power_company">
						<input type="text" class="form-control parsley-validated" data-required="required" name="power_company_type[0]" id="power_company_type[0]" value="<?=isset($editPowerCompanyRecord) ? $editPowerCompanyRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_power_company" title="Add Power Company Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Power Company Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'roof_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- ROOF MASTER TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="roof_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_roof_master?>" onsubmit="return setdefaultdata('roof_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_roof_master_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($roof_master_type) && count($roof_master_type)>0){
					foreach($roof_master_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_roof_master"></div>
					<div id="flash_roof_master"></div>
					<div id="show_roof_master"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="roof_master_update[]" id="roof_master_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="roof_master_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_roof_master('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_roof_master_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="roof_master_id" name="roof_master_id" class="roof_master_id" value="<?=isset($editRoofMasterRecord) ? $editRoofMasterRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_roof_master" class="form-group">
					 <?php if(empty($roof_master_type) || count($roof_master_type) == 0){?>
					  <p>
						<label for="p_scnts_roof_master">
						<input type="text" class="form-control parsley-validated" data-required="required" name="roof_master_type[0]" id="roof_master_type[0]" value="<?=isset($editRoofMasterRecord) ? $editRoofMasterRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_roof_master" title="Add Roof Master Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Roof Master Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'sewer_company_id') || $property_listing_iframe == 'all')) { 
		?>
            <!-- SEWER COMPANY TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="sewer_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_sewer_company?>" onsubmit="return setdefaultdata('sewer_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_sewer_company_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($sewer_company_type) && count($sewer_company_type)>0){
					foreach($sewer_company_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_sewer_company"></div>
					<div id="flash_sewer_company"></div>
					<div id="show_sewer_company"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="sewer_company_update[]" id="sewer_company_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="sewer_company_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_sewer_company('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_sewer_company_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="sewer_company_id" name="sewer_company_id" class="sewer_company_id" value="<?=isset($editSewerCompanyRecord) ? $editSewerCompanyRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_sewer_company" class="form-group">
					 <?php if(empty($sewer_company_type) || count($sewer_company_type) == 0){?>
					  <p>
						<label for="p_scnts_sewer_company">
						<input type="text" class="form-control parsley-validated" data-required="required" name="sewer_company_type[0]" id="sewer_company_type[0]" value="<?=isset($editSewerCompanyRecord) ? $editSewerCompanyRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_sewer_company" title="Add sewer_company Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Sewer Company Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'style_id') || $property_listing_iframe == 'all')) { 
		?>
            	<!-- STYLE MASTER TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="style_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_style_master?>" onsubmit="return setdefaultdata('style_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_style_master_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($style_master_type) && count($style_master_type)>0){
					foreach($style_master_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_style_master"></div>
					<div id="flash_style_master"></div>
					<div id="show_style_master"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="style_master_update[]" id="style_master_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                         <input type="hidden" class="form-control parsley-validated" name="style_master_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_style_master('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_style_master_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="style_master_id" name="style_master_id" class="style_master_id" value="<?=isset($editStyleMasterRecord) ? $editStyleMasterRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_style_master" class="form-group">
					 <?php if(empty($style_master_type) || count($style_master_type) == 0){?>
					  <p>
						<label for="p_scnts_style_master">
						<input type="text" class="form-control parsley-validated" data-required="required" name="style_master_type[0]" id="style_master_type[0]" value="<?=isset($editStyleMasterRecord) ? $editStyleMasterRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_style_master" title="Add Style Master Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Style Master Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		  	if(!empty($property_listing_iframe) && (($property_listing_iframe == 'iframe' && !empty($property_master) && $property_master == 'water_company_id') || $property_listing_iframe == 'all')) { 
		?>
            	<!-- WATER COMPANY TYPE-->
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="water_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_water_company?>" onsubmit="return setdefaultdata('water_<?php echo $viewname;?>');">
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						<!--<i class="fa fa-tasks"></i>-->
						 <?php echo $this->lang->line('label_water_company_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($water_company_type) && count($water_company_type)>0){
					foreach($water_company_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space_water_company"></div>
					<div id="flash_water_company"></div>
					<div id="show_water_company"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					
						<input type="text" class="form-control parsley-validated" name="water_company_update[]" id="water_company_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"<?php if($row['user_type']=='1'){ ?> readonly <?php } ?> />
                        
                        <input type="hidden" class="form-control parsley-validated" name="water_company_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                        
					</td>
					<td>
						<?php if($row['user_type']!='1'){ ?>
						<a href="javascript:void(0);" onclick="get_submit_water_company('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_water_company_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
				 		<?php }?>
				  </td>
				</tr>
				<?php }}?>
			  </table>
			  <table width="100%">
				<tr>
				   <input type="hidden" id="water_company_id" name="water_company_id" class="water_company_id" value="<?=isset($editWaterCompanyRecord) ? $editWaterCompanyRecord[0]['id']:''?>" />
				  <td colspan="2"><div id="p_scents_water_company" class="form-group">
					 <?php if(empty($water_company_type) || count($water_company_type) == 0){?>
					  <p>
						<label for="p_scnts_water_company">
						<input type="text" class="form-control parsley-validated" data-required="required" name="water_company_type[0]" id="water_company_type[0]" value="<?=isset($editWaterCompanyRecord) ? $editWaterCompanyRecord[0]['name'] : ''?>" />
						</label>
				
					  </p>
					  <?php } ?>
					</div></td>
				</tr>
				<tr>
				<td>
					<a href="#" id="addScnt_water_company" title="Add Water Company Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Water Company Type</a>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type">
				  </td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
        <?php 
		  }
		?>
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
    function getproperty_list()
    {
        var property_listid = $("#property_list").val();
        $.ajax({
            type: "post",
            data: {'property_list':property_listid,
            },
            url: '<?php echo $this->config->item('admin_base_url')?>/user/getproperty_list', 
            success: function(msg1) 
            {
                if(msg1 != '')
                {
                    $("#property_listexist").val(msg1);
                    $("#property_list").focus();
                }
                else
                    $("#property_listexist").val(msg1);
            }
        });	
    return false;
    }
</script> 

<!-- ================== START Multipal Input Box Script ================== --> 
<!-- ==== Property INPUT ADD ===== --> 
<script type="text/javascript">

$(function() {

        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt', function(){
        $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="property_list_type[' + i +']" id="property_list_type[' + i +']"/></label>&nbsp;<a href="#" id="remScnt" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });
        
		$('body').on('click', '#remScnt', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== document_list INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_document_list');
        var i = $('#p_scents_document_list p').size();
		$('body').on('click', '#addScnt_document_list', function(){
        $('<p><label for="p_scnts_document_list"><input type="text" class="form-control parsley-validated" data-required="required" name="document_list_type[' + i +']" id="document_list_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_document_list" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_document_list', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== lot_type INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_lot_type');
        var i = $('#p_scents_lot_type p').size();
		$('body').on('click', '#addScnt_lot_type', function(){
        $('<p><label for="p_scnts_lot_type"><input type="text" class="form-control parsley-validated" data-required="required" name="lot_type[' + i +']" id="lot_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_lot_type" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_lot_type', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== transaction INPUT ADD ===== --> 

<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_transactiontype');
        var i = $('#p_scents_transactiontype p').size();
		$('body').on('click', '#addScnt_transactiontype', function(){
        $('<p><label for="p_scnts_transactiontype"><input type="text" class="form-control parsley-validated" data-required="required" name="transaction_type[' + i +']" id="transaction_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_transactiontype" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_transactiontype', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 
<!-- ==== lockbox INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_transaction');
        var i = $('#p_scents_transaction p').size();
		$('body').on('click', '#addScnt_transaction', function(){
        $('<p><label for="p_scnts_transaction"><input type="text" class="form-control parsley-validated" data-required="required" name="transaction_type[' + i +']" id="transaction_type[' + i +']"/> </label><a href="#" id="remScnt_transaction" class="btn btn-xs btn-primary"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_transaction', function(){ 
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});

$(function() {
        var scntDiv = $('#p_scents_lockbox');
        var i = $('#p_scents_lockbox p').size();
		$('body').on('click', '#addScnt_lockbox', function(){
        $('<p><label for="p_scnts_lockbox"><input type="text" class="form-control parsley-validated" data-required="required" name="lockbox_type[' + i +']" id="lockbox_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_lockbox" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_lockbox', function(){ 
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});

</script> 

<!-- ==== sewer INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_sewer');
        var i = $('#p_scents_sewer p').size();
		$('body').on('click', '#addScnt_sewer', function(){
        $('<p><label for="p_scnts_sewer"><input type="text" class="form-control parsley-validated" data-required="required" name="sewer_type[' + i +']" id="sewer_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_sewer" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_sewer', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== basement INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_basement');
        var i = $('#p_scents_basement p').size();
		$('body').on('click', '#addScnt_basement', function(){
        $('<p><label for="p_scnts_basement"><input type="text" class="form-control parsley-validated" data-required="required" name="basement_type[' + i +']" id="basement_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_basement" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_basement', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== Document INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_document');
        var i = $('#p_scents_document p').size();
		$('body').on('click', '#addScnt_document', function(){
        $('<p><label for="p_scnts_document"><input type="text" class="form-control parsley-validated" data-required="required" name="document_type[' + i +']" id="document_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_document" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_document', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== ARCHITECTURE INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_architecture');
        var i = $('#p_scents_architecture p').size();
		$('body').on('click', '#addScnt_architecture', function(){
        $('<p><label for="p_scnts_architecture"><input type="text" class="form-control parsley-validated" data-required="required" name="architecture_type[' + i +']" id="architecture_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_architecture" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_architecture', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== ENERGY SOURCE INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_energy_source');
        var i = $('#p_scents_energy_source p').size();
		$('body').on('click', '#addScnt_energy_source', function(){
        $('<p><label for="p_scnts_energy_source"><input type="text" class="form-control parsley-validated" data-required="required" name="energy_source_type[' + i +']" id="energy_source_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_energy_source" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_energy_source', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ==== EXTERIOR FINISH INPUT ADD ===== --> 
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_exterior_finish');
        var i = $('#p_scents_exterior_finish p').size();
		$('body').on('click', '#addScnt_exterior_finish', function(){
        $('<p><label for="p_scnts_exterior_finish"><input type="text" class="form-control parsley-validated" data-required="required" name="exterior_finish_type[' + i +']" id="exterior_finish_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_exterior_finish" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_exterior_finish', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script> 

<!-- ====FIREPLACE INPUT ADD ===== --> 
 <script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_fireplace');
        var i = $('#p_scents_fireplace p').size();
		$('body').on('click', '#addScnt_fireplace', function(){
        $('<p><label for="p_scnts_fireplace"><input type="text" class="form-control parsley-validated" data-required="required" name="fireplace_type[' + i +']" id="fireplace_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_fireplace" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_fireplace', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== FLOOR COVERING INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_floor_coveringtype');
        var i = $('#p_scents_floor_coveringtype p').size();
		$('body').on('click', '#addScnt_floor_coveringtype', function(){
        $('<p><label for="p_scnts_floor_coveringtype"><input type="text" class="form-control parsley-validated" data-required="required" name="floor_covering_type[' + i +']" id="floor_covering_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_floor_coveringtype" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_floor_coveringtype', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- FOUNDATION TYPE  INPUT ADD-->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_foundation');
        var i = $('#p_scents_foundation p').size();
		$('body').on('click', '#addScnt_foundation', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="foundation_type[' + i +']" id="foundation_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_foundation" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_foundation', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== GREEN CERTIFICATION INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_green_certification');
        var i = $('#p_scents_green_certification p').size();
		$('body').on('click', '#addScnt_green_certification', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="green_certification_type[' + i +']" id="green_certification_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_green_certification" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_green_certification', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== HEATING COOLING INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_heating_cooling');
        var i = $('#p_scents_heating_cooling p').size();
		$('body').on('click', '#addScnt_heating_cooling', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="heating_cooling_type[' + i +']" id="heating_cooling_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_heating_cooling" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_heating_cooling', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== INTERIOR FEATURE INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_interior_feature');
        var i = $('#p_scents_interior_feature p').size();
		$('body').on('click', '#addScnt_interior_feature', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="interior_feature_type[' + i +']" id="interior_feature_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_interior_feature" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_interior_feature', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== PARKING INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_parking');
        var i = $('#p_scents_parking p').size();
		$('body').on('click', '#addScnt_parking', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="parking_type[' + i +']" id="parking_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_parking" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_parking', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== POWER COMPANY INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_power_company');
        var i = $('#p_scents_power_company p').size();
		$('body').on('click', '#addScnt_power_company', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="power_company_type[' + i +']" id="power_company_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_power_company" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_power_company', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== roof_master INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_roof_master');
        var i = $('#p_scents_roof_master p').size();
		$('body').on('click', '#addScnt_roof_master', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="roof_master_type[' + i +']" id="roof_master_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_roof_master" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_roof_master', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== SEWER COMPANY INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_sewer_company');
        var i = $('#p_scents_sewer_company p').size();
		$('body').on('click', '#addScnt_sewer_company', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="sewer_company_type[' + i +']" id="sewer_company_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_sewer_company" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_sewer_company', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== STYLE MASTER INPUT ADD ===== -->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_style_master');
        var i = $('#p_scents_style_master p').size();
		$('body').on('click', '#addScnt_style_master', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="style_master_type[' + i +']" id="style_master_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_style_master" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_style_master', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- WATER COMPANY TYPE-->
<script type="text/javascript">
$(function() {
        var scntDiv = $('#p_scents_water_company');
        var i = $('#p_scents_water_company p').size();
		$('body').on('click', '#addScnt_water_company', function(){
        $('<p><label for="p_scnts_phone"><input type="text" class="form-control parsley-validated" data-required="required" name="water_company_type[' + i +']" id="water_company_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_water_company" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_water_company', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
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
	var property_list = $("#property_list_"+id).val();
	if(property_list=='' && id=='')
	{
		alert("Enter text..");
		$("#property_list").focus();
	}
	else
	{
		$("#flash").show();
		$("#flash").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_property_list',
		data: { property_list_type:property_list,property_list_id:id },
		cache: true,
		success: function(html)
		{
			$("#show").after(html);
			$("#property_list").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== document_list UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_document_list(id)
{
	var document_list = $("#document_list_"+id).val();
	if(document_list=='' && id=='')
	{
		alert("Enter text..");
		$("#document_list").focus();
	}
	else
	{
		$("#flash_document_list").show();
		$("#flash_document_list").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_document_list',
		data: { document_list_type:document_list,document_list_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_document_list").after(html);
			$("#document_list").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== lot_type UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_lot_type(id)
{
	var lot_type = $("#lot_type_"+id).val();
	if(lot_type=='' && id=='')
	{
		alert("Enter text..");
		$("#lot_type").focus();
	}
	else
	{
		$("#flash_lot_type").show();
		$("#flash_lot_type").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_lot_type',
		data: { lot_type:lot_type,lot_type_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_lot_type").after(html);
			$("#lot_type").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== transaction UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_transaction(id)
{
	var transaction = $("#transaction_"+id).val();
	if(transaction=='' && id=='')
	{
		alert("Enter text..");
		$("#transaction").focus();
	}
	else
	{
		$("#flash_transaction").show();
		$("#flash_transaction").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_transaction',
		data: { transaction_type:transaction,transaction_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_transaction").after(html);
			$("#transaction").focus();
		}  
		});
	}
	return false;
}
</script>
<!-- ==== lockbox UPDATE AJAX ===== --> 
<script type="text/javascript">

function get_submit_lockbox(id)
{
	var lockbox = $("#lockbox_"+id).val();
	if(lockbox=='' && id=='')
	{
		alert("Enter text..");
		$("#lockbox").focus();
	}
	else
	{
		$("#flash_lockbox").show();
		$("#flash_lockbox").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_lockbox',
		data: { lockbox_type:lockbox,lockbox_id:id },
		cache: true,
		success: function(html)
		{
			$("#flash_lockbox").after(html);
			$("#lockbox").focus();
		}  
		});
	}
	return false;
}

</script> 

<!-- ==== sewer UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_sewer(id)
{
	var sewer = $("#sewer_"+id).val();
	if(sewer=='' && id=='')
	{
		alert("Enter text..");
		$("#sewer").focus();
	}
	else
	{
		$("#flash_sewer").show();
		$("#flash_sewer").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_sewer',
		data: { sewer_type:sewer,sewer_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_sewer").after(html);
			$("#sewer").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== BASEMENT UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_basement(id)
{
	var basement = $("#basement_"+id).val();
	if(basement=='' && id=='')
	{
		alert("Enter text..");
		$("#basement").focus();
	}
	else
	{
		$("#flash_basement").show();
		$("#flash_basement").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_basement',
		data: { basement_type:basement,basement_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_basement").after(html);
			$("#basement").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== ARCHITECTURE UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_architecture(id)
{
	var architecture = $("#architecture_"+id).val();
	if(architecture=='' && id=='')
	{
		alert("Enter text..");
		$("#architecture").focus();
	}
	else
	{
		$("#flash_architecture").show();
		$("#flash_architecture").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_architecture',
		data: { architecture_type:architecture,architecture_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_architecture").after(html);
			$("#architecture").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== energy_source UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_energy_source(id)
{
	var energy_source = $("#energy_source_"+id).val();
	if(energy_source=='' && id=='')
	{
		alert("Enter text..");
		$("#energy_source").focus();
	}
	else
	{
		$("#flash_energy_source").show();
		$("#flash_energy_source").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_energy_source',
		data: { energy_source_type:energy_source,energy_source_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_energy_source").after(html);
			$("#energy_source").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== EXTERIOR FINISH UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_exterior_finish(id)
{
	var exterior_finish = $("#exterior_finish_"+id).val();
	if(exterior_finish=='' && id=='')
	{
		alert("Enter text..");
		$("#exterior_finish").focus();
	}
	else
	{
		$("#flash_exterior_finish").show();
		$("#flash_exterior_finish").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_exterior_finish',
		data: { exterior_finish_type:exterior_finish,exterior_finish_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_exterior_finish").after(html);
			$("#exterior_finish").focus();
		}  
		});
	}
	return false;
}
</script> 

<!-- ==== FIREPLACE UPDATE AJAX ===== --> 
 <script type="text/javascript">
function get_submit_fireplace(id)
{
	var fireplace = $("#fireplace_"+id).val();
	if(fireplace=='' && id=='')
	{
		alert("Enter text..");
		$("#fireplace").focus();
	}
	else
	{
		$("#flash_fireplace").show();
		$("#flash_fireplace").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_fireplace',
		data: { fireplace_type:fireplace,fireplace_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_fireplace").after(html);
			$("#fireplace").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== FLOOR COVERING UPDATE AJAX ===== --> 
<script type="text/javascript">
function get_submit_floor_covering(id)
{
	var floor_covering = $("#floor_covering_"+id).val();
	if(floor_covering=='' && id=='')
	{
		alert("Enter text..");
		$("#floor_covering").focus();
	}
	else
	{
		$("#flash_floor_covering").show();
		$("#flash_floor_covering").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_floor_covering',
		data: { floor_covering_type:floor_covering,floor_covering_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_floor_covering").after(html);
			$("#floor_covering").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== FOUNDATION UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_foundation(id)
{
	var foundation = $("#foundation_"+id).val();
	if(foundation=='' && id=='')
	{
		alert("Enter text..");
		$("#foundation").focus();
	}
	else
	{
		$("#flash_foundation").show();
		$("#flash_foundation").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_foundation',
		data: { foundation_type:foundation,foundation_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_foundation").after(html);
			$("#foundation").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== GREEN CERTIFICATION UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_green_certification(id)
{
	var green_certification = $("#green_certification_"+id).val();
	if(green_certification=='' && id=='')
	{
		alert("Enter text..");
		$("#green_certification").focus();
	}
	else
	{
		$("#flash_green_certification").show();
		$("#flash_green_certification").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_green_certification',
		data: { green_certification_type:green_certification,green_certification_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_green_certification").after(html);
			$("#green_certification").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== HEATING COOLING UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_heating_cooling(id)
{
	var heating_cooling = $("#heating_cooling_"+id).val();
	if(heating_cooling=='' && id=='')
	{
		alert("Enter text..");
		$("#heating_cooling").focus();
	}
	else
	{
		$("#flash_heating_cooling").show();
		$("#flash_heating_cooling").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_heating_cooling',
		data: { heating_cooling_type:heating_cooling,heating_cooling_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_heating_cooling").after(html);
			$("#heating_cooling").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== INTERIOR FEATURE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_interior_feature(id)
{
	var interior_feature = $("#interior_feature_"+id).val();
	if(interior_feature=='' && id=='')
	{
		alert("Enter text..");
		$("#interior_feature").focus();
	}
	else
	{
		$("#flash_interior_feature").show();
		$("#flash_interior_feature").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_interior_feature',
		data: { interior_feature_type:interior_feature,interior_feature_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_interior_feature").after(html);
			$("#interior_feature").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== PARKING UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_parking(id)
{
	var parking = $("#parking_"+id).val();
	if(parking=='' && id=='')
	{
		alert("Enter text..");
		$("#parking").focus();
	}
	else
	{
		$("#flash_parking").show();
		$("#flash_parking").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_parking_type',
		data: { parking_type:parking,parking_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_parking").after(html);
			$("#parking").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ====  POWER COMPANY UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_power_company(id)
{
	var power_company = $("#power_company_"+id).val();
	if(power_company=='' && id=='')
	{
		alert("Enter text..");
		$("#power_company").focus();
	}
	else
	{
		$("#flash_power_company").show();
		$("#flash_power_company").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_power_company',
		data: { power_company_type:power_company,power_company_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_power_company").after(html);
			$("#power_company").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== roof_master UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_roof_master(id)
{
	var roof_master = $("#roof_master_"+id).val();
	if(roof_master=='' && id=='')
	{
		alert("Enter text..");
		$("#roof_master").focus();
	}
	else
	{
		$("#flash_roof_master").show();
		$("#flash_roof_master").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_roof_master',
		data: { roof_master_type:roof_master,roof_master_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_roof_master").after(html);
			$("#roof_master").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== SEWER COMPANY UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_sewer_company(id)
{
	var sewer_company = $("#sewer_company_"+id).val();
	if(sewer_company=='' && id=='')
	{
		alert("Enter text..");
		$("#sewer_company").focus();
	}
	else
	{
		$("#flash_sewer_company").show();
		$("#flash_sewer_company").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_sewer_company',
		data: { sewer_company_type:sewer_company,sewer_company_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_sewer_company").after(html);
			$("#sewer_company").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- ==== STYLE MASTER UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_style_master(id)
{
	var style_master = $("#style_master_"+id).val();
	if(style_master=='' && id=='')
	{
		alert("Enter text..");
		$("#style_master").focus();
	}
	else
	{
		$("#flash_style_master").show();
		$("#flash_style_master").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_style_master',
		data: { style_master_type:style_master,style_master_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_style_master").after(html);
			$("#style_master").focus();
		}  
		});
	}
	return false;
}
</script>

<!-- WATER COMPANY TYPE-->
<script type="text/javascript">
function get_submit_water_company(id)
{
	var water_company = $("#water_company_"+id).val();
	if(water_company=='' && id=='')
	{
		alert("Enter text..");
		$("#water_company").focus();
	}
	else
	{
		$("#flash_water_company").show();
		$("#flash_water_company").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_water_company',
		data: { water_company_type:water_company,water_company_id:id },
		cache: true,
		success: function(html)
		{
			$("#show_water_company").after(html);
			$("#water_company").focus();
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
