<?php
/*
    @Description: Lead Capturing add/edit page
    @Author: Mohit Trivedi
    @Date: 13-09-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
?>
<style>
	.themebox h2{ margin-bottom:0;}
  .custom-form-group{margin-bottom:0!important;}
</style>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.price_format.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>

<div aria-hidden="true" style="display: none;" id="property_basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title"> <span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body">

        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="row">
              <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                <div class="col-lg-12">
                  <div id="DataTables_Table_0_filter" class="dataTables_filter">
                    <div class="form-group add_property_type">
                        <label for="text-input"><span class="field_name"></span>Name</label>
                        <input type="text" value="" aria-controls="DataTables_Table_0" id="txt_name" name="txt_name" class="addinput">
                	</div>
                    <div class="form-group share_link text-center">
                     <?php
						$live_link = !empty($editRecord[0]['live_link'])?$editRecord[0]['live_link']:'';
						if(!empty($live_link))
						{
							$live_link = explode('--',$live_link);
							if(!empty($live_link[2]))
								unset($live_link[2]);
							$live_link = implode('--',$live_link);
						}
					?>
                        <a class="btn btn-success icon_css" href="https://www.facebook.com/sharer/sharer.php?u=<?=$this->config->item('proprty_listing_base_path')?><?=!empty($live_link)?$live_link:''?>" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a class="btn btn-success icon_css" href="https://twitter.com/home?status=<?=$this->config->item('proprty_listing_base_path').$editRecord[0]['live_link']?>" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a class="btn btn-success icon_css" href="https://plus.google.com/share?url=<?=$this->config->item('proprty_listing_base_path').$editRecord[0]['live_link']?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                        <a class="btn btn-success icon_css" href="https://www.linkedin.com/shareArticle?mini=true&url=<?=$this->config->item('proprty_listing_base_path').$editRecord[0]['live_link']?>&title=<?=!empty($editRecord[0]['property_title'])?$editRecord[0]['property_title']:'Property';?>&summary=&source=" target="_blank"> <i class="fa fa-linkedin"></i> </a>
                        <a class="btn btn-success icon_css" href="https://pinterest.com/pin/create/button/?url=<?=$this->config->item('proprty_listing_base_path').$editRecord[0]['live_link']?>&media=<?=!empty($photo_link[0]['photo'])?$this->config->item('listing_upload_img_small').$photo_link[0]['photo']:base_url().'images/no-img-banner-small.jpg';?>&description=" target="_blank"> <i class="fa fa-pinterest-square"></i> </a>
                	</div>
                  </div>
                </div>
            </div>
            </div>
		   </div>
        </div>
        <div class="cf"></div>
        
      </div>
      <!--<div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success property_insert_data" onclick="insert_data();">Save</button>
      </div>-->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" style="display: none;" id="common_basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title"><span class="common_popup_heading"></span></h3>
      </div>
      <div class="modal-body">
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="row">
              <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                <div class="col-lg-12">
                  <div id="DataTables_Table_0_filter" class="dataTables_filter">
                    <div class="form-group common_tab">
                    	<div class="text-center">
                        	<img src="<?=base_url()?>images/ajaxloader.gif" />
                        </div>
                	</div>
                  </div>
                </div>
            </div>
            </div>
		   </div>
        </div>
        <div class="cf"></div>
        
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <!--<button type="button" class="btn btn-success property_insert_data" onclick="insert_data();">Save</button>-->
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('lead_capturing_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewlisting">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i> Add Listing </h3>
            <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span> </div>
          <div class="portlet-content">
            <ul class="nav nav-tabs" id="myTab1">
              <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?> > <a title="Property Info" data-toggle="tab" href="#Property_info"  onclick="setmapshow();" >Property Info</a> </li>
              <?php if(!empty($editRecord[0]['id'])){ ?>
               <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Features" data-toggle="tab" href="#features" >Features</a> </li>
               <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Photo" data-toggle="tab" href="#photo" >Photos</a> </li>
               <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="Documents" data-toggle="tab" href="#documents" >Documents</a> </li>
               <li <?php if($tabid == 5){?> class="active" <?php } ?>> <a title="Lock Box" data-toggle="tab" href="#lockbox" >Lock Box</a> </li>
               <li <?php if($tabid == 6){?> class="active" <?php } ?>> <a title="Offers" data-toggle="tab" href="#offers" >Offers</a> </li>
               <li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="price_change" data-toggle="tab" href="#price_change" >Price Change</a> </li>
               <li <?php if($tabid == 8){?> class="active" <?php } ?> > <a title="open_houses" data-toggle="tab" href="#open_houses" >Open Houses</a> </li>
               <li <?php if($tabid == 9){?> class="active" <?php } ?>> <a title="showings" data-toggle="tab" href="#showings" >Showings</a> </li>
               <!--<li <?php if($tabid == 10){?> class="active" <?php } ?>> <a title="contacts" data-toggle="tab" href="#contacts" >Contacts</a> </li>-->
               <?php if(!empty($this->modules_unique_name) && in_array('public_visibility',$this->modules_unique_name)){?>
               <li <?php if($tabid == 11){?> class="active" <?php } ?>> <a title="Public Visibility" data-toggle="tab" href="#Public_visibility" >Public Visibility</a> </li>
               <?php } ?>
               <?php if(!empty($this->modules_unique_name) && in_array('flyer',$this->modules_unique_name)){?>
               <li <?php if($tabid == 12){?> class="active" <?php } ?>> <a title="Flyers" data-toggle="tab" href="#flyers" >Flyers</a> </li>
                <?php } ?>
              <?php } ?>
            </ul>
            <input type ="hidden" id="selected_view" name="selected_view">
            <div class="tab-content" id="myTab1Content">
            
              <!-------------------Tab1=> Property Info Tab---------------------->	
              <div <?php if($tabid == '' || $tabid == 1){ ?> class="tab-pane fade in active" <?php }else {?> class="tab-pane fade in" <?php } ?> id="Property_info">
               <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
               <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
               <input id="property_type_name" name="property_type_name" type="hidden" value="">
               <input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />
                <div class="row">
                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                    <div class="col-lg-6">
                    
                    	<div class="row">
                          <div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Contacts Lead(s)</strong> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
                          <div class="col-sm-12 added_contacts_list">
                            
                            <?php $this->load->view('user/listing_manager/selected_contact_ajax')?>
                            
                          </div>
                        </div>
                    
                      <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        
                        <div class="form-group">
                          <label for="text-input">MLS Number </label>
                          <input type="text" value="<?=!empty($editRecord[0]['mls_no'])?$editRecord[0]['mls_no']:''?>" aria-controls="DataTables_Table_0" id="mls_no" name="mls_no" class="addinput">
                        </div>
                        
                        <?php /*
                        <div class="form-group">
                          <label for="text-input">Property Title <span class="val">*</span></label>
                          <input type="text" value="<?=!empty($editRecord[0]['property_title'])?$editRecord[0]['property_title']:''?>" aria-controls="DataTables_Table_0" id="property_title" name="property_title" class="addinput" data-required="true">
                        </div>
                        */ ?>

                        <div class="form-group">
                          <label for="text-input">Property Type <span class="val">*</span> </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('property_type','Property Type')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select data-required="true" id="property_type" name="property_type" class="form-control parsley-validated addinput">
                            <option value="">Select Property Type</option>
                            <?php if(count($property_type_master) > 0){
								foreach($property_type_master as $row)
								{ ?>	
									<option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['property_type']) && $editRecord[0]['property_type'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Transaction Type</label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('transaction_type','Transaction Type')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="transaction_type" name="transaction_type" class="form-control parsley-validated addinput">
                            <option value="">Select Transaction Type</option>
                            <?php if(count($transaction_type_master) > 0){
								foreach($transaction_type_master as $row)
								{ ?>	
									<option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['transaction_type']) && $editRecord[0]['transaction_type'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Listed Date</label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['listed_date']) && $editRecord[0]['listed_date'] != '0000-00-00' && $editRecord[0]['listed_date'] != '1970-01-01') echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['listed_date'])); else echo '';?>" placeholder="" aria-controls="DataTables_Table_0" id="listed_date" name="listed_date" class="addinput my_custom_date_class" readonly="true">
                        </div>
                        <div class="form-group clear">
                          <label for="text-input">Listing Expiration Date</label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['listing_expire_date']) && $editRecord[0]['listing_expire_date'] != '0000-00-00' && $editRecord[0]['listing_expire_date'] != '1970-01-01') echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['listing_expire_date'])); else echo '';?>" aria-controls="DataTables_Table_0" id="listing_expire_date" name="listing_expire_date" class="addinput my_custom_date_class" readonly="true">
                        </div>
                        <div class="form-group clear">
                          <label for="text-input">Closed Date</label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['closed_date']) && $editRecord[0]['closed_date'] != '0000-00-00' && $editRecord[0]['closed_date'] != '1970-01-01') echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['closed_date'])); else echo '';?>" placeholder="" aria-controls="DataTables_Table_0" id="closed_date" name="closed_date" class="addinput my_custom_date_class" readonly="true">
                        </div>
                        <div class="form-group clear">
                          <label for="text-input">Pending Date</label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['pending_date']) && $editRecord[0]['pending_date'] != '0000-00-00' && $editRecord[0]['pending_date'] != '1970-01-01') echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['pending_date'])); else echo '';?>" placeholder="" aria-controls="DataTables_Table_0" id="pending_date" name="pending_date" class="addinput my_custom_date_class" readonly="true">
                        </div>
                        <?php /*
                        <div class="form-group clear">
                          <label for="text-input">Seller/Contacts</label>
                          <input type="text" value="<?=!empty($editRecord[0]['seller_name'])?$editRecord[0]['seller_name']:''?>" aria-controls="DataTables_Table_0" id="seller_name" name="seller_name" class="addinput">
                        </div>
                        */ ?>
                        <div class="form-group">
                          <label for="text-input">Price</label>
                          <input type="text" value="<?=!empty($editRecord[0]['price'])?$editRecord[0]['price']:''?>" aria-controls="DataTables_Table_0" id="price" name="price" class="addinput" onkeypress="return IsNumeric(event)">
                          
                        </div>
                        <div class="form-group">
                          <label for="text-input">Year Built</label>
                          <input type="text" value="<?=!empty($editRecord[0]['year_built'])?$editRecord[0]['year_built']:''?>" placeholder="Year Built" aria-controls="DataTables_Table_0" id="year_built" name="year_built" class="addinput" onkeypress="return IsNumeric(event)"	>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Taxes</label>
                          <input type="text" value="<?=!empty($editRecord[0]['taxes'])?$editRecord[0]['taxes']:''?>" placeholder="Taxes" aria-controls="DataTables_Table_0" id="taxes" name="taxes" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Tax ID </label>
                          <input type="text" value="<?=!empty($editRecord[0]['tax_id'])?$editRecord[0]['tax_id']:''?>" placeholder="Tax ID" aria-controls="DataTables_Table_0" id="tax_id" name="tax_id" class="addinput">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Lot No. </label>
                          <input type="text" value="<?=!empty($editRecord[0]['lot_no'])?$editRecord[0]['lot_no']:''?>" placeholder="Lot No." aria-controls="DataTables_Table_0" id="lot_no" name="lot_no" class="addinput">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Block </label>
                          <input type="text" value="<?=!empty($editRecord[0]['block'])?$editRecord[0]['block']:''?>" placeholder="Block" aria-controls="DataTables_Table_0" id="block" name="block" class="addinput">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Plot/Subdivision/Building Name </label>
                          <input type="text" value="<?=!empty($editRecord[0]['building_name'])? htmlentities($editRecord[0]['building_name']):''?>" placeholder="Plot/Subdivision/Building Name" aria-controls="DataTables_Table_0" id="building_name" name="building_name" class="addinput">
                        </div>
                        
                        <div class="form-group">
                        	<label for="text-input">Community/District </label>
	                        <input type="text" value="<?=!empty($editRecord[0]['district'])? htmlentities($editRecord[0]['district']):''?>" placeholder="Community/District" aria-controls="DataTables_Table_0" id="district" name="district" class="addinput">
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Description</label>
                          <textarea class="form-control parsley-validated addtextarea" id="remarks" name="remarks" placeholder=""><?=!empty($editRecord[0]['remarks'])? htmlentities($editRecord[0]['remarks']):''?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div id="DataTables_Table_0_filter" class="dataTables_filter">
                      	<div class="form-group">
                          <label for="validateSelect">Listing Agent:</label>
                          <select  class="form-control parsley-validated addinput" name="slt_assigned" id="slt_assigned">
                           <!--<option value="">Select User</option>-->
                            <?php if(!empty($user_list)){
                                    foreach($user_list as $row){ ?>
                                        <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['assign_to']) && $editRecord[0]['assign_to'] == $row['id']){ echo ' selected=selected '; }?> ><?php if($row['admin_name']!='') { echo ucwords($row['admin_name']." (".$row['email_id'].")");}else{ echo ucwords($row['user_name']." (".$row['email_id'].")");}?></option>
                            <?php 	}
                                } ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Status <span class="val">*</span> </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('status','Status')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select data-required="true" id="status" name="status" class="form-control parsley-validated addinput">
                            <option value="">Select Status</option>
                            <?php if(count($status_master) > 0){
								foreach($status_master as $row)
								{ ?>	
									<option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Living Area </label>
                          <input type="text" value="<?=!empty($editRecord[0]['living_area'])?$editRecord[0]['living_area']:''?>" placeholder="Living Area" aria-controls="DataTables_Table_0" id="living_area" name="living_area" class="addinput" onkeypress="return IsNumeric(event)">
                          <select id="living_area_unit" name="living_area_unit" class="form-control parsley-validated addselect">
                            
                            <?php if(count($area_unitdata) > 0){
								foreach($area_unitdata as $row)
								{ ?>
									<option value="<?=$row['id']?>{^}<?=$row['unit_title']?>" <?php if(!empty($editRecord[0]['living_area_unit']) && $editRecord[0]['living_area_unit'] == $row['id']) echo "selected=selected"; elseif($row['id'] == 4)  echo "selected=selected";?> ><?=$row['unit_title']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Total Area </label>
                          <input type="text" value="<?=!empty($editRecord[0]['total_area'])?$editRecord[0]['total_area']:''?>" placeholder="Total Area" aria-controls="DataTables_Table_0" id="total_area" name="total_area" class="addinput" onkeypress="return IsNumeric(event)">
                          <select id="total_area_unit" name="total_area_unit" class="form-control parsley-validated addselect">
                            
                            <?php if(count($area_unitdata) > 0){
								foreach($area_unitdata as $row)
								{ ?>
									<option value="<?=$row['id']?>{^}<?=$row['unit_title']?>" <?php if(!empty($editRecord[0]['total_area_unit']) && $editRecord[0]['total_area_unit'] == $row['id']) echo "selected=selected"; elseif($row['id'] == 4) echo "selected=selected";?> ><?=$row['unit_title']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Total Unfinished</label>
                          <input type="text" value="<?=!empty($editRecord[0]['total_unfinished'])?$editRecord[0]['total_unfinished']:''?>" placeholder="Total Area" aria-controls="DataTables_Table_0" id="total_unfinished" name="total_unfinished" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Lot Details</label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('lot_type','Lot Type')"><i class="fa fa-plus-square addplus"></i></a>-->
			            <select id="lot_type" name="lot_type" class="form-control parsley-validated addinput">
                            <option value="">Select Lot Details </option>
						   <?php if(count($lot_type_master) > 0){
                                foreach($lot_type_master as $row)
                                { ?>	
                                    <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['lot_type']) && $editRecord[0]['lot_type'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                            <?php
                                }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Lot Size</label>
                          <input type="text" value="<?=!empty($editRecord[0]['lot_size'])?$editRecord[0]['lot_size']:''?>" placeholder="Lot Size" aria-controls="DataTables_Table_0" id="lot_size" name="lot_size" class="addinput" onkeypress="return IsNumeric(event)">
                          <select id="lot_size_unit" name="lot_size_unit" class="form-control parsley-validated addselect">
                            <?php if(count($size_unitdata) > 0){
								foreach($size_unitdata as $row)
								{ ?>
									<option value="<?=$row['id']?>{^}<?=$row['unit_title']?>" <?php if(!empty($editRecord[0]['lot_size_unit']) && $editRecord[0]['lot_size_unit'] == $row['id']) echo "selected=selected"; elseif($row['id'] == 8) echo "selected=selected";?> ><?=$row['unit_title']?></option>
							<?php
								}
							}
							?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Lot Dimension</label>
                          <input type="text" value="<?=!empty($editRecord[0]['lot_dimension'])?$editRecord[0]['lot_dimension']:''?>" placeholder="Lot Dimension" aria-controls="DataTables_Table_0" id="lot_dimension" name="lot_dimension" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Bedrooms</label>
                          <input type="text" value="<?=!empty($editRecord[0]['bedrooms_count'])?$editRecord[0]['bedrooms_count']:''?>" placeholder="Bedrooms" aria-controls="DataTables_Table_0" id="bedrooms_count" name="bedrooms_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Bathrooms</label>
                          <input type="text" value="<?=!empty($editRecord[0]['bathrooms_count'])?$editRecord[0]['bathrooms_count']:''?>" placeholder="Bathrooms" aria-controls="DataTables_Table_0" id="bathrooms_count" name="bathrooms_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Half Bathrooms</label>
                          <input type="text" value="<?=!empty($editRecord[0]['half_bathrooms_count'])?$editRecord[0]['half_bathrooms_count']:''?>" placeholder="Half Bathrooms" aria-controls="DataTables_Table_0" id="half_bathrooms_count" name="half_bathrooms_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Parking</label>
                          <input type="text" value="<?=!empty($editRecord[0]['parking_count'])?$editRecord[0]['parking_count']:''?>" placeholder="Parking" aria-controls="DataTables_Table_0" id="parking_count" name="parking_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Kitchen</label>
                          <input type="text" value="<?=!empty($editRecord[0]['kitchen_count'])?$editRecord[0]['kitchen_count']:''?>" placeholder="Kitchen" aria-controls="DataTables_Table_0" id="kitchen_count" name="kitchen_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Floors</label>
                          <input type="text" value="<?=!empty($editRecord[0]['floor_count'])?$editRecord[0]['floor_count']:''?>" placeholder="Floors" aria-controls="DataTables_Table_0" id="floor_count" name="floor_count" class="addinput" onkeypress="return IsNumeric(event)">
                        </div>
                        
                        <fieldset class="edit_main_div">
                          <legend class="edit_title">Commission</legend>
                          <div class="cf"></div>
                          <div class="col-xs-12 mrgtop1">
                            <div class="form-group">
                              <label for="text-input">Expected Commission</label>
                              <input type="text" value="<?=!empty($editRecord[0]['expected_commission'])?$editRecord[0]['expected_commission']:''?>" placeholder="Expected Commission" aria-controls="DataTables_Table_0" id="expected_commission" name="expected_commission" class="addinput" onkeypress="return IsNumeric(event)">
                              <select id="expected_commission_unit" name="expected_commission_unit" class="form-control addselect parsley-validated">
                                
								  <?php if(count($price_unitdata) > 0){
                                        foreach($price_unitdata as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['unit_title']?>" <?php if(!empty($editRecord[0]['expected_commission_unit']) && $editRecord[0]['expected_commission_unit'] == $row['id']) echo "selected=selected"; ?> ><?=$row['unit_title']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="text-input">Commission Received</label>
                              <input type="text" value="<?=!empty($editRecord[0]['commission_received'])?$editRecord[0]['commission_received']:''?>" placeholder="Commission Received" aria-controls="DataTables_Table_0" id="commission_received" name="commission_received" class="addinput" onkeypress="return IsNumeric(event)">
                              <select id="commission_received_unit" name="commission_received_unit" class="form-control addselect parsley-validated">
								  <?php if(count($price_unitdata) > 0){
                                        foreach($price_unitdata as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['unit_title']?>" <?php if(!empty($editRecord[0]['commission_received_unit']) && $editRecord[0]['commission_received_unit'] == $row['id']) echo "selected=selected"; ?> ><?=$row['unit_title']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                              </select>
                            </div>
                          </div>
                        </fieldset>
                        <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                        <div class="form-group mrg22">
                          <label for="text-input">Assign Communication</label>
                          <select id="interaction_plan_id" name="interaction_plan_id" class="form-control parsley-validated addinput">
                                <option value="">Select Communication </option>
								  <?php if(count($communication_plans) > 0){
                                        foreach($communication_plans as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['interaction_plan_id']) && $editRecord[0]['interaction_plan_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['plan_name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                          </select>
                         
                        </div>
                        <? } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                      <div class="col-lg-6">
                        <div class="form-group custom-form-group">
                          <label for="text-input">Address <span class="val">*</span> </label>
                          <div class="">
                          <input type="text" value="<?=!empty($editRecord[0]['address_line_1'])? htmlentities($editRecord[0]['address_line_1']):''?>" placeholder="Address1" aria-controls="DataTables_Table_0" id="address_line_1" name="address_line_1" class="form-control parsley-validated parsley-success"  data-required="true" onblur="blurFunction();">
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="text" value="<?=!empty($editRecord[0]['address_line_2'])? htmlentities($editRecord[0]['address_line_2']):''?>" placeholder="Address2" aria-controls="DataTables_Table_0" id="address_line_2" class="addinput form-control parsley-validated" name="address_line_2" onblur="blurFunction();">
                          <!--<input type="text" value="<?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?>" placeholder="Community/District" aria-controls="DataTables_Table_0" id="district" name="district" class="addinput form-control parsley-validated" onblur="blurFunction();">-->
                          <div class="div">
                            <div class="col-lg-3 col-xs-3 nopadding">
                              <input type="text" value="<?=!empty($editRecord[0]['city'])? htmlentities($editRecord[0]['city']):''?>" class="form-control parsley-validated" name="city" id="city" placeholder="City" onblur="blurFunction();">
                            </div>
                            <div class="col-lg-3 col-xs-3 nopadding">
                              <input type="text" value="<?=!empty($editRecord[0]['state'])? htmlentities($editRecord[0]['state']):''?>" class="form-control parsley-validated" name="state" id="state" placeholder="State" onblur="blurFunction();">
                            </div>
                            <div class="col-lg-3 col-xs-3 nopadding">
                              <input type="text" value="<?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?>" class="form-control parsley-validated" data-minlength="5" maxlength="5" name="zip_code" id="zip_code" placeholder="Zip Code" onblur="blurFunction();">
                            </div>
                            <input type="text" value="<?=!empty($editRecord[0]['country'])? htmlentities($editRecord[0]['country']):''?>" placeholder="Country" aria-controls="DataTables_Table_0" id="country" name="country" class="addinput form-control parsley-validated" onblur="blurFunction();">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Latitude</label>
                          <input type="text" value="<?=!empty($editRecord[0]['latitude'])?$editRecord[0]['latitude']:''?>" placeholder="Latitude" aria-controls="DataTables_Table_0" id="latitude" name="latitude" class="addinput" readonly="readonly">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Longitude</label>
                          <input type="text" value="<?=!empty($editRecord[0]['longitude'])?$editRecord[0]['longitude']:''?>" placeholder="Longitude" aria-controls="DataTables_Table_0" id="longitude" name="longitude" class="addinput" readonly="readonly">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <label for="text-input">Map</label>
                      <!--<div class="add_map"> <img src="<?php echo $this->config->item('image_path');?>map.jpg" alt="map"></div>-->
                    
                  <div id="googleMap"  class="map"></div>
						<script type="text/javascript">
                        function changeaddress(passaddress)
                        {
                            var mp = '<iframe width="100%" scrolling="no" style="height:300px" frameborder="0" src="https://maps.google.com/maps?q='+passaddress+'&amp;output=embed&amp; z=10" marginwidth="0" marginheight="0"></iframe>';
                            $('#googleMap').html(mp);
                        }
                        <?php if(!empty($mapaddress[0]['address'])){ ?>
                            changeaddress('<?=$mapaddress[0]['address']?>');
                        <?php }else{ ?>
                            changeaddress('');
                        <?php } ?>
                      </script>
                        <div class="clear"></div>

                    </div>
                  </div>
                </div>
                <div class="col-sm-12 pull-left text-center margin-top-10">
                  <input type="hidden" id="tabid" name="tabid" value="1" />
                  <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                  <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
                  <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
                  </form>
              </div>
              <!-------------------Property Info Tab END------------------------->	
              
              
              <!-------------------Tab2=>Features Tab---------------------------->
			  <div <?php if($tabid == '2'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="features">
              <div class="row">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
              <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                    <div class="col-lg-6">
                      <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <div class="form-group">
                        
                          <label for="text-input">Sewer </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('sewer_id','Sewer')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="sewer_id" name="sewer_id" class="form-control parsley-validated addinput">
                            <option value="">Select Sewer</option>
                            <?php if(count($sewer_master) > 0){
                                        foreach($sewer_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['sewer_id']) && $editRecord[0]['sewer_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Basement </label>
							<!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('basement_id','Basement')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="basement_id" name="basement_id" class="form-control parsley-validated addinput">
                            <option value="">Select Basement</option>
                            <?php if(count($basement_master) > 0){
                                        foreach($basement_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['basement_id']) && $editRecord[0]['basement_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Parking Type</label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('parking_type_id','Parking Type')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="parking_type_id" name="parking_type_id" class="form-control parsley-validated addinput">
                            <option value="">Select Parking Type</option>
                            <?php if(count($parking_master) > 0){
                                        foreach($parking_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['parking_type_id']) && $editRecord[0]['parking_type_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Parking Spaces </label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['parking_spaces'])) echo $editRecord[0]['parking_spaces']; ?>" placeholder="" aria-controls="DataTables_Table_0" id="parking_spaces" name="parking_spaces" class="addinput">
                          
                        </div>
                        <div class="form-group">
                          <label for="text-input">Builder </label>
                          <input type="text" value="<?php if(!empty($editRecord[0]['builder_name'])) echo htmlentities($editRecord[0]['builder_name']); ?>" placeholder="" aria-controls="DataTables_Table_0" id="builder_name" name="builder_name" class="addinput">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Style</label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('style_id','Style')"><i class="fa fa-plus-square addplus"></i></a>-->
                           <select id="style_id" name="style_id" class="form-control parsley-validated addinput">
                            <option value="">Select Style</option>
                            <?php if(count($style_master) > 0){
                                        foreach($style_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['style_id']) && $editRecord[0]['style_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Exterior Finish</label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('exterior_finish_id','Exterior Finish')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="exterior_finish_id" name="exterior_finish_id" class="form-control parsley-validated addinput">
                           <option value="">Select Exterior Finish</option>
                           <?php if(count($exterior_finish_master) > 0){
                                        foreach($exterior_finish_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['exterior_finish_id']) && $editRecord[0]['exterior_finish_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Foundation </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('foundation_id','Foundation')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="foundation_id" name="foundation_id" class="form-control parsley-validated addinput">
                           <option value="">Select Foundation</option>
                           <?php if(count($foundation_master) > 0){
                                        foreach($foundation_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['foundation_id']) && $editRecord[0]['foundation_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>

                        </div>
                        <div class="form-group">
                          <label for="text-input">Roof </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('roof_id','Roof')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="roof_id" name="roof_id" class="form-control parsley-validated addinput">
                           <option value="">Select Roof</option>
                           <?php if(count($roof_master) > 0){
                                        foreach($roof_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['roof_id']) && $editRecord[0]['roof_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Architecture </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('architecture_id','Architecture')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="architecture_id" name="architecture_id" class="form-control parsley-validated addinput">
                           <option value="">Select Architecture</option>
                           <?php if(count($architecture_master) > 0){
                                        foreach($architecture_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['architecture_id']) && $editRecord[0]['architecture_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <div class="form-group">
                          <label for="text-input">Green Certification </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('green_certification_id','Green Certification')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="green_certification_id" name="green_certification_id" class="form-control parsley-validated addinput">
                           <option value="">Select Green Certification</option>
                           <?php if(count($green_certification_master) > 0){
                                        foreach($green_certification_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['green_certification_id']) && $editRecord[0]['green_certification_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
						<div class="form-group">
                          <label for="text-input">Fireplace </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('fireplace_id','Fireplace')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="fireplace_id" name="fireplace_id" class="form-control parsley-validated addinput">
                           <option value="">Select Fireplace</option>
                            <?php if(count($fireplace_master) > 0){
                                        foreach($fireplace_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['fireplace_id']) && $editRecord[0]['fireplace_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Energy Source </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('energy_source_id','Energy Source')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="energy_source_id" name="energy_source_id" class="form-control parsley-validated addinput">
                           <option value="">Select Energy Source</option>
                           <?php if(count($energy_source_master) > 0){
                                        foreach($energy_source_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['energy_source_id']) && $editRecord[0]['energy_source_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Heating/Cooling </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('heating_cooling_id','Heating/Cooling')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="heating_cooling_id" name="heating_cooling_id" class="form-control parsley-validated addinput">
                           <option value="">Select Heating/Cooling</option>
                            <?php if(count($heating_cooling_master) > 0){
                                        foreach($heating_cooling_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['heating_cooling_id']) && $editRecord[0]['heating_cooling_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Floor Covering </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('floor_covering_id','Floor Covering')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="floor_covering_id" name="floor_covering_id" class="form-control parsley-validated addinput">
                           <option value="">Select Floor Covering</option>
                           <?php if(count($floor_covering_master) > 0){
                                        foreach($floor_covering_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['floor_covering_id']) && $editRecord[0]['floor_covering_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Interior Features </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('interior_feature_id','Interior Features')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="interior_feature_id" name="interior_feature_id" class="form-control parsley-validated addinput">
                           <option value="">Select Interior Features</option>
                            <?php if(count($interior_feature_master) > 0){
                                        foreach($interior_feature_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['interior_feature_id']) && $editRecord[0]['interior_feature_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Water Company </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('water_company_id','Water Company')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="water_company_id" name="water_company_id" class="form-control parsley-validated addinput">
                           <option value="">Select Water Company</option>
                           <?php if(count($water_company_master) > 0){
                                        foreach($water_company_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['water_company_id']) && $editRecord[0]['water_company_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Power Company </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('power_company_id','Power Company')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="power_company_id" name="power_company_id" class="form-control parsley-validated addinput">
                           <option value="">Select Power Company</option>
                            <?php if(count($power_company_master) > 0){
                                        foreach($power_company_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['power_company_id']) && $editRecord[0]['power_company_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="text-input">Sewer Company </label>
                         <!-- <a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('sewer_company_id','Sewer Company')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="sewer_company_id" name="sewer_company_id" class="form-control parsley-validated addinput">
                           <option value="">Select Sewer Company</option>
                           <?php if(count($sewer_company_master) > 0){
                                        foreach($sewer_company_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['sewer_company_id']) && $editRecord[0]['sewer_company_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <!--<label for="text-input">Basement : </label>
                          <select data-required="true" id="basement_id" name="basement_id" class="form-control parsley-validated addinput">
                           <option value="">Select Basement</option>
                          </select>-->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 pull-left text-center margin-top-10">
                      <input type="hidden" id="tabid" name="tabid" value="2" />
                      <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                      <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
                      <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> 
                  </div>
                 </form>
                </div>
                </div>
			  <!-------------------Features Tab END------------------------------>
              
              
              <!-------------------Tab3=>Photes Tab------------------------------>
              <div <?php if($tabid == '3'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="photo">
             
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
                
             <!--<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="upload_pic"><img src="<?php echo $this->config->item('image_path');?>upload_pic.jpg" alt="img">
                    <button onclick="deletepopup1('5','First lead capturing form');" title="Delete Record" class="btn btn-xs btn-primary closed"><i class="fa fa-times"></i> Remove Photo</button>
                  </div>
                </div>
                
              </div>-->
              
              			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">

              	<div class="add_emailtype autooverflow">
					<div class="col-sm-12">
						<div class="browse"> <span class="text"> </span>
						  <div class="browse_btn">
							<div class="file_input_div">
							  <input type="button" value="Browse" class="file_input_button"  />
							  <input type="file" alt="1" name="listing_pic" id="listing_pic" onchange="showimagepreview(this)" class="file_input_hidden"/>
							</div>
						  </div>
						  <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
						</div>
						<p><span class="txt">&nbsp;</span>
						  <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
						</p>
                        <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
					</div>
        			<div class="col-sm-10 form-group margin-top-10">
					  <input title="Save and Add More Photos" type="submit" class="btn btn-secondary" value="Save and Add More Photos" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
					</div>

				</div>
                <div class="row">
                <div class="col-sm-12 autooverflow">
                <?php for($i=0;$i<count($photos_trans_data);$i++)
				{ 
				?>
                       <div class="col-lg-4 col-md-4 col-sm-4 margin-top-10 delete_div_<?=($photos_trans_data[$i]['id'])?>">
                         <span class="txt">&nbsp;</span>
                        	<?php  if(!empty($photos_trans_data[$i]['photo']) && file_exists($this->config->item('listing_big_img_path').$photos_trans_data[$i]['photo'])){
							?>
                    <div class="upload_pic"><img src="<?php echo $this->config->item('listing_upload_img_small');?>/<?=(!empty($photos_trans_data[$i]['photo'])?$photos_trans_data[$i]['photo']:'');?>" alt="img">
                    <a href="javascript:void(0);" onclick="delete_image('<?=($photos_trans_data[$i]['photo'])?>','<?=($photos_trans_data[$i]['id'])?>');" title="Delete Record" class="btn btn-xs btn-primary closed"><i class="fa fa-times"></i> Remove Photo</a>
                  </div>
						  <? } else{
				if(!empty($photos_trans_data[$i]['photo']) && file_exists($this->config->item('listing_small_img_path').$photos_trans_data[$i]['photo'])){
				?>
                    <div class="upload_pic"><img src="<?php echo $this->config->item('listing_upload_img_small');?>/<?=(!empty($photos_trans_data[$i]['photo'])?$photos_trans_data[$i]['photo']:'');?>" alt="img">
                    <a href="javascript:void(0);" onclick="delete_image('<?=($photos_trans_data[$i]['photo'])?>','<?=($photos_trans_data[$i]['id'])?>');" title="Delete Record" class="btn btn-xs btn-primary closed"><i class="fa fa-times"></i> Remove Photo</a>
                  </div>

				<? } } ?>
                       </div>
				<?php 
				}
				?> 
                </div>
                </div>
              <div class="col-sm-12 pull-left text-center margin-top-10">
              	<input type="hidden" id="tabid" name="tabid" value="3" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="3" />
                <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
              </form>
            </div>
			  <!-------------------Photes Tab END-------------------------------->
              
              
              <!-------------------Tab4=>Documents Tab---------------------------->
         	  <div <?php if($tabid == 4){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="documents">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="doc_id" name="doc_id" type="hidden" value="">
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-7 form-group">
                              <label for="text-input"> Document Type </label>
                              <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('slt_doc_type','Document Type')"><i class="fa fa-plus-square addplus"></i></a>-->
							  <select class="form-control parsley-validated addinput" name="slt_doc_type" id="slt_doc_type">
							   <option value="">Please Select</option>
							   <?php if(!empty($document_type)){
								   
										foreach($document_type as $row){?>
											<option <?php if(!empty($rowtrans['document_type_id']) && $rowtrans['document_type_id'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['name']?></option>
										<?php } ?>
							   <?php } ?>
							  </select>
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_document_name');?></label>
							  <input id="txt_doc_name" name="txt_doc_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['doc_name'])){ echo htmlentities($editRecord[0]['doc_name']); }?>">
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_desc');?></label>
							  <textarea id="txtarea_doc_desc" name="txtarea_doc_desc" class="form-control parsley-validated"><?php if(!empty($rowtrans['doc_desc'])){ echo htmlentities($rowtrans['doc_desc']); }?></textarea>
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input" class="fleft"><?=$this->lang->line('contact_add_upload_file');?></label>
							  <div class="browse_btn clear">
								  <div class="file_input_div">
									<input type="button" value="Browse" class="file_input_button" />
									<input type="file" alt="1" name="doc_file" id="doc_file" class="file_input_hidden"/>
									<input type="hidden" name="hiddenFiledoc" id="hiddenFiledoc" value="" />
								  </div>
								   <span id="priview_doc"></span>
                                   <label> Allowed File Types: txt,doc,docx,pdf,csv,xls,xlsx</label>
							</div>
								
							</div>
							<div class="col-sm-7 form-group margin-top-10">
							  <input title="Save and Add More Document" type="submit" class="btn btn-secondary" value="Save and Add More Document" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
							</div>

										
						</div>
					</div>
					
					<div class="col-sm-12 clear appendajaxdata toppadding">
						<?php $this->load->view('user/listing_manager/listing_document_ajax'); ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 pull-left text-center">
			
		  		<input type="hidden" id="tabid" name="tabid" value="4" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="4" />
                <input type="submit" name="savebtn" value="Save" id="savecontacttab2" onclick="on_submit(); setsubmitidtab2(1);" class="btn btn-secondary-green">
                <input type="submit" name="submitbtn" value="Save and Continue" onclick="on_submit(); setsubmitidtab2(2);" class="btn btn-secondary">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
 			</form>
         </div>
			  <!-------------------Documents Tab END------------------------------>


              <!-------------------Tab5=>Lock Box Tab----------------------------->
			  <div <?php if($tabid == '5'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="lockbox">
              <div class="row">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
              <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                    <div class="col-lg-6">
                      <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <div class="form-group">
                        
                          <label for="text-input">Lock Box Type </label>
                          <!--<a data-toggle="modal" class="text_color_red addplus" href="#property_basicModal" onclick="property_name('lockbox_type_id','Lock Box Type')"><i class="fa fa-plus-square addplus"></i></a>-->
                          <select id="lockbox_type_id" name="lockbox_type_id" class="form-control parsley-validated addinput">
                            <option value="">Select Type</option>
                            <?php if(count($lock_master) > 0){
                                        foreach($lock_master as $row)
                                        { ?>
                                            <option value="<?=$row['id']?>{^}<?=$row['name']?>" <?php if(!empty($editRecord[0]['lockbox_type_id']) && $editRecord[0]['lockbox_type_id'] == $row['id']) echo "selected=selected"; ?> ><?=$row['name']?></option>
                                    <?php
                                        }
                                  }
                           ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="text-input">Serial </label>
                          <input type="text" placeholder="" aria-controls="DataTables_Table_0" id="lockbox_serial" name="lockbox_serial" class="addinput" value="<?php if(!empty($editRecord[0]['lockbox_serial'])){ echo $editRecord[0]['lockbox_serial']; }?>">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Combination </label>
                          <input type="text" aria-controls="DataTables_Table_0" id="lockbox_combination" name="lockbox_combination" class="addinput" value="<?php if(!empty($editRecord[0]['lockbox_combination'])){ echo $editRecord[0]['lockbox_combination']; }?>">
                        </div>
                        <div class="form-group">
                          <label for="text-input">Lock Box Location </label>
                          <input type="text" aria-controls="DataTables_Table_0" id="lockbox_location_on_property" name="lockbox_location_on_property" class="addinput" value="<?php if(!empty($editRecord[0]['lockbox_location_on_property'])){ echo $editRecord[0]['lockbox_location_on_property']; }?>">
                        </div>
                       	<div class="form-group">
						  <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
						  <textarea id="txtarea_lockbox_notes" name="txtarea_lockbox_notes" class="form-control parsley-validated addtextarea"><?php if(!empty($editRecord[0]['lockbox_notes'])){ echo htmlentities($editRecord[0]['lockbox_notes']); }?></textarea>
							</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 pull-left text-center margin-top-10">
                      <input type="hidden" id="tabid" name="tabid" value="5" />
                      <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                      <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
                      <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> 
                  </div>
                 </form>
                </div>
                </div>
			  <!-------------------Lock Box Tab END------------------------------->


              <!-------------------Tab6=>Offers Tab-------------------------------->
         	  <div <?php if($tabid == 6){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="offers">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="offers_id" name="offers_id" type="hidden" value="">
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
					<div class="col-sm-8">
						<div class="row">

							<div class="col-sm-7 form-group">
                            <div class="row">
                            <div class="col-sm-6 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_price');?></label>
                              <input id="txt_offer_price" name="txt_offer_price" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['offer_price'])){ echo $editRecord[0]['offer_price']; }?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                              </div>
                              <div class="col-sm-6 form-group">
                               <label for="text-input"><?=$this->lang->line('common_label_unit');?></label>
                              <select class="form-control parsley-validated add" name="slt_price_unit" id="slt_price_unit">
							   <?php if(!empty($price_unitdata)){
										foreach($price_unitdata as $row){?>
											<option <?php if(!empty($rowtrans['offer_price_unit_id']) && $rowtrans['offer_price_unit_id'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['unit_title']?></option>
										<?php } ?>
							   <?php } ?>
							  </select>
                              </div>
                              </div>
                        	</div>

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_date');?></label>
							  <input id="txt_offer_date" name="txt_offer_date" aria-controls="DataTables_Table_0" class="form-control parsley-validated my_custom_date_class background_white" type="text" value="<?php if(!empty($editRecord[0]['offer_date']) && $editRecord[0]['offer_date'] != '0000-00-00' && $editRecord[0]['offer_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['offer_date'])); }?>" readonly="true">
							</div>


							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_agentname');?></label>
							  <input id="txt_offer_agent" name="txt_offer_agent" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['offer_agent_name'])){ echo htmlentities($editRecord[0]['offer_agent_name']); }?>">
							</div>
							

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_phone');?></label>
							  <input id="txt_offer_phone" name="txt_offer_phone" class="form-control parsley-validated mask_apply_class" type="text" data-maxlength="12" value="<?php if(!empty($editRecord[0]['offer_phone'])){ echo $editRecord[0]['offer_phone']; }?>">
							</div>

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
							  <textarea id="txtarea_offer_notes" name="txtarea_offer_notes" class="form-control"><?php if(!empty($rowtrans['offer_notes'])){ echo htmlentities($rowtrans['offer_notes']); }?></textarea>
							</div>
							
							
							<div class="col-sm-7 form-group margin-top-10">
							  <input title="Save and Add More Offers" type="submit" class="btn btn-secondary" value="Save and Add More Offers" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
							</div>

										
						</div>
					</div>
					
					<div class="col-sm-12 clear appendajaxdata toppadding">
						<?php $this->load->view('user/listing_manager/listing_offers_ajax'); ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 pull-left text-center">
			
		  		<input type="hidden" id="tabid" name="tabid" value="6" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="6" />
                <input type="submit" name="savebtn" value="Save" id="savecontacttab2" onclick="on_submit(); setsubmitidtab2(1);" class="btn btn-secondary-green">
                <input type="submit" name="submitbtn" value="Save and Continue" onclick="on_submit(); setsubmitidtab2(2);" class="btn btn-secondary">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
 			</form>
         </div>
			  <!-------------------Offers Tab END---------------------------------->
			  
              
              <!-------------------Tab7=>Price Change Tab-------------------------->
         	  <div <?php if($tabid == 7){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="price_change">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="price_change_id" name="price_change_id" type="hidden" value="">
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
					<div class="col-sm-8">
						<div class="row">

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_date');?></label>
							  <input id="txt_price_date" name="txt_price_date" aria-controls="DataTables_Table_0" class="form-control parsley-validated my_custom_date_class background_white" type="text" value="<?php if(!empty($editRecord[0]['price_change_date']) && $editRecord[0]['price_change_date'] != '0000-00-00' && $editRecord[0]['price_change_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['price_change_date'])); }?>" readonly="true">
							</div>
                            
                            <div class="col-sm-7 form-group">
                            <div class="row">
                            <div class="col-sm-6 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_newprice');?></label>
                              <input id="txt_new_price" name="txt_new_price" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['new_price'])){ echo $editRecord[0]['new_price']; }?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                              </div>
                              <div class="col-sm-6 form-group">
                               <label for="text-input"><?=$this->lang->line('common_label_unit');?></label>
                              <select class="form-control parsley-validated add" name="slt_new_price_unit_id" id="slt_new_price_unit_id">
							  
							   <?php if(!empty($price_unitdata)){
										foreach($price_unitdata as $row){?>
											<option <?php if(!empty($rowtrans['new_price_unit_id']) && $rowtrans['new_price_unit_id'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['unit_title']?></option>
										<?php } ?>
							   <?php } ?>
							  </select>
                              </div>
                              </div>
                        	</div>

							

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
							  <textarea id="txtarea_price_notes" name="txtarea_price_notes" class="form-control"><?php if(!empty($rowtrans['price_notes'])){ echo htmlentities($rowtrans['price_notes']); }?></textarea>
							</div>
							
							
							<div class="col-sm-7 form-group margin-top-10">
							  <input title="Save and Add More Price" type="submit" class="btn btn-secondary" value="Save and Add More Price" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
							</div>

										
						</div>
					</div>
					
					<div class="col-sm-12 clear appendajaxdata toppadding">
						<?php $this->load->view('user/listing_manager/listing_price_ajax'); ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 pull-left text-center">
			
		  		<input type="hidden" id="tabid" name="tabid" value="7" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="7" />
                <input type="submit" name="savebtn" value="Save" id="savecontacttab2" onclick="on_submit(); setsubmitidtab2(1);" class="btn btn-secondary-green">
                <input type="submit" name="submitbtn" value="Save and Continue" onclick="on_submit(); setsubmitidtab2(2);" class="btn btn-secondary">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
 			</form>
         </div>
			  <!-------------------Price Change Tab END---------------------------->


              <!-------------------Tab8=>Open Houses Tab--------------------------->
         	  <div <?php if($tabid == 8){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="open_houses">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="Open_houses_id" name="Open_houses_id" type="hidden" value="">
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
					<div class="col-sm-8">
						<div class="row">

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_date');?></label>
							  <input id="txt_houses_date" name="txt_houses_date" aria-controls="DataTables_Table_0" class="form-control parsley-validated my_custom_date_class background_white" type="text" value="<?php if(!empty($editRecord[0]['open_house_date']) && $editRecord[0]['open_house_date'] != '0000-00-00' && $editRecord[0]['open_house_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['open_house_date'])); }?>" readonly="true">
							</div>
                            
                            <div class="col-sm-7 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_time');?></label>
                              <div class="col-sm-12  col-lg-6 form-group" style="padding-left:0px;">
                              <input id="txt_houses_time" name="txt_houses_time" class="form-control my_readonly_bg_class background_white" type="text" value="<?php if(!empty($editRecord[0]['open_house_time'])){ echo date("h:i A", strtotime($editRecord[0]['open_house_time'])); }?>">
                               </div>
                               <div class="col-sm-12  col-lg-6 form-group">
                               <input id="txt_houses_end_time" name="txt_houses_end_time" class="form-control my_readonly_bg_class background_white" type="text" value="<?php if(!empty($editRecord[0]['open_house_end_time'])){ echo date("h:i A", strtotime($editRecord[0]['open_house_end_time'])); }?>">
                               </div>
                              </div>

							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
							  <textarea id="txtarea_houses_notes" name="txtarea_houses_notes" class="form-control"><?php if(!empty($editRecord[0]['open_house_notes'])){ echo htmlentities($editRecord[0]['open_house_notes']); }?></textarea>
							</div>
							
							
							<div class="col-sm-7 form-group margin-top-10">
							  <input title="Save and Add More Open Houses" type="submit" class="btn btn-secondary" value="Save and Add More Open Houses" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
							</div>

										
						</div>
					</div>
					
					<div class="col-sm-12 clear appendajaxdata toppadding">
						<?php $this->load->view('user/listing_manager/listing_houses_ajax'); ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 pull-left text-center">
			
		  		<input type="hidden" id="tabid" name="tabid" value="8" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="8" />
                <input type="submit" name="savebtn" value="Save" id="savecontacttab2" onclick="on_submit(); setsubmitidtab2(1);" class="btn btn-secondary-green">
                <input type="submit" name="submitbtn" value="Save and Continue" onclick="on_submit(); setsubmitidtab2(2);" class="btn btn-secondary">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
 			</form>
         </div>
			  <!-------------------Open Houses Tab END----------------------------->


              <!-------------------Tab9=>Showings Tab------------------------------>
         	  <div <?php if($tabid == 9){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="showings">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="showings_id" name="showings_id" type="hidden" value="">
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
						<div class="">
							<div class="col-sm-10 form-group">
							<div class="col-sm-6 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_date');?></label>
							  <input id="txt_showings_date" name="txt_showings_date" aria-controls="DataTables_Table_0" class="form-control parsley-validated my_custom_date_class background_white" type="text" value="<?php if(!empty($editRecord[0]['open_house_date']) && $editRecord[0]['showings_date'] != '0000-00-00' && $editRecord[0]['showings_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['showings_date'])); }?>" readonly="true">
							</div>
                            
                            <div class="col-sm-6 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_time');?></label>
                              <input id="txt_showings_time" name="txt_showings_time" class="form-control parsley-validated my_readonly_bg_class background_white" type="text" value="<?php if(!empty($editRecord[0]['showings_time'])){ echo date("h:i A", strtotime($editRecord[0]['showings_time'])); }?>" readonly="readonly">
                              </div>
							</div>

							<div class="col-sm-10 form-group">
							<div class="col-sm-6 form-group">
							  <label for="text-input"><?=$this->lang->line('agent_rr_weightage_label_name');?></label>
							  <input id="txt_showings_agentname" name="txt_showings_agentname" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['showings_agent_name'])? htmlentities($editRecord[0]['showings_agent_name']):''?> ">
							</div>
                            
                            <div class="col-sm-6 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_agentid');?></label>
                              <input id="txt_showings_agentid" name="txt_showings_agentid" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['showings_agent_id'])?$editRecord[0]['showings_agent_id']:''?> ">
                              </div>
							</div>
							<div class="col-sm-10">
							<div class="col-sm-6 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_agentphone');?></label>
							  <input id="txt_showings_agentphone" name="txt_showings_agentphone"class="form-control parsley-validated mask_apply_class" type="text" value="<?=!empty($editRecord[0]['showings_agent_phone'])?$editRecord[0]['showings_agent_phone']:''?>">
							</div>
                            
                            <div class="col-sm-6 form-group">
                              <label for="text-input"><?=$this->lang->line('common_label_agentemail');?></label>
                              <input id="txt_showings_agentemail" name="txt_showings_agentemail" class="form-control parsley-validated" type="email" value="<?=!empty($editRecord[0]['showings_agent_email'])?$editRecord[0]['showings_agent_email']:''?>" data-parsley-type="email">
                              </div>
							</div>
							<div class="col-sm-10 form-group">
							<div class="col-sm-6 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_agentoffice');?></label>
							  <input id="txt_showings_agentoffice" name="txt_showings_agentoffice" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['showings_agent_office'])? htmlentities($editRecord[0]['showings_agent_office']):''?>">
							</div>
							</div>

                            <div class="col-sm-10 form-group">
							<div class="col-sm-6 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
							  <textarea id="txtarea_showings_notes" name="txtarea_showings_notes" class="form-control"><?php if(!empty($editRecord[0]['showings_notes'])){ echo htmlentities($editRecord[0]['showings_notes']); }?></textarea>
							</div>
							</div>
							
							<div class="col-sm-10 form-group margin-top-10">
							  <input title="Save and Add More Showings" type="submit" class="btn btn-secondary" value="Save and Add More Showings" onclick="on_submit(); return setsubmitidtab2(3);" id="submitbtn" name="submitbtn" />
							</div>
						</div>

					<div class="col-sm-12 clear appendajaxdata toppadding">
						<?php $this->load->view('user/listing_manager/listing_showings_ajax'); ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 pull-left text-center">
		  		<input type="hidden" id="tabid" name="tabid" value="9" />
                <input type="hidden" id="submitvaltab2" name="submitvaltab2" value="9" />
                <input type="submit" name="savebtn" value="Save" id="savecontacttab2" onclick="on_submit(); setsubmitidtab2(1);" class="btn btn-secondary-green">
                <input type="submit" name="submitbtn" value="Save and Continue" onclick="on_submit(); setsubmitidtab2(2);" class="btn btn-secondary">
                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
 			</form>
         </div>
			  <!-------------------Showings end END-------------------------------->


              <!-------------------Tab10=>contacts Tab----------------------------->
              <!--<div <?php if($tabid == '10'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="contacts">
             
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
                
              
              			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">

              	<div class="col-sm-6">
                      <div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Contacts Lead(s)</strong> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
                      <div class="col-sm-12 added_contacts_list">
                        
						<?php $this->load->view('user/listing_manager/selected_contact_ajax')?>
						
                      </div>
                    </div>
                
              <div class="col-sm-12 pull-left text-center margin-top-10">
              	<input type="hidden" id="tabid" name="tabid" value="10" />
                <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
				<input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />

                <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
              </form>
            </div>-->
			  <!-------------------contacts Tab END-------------------------------->


              <!-------------------Tab11=>Public Visibility Tab-------------------->
              <?php if(!empty($this->modules_unique_name) && in_array('public_visibility',$this->modules_unique_name)){?>
              <div <?php if($tabid == '11'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="Public_visibility">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
              	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
            <div class="row">
              <div class="col-lg-12"> <b>Public Visibility</b></div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-3">Visible to Public</div>
              <div class="col-lg-9 col-md-9">
                <div class="col-lg-2">
                  <input name="is_visible_to_public" type="radio" value="1"<?php if($editRecord[0]['is_visible_to_public']=='1'){ echo "checked=checked";} ?> />
                  &nbsp;Yes</div>
                <div class="col-lg-2">
                  <input name="is_visible_to_public" type="radio" value="2" <?php if($editRecord[0]['is_visible_to_public']=='2'){ echo "checked=checked";} ?> />
                  &nbsp; No</div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-3">Live link</div>
              <div class="col-lg-6 ">
			  <!--<input type="hidden" name="live_link" id="live_link" value="<?=!empty($editRecord[0]['live_link'])?$this->config->item('proprty_listing_base_path').$editRecord[0]['live_link']:''?>" />-->
			  <?php if(!empty($editRecord[0]['live_link'])){?>
              	<a href="<?php echo $this->config->item('proprty_listing_base_path').$editRecord[0]['live_link'];?>" target="_blank">
			  		<p class="test"><?php echo $this->config->item('proprty_listing_base_path').$editRecord[0]['live_link'];?></p>
                </a>
              <?php } ?>
			  <!--<input type="text" name="live_link" id="live_link" class="form-control parsley-validated" value="<?=!empty($editRecord[0]['live_link'])?$editRecord[0]['live_link']:''?>" />--></div>
            </div>
            <div class="row">
            <div class="col-lg-3 col-md-3"></div>
              <div class="col-lg-6">
                <a data-toggle="modal" class="text_color_red text_size" href="#property_basicModal" onclick="share_link()"><i class="fa fa-plus-square"></i> Share Link</a>
                </div>
            </div>
            
            <div class="row">
              <div class="col-lg-3 col-md-3">Google Analytics Tracking Code</div>
              <div class="col-lg-6 col-md-6">
                <textarea placeholder="Google Analytics Tracking Code" name="google_analytics_code" id="google_analytics_code" class="form-control parsley-validated addtextarea"><?=!empty($editRecord[0]['google_analytics_code'])?$editRecord[0]['google_analytics_code']:''?></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12"> <b>Select theme</b></div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4">
                <div class="themebox">
                  <h2>Theme1</h2>
                  <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>theme1.png" alt="img">
                  <div class="col-lg-12 mrg13photo text-center"> <!--<a class="btn btn-secondary" href="javascript:history.go(-1);">Preview</a>--> <a class="btn btn-secondary active_class_1 <?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 1) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('<?=$editRecord[0]['id']?>','1');"><?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 1) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <div class="themebox">
                  <h2>Theme2</h2>
                  <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>theme2.png" alt="img">
                  <div class="col-lg-12 mrg13photo text-center"> <!--<a class="btn btn-secondary" href="javascript:history.go(-1);">Preview</a>--> <a class="btn btn-secondary active_class_2 <?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 2) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('<?=$editRecord[0]['id']?>','2');"><?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 2) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <div class="themebox">
                  <h2>Theme3</h2>
                  <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>theme3.png" alt="img">
                  <div class="col-lg-12 mrg13photo text-center"> <!--<a class="btn btn-secondary" href="javascript:history.go(-1);">Preview</a>--> <a class="btn btn-secondary active_class_3 <?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 3) echo 'btn-warning'; ?>" href="javascript:void(0);" onclick="active_theme('<?=$editRecord[0]['id']?>','3');"><?php if(!empty($editRecord[0]['property_selected_theme']) && $editRecord[0]['property_selected_theme'] == 3) echo 'Activated'; else echo 'Activate'; ?></a> </div>
                </div>
              </div>
            </div>
            
            <div class="col-sm-12 pull-left text-center margin-top-10">
              <input type="hidden" id="tabid" name="tabid" value="11" />
                <input type="submit" name="savebtn" value="Save" class="btn btn-secondary-green" onclick="on_submit();">
                <input type="submit" name="submitbtn" value="Save and Continue" class="btn btn-secondary" onclick="on_submit();">
              <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
          </form>
          </div>
          	 <? } ?>
          	  <!-------------------Public Visibility Tab END----------------------->
              
              
              <!-------------------Tab12=>Flyers Tab------------------------------->	
              <?php if(!empty($this->modules_unique_name) && in_array('flyer',$this->modules_unique_name)){?>	
              <div <?php if($tabid == '12'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="flyers">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
          <div class="row">
            <div class="col-lg-12"> <b>Flyers:</b></div>
            <div class="col-lg-12"> <b> Select Theme</b></div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-4">
              <div class="themebox">
                <h2>Theme1</h2>
                <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>flyer1.png" alt="img">
                <div class="col-lg-12 mrg13photo text-center">
                <? if(!empty($editRecord[0]['live_link']))
				{ ?>
                <a href="<?php echo $this->config->item('proprty_listing_base_path').'flyer1/'.$editRecord[0]['live_link'];?>" class="btn btn-secondary">
			  		Download
                </a>
                <? } ?>
                 <!-- <input type="submit" onclick="checkcontactcount();" name="download" value="Download" class="btn btn-secondary">-->
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="themebox">
                <h2>Theme2</h2>
                <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>flyer2.png" alt="img">
                <div class="col-lg-12 mrg13photo text-center">
                <? if(!empty($editRecord[0]['live_link']))
				{ ?>
                 <a href="<?php echo $this->config->item('proprty_listing_base_path').'flyer2/'.$editRecord[0]['live_link'];?>" class="btn btn-secondary">
			  		Download
                </a>
                <? } ?>
                  <!--<input type="submit" onclick="checkcontactcount();" name="download" value="Download" class="btn btn-secondary">-->
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="themebox">
                <h2>Theme3</h2>
                <img class="img-responsive min_height" src="<?php echo $this->config->item('image_path');?>flyer3.png" alt="img">
                <div class="col-lg-12 mrg13photo text-center">
                <? if(!empty($editRecord[0]['live_link']))
				{ ?>
                 <a href="<?php echo $this->config->item('proprty_listing_base_path').'flyer3/'.$editRecord[0]['live_link'];?>" class="btn btn-secondary">
			  		Download
                </a>
                <? } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
            <input type="submit" onclick="checkcontactcount();" name="savebtn" value="Save" class="btn btn-secondary-green">
            <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
         </form>
        </div>
         		<? } ?>
        	  <!-------------------Flyers Tab END---------------------------------->
        
        <!-- End Premium Plan Tab --> 
      </div>
      <!-- /.table-responsive --> 
      
    </div>
  </div>
</div>
</div>
</div>
</div>

<script>
$(document).ready(function(){
	$('#features').live('click',function(){
		<?php if(!empty($mapaddress[0]['address'])){ ?>
			changeaddress('<?=$mapaddress[0]['address']?>');
		<?php }else{ ?>
			changeaddress('');
		<?php } ?>
	});

$('#price').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});

	
$('#txt_offer_price').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});

$('#txt_new_price').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});


$('.mask_apply_class').mask('999-999-9999');
	$("#div_msg").fadeOut(4000); 
    $("#div_msg1").fadeOut(4000);
	$("#txt_houses_time").attr("readonly","readonly");
	$("#txt_houses_end_time").attr("readonly","readonly");
	$(function() {
		$( "#listed_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false,
			//minDate: 0,
			onClose: function (date) {
				$('#listing_expire_date').datepicker('setDate', date);
				$('#listing_expire_date').datepicker('option', 'minDate', date);
				$('#listing_expire_date').focus();
			}
		});

		
		$( "#listing_expire_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		
		$( "#closed_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		
		$( "#pending_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});

		$( "#txt_offer_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});

		$( "#txt_price_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		$( "#txt_houses_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		$( "#txt_showings_date" ).datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'mm/dd/yy',
			buttonImageOnly: false
		});
		
		$("#txt_houses_time").timepicker({
			showNowButton: true,
			showDeselectButton: true,
			showPeriod: true,
			showLeadingZero: true,
			defaultTime: '',  // removes the highlighted time for when the input is empty.
			showCloseButton: true
		});
		$("#txt_houses_end_time").timepicker({
			showNowButton: true,
			showDeselectButton: true,
			showPeriod: true,
			showLeadingZero: true,
			defaultTime: '',  // removes the highlighted time for when the input is empty.
			showCloseButton: true
		});
		$("#txt_showings_time").timepicker({
			showNowButton: true,
			showDeselectButton: true,
			showPeriod: true,
			showLeadingZero: true,
			defaultTime: '',  // removes the highlighted time for when the input is empty.
			showCloseButton: true
		});
	});
});
    function load_view(id)
    {
        if(id == 'my_plan' || id == '1') {
            $('#selected_view').val('1');
            $("#premium_plan").hide();
        }
        else if(id == 'premium_plan' || id == '2') {
            $('#selected_view').val('2');
            $("#my_plan").hide();
        }
        
        var selected_view = $('#selected_view').val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:$('#selected_view').val()},
            success: function(html){
                if(selected_view == '2')
                {
                    $("#premium_plan").show();
                    $("#div_msg1").fadeOut(4000);
                }    
                else {
                    $("#my_plan").show();
                    $("#div_msg").fadeOut(4000); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }
    function active_plan(id,name)
    {      
        var boxes = $('input[name="check[]"]:checked');
        if(boxes.length == '0')
        {
        $.confirm({'title': 'Alert','message': " <strong> Please Select communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        //alert('Select Contacts');
        return false;}
        else
        {
            $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans();
            }},'No'	: {'class'	: 'special'}}});
        }
    } 
	 
	 function active_plan_single(name,id)
    {   
           $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to Archive "+id+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans(name);
            }},'No'	: {'class'	: 'special'}}});
    } 
    function active_all_plans(name)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check[]"]:checked');
        $(boxes).each(function(){
            myarray[i]=this.value;
            i++;
        });

        if(name != '0')
        {
            var single_active_id = name;
			//alert(single_active_id);
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_Inactive_all';?>",
            dataType: 'json',
            async: false,
            data: {'myarray':myarray,'single_active_id':name,selected_view:$('#selected_view').val()},
            success: function(data){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>user/interaction_plans/"+data,
                    data: {
                        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),selected_view:$('#selected_view').val()
                    },
                    beforeSend: function() {
                        $('#common_div').block({ message: 'Loading...' }); 
                    },
                    success: function(html){
                            $("#common_div").html(html);
                            $('#common_div').unblock(); 
                    }
                });
                return false;
            }
        });
    }

    function active_plan1(name)
    {      
        var boxes = $('input[name="check1[]"]:checked');
        if(boxes.length == '0')
        {
        $.confirm({'title': 'Alert','message': " <strong> Please select communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        //alert('Select Contacts');
        return false;}
        else
        {
            $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans1();
            }},'No'	: {'class'	: 'special'}}});
        }
    } 
	 function active_plan_single1(name,id)
    {   
           $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive "+id+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans1(name);
            }},'No'	: {'class'	: 'special'}}});
    } 
    function active_all_plans1(name)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check1[]"]:checked');
        $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
        });

        if(name != '0')
        {
                var single_active_id = name;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_Inactive_all';?>",
            dataType: 'json',
            async: false,
            data: {'myarray':myarray,'single_active_id':name,selected_view:$('#selected_view').val()},
            success: function(data){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>user/interaction_plans/"+data,
                    data: {
                        result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),selected_view:$('#selected_view').val()
                    },
                    beforeSend: function() {
                        $('#premium_common_div').block({ message: 'Loading...' }); 
                    },
                    success: function(html){
                        $("#premium_common_div").html(html);
                        $('#premium_common_div').unblock(); 
                    }   
                });
                return false;
            }
        });
    }
	
    function pubunpub_data(count1,id)
{
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('user_base_url').$viewname; ?>/publish_record/"+id;
		
	}
	else
	{
		url = "<?php echo  $this->config->item('user_base_url').$viewname; ?>/unpublish_record/"+id;
		
	}
	$.ajax({
			type: "POST",
			url :url,
			async: false,
			success: function(data){
			
				$("#view_archive_"+id).hide();
				
			
			}
	});
}

</script>
<script type="text/javascript">
	$(function(){
		var btnUpload1=$('#doc_file');
		new AjaxUpload(btnUpload1, {
			type: 'post',
			data:{},
			action: '<?=$this->config->item('user_base_url').$viewname."/upload_document";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(txt|doc|pdf|docx|csv|xls|xlsx)$/.test(ext))){ 
                    // extension is not allowed 
					$.confirm({'title': 'Alert','message': " <strong> You can upload only txt,doc,docx,pdf,csv,xls,xlsx. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;
				}
				
				$('#priview_doc').html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
			},
			onComplete: function(file, response){
			var data = jQuery.parseJSON(response);
				var result=data.document_name.split('-');
				if(result.length > 1)
				{
					var arrayindex = jQuery.inArray( result[0] , result );
					
					if(arrayindex >= 0)
					{
						result.splice( arrayindex, 1 );
					}
				}
				$('#priview_doc').text(result.join('-'));	
				$('#hiddenFiledoc').val(data.document_name);
			}
		});
		
	});
	
	var frm = $('#<?php echo $viewname;?>ajax');
    frm.submit(function (ev) {
	
		$("#savecontacttab2").hide();
	
		try{
		
			$.ajax({
					type: frm.attr('method'),
					url: frm.attr('action'),
					data: frm.serialize(),
					success: function (data) {
						
						$(".appendajaxdata").html(data);
						frm[0].reset();
						$("#priview_doc").text('');
						$("#hiddenFiledoc").val('');
						$("#hiddenFile").val('');
						
						var chkval = $('#submitvaltab2').val();
						var cid = $('#id').val();
						
						if(chkval == 1)
							window.location.href = '<?=base_url().'user/'.$viewname;?>';
						else if(chkval == 3)
							
							$(".toppadding").html(data);

						else
							window.location.href = '<?=base_url().'user/'.$viewname.'/edit_record/';?>'+cid+'/6';
						
					}
			});
			
		}
		catch(e){ alert('Something went wrong.Please try again.');window.location.reload();}
		
		$("#savecontacttab2").show();

        ev.preventDefault();
    });
	
	function editdoctransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_doc_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#doc_id").val(msg.id);
						$("#slt_doc_type").val(msg.document_type_id);
						$("#txt_doc_name").val(msg.doc_name);
						$("#txtarea_doc_desc").val(msg.doc_desc);
						$("#hiddenFiledoc").val(msg.doc_file);
						
						var result=msg.doc_file.split('-');
						if(result.length > 1)
						{
							var arrayindex = jQuery.inArray( result[0] , result );
							
							if(arrayindex >= 0)
							{
								result.splice( arrayindex, 1 );
							}
						}
						
						$("#priview_doc").text(result.join('-'));
					}
				}//succsess
			});//ajax
	}

	function editofferstransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_offers_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#offers_id").val(msg.id);
						$("#txt_offer_price").val(msg.offer_price);
						$("#slt_price_unit").val(msg.offer_price_unit_id);
						$("#txt_offer_date").val(msg.offer_date);
						$("#txt_offer_agent").val(msg.offer_agent_name);
						$("#txt_offer_phone").val(msg.offer_phone);
						$("#txtarea_offer_notes").val(msg.offer_notes);
					}
				}
			});
	}

	function editpricestransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_price_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#price_change_id").val(msg.id);
						$("#txt_new_price").val(msg.new_price);
						$("#slt_new_price_unit_id").val(msg.new_price_unit_id);
						$("#txt_price_date").val(msg.price_change_date);
						$("#txtarea_price_notes").val(msg.price_notes);
					}
				}
			});
	}

	function edithousesstransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_houses_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#Open_houses_id").val(msg.id);
						$("#txt_houses_date").val(msg.open_house_date);
						$("#txt_houses_time").val(msg.open_house_time);
						$("#txt_houses_end_time").val(msg.open_house_end_time);
						$("#txtarea_houses_notes").val(msg.open_house_notes);
					}
				}
			});
	}

	function editshowingsstransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('user_base_url').$viewname."/get_showings_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#showings_id").val(msg.id);
						$("#txt_showings_date").val(msg.showings_date);
						$("#txt_showings_time").val(msg.showings_time);
						$("#txt_showings_agentname").val(msg.showings_agent_name);
						$("#txt_showings_agentid").val(msg.showings_agent_id);
						$("#txt_showings_agentphone").val(msg.showings_agent_phone);
						$("#txt_showings_agentemail").val(msg.showings_agent_email);
						$("#txt_showings_agentoffice").val(msg.showings_agent_office);
						$("#txtarea_showings_notes").val(msg.showings_notes);
					}
				}
			});
	}


	function ajaxdeletetransdata(functionname,id)
	{	
		var id1 = $('#id').val();
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('user_base_url')?><?=$viewname;?>/'+functionname+'/'+id,
											data: {'id1':id1}, 
											success: function(msg1) 
											{
												if(id == $("#doc_id").val())
												{
													$("#doc_id").val('');
													$("#slt_doc_type").val('');
													$("#txt_doc_name").val('');
													$("#txtarea_doc_desc").val('');
												}
												else if(id == $("#offers_id").val())
												{
													$("#offers_id").val('');
													$("#txt_offer_price").val('');
													$("#slt_price_unit").val('');
													$("#txt_offer_date").val('');
													$("#txt_offer_agent").val('');
													$("#txt_offer_phone").val('');
													$("#txtarea_offer_notes").val('');
												}
												else if(id == $("#price_change_id").val())
												{
													$("#price_change_id").val('');
													$("#txt_price_date").val('');
													$("#txt_new_price").val('');
													$("#slt_new_price_unit_id").val('');
													$("#txtarea_price_notes").val('');
												}
												else if(id == $("#Open_houses_id").val())
												{
													$("#Open_houses_id").val('');
													$("#txt_houses_date").val('');
													$("#txt_houses_time").val('');
													$("#txt_houses_end_time").val('');
													$("#txtarea_houses_notes").val('');
												}
												else if(id == $("#showings_id").val())
												{
													$("#showings_id").val('');
													$("#txt_showings_date").val('');
													$("#txt_showings_time").val('');
													$("#txt_showings_agentname").val('');
													$("#txt_showings_agentid").val('');
													$("#txt_showings_agentphone").val('');
													$("#txt_showings_agentemail").val('');
													$("#txt_showings_agentoffice").val('');
													$("#txtarea_showings_notes").val('');
												}
												$('.'+functionname+id).remove();
												
											}
										});	
								
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
	}
	

