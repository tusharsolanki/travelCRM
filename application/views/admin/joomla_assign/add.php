<?php

/*
    @Description: Joomla Assign
    @Author     : Mohit Trivedi
    @Date       : 13-09-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>
<style>
.ui-multiselect {
	width:100% !important;
}
.tabelbdr table {
	width:100%;
}
.tabelbdr table label {
	float:left;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.min.js"></script>
<!-- <script type="text/javascript" src="<?php //echo base_url();?>js/autoNumeric.js"></script> -->

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('joomla_assign_plan_head');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php if(!empty($editRecord)){ echo $this->lang->line('joomla_assign_plan_edit_head');}
                         else{ echo $this->lang->line('joomla_assign_plan_add_head'); }?> </h3>
                         <span class="float-right margin-top--15">
                        <a href="javascript:void(0)" onclick="history.go(-1)" title="Back" class="btn btn-secondary">Back</a> </span>

                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" data-validate="parsley" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate onkeypress="return event.keyCode != 13;">
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-8">
                                            <div class="row mycalclass">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('label_assintplan')?><span class="val">*</span></label>
                                                    <select class="form-control parsley-validated" name='assigned_interaction_plan_id' id='assigned_interaction_plan_id' data-required="true">
                                                        <option value="">Select Interaction Plan</option>
                                                        <?php if(isset($plan) && count($plan) > 0){
                                                                foreach($plan as $row1){
                                                                    if(!empty($row1['id'])){?>
                                                                        <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['interaction_plan_id']) && $editRecord[0]['interaction_plan_id'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['plan_name']);?></option>
                                                      <?php         }
                                                                }
                                                            } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='row mycalclass'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_assign_plan_ptype')?> <span class="val">*</span></label>
                                                    <select class="form-control parsley-validated" name='prospect_type' id='prospect_type'>
                                                        <option value="Buyer" <?php if(!empty($editRecord[0]['prospect_type']) && $editRecord[0]['prospect_type'] == 'Buyer'){ echo "selected=selected"; } ?>>Buyer</option>
                                                        <option value="Seller" <?php if(!empty($editRecord[0]['prospect_type']) && $editRecord[0]['prospect_type'] == 'Seller'){ echo "selected=selected"; } ?>>Seller</option>
                                                        <option value="Buyer/Seller" <?php if(!empty($editRecord[0]['prospect_type']) && $editRecord[0]['prospect_type'] == 'Buyer/Seller'){ echo "selected=selected"; } ?>>Buyer/Seller</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php /*
                                            <div class='row mycalclass'>
                                                <div class="col-sm-12 form-group">                                                    
                                                    <label for="text-input"><?=$this->lang->line('joomla_assign_plan_min_price');?> <?php echo "(In ".$this->lang->line('currency').")" ?> </label>
                                                    <input id="min_price" name="min_price" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['min_price'])){ echo $editRecord[0]['min_price']; }?>"><!-- data-parsley-lte="#max_price" >-->
                                                </div>
                                            </div>
                                            <div class='row mycalclass'>
                                                <div class="col-sm-12 form-group">
                                                 <label for="text-input"><?=$this->lang->line('joomla_assign_plan_max_price');?> <?php echo "(In ".$this->lang->line('currency').")" ?> </label>
                                                 <input id="max_price" name="max_price" maxlength="10" class="form-control parsley-validated prz" type="text" onkeypress="return isNumberKey(event)" value="<?php if(!empty($editRecord[0]['max_price'])){ echo $editRecord[0]['max_price']; }?>"><!-- data-parsley-gte="#min_price" >-->
                                                </div>
                                            </div>
                                             */?>
                                            <div class='row mycalclass'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_assign_plan_status');?><!--<span class="mandatory_field margin-left-5px">*</span>--></label>
                                                    <input type="radio" class="newjoomla_mar" value="On" name="plan_status" id="plan_status1" <?php if(!empty($editRecord[0]['status']) && $editRecord[0]['status'] == 'On'){ echo 'checked="checked"'; }?>>On
                                                    <input class="newjoomla_mar" type="radio" value="Off" name="plan_status" id="plan_status2" <?php if(!empty($editRecord[0]['status']) && $editRecord[0]['status'] == 'Off'){ echo 'checked="checked"'; }?> <?php if(empty($editRecord[0]['status'])) { echo 'checked="checked"'; }?>>Off
                                                </div>
                                            </div>
                                            <div class='row mycalclass'>
                                                <div class="col-sm-12 form-group">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="submit" class="btn btn-secondary-green" value="Save" title="Save" name="submitbtn" id="ja_submitbtn" onclick="return success_submit();" /> 
                                            <a title="Close" class="btn  btn-danger mrg26" href="javascript:history.go(-1);" id="ja_cancel">Close</a> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.portlet-content --> 
      
    </div>
