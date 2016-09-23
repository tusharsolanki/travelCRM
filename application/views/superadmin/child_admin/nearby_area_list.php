<?php
$viewname = $this->router->uri->segments[2];
$loadcontroller='insert_nearbyarea';
//isset($editRecord) ? $loadcontroller='update_nearbyarea' : $loadcontroller='insert_nearbyarea';
$path_area = $viewname."/".$loadcontroller;
?>
<form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="area_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_area?>" class="form parsley-form" data-validate="parsley"  novalidate>
    <div class="col-sm-12 col-lg-8">
        <div class="mrg-bottom-40">
            <?php $edit_id = !empty($editarea_id)?$editarea_id:0; ?>
            <input type="hidden" value="<?=$edit_id?>" name="edit_id" id="editarea_id">
            <input type="hidden" id="contacttab" name="contacttab" value="4" />
            <!--Not original but correct things-->
            <div width="100%" class="iconment_title_in row" >
                    <div class="col-sm-3"><label for="location_text"><?php echo $this->lang->line('nearbyplace_label_location_text')?></label></div>
                    <div class="col-sm-4"><label for="location_url"><?php echo $this->lang->line('nearbyplace_label_url')?></label></div>
                    <div class="col-sm-3"><label for="order_of_display"><?php echo $this->lang->line('nearbyplace_label_order_of_display')?></label></div>
                    <div class="col-sm-2"><label for="action"><?php echo $this->lang->line('common_label_action')?></label></div>
                <?php
                if(!empty($nearby_arealist) && count($nearby_arealist)>0){
                    foreach($nearby_arealist as $row)
                    {   
                    ?>
                        <div colspan="4">
                            <div class="space"></div>
                            <div id="flash"></div>
                            <div id="show"></div>
                        </div>
                        <div class="form-group nearby_close_<?=$row['id']?> row">
                            <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="location_text_update[]" id="loc_<?=$row['id']?>" value="<?php echo  htmlentities($row['location_text']) ?>" data-required="true" placeholder="e.g. New York"/></div>
                            <div class="col-sm-4"><input type="text" class="form-control parsley-validated" name="location_url_update[]" id="url_<?=$row['id']?>" value="<?php echo  htmlentities($row['location_url']) ?>" data-required="true" data-type="map_url" data-parsley-type="map_url"  placeholder="e.g. http://xyz.com"/></div>
                            <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="order_update[]" id="order_<?=$row['id']?>" value="<?php echo  htmlentities($row['order_of_display']) ?>" onkeypress="return isNumberKey(event);" placeholder="e.g. 1"/></div>
                            <input type="hidden" class="form-control parsley-validated" name="location_idd[]" id="location_idd" value="<?php echo  $row['id'] ?>"/>
                            <input type="hidden" class="form-control parsley-validated" name="child_record_id[]" id="child_record_id_<?=$row['id']?>" value="<?php echo  $row['child_record_id'] ?>"/>
                            <div class="col-sm-2">
                                <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:void(0);" class="btn btn-xs btn-primary remove_selected_area_<?=$row['id']?>" onclick="deletepopup('<?=$row['id']?>','<?=rawurlencode(ucfirst(strtolower($row['location_text'])))?>','<?=$row['child_record_id']?>');"> <i class="fa fa-times"></i> </a>
                            </div>
                        </div>
                    <?php }
                }?>
            </div>
            <div>
                <div id="p_scents" class="form-group row">
                    <?php if(empty($nearby_arealist) || count($nearby_arealist) == 0) {?>
                            <div class="form-group row">
                                <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="location_text[0]" id="location_text[0]" placeholder="e.g. New York"/></div>
                                <div class="col-sm-4"><input type="text" class="form-control parsley-validated" name="location_url[0]" id="location_url[0]" data-type="map_url" data-parsley-type="map_url"  placeholder="e.g. http://xyz.com" /></div>
                                <div class="col-sm-3"><input type="text" class="form-control parsley-validated" name="order_of_display[0]" id="order_of_display[0]" onkeypress="return isNumberKey(event);" placeholder="e.g. 1"/></div>
                            </div>
                    <?php } ?>
                </div>
                
                    <div>
                        <a href="javascript:void(0);" id="addarea" title="Add Area" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add More</a>
                    </div>
                    <div>&nbsp;</div>
                    <div>
                        <div class="col-sm-12 pull-left text-center margin-top-10">
                            <input type="hidden" id="contacttab" name="contacttab" value="4" />
                            <input type="submit" title="Save" class="btn btn-secondary-green" value="Save" id="submit" name="submitbtn" onclick="return showloading('area_form_<?php echo $viewname;?>');" />
                            <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" name="submitbtn" onclick="return showloading('area_form_<?php echo $viewname;?>');"/>
                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                        </div>
                    </div>
                    <div>&nbsp;</div>
            </div>

        </div>
    </div>
</form>