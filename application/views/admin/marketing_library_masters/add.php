<?php
    $viewname = $this->router->uri->segments[2]; 
    isset($editRecord) ? $loadcontroller='update_plan_type' : $loadcontroller='insert_plan_type';
    $path_plan_type = $viewname."/".$loadcontroller;
    isset($editStatusRecord) ? $loadcontroller='update_status' : $loadcontroller='insert_status';
    $path_status = $viewname."/".$loadcontroller;
	$message = $this->session->userdata('message_session');
	if(!empty($message) && ($message['message'] == 'insert_successfully' || $message['message'] == 'delete_successfully')){
		$this->session->unset_userdata('message_session');
?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
jQuery(document).ready(function(){
	try{
		parent.selectnewcategory();
	 }
	 catch(err) {
        // Handle error(s) here
    }
	parent.parent.$('.close_contact_select_popup').trigger('click');
	$.unblockUI();
});
</script>
<?php } ?>
<style>
<?php if($this->uri->segment(4) == 'iframe'){ ?>
#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back{ display:none !important;}
#content{ margin-left:0;}
<?php } ?>
</style>
<div id="<?php if($this->uri->segment(4) != 'iframe'){ ?>content<?php } ?>" class="contact-masters">
    <div id="content-header">
        <h1>Master Library Configuration</h1>
    </div>
    <div id="content-container">
	
	<div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
      
       <h3> <i class="fa fa-tasks"></i>Master Library Configuration</h3>
       		 <span class="float-right margin-top--15"><?php if($this->uri->segment(4) != 'iframe'){ ?> <a class="btn btn-secondary" title="Back" href="<?=base_url('admin/livewire_configuration');?>"><?php echo $this->lang->line('common_back_title')?></a> <?php } ?> </span>  
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
	
        <div class="">
            <div class="chart_bg1 tbl_border">
                <!-- Plan TYPE-->
                <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="plan_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_plan_type?>" class="form parsley-form" >
                    <div class="col-md-6">
                        <div class="mrg-bottom-40">
                            <div class="portlet-header">
                                <h3>
                                        <!--<i class="fa fa-tasks"></i>-->
                                    <?php echo $this->lang->line('category')?>
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
                                                    
                                                    <input type="text" class="form-control parsley-validated" name="plantype_update[]" id="plantype_<?=$row['id']?>" value="<?php echo  $row['category'] ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php }?> />
                                                    
                                                    <input type="hidden" class="form-control parsley-validated" name="plan_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                                                    
                                                </td>
                                                <td>
										<?php if($row['user_type']!='1'){ ?>	
                                        <? if(empty($row['superadmin_cat_id'])) {?>
                                                    <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=addslashes(ucfirst(strtolower($row['category'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_plan_type_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> <? } ?><?php } ?>
													
                                                </td> 
                                            </tr>
                                  <?php }

                                    }
                                    ?>
                                    <input type="hidden" id="plan_type_id" name="plan_type_id" class="email_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                                    <tr>
                                        <td colspan="2">
                                            <div id="p_scents">
											<?php if(empty($plan_type) || ($plan_type)== 0)
											{
											?>
                                                <p>
                                                    <label for="p_scnts">
                                                    <input type="text" class="form-control parsley-validated" data-required="required" name="plan_type[0]" id="plan_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['category'] : ''?>" />
                                                    </label>
                                                    
                                                </p>
											<?php } ?>	
                                            </div>
                                        </td>
                                    </tr>
									<tr>
										<td>
											<a href="#" id="addScnt" title="Add Category" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Category</a>
										</td>
										<td>&nbsp;</td>
									</tr>
									
                                    <tr>
                                        <td><input type="submit" style="width:auto;" onclick="return setdefaultdata('plan_form_<?php echo $viewname;?>');" class="btn btn-primary margin_tops" value="Save" name="type" ></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
			
                <!-- STATUS-->
                <!--<form  class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_status?>" >
                 <div class="col-md-6">
                   <div class="mrg-bottom-40">
                      <div class="portlet-header">
                                <h3><?php echo $this->lang->line('sub_category')?></h3>
                            </div>
                      <div class="portlet-content">
					  
					  		<div class="col-md-12 addmoresubcatdata">
								<div class="row">
								<div class="col-md-5">
									<label for="text-input"><b><?php echo $this->lang->line('common_label_title')?></b></label>
								</div>
								<div class="col-md-4">
								<label for="text-input"><b>Parent</b></label>
									
								</div>
								<div class="col-md-3">
									
									<label><b><?php echo $this->lang->line('common_label_action')?></b></label>
									
								</div>
								 <?php
                                 	if(!empty($category_list))
									{
										
									for($i=0;$i<count($category_list);$i++){ ?>
									
					  					
								<div class="space"></div>
									<div id="flash_sub_cat"></div>
									<div id="show"></div>
								<div class="col-md-5 margin-top-2">
									<input type="text" class="form-control parsley-validated" data-required="required" name="categoryname" id="category_id_<?php echo $i; ?>" value="<?php echo $category_list[$i]['category'];?>"<?php if($category_list[$i]['user_type']=='1'){ ?> readonly <?php }?>/>
								</div>
								<div class="col-md-4 margin-top-2">
									<?php if($category_list[$i]['user_type']=='1'){?>
									<select  class="form-control parsley-validated" name="slt_category_type" id="slt_category_type_<?php echo $i; ?>"  disabled="disabled">	
										<?php foreach($plan_type as $row1){ ?>
										<option value="<?php echo $row1['id'];?>" <?php if(!empty($category_list[$i]['parent']) && $category_list[$i]['parent'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?> </option>
										<?php }}else{?>
									<select  class="form-control parsley-validated" name="slt_category_type" id="slt_category_type_<?php echo $i; ?>" >	
										<?php foreach($plan_type as $row1){ 
										if($row1['user_type']!=1){?>
										<option value="<?php echo $row1['id'];?>" <?php if(!empty($category_list[$i]['parent']) && $category_list[$i]['parent'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?> </option>
									<?php }}}?>
									
										
									</select>
									<input type="hidden" name="cat_id" value="<?=isset($category_list) ? $category_list[$i]['id']:''?>" id="cat_id" />
								</div>
								<div class="col-md-3 margin-top-2">
									<?php if($category_list[$i]['user_type']!='1'){ ?> 
									<a href="javascript:void(0);" onclick="get_submit_status(<?php echo $category_list[$i]['id'] ?>,<?php echo $i; ?>)" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i></a>
									
                                           <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=addslashes($category_list[$i]['category'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_status_record/<?php echo  $category_list[$i]['id'] ?>');"> <i class="fa fa-times"></i> </a>
								<?php } ?>		   
									
								</div>
								<?php } }?>
								<div class="col-md-5 margin-top-2 ">
									
									<?php if(empty($category_list) || ($category_list) == 0)
									{ ?>
									<input type="text" class="form-control parsley-validated" data-required="required" name="category_name[]" id="category_name[]" value="<?=isset($editStatusRecord) ? $editStatusRecord[0]['category'] : ''?>"/>
								</div>
								<div class="col-md-4 margin-top-2">
									<select  class="form-control parsley-validated" name="slt_category_type[]" id="slt_interaction_type" >	
										 
										<?php foreach($plan_type as $row1){ ?>
										<option value="<?php echo $row1['id'];?>"><?php echo $row1['category'];?></option>
										<?php }?>
									</select>
								<?php } ?>	
								</div>
								<div class="col-md-3">
									<div class="margin-top-2">
									
										   
									</div>
								</div>
								
								
								</div>
							</div>
					  			
			<div class="col-sm-12 topnd_margin">
			
			 <a href="#" id="addScnt_status"  title="Add Sub Category" class="text_color_red text_size"><i class="fa fa-plus-square"></i> Add Sub Category</a> </div>
           
                             <div class="col-md-12 clear margin-top-10">
                             <input type="submit" style="width:auto;" class="btn btn-primary" value="Save" name="type">
                             </div>
                            </div>
                   </div>
                 </div>
                </form>-->
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
                    //i--;
                }
                return false;
        });
    });
</script>

<!-- ==== Status INPUT ADD ===== -->
<script type="text/javascript">
	
	var catsubcatcount = 0;
    $(function() {
		$('body').on('click', '#addScnt_status', function(){
		
		innerhtml = '';
		
		innerhtml += '<div class="padding-top-2 clear row catsubcatdiv_'+catsubcatcount+'">';
			innerhtml += '<div class="col-md-5 ">';
			innerhtml += '<input type="text" class="form-control parsley-validated" name="category_name[]" /></div>';
			innerhtml += '<div class="col-md-4 ">';
				innerhtml += '<select  class="form-control parsley-validated" name="slt_category_type[]" id="slt_category">';
							<?php foreach($plan_type as $row1){ ?>
                        innerhtml += '<option value="<?php echo $row1['id'];?>"><?php echo $row1['category'];?></option>';
							<?php }?>
				innerhtml += '</select>';
			innerhtml += '</div>';
			innerhtml += '<div class="col-md-3"> <button value="catsubcatdiv_'+catsubcatcount+'" class="btn btn-xs btn-primary remScnt"><i class="fa fa-times"></i></button></div>';
		innerhtml += '</div>';
		
		$(innerhtml).appendTo('.addmoresubcatdata');
		
		catsubcatcount++;
		return false;
        }); 
		     
		$('body').on('click', '.remScnt', function(){ 
			$(this).closest('div.'+this.value).remove();
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

    function get_submit_status(cat_id,cat_name)
    {
	var parent_id = $("#slt_category_type_"+cat_name).val();
	var id = cat_id;
	var category_name = $("#category_id_"+cat_name).val();
	if(category_name=='' && id=='')
	{
            alert("Enter text..");
            $("#slt_category_type").focus();
	}
	else
	{
            $("#flash_sub_cat").show();
            $("#flash_sub_cat").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>admin/<?=$viewname;?>/update_status',
                data: { category_name:category_name,id:id,parent_id:parent_id },
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
	function setdefaultdata(id)
	{
		 if ($('#'+id).parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
	}
</script>

<!-- ================== END Ajax Script ================== -->