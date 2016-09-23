<?php
    isset($editRecord) ? $loadcontroller='update_plan_type' : $loadcontroller='insert_plan_type';
    $path_plan_type = $viewname."/".$loadcontroller;
    isset($editStatusRecord) ? $loadcontroller='update_status' : $loadcontroller='insert_status';
    $path_status = $viewname."/".$loadcontroller;
?>

  
	
	
     <div class="portlet">
      <div class="portlet-header">
      
       
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
                                                    
                                                    <input type="text" class="form-control parsley-validated" name="plantype" id="plantype_<?=$row['id']?>" value="<?php echo  $row['category'] ?>" <?php if($row['user_type']=='1'){ ?> readonly <?php }?> />
                                                </td>
                                                <td>
										<?php if($row['user_type']!='1'){ ?>	
                                                    <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['category'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_plan_type_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> <?php } ?>
													
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
									<a href="javascript:void(0);" onclick="get_submit_status(<?php echo $category_list[$i]['id'] ?>,<?php echo $i; ?>)" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
									
                                           <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($category_list[$i]['category'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_status_record/<?php echo  $category_list[$i]['id'] ?>');"> <i class="fa fa-times"></i> </a>
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
                </form>
            </div>
        </div>
       </div>
	</div>


