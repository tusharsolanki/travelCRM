<?php
/*
    @Description: Saved Searches Add/Edit
    @Author     : Sanjay Moghariya
    @Date       : 29-12-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_saved_search_data':'insert_saved_search_data'; 
if(isset($insert_data))
{
    $formAction ='insert_saved_search_data'; 
}
$path = $viewname.'/'.$formAction;
?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('saved_searches_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php 
                            if(!empty($editRecord)){ echo $this->lang->line('saved_searches_edit_header'); } 
                            else{ echo $this->lang->line('saved_searches_add_head'); }?> </h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" title="Back" onclick="history.go(-1)" class="btn btn-secondary" id="back">Back</a> </span>
                    </div>
    
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">

                                <div class="row tab-pane fade in active" id="home">

                                  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" data-validate="parsley" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate>
                                        <?php/*
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <input id="sel_contact_id" name="sel_contact_id" type="hidden" value="<?php if(!empty($sel_contact_id)){ echo $sel_contact_id; }?>">
                                        <input id="joomla_user_id" name="joomla_user_id" type="hidden" value="<?php if(!empty($joomla_user_id)){ echo $joomla_user_id; } else if(!empty($editRecord[0]['uid'])) { echo $editRecord[0]['uid'];} else { echo 0;}?>">
                                        <input id="edit_domain" name="joomla_domain" type="hidden" value="<?php if(!empty($editRecord[0]['domain'])){ echo $editRecord[0]['domain']; } else if(!empty($domain)) { echo $domain;}?>">
                                        <input id="joomla_sid" name="joomla_sid" type="hidden" value="<?php if(!empty($editRecord[0]['sid'])){ echo $editRecord[0]['sid']; } ?>">
                                        <input id="search_url" name="search_url" type="hidden" value="">
                                        */ ?>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <input type="hidden" name="search_category" id="search_category" value="<?php if(!empty($editRecord[0]['search_category'])){ echo $editRecord[0]['search_category'];}?>">
                                                    <label for="text-input"><?=$this->lang->line('saved_search_label_name');?><span class="val">*</span></label>
                                                    <input id="txt_name" name="txt_name" placeholder="Name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['name'])){ echo $editRecord[0]['name'];}?>" data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('search_criteria_label');?></label>
                                                    <input id="searchc" name="search" placeholder="Type any City Area, Address, ZIP, School, etc." class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['search_criteria'])){ echo $editRecord[0]['search_criteria'];}?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_min_price');?></label>
                                                <select class="form-control parsley-validated" name="min_price" id="min_price">
                                                    <option selected="selected" value="">Minimum Price</option>
                                                    <option value="">No Min</option>
                                                    <?php $i = 50000;
                                                    while ($i < 5000000) {
                                                      ?>
                                                      <option value="<?= number_format($i) ?>" <?php if (!empty($editRecord[0]['min_price']) && $editRecord[0]['min_price'] == $i) echo "selected=selected"; ?> > $<?= number_format($i) ?> </option>
                                                      <?php
                                                      if (100000 > $i)
                                                        $i = $i + 10000;
                                                      else
                                                        $i = $i + 25000;
                                                    }
                                                    ?>
                                                    <option value="5000000" <?php if (!empty($editRecord[0]['min_price']) && $editRecord[0]['min_price'] == 5000000) echo "selected=selected"; ?> > $5,000,000 </option>
                                                </select>
                                                <?php /*<input id="min_price" name="min_price" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['min_price'])){ echo $editRecord[0]['min_price']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_max_price');?></label>
                                                <select class="form-control parsley-validated" name="max_price" id="max_price">
                                                    <option value="" selected="selected">Maximum Price</option>
                                                    <option value="0">No Max</option>
                                                    <?php $i = 50000;
                                                    while ($i < 5000000) {
                                                      ?>
                                                      <option value="<?= number_format($i) ?>" <?php if (!empty($editRecord[0]['max_price']) && $editRecord[0]['max_price'] == $i) echo "selected=selected"; ?> > $<?= number_format($i) ?> </option>
                                                      <?php
                                                      if (100000 > $i)
                                                        $i = $i + 10000;
                                                      else
                                                        $i = $i + 25000;
                                                    }
                                                    ?>
                                                    <option value="5000000" <?php if (!empty($editRecord[0]['max_price']) && $editRecord[0]['max_price'] == 5000000) echo "selected=selected"; ?> > $5,000,000 </option>
                                                </select>
                                                <?php /*<input id="max_price" name="max_price" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['max_price'])){ echo $editRecord[0]['max_price']; }?>">*/?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_bedroom');?></label>
                                                <select class="form-control parsley-validated" name="beds" id="beds">
                                                    <option value=""> Beds </option>
                                                    <option value=""> Any </option>
                                                    <?php for ($i = 2; $i <= 5; $i++) { ?>
                                                        <option value="<?= $i ?>" <?php if (!empty($editRecord[0]['bedroom']) && $editRecord[0]['bedroom'] == $i) echo "selected=selected"; ?> > <?= $i ?>+ </option>
                                                    <?php } ?>
                                                </select>
                                                <?php /*<input id="bedroom" name="bedroom" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['bedroom'])){ echo $editRecord[0]['bedroom']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_bathroom');?></label>
                                                <select class="form-control parsley-validated" name="baths" id="baths">
                                                    <option value=""> Baths </option>
                                                    <option value=""> Any </option>
                                                    <?php for ($i = 2; $i <= 5; $i++) { ?>
                                                    <option value="<?= $i ?>" <?php if (!empty($editRecord[0]['bathroom']) && $editRecord[0]['bathroom'] == $i) echo "selected=selected"; ?> > <?= $i ?>+ </option>
                                                    <?php } ?>
                                                </select>
                                                <?php /*<input id="bathroom" name="bathroom" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['bathroom'])){ echo $editRecord[0]['bathroom']; }?>">*/?>
                                            </div>
                                            
                                            <?php /*<div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_min_area');?></label>
                                                <input id="min_area" name="min_area" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['min_area'])){ echo $editRecord[0]['min_area']; }?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_max_area');?></label>
                                                <input id="max_area" name="max_area" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['max_area'])){ echo $editRecord[0]['max_area']; }?>">
                                            </div>
                                            */ ?>
                                            
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_year_built');?></label>
                                                <select class="form-control parsley-validated" name="year_built" id="year_built">
                                                    <option value=""> Year Built </option>
                                                    <?php
                                                    for ($i = date('Y'); $i >= 1900 ; $i--) {
                                                      ?>
                                                      <option value="<?=$i?>" <?php if (!empty($editRecord[0]['min_year_built']) && $i == $editRecord[0]['min_year_built']) echo "selected=selected"; ?>> <?=$i?></option>
                                                      <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php /*
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_min_year_built');?></label>
                                                <input id="min_year_built" name="min_year_built" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['min_year_built'])){ echo $editRecord[0]['min_year_built']; }?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_max_year_built');?></label>
                                                <input id="max_year_built" name="max_year_built" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['max_year_built'])){ echo $editRecord[0]['max_year_built']; }?>">
                                            </div>
                                             */?>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_fireplaces_total');?></label>
                                                <select class="form-control parsley-validated" name="fireplaces" id="fireplaces">
                                                    <option value=""> Fireplaces Total </option>
                                                    <?php
                                                    for($i=0;$i<=5;$i++) {
                                                      ?>
                                                      <option value="<?=$i?>" <?php if (!empty($editRecord[0]['fireplaces_total']) && $i == $editRecord[0]['fireplaces_total']) echo "selected=selected"; ?>> <?=$i?>+</option>
                                                      <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="fireplaces_total" name="fireplaces_total" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['fireplaces_total'])){ echo $editRecord[0]['fireplaces_total']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_lotsize');?></label>
                                                <select class="form-control parsley-validated" name="lot_size" id="lot_size">
                                                    <option value=""> Lot Size </option>
                                                    <?php $i = 1000;
                                                    while ($i <= 19000) {
                                                      ?>
                                                      <option value="<?=$i?>" <?php if (!empty($editRecord[0]['min_lotsize']) && $editRecord[0]['min_lotsize'] == $i) echo "selected=selected"; ?> > <?= number_format($i) ?>+ </option>
                                                      <?php
                                                      $i = $i + 3000;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php /*<div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_min_lotsize');?></label>
                                                <input id="min_lotsize" name="min_lotsize" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['min_lotsize'])){ echo $editRecord[0]['min_lotsize']; }?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_max_lotsize');?></label>
                                                <input id="max_lotsize" name="max_lotsize" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['max_lotsize'])){ echo $editRecord[0]['max_lotsize']; }?>">
                                            </div>
                                             */?>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('common_label_property_type');?></label>
                                                <select class="form-control parsley-validated" name="type" id="type" onchange="fields_hide_show()">
                                                    <option value=""> Property Type </option>
                                                    <?php
                                                    if (!empty($property_type)) {
                                                        foreach ($property_type as $row) {
                                                          ?>
                                                          <option value="<?=$row['name']?>" <?php if ((!empty($editRecord[0]['property_type']) && $row['name'] == $editRecord[0]['property_type'])) echo "selected=selected"; ?>> <?=$row['comment']?></option>
                                                          <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_garage_spaces');?></label>
                                                <select class="form-control parsley-validated" name="garage_spaces" id="garage_spaces">
                                                    <option value=""> Garage Spaces </option>
                                                    <?php
                                                    for($i=0;$i<=5;$i++) {
                                                      ?>
                                                      <option value="<?=$i?>" <?php if (!empty($editRecord[0]['garage_spaces']) && $i == $editRecord[0]['garage_spaces']) echo "selected=selected"; ?>> <?=$i?>+</option>
                                                      <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="garage_spaces" name="garage_spaces" maxlength="4" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['garage_spaces'])){ echo $editRecord[0]['garage_spaces']; }?>">*/ ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_architecture');?></label>
                                                <select class="form-control parsley-validated" name="architecture" id="architecture">
                                                    <option value=""> Architecture </option>
                                                    <?php
                                                    if (!empty($property_architecture)) {
                                                        foreach ($property_architecture as $row) {
                                                          ?>
                                                          <option value="<?=$row['value_code']?>" <?php if (!empty($editRecord[0]['architecture']) && $row['value_code'] == $editRecord[0]['architecture']) echo "selected=selected"; ?>> <?=$row['value_description']?></option>
                                                          <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="architecture" name="architecture" readonly="readonly" class="form-control parsley-validated prz" type="text" value="<?php if(!empty($editRecord[0]['architecture'])){ echo $editRecord[0]['architecture']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_school_district');?></label>
                                                
                                                <select class="form-control parsley-validated" name="school_district" id="school_district">
                                                    <option value=""> School District </option>
                                                    <?php
                                                    if (!empty($school_district)) {
                                                      foreach ($school_district as $row) {
                                                        ?>
                                                        <option value="<?=$row['school_district_code']?>" <?php if (!empty($editRecord[0]['school_district']) && $row['school_district_code'] == $editRecord[0]['school_district']) echo "selected=selected"; ?>> <?=$row['school_district_description']?></option>
                                                        <?php
                                                      }
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="school_district" name="school_district" readonly="readonly" class="form-control parsley-validated prz" type="text" value="<?php if(!empty($editRecord[0]['school_district'])){ echo $editRecord[0]['school_district']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_waterfront');?></label>
                                                <select class="form-control parsley-validated" name="waterfront[]" id="waterfront" multiple="multiple">
                                                    <?php
                                                    if(!empty($editRecord[0]['waterfront']))
                                                        $wfront = explode('{^}',$editRecord[0]['waterfront']);
                                                    if (!empty($waterfront)) {
                                                      foreach ($waterfront as $row) {
                                                        ?>
                                                        <option <?php if(!empty($editRecord[0]['waterfront']) && is_array($wfront) && in_array($row['value_code'],$wfront)) echo 'selected=selected'; ?>value="<?=$row['value_code']?>"> <?=$row['value_description']?></option>
                                                        <?php
                                                      }
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="waterfront" name="waterfront" readonly="readonly" class="form-control parsley-validated prz" type="text" value="<?php if(!empty($editRecord[0]['waterfront'])){ echo $editRecord[0]['waterfront']; }?>">*/ ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_s_view');?></label>
                                                <select class="form-control parsley-validated" name="property_views[]" id="property_views" multiple="multiple">
                                                    <?php
                                                    if(!empty($editRecord[0]['s_view']))
                                                        $sview = explode('{^}',$editRecord[0]['s_view']);
                                                    if (!empty($property_views)) {
                                                      foreach ($property_views as $row) {
                                                        ?>
                                                        <option <?php if(!empty($editRecord[0]['s_view']) && is_array($sview) && in_array($row['value_code'],$sview)) echo 'selected=selected'; ?>value="<?=$row['value_code']?>"> <?=$row['value_description']?></option>
                                                        <?php
                                                      }
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="s_view" name="s_view" readonly="readonly" class="form-control parsley-validated prz" type="text" value="<?php if(!empty($editRecord[0]['s_view'])){ echo $editRecord[0]['s_view']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_parking_type');?></label>
                                                <select class="form-control parsley-validated" name="parking_type" id="parking_type">
                                                    <option value=""> Parking Type </option>
                                                    <?php
                                                    if (!empty($parking_type)) {
                                                        foreach ($parking_type as $row) {
                                                        ?>
                                                            <option value="<?=$row['value_code']?>" <?php if (!empty($editRecord[0]['parking_type']['parking_type']) && $row['value_code'] == $editRecord[0]['parking_type']['parking_type']) echo "selected=selected"; ?>> <?=$row['value_description']?></option>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <?php /*<input id="parking_type" name="parking_type" readonly="readonly" class="form-control parsley-validated prz" type="text" value="<?php if(!empty($editRecord[0]['parking_type'])){ echo $editRecord[0]['parking_type']; }?>">*/?>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('common_label_city');?></label>
                                                <select class="form-control parsley-validated" name="city[]" id="city" multiple="multiple">
                                                    <?php
                                                    if(!empty($editRecord[0]['city']))
                                                        $city = explode('{^}',$editRecord[0]['city']);
                                                    if (!empty($citylist)) {
                                                      foreach ($citylist as $row) {
                                                        ?>
                                                        <option <?php if(!empty($editRecord[0]['city']) && is_array($city) && in_array($row['CIT'],$city)) echo 'selected=selected'; ?>value="<?=$row['CIT']?>"> <?=$row['CIT']?></option>
                                                        <?php
                                                      }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('saved_search_new_construction');?></label>
                                                <select class="form-control parsley-validated" name="new_construction" id="new_construction">
                                                    <option value=""> New Construction </option>
                                                    <option value="Y" <?php if (!empty($editRecord[0]['new_construction']) && $editRecord[0]['new_construction'] == 'Y') echo "selected=selected"; ?>>Yes</option> 
                                                    <option value="N" <?php if (!empty($editRecord[0]['new_construction']) && $editRecord[0]['new_construction'] == 'N') echo "selected=selected"; ?>>No</option> 
                                                    <?php /*
                                                    <option value=""> Construction </option>
                                                    <?php
                                                    if (!empty($new_construction)) {
                                                        foreach($new_construction as $row) {
                                                    ?>
                                                            <option value="<?=$row['value_code']?>" <?php if (!empty($editRecord[0]['new_construction']) && $row['value_code'] == $editRecord[0]['new_construction']) echo "selected=selected"; ?>> <?=$row['value_description']?></option>
                                                    <?php
                                                        }
                                                    }*/
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-multi-input"><?=$this->lang->line('common_label_istatus');?></label>
                                                <select class="form-control parsley-validated" name="property_status" id="property_status">
                                                    <option value="Active" <?php if (!empty($editRecord[0]['property_status']) && $editRecord[0]['property_status'] == 'Active') echo "selected=selected"; ?> > Active </option>
                                                    <option value="Pending" <?php if (!empty($editRecord[0]['property_status']) && $editRecord[0]['property_status'] == 'Pending') echo "selected=selected"; ?> > Pending </option>
                                                    <option value="Sold" <?php if (!empty($editRecord[0]['property_status']) && $editRecord[0]['property_status'] == 'Sold') echo "selected=selected"; ?> > Sold </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="text-input"><?=$this->lang->line('saved_search_bank_owned')?></label>
                                                <label class="lblwidthac"><input type="radio" name="bank_owned" <?php if(!empty($editRecord[0]['bank_owned']) && $editRecord[0]['bank_owned'] == 'Y') echo "checked='checked'";?> value="Y">Yes</label>
                                                <label class="lblwidthac"><input type="radio" name="bank_owned" <?php if(!empty($editRecord[0]['bank_owned']) && $editRecord[0]['bank_owned'] == 'N') echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="N">No</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="text-input"><?=$this->lang->line('saved_search_short_sale')?></label>
                                                <label class="lblwidthac"><input type="radio" name="short_sale" <?php if(!empty($editRecord[0]['short_sale']) && $editRecord[0]['short_sale'] == 'Y') echo "checked='checked'";?> value="Y">Yes</label>
                                                <label class="lblwidthac"><input type="radio" name="short_sale" <?php if(!empty($editRecord[0]['short_sale']) && $editRecord[0]['short_sale'] == 'N') echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="N">No</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="text-input"><?=$this->lang->line('saved_search_cdom')?></label>
                                                <input id="CDOM" name="CDOM" placeholder="New in the last #days" class="form-control parsley-validated" type="text" maxlength="4" value="<?php if(!empty($editRecord[0]['CDOM'])){ echo $editRecord[0]['CDOM'];}?>" onkeypress="return isNumberKey(event)">
                                            </div>
                                            <div class="form-group">
                                                <label for="text-input">#<?=$this->lang->line('mls_list')?></label>
                                                <input id="mls_id" name="mls_id" placeholder="#MLS" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['mls_id'])){ echo $editRecord[0]['mls_id'];}?>" onkeypress="return isNumberKey(event)">
                                            </div>
                                            
                                            <?php /*
                                            <div class="form-group">
                                                <label for="select-multi-input">
                                                  <?=$this->lang->line('search_criteria_label');?>
                                                </label>
                                                <textarea name="search_criteria" id="search_criteria" placeholder="Search Criteria" class="form-control parsley-validated"  ><?=!empty($editRecord[0]['search_criteria'])?$editRecord[0]['search_criteria']:'';?></textarea>
                                            </div>
                                            */?>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="submit" class="btn btn-secondary" title="Save" value="Save" id="ass_submitbtn" name="submitbtn" onclick="return get_submit();" />
                                            <a class="btn btn-primary" id="ass_cancel" href="javascript:history.go(-1);" title="Cancel">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
  </div>
  
 </div>
 
<script type="text/javascript">
    $(document).ready(function() {
	$("select#city").multiselect({
            header: true,
            noneSelectedText: "Select City",
            selectedList: 0
        }).multiselectfilter();
        $("select#property_views").multiselect({
            header: true,
            noneSelectedText: "Views",
            selectedList: 0
        }).multiselectfilter();
        $("select#waterfront").multiselect({
            header: true,
            noneSelectedText: "Waterfront",
            selectedList: 0
        }).multiselectfilter();
	<?php if (!empty($editRecord[0]['type'])) { ?>
            fields_hide_show();
        <?php } ?>
    });
    
    /*
        @Description: Function for hide or show garage space fields
        @Author: Sanjay Chabhadiya
        @Input: - 
        @Output: - Property name
        @Date: 27-05-2015
     */
    
    function fields_hide_show()
    {
        var property_type = $("#type").val();
        if(property_type == 'COND')
        {
            $("#garage_spaces").parent('div').hide();
            $("#garage_spaces").val('');
        }
        else
            $("#garage_spaces").parent('div').show();
    }
    
    /*
        @Description: Logic for group by autosuggest
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Suggestion list
        @Date       : 01-07-2015
    */
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _create: function() {
            this._super();
            this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
        },
        _renderMenu: function( ul, items ) {
            var that = this,
            currentCategory = "";
            $.each( items, function( index, item ) {
                var li;
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                li = that._renderItemData( ul, item );
                if ( item.category ) {
                    li.attr( "aria-label", item.category + " : " + item.label );
                }
            });
        }
    });
  
    $(function() {
        $('#search_category').val('<?=!empty($editRecord[0]['search_category'])?$editRecord[0]['search_category']:''?>');
        $( "#searchc" ).catcomplete({
            delay: 0,
            source: '<?=$this->config->item('user_base_url')?>contacts/get_property/<?=!empty($mls_id)?$mls_id:0?>',
            change: function( event, ui ) {
                if (ui.item) {
                    var sval = ui.item.label;
                    var scat = ui.item.category;
                    var svalue = ui.item.group_value;
                    $('#search_category').val(scat);

                    if(scat == 'MLS#') {
                        $('#mls_id').val(sval);
                    }
                    else if(scat == 'School District') {
                        $('#school_district').val(svalue);
                    } else if(scat == 'City') {
                        $("#city").val(sval);
                        jq("select#city").multiselect('refresh').multiselectfilter();
                    }
                } else {
                    $('#search_category').val('');
                }
            }
        });
    });
    /*
        @ End Logic for group autosuggest
    */
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function get_submit()
    {
        if ($('#<?php echo $viewname?>').parsley().isValid()) {
            //var url = 'property/search?' + $("#<?=$viewname?>").serialize();
            var url = $("#<?= $viewname ?>").find('input, select').not('[value=""]').serialize();
            
            var html = '<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">';
            html += '<input id="sel_contact_id" name="sel_contact_id" type="hidden" value="<?php if(!empty($sel_contact_id)){ echo $sel_contact_id; }?>">';
            html += '<input id="joomla_user_id" name="joomla_user_id" type="hidden" value="<?php if(!empty($joomla_user_id)){ echo $joomla_user_id; } else if(!empty($editRecord[0]['uid'])) { echo $editRecord[0]['uid'];} else { echo 0;}?>">';
            html += '<input id="edit_domain" name="joomla_domain" type="hidden" value="<?php if(!empty($editRecord[0]['domain'])){ echo $editRecord[0]['domain']; } else if(!empty($domain)) { echo $domain;}?>">';
            html += '<input id="joomla_sid" name="joomla_sid" type="hidden" value="<?php if(!empty($editRecord[0]['sid'])){ echo $editRecord[0]['sid']; } ?>">';
            html += '<input id="old_url" name="old_url" type="hidden" value="<?php if(!empty($editRecord[0]['url'])){ echo $editRecord[0]['url']; } ?>">';
            html += '<input id="search_url" name="search_url" type="hidden" value="'+url+'">';
            $('#<?php echo $viewname?>').append(html);
            
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            return true;
        }
    }
    $(function () {
        var all_values = [];
        var initial_options = $('#min_price').get(0).options;
        for (i = 0; i < initial_options.length; i++)
        {
            var val = initial_options[i].value;
            var lbl = initial_options[i].label;
            all_values[i] = {value: val, label: lbl};

        }

        document.getElementById('min_price').options;

        $('#min_price').change(function () {
            var $src = $('#min_price');
            var $target = $('#max_price');
            var prev_max_value = $target.val();
            var current_min_value = $src.val();
            if (!current_min_value)
            {
                $target.get(0).options[0] = new Option("Maximum Price", "");
                $target.get(0).options[1] = new Option("No Max", "0");
                for (i = 2; i < all_values.length; i++)
                {
                    $target.get(0).options[i] = new Option(all_values[i].label, all_values[i].value);
                }
            }
            else
            {
                //clear max drop down list
                $target.get(0).options.length = 0;
                $target.get(0).options[0] = new Option("Maximum Price", "");
                $target.get(0).options[1] = new Option("No Max", "0");
                var j = 2;
                for (i = $src.get(0).selectedIndex; i < all_values.length; i++)
                {
                    $target.get(0).options[j++] = new Option(all_values[i].label, all_values[i].value);
                }
            }
            $target.val(prev_max_value);
        });
    });

    $(document).ready(function () {        
        //search parsley validation
        var all_values = [];
        var initial_options = $('#min_price').get(0).options;
        for (i = 0; i < initial_options.length; i++)
        {
            var val = initial_options[i].value;
            var lbl = initial_options[i].label;
            all_values[i] = {value: val, label: lbl};

        }

        document.getElementById('min_price').options;

            var $src = $('#min_price');
            var $target = $('#max_price');
            var prev_max_value = $target.val();
            var current_min_value = $src.val();
            if (!current_min_value)
            { }
            else
            {
                //clear max drop down list
                $target.get(0).options.length = 0;
                $target.get(0).options[0] = new Option("Maximum Price", "");
                $target.get(0).options[1] = new Option("No Max", "0");
                var j = 2;
                for (i = $src.get(0).selectedIndex; i < all_values.length; i++)
                {
                    $target.get(0).options[j++] = new Option(all_values[i].label, all_values[i].value);
                }
            }
            $target.val(prev_max_value);
    });
</script>