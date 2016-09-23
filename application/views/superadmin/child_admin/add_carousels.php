<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
    $tabid = $this->router->uri->segments[5];
else
    $tabid = 1;
	
$formAction = !empty($editRecord)?'insert_carousels':'insert_carousels'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$viewname = 'child_admin';
$path = $viewname.'/'.$formAction;

//pr($editRecord1);exit;

?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<div id="content">
    <div id="content-header">
        <h1>
            <?=$this->lang->line('label_carousels_add');?>
        </h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i>
                          <?php if(empty($editRecord)){ echo $this->lang->line('label_carousels_add');}
                            else if(!empty($insert_data)){ echo $this->lang->line('label_carousels_add'); } 
                            else{ echo $this->lang->line('label_carousels_edit'); }?>
                        </h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
                    </div>
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="carousels" > 
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="carousels_<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate onkeypress="return event.keyCode != 13;">
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_title')?>  <span class="val">*</span></label>
                                                    <input type="text" id="carousels_name" name="carousels_name" class="form-control parsley-validated"  data-required="true" value="<?php if(!empty($editRecord[0]['carousels_name'])){ echo $editRecord[0]['carousels_name'];}?>" placeholder="Title"><!-- onblur="check_carousels_name();">-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_order')?></label>
                                                    <input type="text" id="order_of_position" name="order_of_position" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['order_of_position'])){ echo $editRecord[0]['order_of_position'];} else { echo ''; }?>" placeholder="Order of Position" maxlength="4" onkeypress="return isNumberKey(event);">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_istatus')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="status" <?php if(!empty($editRecord) && $editRecord[0]['status'] == 1) echo "checked='checked'";?> value="1"><?=$this->lang->line('common_label_publish')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="status" <?php if(!empty($editRecord) && $editRecord[0]['status'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0"><?=$this->lang->line('common_label_unpublish')?></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_no_of_properties')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="no_of_properties" <?php if(!empty($editRecord) && $editRecord[0]['carousels_type'] == 1) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="1">3</label>
                                                    <label class="lblwidthac"><input type="radio" name="no_of_properties" <?php if(!empty($editRecord) && $editRecord[0]['carousels_type'] == 2) echo "checked='checked'";?> value="2">6</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_property_type')?></label>
                                                    <select class="form-control parsley-validated" name="property_type[]" id="property_type" multiple >
                                                        <?php if(!empty($property_type)){
                                                            foreach($property_type as $row){ ?>
                                                        <option <?php if(!empty($property_type_id) && in_array($row['id'],$property_type_id)) {echo 'selected="selected"';}?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                                        <?php } } ?>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_views')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_views" <?php if(!empty($editRecord) && $editRecord[0]['only_views'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_views" <?php if(!empty($editRecord) && $editRecord[0]['only_views'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_shortsale')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_shortsale" <?php if(!empty($editRecord) && $editRecord[0]['only_shortsale'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_shortsale" <?php if(!empty($editRecord) && $editRecord[0]['only_shortsale'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_new_construction')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_new_construction" <?php if(!empty($editRecord) && $editRecord[0]['only_new_construction'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_new_construction" <?php if(!empty($editRecord) && $editRecord[0]['only_new_construction'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_open_houses')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_open_houses" <?php if(!empty($editRecord) && $editRecord[0]['only_open_houses'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_open_houses" <?php if(!empty($editRecord) && $editRecord[0]['only_open_houses'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_firms_listing')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_firms_listing" <?php if(!empty($editRecord) && $editRecord[0]['only_firms_listing'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_firms_listing" <?php if(!empty($editRecord) && $editRecord[0]['only_firms_listing'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_forclosures')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_forclosures" <?php if(!empty($editRecord) && $editRecord[0]['only_forclosures'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_forclosures" <?php if(!empty($editRecord) && $editRecord[0]['only_forclosures'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_agent_listing')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_agent_listing" <?php if(!empty($editRecord) && $editRecord[0]['only_agent_listing'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_agent_listing" <?php if(!empty($editRecord) && $editRecord[0]['only_agent_listing'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_only_waterfront')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="only_waterfront" <?php if(!empty($editRecord) && $editRecord[0]['only_waterfront'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="only_waterfront" <?php if(!empty($editRecord) && $editRecord[0]['only_waterfront'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_custom_db_fields')?></label>
                                                    <input type="text" id="custom_db_fields" name="custom_db_fields" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['custom_db_fields'])){ echo $editRecord[0]['custom_db_fields'];}?>" placeholder="Custom DB Fields e.g. ST = 'A'">
                                                    <a title="check_query" class="btn btn-primary" href="javascript:void(0);" onclick="return check_query();">Check</a>
                                                    <span class="query_check"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('leads_dashboard_price_range')?></label>
                                                    <div class="col-sm-6">
                                                        <input type="hidden" name="oldminprice" id="oldminprice" value="<?=!empty($editRecord[0]['min_price'])?$editRecord[0]['min_price']:'';?>"  />
                                                        
                                                        <select class="form-control" name="min_price" id="min_price" onchange="getmaxprice();">
                                                            <option value="">Minimum Price</option>
                                                            <?php $i = 50000;
                                                            while ($i < 5000000) {
                                                            ?>
                                                                <option value="<?=$i?>" <?=!empty($editRecord[0]['min_price'])?($editRecord[0]['min_price']==$i)?'selected=selected':'':'';?>> $<?= $i ?></option>
                                                            <?php
                                                            if (100000 > $i)
                                                                $i = $i + 10000;
                                                            else
                                                                $i = $i + 25000;
                                                            }
                                                            ?>
                                                            <option value="5000000" <?=!empty($editRecord[0]['min_price'])?($editRecord[0]['min_price']==$i)?'selected=selected':'':'';?> > $5000000 </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="hidden" name="oldmaxprice" id="oldmaxprice" value="<?=!empty($editRecord[0]['max_price'])?$editRecord[0]['max_price']:'';?>"  />
                                                        <select  class="form-control" name="max_price" id="max_price" onchange="getminprice();">
                                                            <option value="">Maximum Price</option>
                                                            <?php $i = 50000;
                                                            while ($i < 5000000) {
                                                            ?>
                                                                <option value="<?=$i?>" <?=!empty($editRecord[0]['max_price'])?($editRecord[0]['max_price']==$i)?'selected=selected':'':'';?>> $<?= $i ?></option>
                                                            <?php
                                                            if (100000 > $i)
                                                                $i = $i + 10000;
                                                            else
                                                                $i = $i + 25000;
                                                            }
                                                            ?>
                                                            <option value="5000000" <?=!empty($editRecord[0]['max_price'])?($editRecord[0]['max_price']==$i)?'selected=selected':'':'';?> > $5000000 </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('carousels_label_location_filter')?></label>
                                                    <label class="lblwidthac"><input type="radio" name="location_filter" <?php if(!empty($editRecord) && $editRecord[0]['location_filter'] == 1) echo "checked='checked'";?> value="1">Yes</label>
                                                    <label class="lblwidthac"><input type="radio" name="location_filter" <?php if(!empty($editRecord) && $editRecord[0]['location_filter'] == 0) echo "checked='checked'";?> <?php if(empty($editRecord)) echo "checked='checked'"?> value="0">No</label>
                                                </div>
                                            </div>
                                            <div class="location_div">
                                                <div class="row" >
                                                    <div class="col-sm-12 form-group">
                                                        <label for="text-input"><?=$this->lang->line('carousels_label_county')?> </label>
                                                        <input type="text" id="county" name="county" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['county'])){ echo $editRecord[0]['county'];}?>" placeholder="County">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="text-input"><?=$this->lang->line('common_label_city')?></label>
                                                        <input type="text" id="city" name="city" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['city'])){ echo $editRecord[0]['city'];}?>" placeholder="City">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="text-input"><?=$this->lang->line('carousels_label_community_name')?></label>
                                                        <input type="text" id="community_name" name="community_name" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['community_name'])){ echo $editRecord[0]['community_name'];}?>" placeholder="Community Name">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="text-input"><?=$this->lang->line('common_label_zipcode')?></label>
                                                        <input type="text" id="zipcode" name="zipcode" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['zipcode'])){ echo $editRecord[0]['zipcode'];}?>" placeholder="Zip Code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="3" />
                                            <input type="hidden" id="child_record_id" name="child_record_id" value="<?php if(!empty($editRecord[0]['child_record_id'])){ echo $editRecord[0]['child_record_id'];}?>" />
                                            <input type="hidden" id="edit_id" name="edit_id" value="<?=!empty($edit_id)?$edit_id:0?>" />
                                            <input type="button" title="Save" class="btn btn-secondary-green" value="Save" id="submit_carousels" name="submitbtn" onclick="return showloading('carousels_<?php echo $viewname;?>');"/>
                                            <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
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