</script>
<script type="text/javascript">
function IsNumeric(e)
{
	var charCode = (e.which) ? e.which : e.keyCode;
	if(charCode > 31 && charCode != 45 && charCode != 46 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}
</script>
<script type="text/javascript">
function showimagepreview(input) 
{
	var maximum = input.files[0].size/1024;
	if (input.files && input.files[0] && maximum <= 2048) 
	{
		var arr1 = input.files[0]['name'].split('.');
		var arr= arr1[1].toLowerCase();	
		if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'bmp' || arr == 'gif')
		{
			var filerdr = new FileReader();
			filerdr.onload = function(e) {
			$('#uploadPreview1').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}	
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Maximum upload size 2 MB "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
	}
}

function delete_image(name,divid)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete image?",'buttons': {'Yes': {'class': '',
'action': function(){
		var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:divid,name:name},
			url: '<?=$this->config->item('user_base_url').$viewname."/delete_image";?>',
			success:function(msg){
					if(msg == 'done')
					{
					$('.delete_div_'+divid).hide();
			      	$('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
				  }
				}
			});
			
			}},'No'	: {'class'	: 'special'}}});
	}
	
</script>
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Contact Select</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_search();">
                	<option value="">Contact Type</option>
                    <?php if(!empty($contact_type)){
                    		foreach($contact_type as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_search();">
                	<option value="">Contact Source</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_search();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag" class="form-control inputsrh pull-left" name="search_tag">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{onAdd: function (item) {
					contact_search();
				},onDelete: function (item) {
					contact_search();
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_to"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="">
		  <?php $this->load->view('user/listing_manager/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstolistingmanager();">Assign to Listing Manager</button>
        
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
});
	var arraydatacount = 0;
	var popupcontactlist = Array();
	
	$('body').on('click','#selecctall',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
		 
                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
			
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex >= 0)
				{
					popupcontactlist.splice( arrayindex, 1 );
					arraydatacount--;
				}
				
            });        
        }
		$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
    });
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),search_tag:$("#search_tag").val(),perpage:$("#perpage").val()
            },
			beforeSend: function() {
						$('.add_new_contact_popup').block({ message: 'Loading...' });
					  },
                success: function(html){
                   
                    $(".add_new_contact_popup").html(html);
					
					try
					{
						for(i=0;i<popupcontactlist.length;i++)
						{
							$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
						}
					}
					catch(e){}
					
					$('.add_new_contact_popup').unblock();
                }
            });
            return false;
        });

	function checkbox_checked(contact_id)
	{
		if($('.mycheckbox:checkbox[value='+parseInt(contact_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
	}

		
	$('#search_contact_popup_ajax').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search();
			}
	});
	
	$('body').on('click','.a_search_contacts',function(e){
		contact_search();
	});
	// view All Recored
	function clearfilter_contact()
	{
		$("#search_tag").tokenInput("clear");
		$("#search_contact_popup_ajax").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
		contact_search();
	}
	
	function changepages()
	{
		contact_search('');
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/listing_manager/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),search_tag:$("#search_tag").val(),perpage:$("#perpage").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup').unblock(); 
			}
		});
		return false;
	}
	function addcontactstolistingmanager()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/listing_manager/add_contacts_to_listing_manager/",
			data: {
			result_type:'ajax',contacts:popupcontactlist
		},
		beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
				  },
			success: function(html){
				
				$(".added_contacts_list").html(html);
				$('.added_contacts_list').unblock(); 
			}
		});
	}

	$('body').on('click','.remove_selected_contact',function(e){
		var myvalue = $(this).data("id");
		var property = $(this).data("group");
		var mytr = $(this).closest("tr");
			$.confirm({
		'title': 'Delete','message': " <strong> Are you sure want to remove contact from listing manager?",'buttons': {'Yes': {'class': '',
		'action': function(){
			
			var arrayindex = jQuery.inArray( myvalue, popupcontactlist );
			if(arrayindex >= 0)
			{
				$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
				mytr.remove();
			}
			$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
			
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('user_base_url').$viewname.'/delete_contact_from_listing_manager';?>",
				data: {'property_id':property,'contact_id':myvalue},
				success: function(html){
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
				
		}},'No'	: {'class'	: 'special'}}});
		
		
		
		return false;
	});
	$("#<?php echo $viewname;?>").submit(function(e) {
	  
			$('#finalcontactlist').val(popupcontactlist);
			return true;
	  
	});
	
<?php 
if(!empty($editRecord[0]['id']) && !empty($contacts_data)){
	foreach($contacts_data as $row){?>
	
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
			//alert(popupcontactlist);
		}
	
<?php }
}
?>
</script>
<script type="text/javascript">
function blurFunction()
{
	var address=$("#address_line_1").val()+',';
	address+=$("#address_line_2").val()+',';
	address+=$("#district").val()+',';
	address+=$("#city").val()+',';
	address+=$("#state").val()+',';
	address+=$("#zip_code").val()+',';
	address+=$("#country").val();
	
	changeaddress(address);
	
	$.ajax({
	type: "POST",
	url: "<?php echo $this->config->item('user_base_url').$viewname.'/getLatLong';?>",
	dataType: 'json',
	data: {'address':address},
	success: function(data)
	{
		if(data.msg == 'Access Denied')
		{
			alert('Something went wrong.');
		}
		else
		{
			$("#latitude").val(data.lat);
			$("#longitude").val(data.long);
		}
	}
});

}

