<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$formAction = 'update_data'; 
$path = $viewname.'/'.$formAction;
?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.min.js"></script>
<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('user_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i> Assign RR Weightage </h3>
            <span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('superadmin_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
          </div>
            <!-- /.portlet-header -->
            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate onkeypress="return event.keyCode != 13;" >
                <div class="portlet-content">
                  <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="background-color:#FFFFFF;">
                      <thead>
                        <tr role="row">
                          <th width="25%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?php echo $this->lang->line('agent_rr_weightage_label_name');?></th>
                          <th width="25%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?php echo $this->lang->line('agent_rr_weightage_label_weightage');?></th>
                          <th width="25%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?php echo $this->lang->line('agent_rr_weightage_label_min_price');?> <?php echo "(In ".$this->lang->line('currency').")" ?></th>
                          <th width="25%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?php echo $this->lang->line('agent_rr_weightage_label_max_price');?> <?php echo "(In ".$this->lang->line('currency').")" ?></th>
                        </tr>
                      </thead>
                      <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <?php 
                        if(!empty($editRecord))
                        { ?>
                            <tr>
                                <td class="hidden-xs hidden-sm "><label for="validateSelect"> <?php echo ucwords($editRecord[0]['agent_name'])."<br>";?></label></td>
                                <td class="hidden-xs hidden-sm ">
                                    <input type="hidden" name="suser_id" id="suser_id" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                    <input type="hidden" name="is_edit_single" id="is_edit_single" value="<?php if(!empty($edit_single)){ echo $edit_single; }?>">
                                    <input type="text" name="sweightage" id="sweightage" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['user_weightage'])){ echo $editRecord[0]['user_weightage']; }?>" onkeypress="return isNumberKey(event);">
                                </td>
                                <td class="hidden-xs hidden-sm "><input type="text" name="smin_price" id="smin_price" maxlength="10" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['minimum_price'])){ echo $editRecord[0]['minimum_price']; } else { echo 0;}?>"  onkeypress="return isNumberKey(event);" /></td>
                              <td class="hidden-xs hidden-sm "><input type="text" name="smax_price" id="smax_price" maxlength="10" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['maximum_price'])){ echo $editRecord[0]['maximum_price']; }  else { echo 0;}?>"  onkeypress="return isNumberKey(event);" data-parsley-gte="#smin_price" /></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="center"><input type="submit" name="ssubmitbtn" title="Save" value="Save" class="btn btn-secondary" id="ssubmitbtn" onclick="return submit_success();"></td>
                            </tr>
                        <?php    
                        }
                        else {
                            if(!empty($datalist) && count($datalist)>0){
                                                  $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                foreach($datalist as $row){?>
                            <tr>
                                <td class="hidden-xs hidden-sm "><label for="validateSelect"> <?php echo ucwords($row['agent_name'])."<br>";?></label></td>
                              <td class="hidden-xs hidden-sm ">
                                  <input type="hidden" name="user_id[]" id="user_id" value="<?php if(!empty($row['id'])){ echo $row['id']; }?>">
                                  <input type="text" name="weightage[]" id="weightage" class="form-control parsley-validated" value="<?php if(!empty($row['user_weightage'])){ echo $row['user_weightage']; }?>" onkeypress="return isNumberKey(event);">
                              </td>
                              <td class="hidden-xs hidden-sm "><input type="text" name="min_price[]" id="min_price_<?=$row['id']?>" maxlength="10" class="form-control parsley-validated" value="<?php if(!empty($row['minimum_price'])){ echo $row['minimum_price']; } else { echo 0;}?>"  onkeypress="return isNumberKey(event);" /></td>
                              <td class="hidden-xs hidden-sm "><input type="text" name="max_price[]" id="max_price_<?=$row['id']?>" maxlength="10" class="form-control parsley-validated" value="<?php if(!empty($row['maximum_price'])){ echo $row['maximum_price']; }  else { echo 0;}?>"  onkeypress="return isNumberKey(event);" data-parsley-gte="#min_price_<?=$row['id']?>" /></td>
                            </tr>
                            <?php } ?>
                        <tr>
                          <td colspan="4"  align="center"><input type="submit" name="submitbtn" title="Save" value="Save" id="submitbtn" class="btn btn-secondary" onclick="this.disabled=true;this.value='Please wait...';"></td>
                        </tr>
                        <?php } else {?>
                        <tr>
                          <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                        </tr>
                        <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.portlet-content --> 
          </form>
          
        </div>
      </div>
    </div>
  </div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
</div>
<script type="text/javascript">
    function submit_success()
    {
        if ($('#<?php echo $viewname?>').parsley().isValid()) {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        }
    }
    <?php if(!empty($datalist) && count($datalist)>0) {
            foreach($datalist as $row) { ?>
                $('#min_price_'+<?=$row['id']?>).priceFormat({
                    prefix: '',
                    clearPrefix: true,
                    centsLimit: 0
                });

                $('#max_price_'+<?=$row['id']?>).priceFormat({
                        prefix: '',
                        clearPrefix: true,
                        centsLimit: 0
                });
    <?php } } ?>
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function getominprice(id)
    {
        var cpr1 = $('#min_price_'+id).val();
        var cpr2 = $('#max_price_'+id).val();
        cpr1 = parseFloat(cpr1.replace(/,/g, ""));
        cpr2 = parseFloat(cpr2.replace(/,/g, ""));
        if(cpr1 > cpr2)
        {
            $('#max_price_'+id).attr('min',cpr1);
            $('#max_price_'+id).attr('data-parsley-min',cpr1);
        }
        else
        {
            $('#max_price_'+id).removeAttr('min');
            $('#max_price_'+id).removeAttr('data-parsley-min');
        }
    }
    
$('#smin_price').priceFormat({
    prefix: '',
    clearPrefix: true,
    centsLimit: 0
});

$('#smax_price').priceFormat({
        prefix: '',
        clearPrefix: true,
        centsLimit: 0
});
    //$("#txt_price_range_from, #txt_price_range_to").change(function (e) {
$('body').on('change','#smin_price, #smax_price',function(e){
	//alert($("#smin_price").val());
	
	var from_val = $("#smin_price").val();
	from_val = from_val.replace(/\,/g, '');
	from_val = from_val.replace('$', '');
	
	var to_val = $("#smax_price").val();
	to_val = to_val.replace(/\,/g, '');
	to_val = to_val.replace('$', '');
	
    var lil = parseInt(from_val, 10);
    var big = parseInt(to_val, 10);
	
	//alert(lil);
	//alert(big);
	
    $('#lil').text(lil);
    $('#big').text(big);
    if (lil > big) {
        var targ = $(e.target);
        if (targ.is("#smax_price")) {
            //alert("Max must be greater than Min");
		$('#submitbtn').attr('disabled','disabled');
                $('#ssubmitbtn').attr('disabled','disabled');
            $.confirm({'title': 'Alert','message': " <strong> Max price must be greater than Min "+"<strong></strong>",
                'buttons': {'ok'	: {
                'class'	: 'btn_center alert_ok',	
                'action': function(){
                    $('#smax_price').val(lil);
                    $('#smax_price').focus();
                    $('#submitbtn').removeAttr('disabled');
                    $('#ssubmitbtn').removeAttr('disabled');
                }},  }
            });
			
            //$('#txt_price_range_to').val(lil);
        }
        if (targ.is("#smin_price")) {
            //alert("Min must be less than Max");
	    $('#submitbtn').attr('disabled','disabled');
            $('#ssubmitbtn').attr('disabled','disabled');
            $.confirm({'title': 'Alert','message': " <strong> Min price must be less than Max "+"<strong></strong>",
                'buttons': {'ok'	: {
                'class'	: 'btn_center alert_ok',	
                'action': function(){
                        $('#smin_price').val(big);
                        $('#smin_price').focus();
                        $('#submitbtn').removeAttr('disabled');
                        $('#ssubmitbtn').removeAttr('disabled');
                }},  }
            });
        }
    }
});
</script>