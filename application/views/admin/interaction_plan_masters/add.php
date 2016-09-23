<?php
    $viewname = $this->router->uri->segments[2]; 
    isset($editRecord) ? $loadcontroller='update_plan_type' : $loadcontroller='insert_plan_type';
    $path_plan_type = $viewname."/".$loadcontroller;
    isset($editStatusRecord) ? $loadcontroller='update_status' : $loadcontroller='insert_status';
    $path_status = $viewname."/".$loadcontroller;
?>
<div id="content" class="contact-masters">
    <div id="content-header">
        <h1>Communication Masters</h1>
    </div>
    <div id="content-container">
	
	<div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
      
       <h3> <i class="fa fa-tasks"></i>Communication Masters</h3>       
       		 <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>  
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
	
        <div class="">
            <div class="chart_bg1 tbl_border">
                <!-- Plan TYPE-->
                <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_plan_type?>" class="form parsley-form" >
                    <div class="col-md-6">
                        <div class="mrg-bottom-40">
                            <div class="portlet-header">
                                <h3>
                                        <!--<i class="fa fa-tasks"></i>-->
                                    <?php echo $this->lang->line('common_label_plan_type')?>
                                </h3>
                            </div>
                            <div class="portlet-content">
                                <table width="100%" class="iconment_title_in" >
                                    <tr>
                                        <th><?php echo $this->lang->line('common_label_title')?></th>
                                        <th><?php echo $this->lang->line('common_label_action')?></th>
                                    </tr>
                                    <?php
                                    if(!empty($plan_type))
                                    {
                                        foreach($plan_type as $row)
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
                                                    
                                                    <input type="text" class="form-control parsley-validated" class="form-control parsley-validated" name="plantype" id="plantype_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_plan_type_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
                                                </td> 
                                            </tr>
                                  <?php }

                                    }
                                    ?>
                                    <input type="hidden" id="plan_type_id" name="plan_type_id" class="email_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                                    <tr>
                                        <td colspan="2">
                                            <div id="p_scents" class="form-group">
											<?php if(empty($plan_type) || $plan_type == 0)
                                    		{?>
                                                <p>
                                                    <label for="p_scnts">
                                                    <input type="text" class="form-control parsley-validated" data-required="required" name="plan_type[0]" id="plan_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['name'] : ''?>" />
                                                    </label>
                                                    
                                                </p>
												<?php } ?>
                                            </div>
                                        </td>
                                    </tr>
									<tr>
										<td>
										<a href="#" id="addScnt" title="Add Plan Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Plan Type</a></td>
										</td>&nbsp;</td>
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
			
                <!-- STATUS-->
                <form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_status?>" >
                    <div class="col-md-6">
                        <div class="mrg-bottom-40">
                            <div class="portlet-header">
                                <h3><?php echo $this->lang->line('common_label_istatus')?></h3>
                            </div>
                            <div class="portlet-content">
                                <table width="100%" class="iconment_title_in" >
                                    <tr >
                                        <th><?php echo $this->lang->line('common_label_title')?></th>
                                        <th><?php echo $this->lang->line('common_label_action')?></th>
                                    </tr>
                                    <?php
                                    if(!empty($status))
                                    {
                                        foreach($status as $row)
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
                                                    <div class="space_status"></div>
                                                    <div id="flash_status"></div>
                                                    <div id="show_status"></div>
                                                    <input type="text" class="form-control parsley-validated" name="status" id="status_<?=$row['id']?>" value="<?php echo  htmlentities($row['name']) ?>" />
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="get_submit_status('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_status_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a>
                                                </td>
                                            </tr>
                                    <?php }
                                    }
                                    ?>
                                </table>
                                <table width="100%">
                                    <tr>
                                        <input type="hidden" name="id" value="<?=isset($editStatusRecord) ? $editStatusRecord[0]['id']:''?>" />
                                        <td colspan="2">
                                            <div id="p_scents_status" class="form-group">
											<?php  if(empty($status) || ($status) == 0)
                                    		{?>
                                                <p>
                                                    <label for="p_scnts_status">
                                                    <input type="text" class="form-control parsley-validated" data-required="required" name="status_add[0]" id="status_add[0]" value="<?=isset($editStatusRecord) ? $editStatusRecord[0]['name'] : ''?>" />
                                                    </label>
                                                  
                                                </p>
												<?php } ?>
                                            </div>
                                        </td>
                                    </tr>
									<tr>
										<td>
										<a href="#" id="addScnt_status" title="Add Status" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Status</a>
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
            </div>
        </div>
    </div>
   </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
            /*$('#<?php echo $viewname;?>').bValidator();*/
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
            url: '<?php echo $this->config->item('admin_base_url')?>/user/getemail', 
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
                $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="plan_type[' + i +']" id="plan_type[' + i +']"/></label><a href="#" id="remScnt" class="btn btn-xs btn-primary margin_tops1"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
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

<!-- ==== Status INPUT ADD ===== -->
<script type="text/javascript">

    $(function() {
        var scntDiv = $('#p_scents_status');
        var i = $('#p_scents_status p').size();
		$('body').on('click', '#addScnt_status', function(){
        $('<p><label for="p_scnts_status"><input type="text" class="form-control parsley-validated" data-required="true" name="status_add[' + i +']" id="status_add[' + i +']"/> </label><a href="#" id="remScnt_status" class="btn btn-xs btn-primary  margin_tops1"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });      
		$('body').on('click', '#remScnt_status', function(){ 
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
	var email = $("#plantype_"+id).val();
	if(email=='' && id=='')
	{
            alert("Enter text..");
            $("#plantype").focus();
	}
	else
	{
            //alert(id);
            $("#flash").show();
            $("#flash").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>admin/<?=$viewname;?>/update_plan_type',
                data: { plan_type:email,plan_id:id },
                cache: true,
                success: function(html)
                {
                        $("#show").after(html);
                        //$("#flash").hide();
                        $("#plantype").focus();
                }  
            });
	}
	return false;
    }
</script>

<!-- ==== STATUS UPDATE AJAX ===== -->
<script type="text/javascript">

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
            $("#flash_status").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>admin/<?=$viewname;?>/update_status',
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

<!-- ================== END Ajax Script ================== -->