$('#features').live('click',function(){
	<?php if(!empty($mapaddress[0]['address'])){ ?>
		changeaddress('<?=$mapaddress[0]['address']?>');
	<?php }else{ ?>
		changeaddress('');
	<?php } ?>
});

function active_theme(property_id,theme_id)
{
	$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/listing_manager/active_theme/",
			data: {
			result_type:'ajax',theme_id:theme_id,property_id:property_id
		},
		beforeSend: function() {
					//$('.added_contacts_list').block({ message: 'Loading...' }); 
					//$('.close_contact_select_popup').trigger('click');
				  },
			success: function(html){
				for(i=1;i<=3;i++)
				{
					if(i == theme_id)
					{
						$(".active_class_"+theme_id).html('Activated');
						$(".active_class_"+theme_id).addClass('btn-warning');
					}
					else
					{
						$(".active_class_"+i).html('Activate');
						$(".active_class_"+i).removeClass('btn-warning');
					}
				}
				//$(".added_contacts_list").html(html);
				//$('.added_contacts_list').unblock(); 
			}
		});
}

function property_name(name,title)
{
	$(".add_property_type").show();$(".property_insert_data").show();
	$(".share_link").hide();
	$(".popup_heading_h3").html("Add "+title);
	//$(".popup_heading_h3").html(title);
	$("#property_type_name").val(name);
	//alert($("#property_type_name").val());
}