<script type="text/javascript">
$(document).ready(function(){
    $("select#property_type").multiselect({
        multiple: true,
        header: true,
        noneSelectedText: "Property Type",
        /*selectedList: 1*/
    }).multiselectfilter();
    
    getmaxprice();
    
    var lf = $('input[name=location_filter]:checked').val();
    if(lf == '0') {
        $('.location_div').css('display','none');
        $('#county').attr('disabled','disabled');
        $('#carousels_<?=$viewname?>').parsley().destroy();
        $('#carousels_<?=$viewname?>').parsley();
    } else if(lf == '1') {
        $('.location_div').css('display','block');
        $('#carousels_<?=$viewname?>').parsley().destroy();
        $('#carousels_<?=$viewname?>').parsley();
        $('#county').removeAttr('disabled');
    }
    
    $("input[name=location_filter]:radio").click(function() {
        if($(this).val() == '0') {
            $('.location_div').css('display','none');
            $('#county').attr('disabled','disabled');
            $('#carousels_<?=$viewname?>').parsley().destroy();
            $('#carousels_<?=$viewname?>').parsley();
        } else if($(this).val() == '1') {
            $('.location_div').css('display','block');
            $('#county').removeAttr('disabled');
            $('#carousels_<?=$viewname?>').parsley().destroy();
            $('#carousels_<?=$viewname?>').parsley();
        }
    });
    
});