</div>
</div>
</div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
 </div>

<script type="text/javascript">
	$("select#plan_id").multiselect({
		 multiple: false,
		 selectedList: 1
	}).multiselectfilter();
    /*jQuery(function($) {
        $('.prz').autoNumeric('init');
    });*/
var id_array = [];
$(document).ready(function(){
    /*$('#form').each(function(i, div) {
        $(div).find('input').each(function(j, element){
            var eleid = ($(element).attr('id'));
            if($(element).attr('data-required') == 'true') {
                id_array.push(eleid);
                $(element).removeAttr('data-required');
            }
                
        });
        $(div).find('textarea').each(function(k, element){
            var eleid = ($(element).attr('id'));
            if($(element).attr('data-required') == 'true') {
                id_array.push(eleid);
                $(element).removeAttr('data-required');
            }
        });
    });*/
});	

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function select_box()
{
    for ( var i = 0; i < id_array.length; i = i + 1 ) {
        $('#'+id_array[i]).attr('data-required','true');
    }

	var abc = $("#plan_id").val();
	if(abc > 0)
	{
            $('.parsley-form').submit();
	}
	else
	{
		//$.confirm({'title': 'Alert','message': " <strong> Please Select Atleast One Communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					//return false;

	}
	var content1 = $('#form').html(); 
	$("#divcontent1").val(content1);
	return true;

}
	</script>  
    <script type="text/javascript">
	   /*$("select#assigned_interaction_plan_id").multiselect({
		}).multiselectfilter();
		*/
function select_box()
{
	var abc = $("#assigned_interaction_plan_id").multiselect("widget").find(":checkbox").filter(':checked').length;
	if(popupcontactlist != '')
	{
		if(abc > 1)
		{
			$.confirm({'title': 'Alert','message': " <strong> Contacts assign to only one user. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;
		}
	}
	if(abc > 0)
	{
		$('.parsley-form').submit();
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select atleast one user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;

	}
}

function success_submit()
{
    if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        /*$('#ja_submitbtn').attr('disabled','disabled');
        $('#ja_cancel').attr('disabled','disabled');*/
        return true;
    }
}

$('#min_price').priceFormat({
    prefix: '',
    clearPrefix: true,
    centsLimit: 0
});

$('#max_price').priceFormat({
        prefix: '',
        clearPrefix: true,
        centsLimit: 0
});
    //$("#txt_price_range_from, #txt_price_range_to").change(function (e) {
$('body').on('change','#min_price, #max_price',function(e){
	//alert($("#min_price").val());
	
	var from_val = $("#min_price").val();
	from_val = from_val.replace(/\,/g, '');
	from_val = from_val.replace('$', '');
	
	var to_val = $("#max_price").val();
	to_val = to_val.replace(/\,/g, '');
	to_val = to_val.replace('$', '');
	
    var lil = parseInt(from_val, 10);
    var big = parseInt(to_val, 10);
	
	//alert(lil);
	//alert(big);
	
    $('#lil').text(lil);
    $('#big').text(big);
    if((lil > 0 && big != 0) || (lil != 0 && big > 0) || (lil > 0 && big > 0))
    {
        if (lil > big) {
            var targ = $(e.target);
            if (targ.is("#max_price")) {
                //alert("Max must be greater than Min");
                $('#ja_submitbtn').attr('disabled','disabled');
                $('#ja_cancel').attr('disabled','disabled');
                $.confirm({'title': 'Alert','message': " <strong> Max price must be greater than min "+"<strong></strong>",
                    'buttons': {'ok'	: {
                    'class'	: 'btn_center alert_ok',	
                    'action': function(){
                            $('#max_price').val(lil);
                            $('#max_price').focus();
                            $('#ja_submitbtn').removeAttr('disabled');
                            $('#ja_cancel').removeAttr('disabled');
                    }},  }
                });

                //$('#txt_price_range_to').val(lil);
            }
            if (targ.is("#min_price")) {
                //alert("Min must be less than Max");
                $('#ja_submitbtn').attr('disabled','disabled');
                $('#ja_cancel').attr('disabled','disabled');	
                $.confirm({'title': 'Alert','message': " <strong> Min price must be less than max "+"<strong></strong>",
                    'buttons': {'ok'	: {
                    'class'	: 'btn_center alert_ok',	
                    'action': function(){
                            $('#min_price').val(big);
                            $('#min_price').focus();
                            $('#ja_submitbtn').removeAttr('disabled');
                            $('#ja_cancel').removeAttr('disabled');
                    }},  }
                });
            }
        }
    }
});
</script>