function share_link()
{
	$(".property_insert_data").hide();
	$(".popup_heading_h3").html("Share Link");
	$(".add_property_type").hide();
	$(".share_link").show();	
}

function insert_data()
{
	//alert($("#property_type_name").val());
	var select_box = $("#property_type_name").val();
	var e = document.getElementById(select_box);
	var default_option = e.options[0].text;
	//alert(strUser);
	//alert(("#property_type option[0]").text());
	//alert($('#property_type option').eq(0).val().text);
	if($("#txt_name").val().trim() != '')
	{
		$.ajax({
				type: "POST",
				dataType: 'json',
				url: "<?php echo base_url();?>user/listing_manager/property_listing_master/",
				data: {
				result_type:'ajax',txt_name:$("#txt_name").val(),property_type_name:select_box
			},
			beforeSend: function() {
						//$('.added_contacts_list').block({ message: 'Loading...' }); 
						//$('.close_contact_select_popup').trigger('click');
					  },
				success: function(data){
					//$(".added_contacts_list").html(html);
					$("#txt_name").val('');
					if(data.property_listing_master.length != 0)
					{
						var myObject = eval(data.property_listing_master);
						var html = '<option value="">'+default_option+'</option>';
						for (var i=0;i< myObject.length;i++)
						{
							if(data.exist != 0 && data.exist == myObject[i].id)
								html += '<option value="'+myObject[i].id+'" selected="selected">'+myObject[i].name+'</option>';
							else if(i == 0)
								html += '<option value="'+myObject[i].id+'" selected="selected">'+myObject[i].name+'</option>';
							else
								html += '<option value="'+myObject[i].id+'">'+myObject[i].name+'</option>';
						} 
					}
					else
						var html = '<option value="">'+default_option+'</option>';
					$('#'+select_box).html(html);
					
					$("#property_type_name").val('');
					$('.close_contact_select_popup').trigger('click');
				}
			});
	}
	else
		$('.close_contact_select_popup').trigger('click');
}
function on_submit()
{
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
   		}		
}
function setmapshow()
{
	<?php if(!empty($mapaddress[0]['address'])){ ?>
		changeaddress('<?=$mapaddress[0]['address']?>');
	<?php }else{ ?>
		changeaddress('');
	<?php } ?>
}