function check_query()
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_carousel_valid';?>",
        dataType: 'json',
        //async: false,
        data: $('#carousels_child_admin ').serialize(),
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...Database query validation is checking.'});
        },
        success: function(data){
            if(data == 2)
            {
                $('.query_check').addClass('val');
                $('.query_check').html('Something went wrong with the query');
                
            }
            else
            {
                $('.query_check').removeClass('val');
                $('.query_check').html('Success');
            }
            $.unblockUI();
        }
    });
    return false;
}

function showloading(formid)
{
    var lf = $('input[name=location_filter]:checked').val();
    var county = $("#county").val().trim();
    var city = $("#city").val().trim();
    var c_name = $("#community_name").val().trim();
    var zipcode = $("#zipcode").val().trim();
    var st = county+city+c_name+zipcode;
    var custom_db = $("#custom_db_fields").val().trim();
    
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_carousel_valid';?>",
            dataType: 'json',
            //async: false,
            data: $('#carousels_child_admin ').serialize(),
            beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...Database query validation is checking.'});
            },
            success: function(data){
                if(data == 2)
                {
                    $('.query_check').addClass('val');
                    $('.query_check').html('Something went wrong with the query');
                    $('#custom_db_fields').focus();
                    $.unblockUI();
                }
                else
                {
                    $.unblockUI();
                    $('.query_check').removeClass('val');
                    $('.query_check').html('');
                    if(lf == 1)
                    {
                        if(st.length > 0)// || (city.length > 0) || (c_name.length > 0) || (zipcode.length > 0))))
                        {
                            $('#'+formid).submit();
                            if ($('#'+formid).parsley().isValid()) {
                                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                            }
                        } else {        
                            $('#submit_carousels').attr('disabled','disabled');
                            $.confirm({'title': 'Alert','message': " <strong> Please input atleast one Location Filter option (County, City, Community Name, Zipcode) "+"<strong></strong>",'buttons': {'ok' : {'class'  : 'btn_center alert_ok','action': function()
                            {
                                $('#county').focus();
                                $('#submit_carousels').removeAttr('disabled');
                                $.unblockUI();
                            }}}});
                            return false;
                        }
                    } else {
                        $('#'+formid).submit();
                        if ($('#'+formid).parsley().isValid()) {
                            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                        }
                    }
                }
                //$('#custom_db_fields').focus();
                //$.unblockUI();
            }
        });
        return false;
    // if(lf == 1)
    // {
    //     if(st.length > 0)// || (city.length > 0) || (c_name.length > 0) || (zipcode.length > 0))))
    //     {
    //         $('#'+formid).submit();
    //         if ($('#'+formid).parsley().isValid()) {
    //             $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
    //         }
    //     } else {        
    //         $('#submit_carousels').attr('disabled','disabled');
    //         $.confirm({'title': 'Alert','message': " <strong> Please input atleast one Location Filter option (State, City, Community Name, Zipcode) "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function()
    //         {
    //             $('#state').focus();
    //             $('#submit_carousels').removeAttr('disabled');
    //             $.unblockUI();
    //         }}}});
    //         return false;
    //     }
    // } else {
    //     $('#'+formid).submit();
    //     if ($('#'+formid).parsley().isValid()) {
    //         $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
    //     }
    // }
    // return false;
}
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

