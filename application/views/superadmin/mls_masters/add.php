<?php
$viewname = $this->router->uri->segments[2]; 
isset($editRecord) ? $loadcontroller='update_property_type' : $loadcontroller='insert_property_type';
    $path_property_list = $viewname."/".$loadcontroller;
isset($editmls_status) ? $loadcontroller='update_mls_status' : $loadcontroller='insert_mls_status';
    $path_mls_status = $viewname."/".$loadcontroller;
isset($editmls_area) ? $loadcontroller='update_mls_area' : $loadcontroller='insert_mls_area';
    $path_mls_area = $viewname."/".$loadcontroller;
?>

<div id="content" class="contact-masters">
    <div id="content-header">
        <h1>MLS Masters</h1>
    </div>
    <div id="content-container">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-header"><h3> <i class="fa fa-tasks"></i>MLS Masters</h3></div>
      
                <div class="portlet-content" style="max-height:none;">
                    <div class="">
                        <div class="chart_bg1 tbl_border"> 
                            <!-- Property TYPE-->
                            <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_property_list?>" class="form parsley-form" >
                                <div class="col-md-6">
                                    <div class="mrg-bottom-40">
                                        <div class="portlet-header">
                                            <h3><?php echo $this->lang->line('common_label_property_type')?> </h3>
                                        </div>
                                        <div class="portlet-content">
                                            <table width="100%" class="iconment_title_in" >
                                                <tr>
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
                                                                <div id="show"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="property_type_update[]" id="property_list_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" /><input type="hidden" class="form-control parsley-validated" name="property_idd[]" id="" value="<?php echo  $row['id'] ?>"/> </td>
                                                            <td><a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower(($row['name']))))?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_property_type/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a></td>
                                                        </tr>
                                                    <?php }
                                                }?>
                                                <input type="hidden" id="property_type_id" name="property_type_id" class="property_type_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                                                <tr>
                                                    <td colspan="2">
                                                        <div id="p_scents" class="form-group">
                                                            <?php if(empty($property_type) || count($property_type) == 0){?>
                                                                <p>
                                                                  <label for="p_scnts">
                                                                    <input type="text" class="form-control parsley-validated" data-required="required" name="property_type[0]" id="property_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['name'] : ''?>" />
                                                                  </label>
                                                                </p>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#" id="addScnt" title="Add Property Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Property Type</a></td>
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

                            <!-- MLS STATUS -->
                            <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_mls_status?>" >
                                <div class="col-md-6">
                                    <div class="mrg-bottom-40">
                                        <div class="portlet-header">
                                            <h3><?php echo "Status";?></h3>
                                        </div>
                                        <div class="portlet-content">
                                            <table width="100%" class="iconment_title_in" >
                                                <tr >
                                                    <th><?php echo $this->lang->line('common_label_title')?></th>
                                                    <th><?php echo $this->lang->line('common_label_action')?></th>
                                                </tr>
                                                <?php
                                                if(!empty($mls_status) && count($mls_status)>0){
                                                    foreach($mls_status as $row)
                                                    {   
                                                    ?>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="space_property_type"></div>
                                                                <div id="flash_property_type"></div>
                                                                <div id="show_property_type"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text_capitalize" width="70%">
                                                                <input type="text" class="form-control parsley-validated" name="mls_status_update[]" id="mls_status_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                                                                <input type="hidden" class="form-control parsley-validated" name="mls_status_idd[]" id="" value="<?php echo  $row['id'] ?>"/>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="get_mls_status('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                                                <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode($row['name'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_mls_status/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }?>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <input type="hidden" id="mls_status_id" name="mls_status_id" class="mls_status_id" value="<?=isset($mls_status) ? $mls_status[0]['id']:''?>" />
                                                    <td colspan="2">
                                                        <div id="p_mls_status" class="form-group">
                                                            <?php if(empty($mls_status) || count($mls_status) == 0){?>
                                                                <p>
                                                                    <label for="p_mls_status">
                                                                        <input type="text" class="form-control parsley-validated" data-required="required" name="mls_status_type[0]" id="mls_status_type[0]" value="<?=isset($mls_status) ? htmlentities($mls_status[0]['name']) : ''?>" />
                                                                    </label>
                                                                </p>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#" id="addScnt_mls_status" title="Add MLS Status" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Status</a>
                                                    </td>
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

                            <!-- MLS Area -->
                            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path_mls_area?>" >
                                <div class="col-md-6">
                                    <div class="mrg-bottom-40">
                                        <div class="portlet-header">
                                            <h3><?php echo $this->lang->line('label_mls_area')?> </h3>
                                        </div>
                                        <div class="portlet-content">
                                            <table width="100%" class="iconment_title_in" >
                                                <tr>
                                                    <th><?php echo $this->lang->line('common_label_title')?></th>
                                                    <th><?php echo $this->lang->line('common_label_action')?></th>
                                                </tr>
                                                <?php
                                                if(!empty($mls_area) && count($mls_area)>0){
                                                    foreach($mls_area as $row)
                                                    {   
                                                    ?>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="space_document_list"></div>
                                                                <div id="flash_document_list"></div>
                                                                <div id="show_document_list"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text_capitalize" width="70%"><input type="text" class="form-control parsley-validated" name="area_list_update[]" id="area_list_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>"/><input type="hidden" class="form-control parsley-validated" name="area_idd[]" id="" value="<?php echo  $row['id'] ?>"/></td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="get_submit_area_list('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower(($row['name']))))?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('superadmin_base_url').$viewname;?>/delete_mls_area/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }?>
                                            </table>
                                            <input type="hidden" id="area_list_id" name="area_list_id" class="area_list_id" value="<?=isset($mls_area) ? $mls_area[0]['id']:''?>" />
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="2">
                                                        <div id="p_scents_area_list" class="form-group">
                                                            <?php if(empty($mls_area) || count($mls_area) == 0){?>
                                                                <p>
                                                                    <label for="p_scnts_area_list">
                                                                        <input type="text" class="form-control parsley-validated" data-required="required" name="area_list_type[0]" id="area_list_type[0]" value="<?=isset($mls_area) ? $mls_area[0]['name'] : ''?>" />
                                                                    </label>
                                                                    <?php /*?><a href="#" id="addScnt_document_list" title="Add More" style="color:#999900;font-size:10px;"><img src="<?=base_url('images/add_icon.jpg') ?>"/></a><?php */?>
                                                                </p>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#" id="addScnt_mls_area" title="Add MLS Area" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add MLS Area</a></td>
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
</script> 

<!-- ================== START Multipal Input Box Script ================== --> 
<!-- ==== Property INPUT ADD ===== --> 
<script type="text/javascript">
    $(function() {
        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size();
        $('body').on('click', '#addScnt', function(){
            $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="property_type[' + i +']" id="property_type[' + i +']"/></label>&nbsp;<a href="#" id="remScnt" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
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

<!-- ==== MLS Area INPUT ADD ===== --> 
<script type="text/javascript">
    $(function() {
        var scntDiv = $('#p_scents_area_list');
        var i = $('#p_scents_area_list p').size();
        $('body').on('click', '#addScnt_mls_area', function(){
            $('<p><label for="p_scnts_area_list"><input type="text" class="form-control parsley-validated" data-required="required" name="area_list_type[' + i +']" id="area_list_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_area_list" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
            i++;
            return false;
        });      
        $('body').on('click', '#remScnt_area_list', function(){
            if( i > 0 ) {
                $(this).parents('p').remove();
                i--;
            }
            return false;
        });
    });
</script> 

<!-- PROPERY STATUS TYPE -->
<script type="text/javascript">
    $(function() {
        var scntDiv = $('#p_mls_status');
        var i = $('#p_mls_status p').size();
        $('body').on('click', '#addScnt_mls_status', function(){
            $('<p><label for="p_mls_status"><input type="text" class="form-control parsley-validated" data-required="required" name="mls_status_type[' + i +']" id="mls_status_type[' + i +']"/> </label>&nbsp;<a href="#" id="remScnt_mls_status" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
            i++;
            return false;
        });      
        $('body').on('click', '#remScnt_mls_status', function(){
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
<!-- ==== Type UPDATE AJAX ===== --> 
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
            $("#flash").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_property_type',
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

<!-- ==== MLS Area UPDATE AJAX ===== --> 
<script type="text/javascript">
    function get_submit_area_list(id)
    {
	var document_list = $("#area_list_"+id).val();
	if(document_list=='' && id=='')
	{
            alert("Enter text..");
            $("#area_list").focus();
	}
	else
	{
            $("#flash_document_list").show();
            $("#flash_document_list").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_mls_area',
                data: { area_list_type:document_list,area_list_id:id },
                cache: true,
                success: function(html)
                {
                    $("#show_document_list").after(html);
                    $("#area_list").focus();
                }  
            });
	}
	return false;
    }
</script> 

<!-- MLS Status -->
<script type="text/javascript">
    function get_mls_status(id)
    {
        var property_status = $("#mls_status_"+id).val();
        if(property_status=='' && id=='')
        {
            alert("Enter text..");
            $("#mls_status").focus();
        }
        else
        {
            $("#flash_property_type").show();
            $("#flash_property_type").fadeOut(3000).html('<span class="load">Updated Successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>superadmin/<?=$viewname;?>/update_mls_status',
                data: { mls_status_type:property_status,mls_status_id:id },
                cache: true,
                success: function(html)
                {
                    $("#show_property_type").after(html);
                    $("#property_type").focus();
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