$('body').on('click','.view_offer',function(e){
	var id = $(this).attr('data-id');
	var date = $('#offer_date_'+id).text();
	var price = $('#offer_price_'+id).text();
	var agent_name = $('#offer_agent_name_'+id).text();
	var phone = $('#offer_phone_'+id).text();
	var offer_note = $('#offer_note_'+id).text();
	
	var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Date </label></td><td> : </td><td align="left">'+date+'</td></tr><tr><td align="left"><label align="left" for="text-input">Price  </label></td><td> : </td><td align="left">'+price+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Name </label></td><td> : </td><td align="left">'+agent_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Phone </label></td><td> : </td><td align="left">'+phone+'</td></tr><tr><td align="left"><label align="left" for="text-input">Notes </label></td><td> : </td><td align="left">'+offer_note+'</td></tr></table>';
	//alert(form_data)
	$(".common_popup_heading").html('Offer');
	$(".common_tab").html(form_data);
});

$('body').on('click','.view_price',function(e){
	var id = $(this).attr('data-id');
	var date = $('#price_date_'+id).text();
	var price = $('#price_price_'+id).text();
	var offer_note = $('#price_note_'+id).text();

	var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Date </label></td><td> : </td><td align="left">'+date+'</td></tr><tr><td align="left"><label align="left" for="text-input">Price  </label></td><td> : </td><td align="left">'+price+'</td></tr><tr><td align="left"><label align="left" for="text-input">Notes </label></td><td> : </td><td align="left">'+offer_note+'</td></tr></table>';
	//alert(form_data)
	$(".common_popup_heading").html('Price Change');
	$(".common_tab").html(form_data);
});