/*
    @Description: Function for get maximun price based on min price
    @Author     : Sanjay Moghariya
    @Input      : min price
    @Output     : max price list
    @Date       : 05-05-2015
*/
function getmaxprice()
{
    var from_val = $("#min_price").val();
    var old_max = $('#oldmaxprice').val();
    var html = '';var html1 = '';
    if(from_val != '')
    {
        from_val = from_val.replace(/\,/g, '');
        from_val = from_val.replace('$', '');
        var lil = parseInt(from_val, 10);
        var i = lil;
        while (i < 5000000) {
            if($('#oldmaxprice').val() == i)
                html += '<option selected=selected value='+i+'>$'+i+'</option>';
            else
                html += '<option value='+i+'>$'+i+'</option>';
            if (100000 > i)
                i = i + 10000;
            else
                i = i + 25000;
        }
        var sel = '';
        if(old_max == 5000000)
            sel = 'selected="selected"';
        
        html += '<option value="5000000"'+' '+ sel+' > $5000000 </option>';
    } else {
        html1 += '<option value="">Minimum Price</option>';
        var i = 50000;
        while (i < 5000000) {
            html1 += '<option value='+i+'>$'+i+'</option>';
            if (100000 > i)
                i = i + 10000;
            else
                i = i + 25000;
        }    
        html1 += '<option value="5000000"'+' '+ sel+' > $5000000 </option>';
        html += '<option value="">Maximum Price</option>';
        var i = 50000;
        while (i < 5000000) {
            if($('#max_price').val() == i)
                html += '<option selected=selected value='+i+'>$'+i+'</option>';
            else
                html += '<option value='+i+'>$'+i+'</option>';
            // html += '<option value='+i+'>$'+i+'</option>';
            if (100000 > i)
                i = i + 10000;
            else
                i = i + 25000;
        }    
        html += '<option value="5000000"'+' '+ sel+' > $5000000 </option>';
        $('#min_price').html(html1);
    }
    $('#max_price').html(html);
}

/*
    @Description: Function for get min price based on max price
    @Author     : Sanjay Moghariya
    @Input      : max price
    @Output     : min price list
    @Date       : 05-05-2015
*/
function getminprice()
{
    var from_val = $("#max_price").val();
    var old_min = $('#min_price').val();
    var html = '';
    if(from_val != '')
    {
        from_val = from_val.replace(/\,/g, '');
        from_val = from_val.replace('$', '');

        var lil = parseInt(from_val, 10);
        var s='';
        html += '<option value='+s+'>Minimum Price</option>';
        var i = 50000;
        var ori_lil = lil;
        if(lil == 5000000) {
            lil = 5000000;
        } else {
            lil = 5000000;
        }
        while (i < lil) {
             if($('#min_price').val() == i)
                html += '<option selected=selected value='+i+'>$'+i+'</option>';
            else
                html += '<option value='+i+'>$'+i+'</option>';
            if (100000 > i)
                i = i + 10000;
            else
                i = i + 25000;
        }
        var sel = '';
        if(old_min == 5000000)
            sel = 'selected="selected"';
        html += '<option value="5000000"'+' '+ sel+' > $5000000 </option>';
    } else {
        html += '<option value="">Minimum Price</option>';
        var i = 50000;
        while (i < 5000000) {
            html += '<option value='+i+'>$'+i+'</option>';
            if (100000 > i)
                i = i + 10000;
            else
                i = i + 25000;
        }    
        html += '<option value="5000000"'+' '+ sel+' > $5000000 </option>';
    }
    $('#min_price').html(html);
}

/*
    @Description: Function for Check name exists or not
    @Author     : Sanjay Moghariya
    @Input      : carousels name
    @Output     : exist or not
    @Date       : 05-05-2015
*/
function check_carousels_name()
{
    var name = $("#carousels_name").val();
    var flag = 0;
    if($("#carousels_name").val().trim() != '')
    {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_carousels_name';?>",
            //dataType: 'json',
            //async: false,
            data: {'name':name,'id':<?=!empty($editRecord)?$editRecord[0]['id']:'0'?>, 'edit_id':$('#edit_id').val()},
            beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
            },
            success: function(data){
                if(data == '1')
                {
                    $('#carousels_name').focus();
                    $('#submit_carousels').attr('disabled','disabled');
                    $.confirm({'title': 'Alert','message': " <strong> This title already exist! Please input other title "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function()
                    {
                        $('#carousels_name').focus();
                        $('#submit_carousels').removeAttr('disabled');
                        $.unblockUI();
                    }}}});
                    return false;
                }
                else
                    $.unblockUI();
            }
        });
    }
}
</script>