$('body').on('click','.view_house',function(e){
	var id = $(this).attr('data-id');
	var date = $('#house_date_'+id).text();
	var time = $('#house_time_'+id).text();
	var note = $('#house_note_'+id).text();
	
	var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Date </label></td><td> : </td><td align="left">'+date+'</td></tr><tr><td align="left"><label align="left" for="text-input">Time  </label></td><td> : </td><td align="left">'+time+'</td></tr><tr><td align="left"><label align="left" for="text-input">Notes </label></td><td> : </td><td align="left">'+note+'</td></tr></table>';
	//alert(form_data)
	$(".common_popup_heading").html('Open House');
	$(".common_tab").html(form_data);
});

$('body').on('click','.view_showing',function(e){
	var id = $(this).attr('data-id');
	var date = $('#showing_date_'+id).text();
	var time = $('#showing_time_'+id).text();
	var agent_name = $('#showing_agent_name_'+id).text();
	var phone = $('#showing_phone_'+id).text();
	var agent_id = $('#showing_agent_id_'+id).text();
	var agent_email = $('#showing_agent_email_'+id).text();
	var agent_office = $('#showing_agent_office_'+id).text();
	var note = $('#showing_note_'+id).text();
	
	var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Date </label></td><td> : </td><td align="left">'+date+'</td></tr><tr><td align="left"><label align="left" for="text-input">Time  </label></td><td> : </td><td align="left">'+time+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Name </label></td><td> : </td><td align="left">'+agent_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Phone </label></td><td> : </td><td align="left">'+phone+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Id </label></td><td> : </td><td align="left">'+agent_id+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Email </label></td><td> : </td><td align="left">'+agent_email+'</td></tr><tr><td align="left"><label align="left" for="text-input">Agent Office </label></td><td> : </td><td align="left">'+agent_office+'</td></tr><tr><td align="left"><label align="left" for="text-input">Notes </label></td><td> : </td><td align="left">'+note+'</td></tr></table>';
	//alert(form_data)
	$(".common_popup_heading").html('Open House');
	$(".common_tab").html(form_data);
});

$('body').on('click','.view_document',function(e){
	var id = $(this).attr('data-id');
	var date = $('#document_date_'+id).text();
	var doc_type = $('#document_type_'+id).text();
	var doc_name = $('#document_doc_name_'+id).text();
	var doc_desc = $('#document_doc_desc_'+id).text();
	var doc_file = $('#document_doc_file_'+id).text();
	
	var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Date </label></td><td> : </td><td align="left">'+date+'</td></tr><tr><td align="left"><label align="left" for="text-input">Doc Type </label></td><td> : </td><td align="left">'+doc_type+'</td></tr><tr><td align="left"><label align="left" for="text-input">Doc Name </label></td><td> : </td><td align="left">'+doc_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Description</label></td><td> : </td><td align="left">'+doc_desc+'</td></tr><tr><td align="left"><label align="left" for="text-input">Document</label></td><td> : </td><td align="left">'+doc_file+'</td></tr></table>';
	//alert(form_data)
	$(".common_popup_heading").html('Documents');
	$(".common_tab").html(form_data);
});
</script>
