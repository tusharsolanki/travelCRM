<?php
/*
    @Description: Admin add/edit page
    @Author: Mohit Trivedi
    @Date: 01-09-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'insert_rights':'insert_rights'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
?>
<style>
.view_module{display:none;}
.hide_module{display:none;}
.checkall label{cursor:pointer;}
.checkall label:hover{text-decoration:underline;}
.heading{background-color:#e8f6ee;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('user_right_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('user_right_add_head');}
	  			 	else if(!empty($insert_data)){ echo $this->lang->line('user_right_add_head'); } 
	   				else{ echo $this->lang->line('user_right_edit_head'); }?>
            </h3>
            <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span> </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
                <div class="row tab-pane fade in active" id="home">
                  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
                    <div class="row">
                    <div class="col-sm-6">
                      <label for="text-input">
                        <?=$this->lang->line('common_label_user');?>
                       </label>
                       <? if(!empty($admin_name)) { $dis='style="display:none;"';
					    echo '<br>'.$admin_name[0]['admin_name'].'  ('.$admin_name[0]['email_id'].')';
					   }else{ $dis='';}
					  ?>
                       <div <?=$dis?> >
                      <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='user_id[]' id='user_id'>
                        <!--<option value=''>Select Employee</option>-->
                        <?php 
						if(isset($admin_list) && count($admin_list) > 0){
				
							foreach($admin_list as $row){
								if(!empty($row['id'])){?>
                        <option value='<?php echo $row['id'];?>' <?php if(!empty($editRecord['user_id']) && ($editRecord['user_id'] == $row['id'])){ echo "selected";}?> >
                        <?=$row['admin_name']." (".$row['email_id'].")"?>
                        </option>
                        <?php 		}
							}
						} ?>
                      </select>
                      </div>
                    </div>
                  </div>
                    <div class="row checkall">
                    <div class="col-sm-2">
                      <label for="check_all" id="check_all">
                        Check All
                       </label>
                       <!--<input type="checkbox" id="check_all" class="" name="check_all" value="">-->
                    </div>
                    <div class="col-sm-2">
                      <label for="uncheck_all" id="uncheck_all">
                        Uncheck All
                       </label>
                      <!-- <input type="checkbox" id="uncheck_all" class="" name="uncheck_all" value="">-->
                    </div>
                    </div>
	               
                    <table class="table table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                     <thead class="heading">
                      <tr>
                      	<th>Sr. No.</th>
                        <th>Module Name</th>
                        <th>Sub Module Name</th>
                      	<th>All</th>
                        <th>View</th>
                        <th>Add</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                      </thead>
                       <tbody role="alert" aria-live="polite" aria-relevant="all">
                      <?php 
                      $dissub='';
					  if(!empty($datalist) && count($datalist)>0){
						
							@$i=!empty($this->router->uri->segments[4])?($this->router->uri->segments[3] == 'edit_right')?$this->router->uri->segments[5]+1:$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row)
					  {
						  if(!empty($row['module_right']))
						  {
								if(!empty($row['module_right']))
								{
									$module_right=	explode(',',$row['module_right']);
									$module_right_id=	explode(',',$row['module_right_id']);
									$com = array_combine($module_right, $module_right_id);
									//pr($assign_rights);
									$disbled='';
									$maincls=$row['module_unique_name'];
									if($maincls == 'configuration_listing_manager' || $maincls =='market_watch' || $maincls =='user_management' || $maincls =='configuration_contact' || $maincls =='configuration_template_library' || $maincls =='text_response' || $maincls =='analytics')
										{$disp='hide_module';}else{$disp='';}
									if($maincls == 'contact' && in_array(!empty($com['view'])?$com['view']:'',$assign_rights))
								    {$disbled='disabled="disabled"';}
                  if($maincls == 'bomb_bomb_email_blast')
                    {if(empty($bomb_bomb_conection)){$disp='hide_module';}else{$disp='';}$dissub='yes';}
								}
								$ck_all='';
						  ?>
                           <?php  
						   if(count($com) == 4)
						   {
							   if(isset($editRecord)){ if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['add'])?$com['add']:'',$assign_rights) && in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights) && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights))) { 
							   
							   $ck_all='checked="checked"'; 
							   /*if($maincls == 'contact')
							   {
								?>
                                
                                <input class="hide_module" type="checkbox" checked="checked" value="<?=!empty($row['id'])?$row['id']:'';?>" name="chk_right[]" />
                                   <input class="hide_module" type="checkbox" checked="checked" value="<?=!empty($com['view'])?$com['view']:'';?>" name="chk_right[]" />
                                   <input class="hide_module" type="checkbox" checked="checked" value="<?=!empty($com['edit'])?$com['edit']:'';?>" name="chk_right[]" />
                                   <input class="hide_module" type="checkbox" checked="checked" value="<?=!empty($com['add'])?$com['add']:'';?>" name="chk_right[]" />
                                   <input class="hide_module" type="checkbox" checked="checked" value="<?=!empty($com['delete'])?$com['delete']:'';?>" name="chk_right[]" />
                                <?   
							   }*/
						   } }
						   }
						   else
						   {
								
								if(isset($editRecord))
								{ 
									if(isset($com['view']) && isset($com['edit']) && isset($com['delete']))
									{if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights)  && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights) )) { $ck_all='checked="checked"'; } }
									else if(isset($com['view']) && isset($com['add']) && isset($com['delete']))
									{if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['add'])?$com['add']:'',$assign_rights)  && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights) )) { $ck_all='checked="checked"'; } }
									else if(isset($com['view']) && isset($com['delete']))
									{if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights))) { $ck_all='checked="checked"'; }}
									else if(isset($com['view']))
									{if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights))) { $ck_all='checked="checked"'; }}
									else{$ck_all='';}
								}
						   }
						   ?> 
                           
                     		 <tr  class="<?=$disp?>" id="module_<?php echo  $row['id'] ?>" > 
                                <td><?=$i?></td>
                                <td class="hidden-xs hidden-sm" id="temp_name_<?php echo  $row['id'] ?>">
								  <?=!empty($row['module_name'])?ucfirst(strtolower($row['module_name'])):'';?>
                                  </td>
                                <td class="hidden-xs hidden-sm" >&nbsp;</td>
                                <td class="hidden-xs hidden-sm" >
                                    <input <?=$ck_all?> <?=$disbled?> type="checkbox" class="mycheckbox all  <?=!empty($maincls)?'all_'.$maincls:'';?>" name="all" value="<?=!empty($row['id'])?$row['id']:'';?>"/>
                                    <input type="checkbox" class="mycheckbox main view_module  <?=!empty($maincls)?'view_'.$maincls:'';?>" <?php  if(isset($editRecord)){ if(in_array(!empty($com['view'])?$com['view']:'',$assign_rights)) {?> checked="" <?php } }?>  name="chk_right[]" value="<?=!empty($com['view'])?$com['view']:'';?>"/>
                                </td>
                                <td class="hidden-xs hidden-sm" >
                                    <? if(in_array('view',$module_right)){
										if(!empty($maincls) && $maincls == 'template_library'){
									?>
                                         <input class="hide_module hide_module_class" type="checkbox" value="<?=!empty($row['id'])?$row['id']:'';?>" name="chk_right[]" <?=!empty($view_communication)?'checked="checked"':'disabled="disabled"'?>  />
									<?php }  ?>
                                    <input  <?=$disbled?> class="<?=!empty($maincls)?'view_'.$maincls:'';?> all_check main parent_view view_<?=!empty($row['id'])?$row['id']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($row['id'])?$row['id']:'',$assign_rights)) { if($maincls == 'communications') { $view_communication = 'disabled="disabled"'; } if(!empty($maincls) && $maincls == 'email_blast')	$email_blast = '1';if(!empty($maincls) && $maincls == 'bomb_bomb_email_blast')	$bomb_email_blast = '1'; if(!empty($maincls) && $maincls == 'text_blast') $text_blast = '1'; ?> checked="" <?php } }?> value="<?=!empty($row['id'])?$row['id']:'';?>" name="chk_right[]" <?php if(!empty($view_communication) && !empty($maincls) && $maincls == 'template_library') echo 'disabled="disabled"'; ?> /><? } else{echo 'N/A';}?>
                                    <?
                                    if($maincls == 'contact')
							   		{
								?>
                                        <input class="hide_module hide_module_contacts" type="checkbox" checked="checked" value="<?=!empty($row['id'])?$row['id']:'';?>" name="chk_right[]" />
                                           <input class="hide_module hide_module_contacts" type="checkbox" checked="checked" value="<?=!empty($com['view'])?$com['view']:'';?>" name="chk_right[]" />
                                           <input class="hide_module hide_module_contacts" type="checkbox" checked="checked" value="<?=!empty($com['edit'])?$com['edit']:'';?>" name="chk_right[]" />
                                           <input class="hide_module hide_module_contacts" type="checkbox" checked="checked" value="<?=!empty($com['add'])?$com['add']:'';?>" name="chk_right[]" />
                                           <input class="hide_module hide_module_contacts" type="checkbox" checked="checked" value="<?=!empty($com['delete'])?$com['delete']:'';?>" name="chk_right[]" />
                                <?   
							   }
									?>
                              </td>
                                <td class="hidden-xs hidden-sm" >
                                <? if(in_array('add',$module_right)){ ?><input  <?=$disbled?> class="<?=!empty($maincls)?'add_'.$maincls:'';?> all_check parent_add add_<?=!empty($com['view'])?$com['view']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['add'])?$com['add']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['add'])?$com['add']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                            </td>
                                <td class="hidden-xs hidden-sm" >
                                <? if(in_array('edit',$module_right)){ ?><input  <?=$disbled?> class="<?=!empty($maincls)?'edit_'.$maincls:'';?> all_check parent_edit edit_<?=!empty($com['view'])?$com['view']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['edit'])?$com['edit']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                            </td>
                                <td class="hidden-xs hidden-sm" >
                                <? if(in_array('delete',$module_right)){ ?><input  <?=$disbled?> class="<?=!empty($maincls)?'delete_'.$maincls:'';?> all_check parent_delete delete_<?=!empty($com['view'])?$com['view']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['delete'])?$com['delete']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                            </td>
	                         </tr>
                           <?
                           
						   //Call sub module
						   if(!empty($row['id'])) 
						   {
							  /* $table='module_master';
							   $where = array("module_parent"=>$row['id']);
							   $fields = array('*,GROUP_CONCAT(case when module_right="" then null else module_right end) module_right,GROUP_CONCAT(case when module_right="" then null else id end) module_right_id');
							   $group_by='module_id';	
							   $subdatalist=$this->module_master_model->getmultiple_tables_records($table,$fields,'','','','','',$config['per_page'], $uri_segment,'id','desc',$group_by,$where);   */
							    $table='module_master as m1';
								$join_tables = array(
												'module_master as m2' 	=> 'm1.id= m2.module_id',
											);
								$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) as module_right,GROUP_CONCAT(case when m2.module_right="" then null else m2.id end) module_right_id');
								
								$group_by='m2.module_id';
								//$where = "m1.module_parent = 0";
								$where = array("m1.module_parent"=>$row['id']);
								$subdatalist=$this->module_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.id','asc',$group_by,$where);
								//echo $this->db->last_query();pr($subdatalist);exit;
								
							   if(!empty($subdatalist))
							   {
								   foreach($subdatalist as $row1)
									{
										if(!empty($row1['module_right']))
										{
											$subcls=$row1['module_unique_name'];
											if(/*$maincls == 'lead_dashboard' || $subcls == 'import_contacts' || $subcls == 'premium_plans' || $subcls == 'play_push_stop' ||*/$subcls == 'premium_plans' || $subcls == 'lead_distribution_agent' || $subcls == 'lead_distribution_lender')
								            {$dis1='style="display:none;"';}else{$dis1='';$i++;}
											if($subcls == 'bomb_bomb_library')
                      {if(empty($bomb_bomb_conection) || empty($dissub)){$dis1='style="display:none;"';}else{$dis1='';}
                      }
									   ?>
                                          <tr <?=$dis1?>  > 
                                            <!--<td class="">
                                                  <div class="text-center">
                                                      <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row1['id'] ?>">
                                                  </div>
                                                </td>-->
                                                <td><?=$i?></td>
                                           
                                            <td class="hidden-xs hidden-sm" ></td>
                                             <td class="hidden-xs hidden-sm" id="temp_name_<?php echo  $row1['id'] ?>">
                                              <?=!empty($row1['module_name'])?ucfirst(strtolower($row1['module_name'])):'';?>
                                              </td>
                                            <?
                                            if(!empty($row1['module_right']))
                                            {
                                                $module_right=	explode(',',$row1['module_right']);
                                                $module_right_id=	explode(',',$row1['module_right_id']);
												$com = array_combine($module_right, $module_right_id);
												
												//pr($assign_rights);
												
                                            }
                                            ?>
                                             <td class="hidden-xs hidden-sm" >
                                             <?
                                             if($row1['module_unique_name'] == 'all_channels')
											 {$cls=$row1['module_unique_name'];}else{$cls='';}
											 if($row['module_unique_name'] == 'social' && $row1['module_unique_name'] != 'all_channels')
											 {$cls1=$row['module_unique_name'];}else{$cls1=$row1['module_unique_name'];}
											  $subck_all='';
											 ?>
                                              <?php  
                                            if(count($com) == 4)
                                            {
                                            if(isset($editRecord)){ if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['add'])?$com['add']:'',$assign_rights) && in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights) && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights))) { $subck_all='checked="checked"'; } }
                                            }
                                            else
                                            {

                                                         if(isset($editRecord))
                                                         { 
                                                                 if(isset($com['view']) && isset($com['edit']) && isset($com['delete']))
                                                                 {if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights)  && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights) )) { $subck_all='checked="checked"'; } }
                                                                 else if(isset($com['view']) && isset($com['add']) && isset($com['delete']))
                                                                 {if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['add'])?$com['add']:'',$assign_rights)  && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights) )) { $subck_all='checked="checked"'; } }
                                                                 else if(isset($com['view']) && isset($com['delete']))
                                                                 {if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights))) { $subck_all='checked="checked"'; }}
                                                                 else if(isset($com['view']) && isset($com['edit']))
                                                                 {if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights) && in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights))) { $subck_all='checked="checked"'; }}
                                                                 else if(isset($com['view']))
                                                                 {if((in_array(!empty($com['view'])?$com['view']:'',$assign_rights))) { $subck_all='checked="checked"'; }}
                                                                 else{$subck_all='';}
                                                         }
                                            }
                                            ?> 
                                               <input <?=$subck_all?> readonly="readonly" type="checkbox" class="sub_<?=!empty($row['id'])?$row['id']:'';?> mycheckbox all <?=$cls?> <?=!empty($cls1)?'all_'.$cls1:'';?>" name="all" value="">
                                                 <input type="checkbox" class="<?=!empty($cls)?'view_'.$cls:'';?> <?=!empty($cls1)?'view_'.$cls1:'';?> mycheckbox main view_module sub_<?=!empty($row['id'])?$row['id']:'';?>" <?php  if(isset($editRecord)){ if(in_array(!empty($com['view'])?$com['view']:'',$assign_rights)) {?> checked="" <?php } }?>  name="chk_right[]" value="<?=!empty($com['view'])?$com['view']:'';?>">
                                            </td>
                                            <td class="hidden-xs hidden-sm" >
                                                <? if(in_array('view',$module_right)){ ?>
                                                <?php
													if(!empty($maincls) && $maincls == 'template_library'){
														//pr($assign_rights);
												?>
														<input class="hide_module hide_module_class <?=$subcls?>" type="checkbox" value="<?=!empty($row1['id'])?$row1['id']:'';?>" name="chk_right[]" checked="checked" <? if(!empty($view_communication) || (!empty($email_blast) && !empty($subcls) && $subcls == 'email_library') || (!empty($bomb_email_blast) && !empty($subcls) && $subcls == 'bomb_bomb_library') || (!empty($text_blast) && !empty($subcls) && $subcls == 'sms_texts')) echo 'checked="checked"'; else echo 'disabled="disabled"'; ?>  />
                                                <?php } ?>
                                                <input class="sub_<?=!empty($row['id'])?$row['id']:'';?> <?=!empty($cls)?'view_'.$cls:'';?> <?=!empty($cls1)?'view_'.$cls1:'';?> all_check main subview_<?=!empty($row['id'])?$row['id']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($row1['id'])?$row1['id']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($row1['id'])?$row1['id']:'';?>" name="chk_right[]" <? if(!empty($view_communication) && !empty($maincls) && $maincls == 'template_library') echo $view_communication; elseif(!empty($email_blast) && !empty($subcls) && $subcls == 'email_library') echo 'disabled="disabled"'; elseif(!empty($bomb_email_blast) && !empty($subcls) && $subcls == 'bomb_bomb_library') echo 'disabled="disabled"'; elseif(!empty($text_blast) && !empty($subcls) && $subcls == 'sms_texts') echo 'disabled="disabled"'; ?> /><? } ?>
                                            </td>
                                             <td class="hidden-xs hidden-sm" >
                                                <? if(in_array('add',$module_right)){ ?><input class="sub_<?=!empty($row['id'])?$row['id']:'';?> <?=!empty($cls)?'add_'.$cls:'';?> <?=!empty($cls1)?'add_'.$cls1:'';?> all_check subadd_<?=!empty($row['id'])?$row['id']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['add'])?$com['add']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['add'])?$com['add']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                                            </td>
                                             <td class="hidden-xs hidden-sm" >
                                                <? if(in_array('edit',$module_right)){ ?><input class="sub_<?=!empty($row['id'])?$row['id']:'';?> <?=!empty($cls)?'edit_'.$cls:'';?> <?=!empty($cls1)?'edit_'.$cls1:'';?> all_check subedit_<?=!empty($row['id'])?$row['id']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['edit'])?$com['edit']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['edit'])?$com['edit']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                                            </td>
                                             <td class="hidden-xs hidden-sm" >
                                                <? if(in_array('delete',$module_right)){ ?><input class="sub_<?=!empty($row['id'])?$row['id']:'';?> <?=!empty($cls)?'delete_'.$cls:'';?> <?=!empty($cls1)?'delete_'.$cls1:'';?> all_check subdelete_<?=!empty($row['id'])?$row['id']:'';?>" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($com['delete'])?$com['delete']:'',$assign_rights)) {?> checked="" <?php } }?> value="<?=!empty($com['delete'])?$com['delete']:'';?>" name="chk_right[]" /><? } else{echo 'N/A';}?>
                                            </td>
                                            
                                          </tr>
                                          <? 
										}
									}  $i++;
						 		} 
								else
								{$i++;}
							}
						   ?>
                      <?php } } }else {?>
                      <tr>
                        <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                      </tr>
                      <?php } ?>
                        </tbody>
                      
                    </table>
                    <div class="col-sm-12 pull-left text-center margin-top-10">
                    <?
                    $id=$this->uri->segment(4);
					?>
                    <input type="hidden" name="id" value="<?=!empty($id)?$id:''?>" />
                  	<input type="submit" class="btn btn-secondary-green" title="Save" value="Save" onclick="return showloading();" name="submitbtn" />
                  		<a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
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
        //For check all check box
		$('#check_all').click(function(){
			 $('#DataTables_Table_0 input:checkbox').each(function(){
			 	   this.checked=true;
			 });
			$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').prop('checked',true);
			$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').attr('disabled',true);
			$('.hide_module_class').removeAttr('disabled');
			$('.hide_module_class').prop('checked',true);

			 $("#uncheck_all").prop("checked", false);
		});
		//For uncheck all check box
		$('#uncheck_all').click(function(){
			 $('#DataTables_Table_0 input:checkbox').each(function(){
			 	 this.checked=false;
			 });
			$('.all_contact,.view_contact,.add_contact,.edit_contact,.delete_contact,.hide_module_contacts').prop('checked',true);
			$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').removeAttr('disabled');
			$('.hide_module_class').attr('disabled',true);
			$("#check_all").prop("checked", false);
		});
		
		$('.all').click(function(){
			var id=$(this).val();
			if($(this).is(':checked'))
			{
				$(this).parent().parent().children().children().prop('checked',true);
				$('.sub_'+id).prop('checked',true);
			}
			else
			{
				$(this).parent().parent().children().children().prop('checked',false);
				$('.sub_'+id).prop('checked',false);
				
				if($('.view_template_library').is('[disabled=disabled]'))
					$('.view_template_library').prop('checked',true);	
				if($('.view_email_library').is('[disabled=disabled]'))
					$('.view_email_library').prop('checked',true);
				if($('.view_auto_responder').is('[disabled=disabled]'))
					$('.view_auto_responder').prop('checked',true);
				if($('.view_envelope_library').is('[disabled=disabled]'))
					$('.view_envelope_library').prop('checked',true);
				if($('.view_social_media_posts').is('[disabled=disabled]'))
					$('.view_social_media_posts').prop('checked',true);
				if($('.view_phone_call_scripts').is('[disabled=disabled]'))
					$('.view_phone_call_scripts').prop('checked',true);
				if($('.view_sms_texts').is('[disabled=disabled]'))
					$('.view_sms_texts').prop('checked',true);
				if($('.view_label_library').is('[disabled=disabled]'))
					$('.view_label_library').prop('checked',true);
				if($('.view_letter_library').is('[disabled=disabled]'))
					$('.view_letter_library').prop('checked',true);
				if($('.view_bomb_bomb_library').is('[disabled=disabled]'))
					$('.view_bomb_bomb_library').prop('checked',true);
                                if($('.view_sms_auto_responder').is('[disabled=disabled]'))
					$('.view_sms_auto_responder').prop('checked',true);
				
				$('.hide_module_class').prop('checked','checked');
			}
		});
		$('.all_check').click(function(){
			if($(this).is(':checked'))
			{
				$(this).parent().parent().children().children('.main').prop('checked',true);
			}
		});
		$('.main').click(function(){
			if($(this).is(':checked'))
			{
				$(this).parent().parent().children().children('.view_module').prop('checked',true);
				var value=$(this).val();
			}
			else
			{
				$(this).parent().parent().children().children('.view_module').prop('checked',false);
				$(this).parent().parent().children().children('.all_check').prop('checked',false);
				$(this).parent().parent().children().children('.all').prop('checked',false);
				
			}
		});
		$('.parent_view').click(function(){
			var value=$(this).val();
			if($(this).is(':checked'))
			{
				$('.subview_'+value).prop('checked',true);
			}
			else
			{
				$('.subview_'+value).prop('checked',false);
				$('.sub_'+value).prop('checked',false);
				
				if($('.view_email_library').is('[disabled=disabled]'))
					$('.view_email_library').prop('checked',true);
				if($('.view_auto_responder').is('[disabled=disabled]'))
					$('.view_auto_responder').prop('checked',true);
				if($('.view_envelope_library').is('[disabled=disabled]'))
					$('.view_envelope_library').prop('checked',true);
				if($('.view_social_media_posts').is('[disabled=disabled]'))
					$('.view_social_media_posts').prop('checked',true);
				if($('.view_phone_call_scripts').is('[disabled=disabled]'))
					$('.view_phone_call_scripts').prop('checked',true);
				if($('.view_sms_texts').is('[disabled=disabled]'))
					$('.view_sms_texts').prop('checked',true);
				if($('.view_label_library').is('[disabled=disabled]'))
					$('.view_label_library').prop('checked',true);
				if($('.view_letter_library').is('[disabled=disabled]'))
					$('.view_letter_library').prop('checked',true);
				if($('.view_bomb_bomb_library').is('[disabled=disabled]'))
					$('.view_bomb_bomb_library').prop('checked',true);
                                if($('.view_sms_auto_responder').is('[disabled=disabled]'))
					$('.view_sms_auto_responder').prop('checked',true);
					
				$('.hide_module_class').prop('checked','checked');
			}
		});
		$('.parent_add').click(function(){
			var val=$(this).parent().parent().attr('id');
			var val1=val.split('_');
			var value=val1[1];
			if($(this).is(':checked'))
			{
				$('.subadd_'+value).prop('checked',true);
			}
			else
			{
				$('.subadd_'+value).prop('checked',false);
			}
		});
		$('.parent_edit').click(function(){
			var val=$(this).parent().parent().attr('id');
			var val1=val.split('_');
			var value=val1[1];
			if($(this).is(':checked'))
			{
				$('.subedit_'+value).prop('checked',true);
			}
			else
			{
				$('.subedit_'+value).prop('checked',false);
			}
		});
		$('.parent_delete').click(function(){
			var val=$(this).parent().parent().attr('id');
			var val1=val.split('_');
			var value=val1[1];
			if($(this).is(':checked'))
			{
				$('.subdelete_'+value).prop('checked',true);
			}
			else
			{
				$('.subdelete_'+value).prop('checked',false);
			}
		});
		/*$('.all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_social').prop('checked',true);
				$('.view_social').prop('checked',true);
				$('.add_social').prop('checked',true);
				$('.edit_social').prop('checked',true);
				$('.delete_social').prop('checked',true);
			}
			else
			{
				$('.all_social').prop('checked',false);
				$('.view_social').prop('checked',false);
				$('.add_social').prop('checked',false);
				$('.edit_social').prop('checked',false);
				$('.delete_social').prop('checked',false);
			}
		});*/
		//select all social sub module
		//Changes 26/2/2015
		
		/*$('.view_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.view_social').prop('checked',true);
			}
			else
			{
				$('.view_social').prop('checked',false);
			}
		});*/
		
		//Changes 26/2/2015
		/*$('.add_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.add_social').prop('checked',true);
			}
			else
			{
				$('.add_social').prop('checked',false);
			}
		});*/
		
		//Changes 26/2/2015
		
		/*$('.edit_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.edit_social').prop('checked',true);
			}
			else
			{
				$('.edit_social').prop('checked',false);
			}
		});*/
		
		//Changes 26/2/2015
		
		/*$('.delete_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.delete_social').prop('checked',true);
			}
			else
			{
				$('.delete_social').prop('checked',false);
			}
		});*/
		
		$('.view_social').click(function(){
			//Changes 26/2/2015
			
			//$('.view_all_channels').prop('checked',false);
			//$('.add_all_channels').prop('checked',false);
			//$('.edit_all_channels').prop('checked',false);
			//$('.delete_all_channels').prop('checked',false);
		});
		//Changes 26/2/2015
		/*$('.add_social').click(function(){
			$('.add_all_channels').prop('checked',false);
		});*/
		/*$('.edit_social').click(function(){
			$('.edit_all_channels').prop('checked',false);
		});*/
		/*$('.delete_social').click(function(){
			$('.delete_all_channels').prop('checked',false);
		});*/
		
		
		/*Select email library when select email blast*/
		$('.all_email_blast,.view_email_blast,.add_email_blast,.edit_email_blast,.delete_email_blast').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_email_library,.view_email_library,.add_email_library,.edit_email_library,.delete_email_library,.email_library').prop('checked',true);
				$('.view_email_library').attr('disabled',true);
				$('.email_library').removeAttr('disabled');
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
				$('.all_email_signature,.view_email_signature,.add_email_signature,.edit_email_signature,.delete_email_signature').prop('checked',true);
			}
			else
			{
				//$('.all_email_library,.view_email_library,.add_email_library,.edit_email_library,.delete_email_library').prop('checked',false);
				if($('.view_template_library').is('[disabled=disabled]'))
				{
				}
				else
				{
					$('.view_email_library').removeAttr('disabled');
					$('.email_library').attr('disabled',true);
				}
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
				$('.all_email_signature,.view_email_signature,.add_email_signature,.edit_email_signature,.delete_email_signature').prop('checked',false);}
		});
		/*Select email library when select email blast*/
		$('.all_bomb_bomb_email_blast,.view_bomb_bomb_email_blast,.add_bomb_bomb_email_blast,.edit_bomb_bomb_email_blast,.delete_bomb_bomb_email_blast').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_bomb_bomb_library,.view_bomb_bomb_library,.add_bomb_bomb_library,.edit_bomb_bomb_library,.delete_bomb_bomb_library,.bomb_bomb_library').prop('checked',true);
				$('.view_bomb_bomb_library').attr('disabled',true);
				$('.bomb_bomb_library').removeAttr('disabled');
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
				$('.all_email_signature,.view_email_signature,.add_email_signature,.edit_email_signature,.delete_email_signature').prop('checked',true);
			}
			else
			{
				//$('.all_bomb_bomb_library,.view_bomb_bomb_library,.add_bomb_bomb_library,.edit_bomb_bomb_library,.delete_bomb_bomb_library').prop('checked',false);
				if($('.view_template_library').is('[disabled=disabled]'))
				{
				}
				else
				{
					$('.view_bomb_bomb_library').removeAttr('disabled');
					$('.bomb_bomb_library').attr('disabled',true);
				}
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
				$('.all_email_signature,.view_email_signature,.add_email_signature,.edit_email_signature,.delete_email_signature').prop('checked',false);}
		});
		/*Select text library when select text blast*/
		$('.all_text_blast,.view_text_blast,.add_text_blast,.edit_text_blast,.delete_text_blast').click(function(){
			if($(this).is(':checked'))
			{
					$('.all_template_library,.view_template_library,.all_sms_texts,.view_sms_texts,.add_sms_texts,.edit_sms_texts,.delete_sms_texts,.sms_texts').prop('checked',true);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
				
				$('.view_sms_texts').attr('disabled',true);
				$('.sms_texts').removeAttr('disabled');
				
			}
			else
			{
				//$('.all_sms_texts,.view_sms_texts,.add_sms_texts,.edit_sms_texts,.delete_sms_texts').prop('checked',false);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',false);
				if($('.view_template_library').is('[disabled=disabled]'))
				{}
				else
				{
					$('.view_sms_texts').removeAttr('disabled');
					$('.sms_texts').attr('disabled',true);
				}

			}
		});
		/*Select text library when select text blast*/
		$('.all_letter,.view_letter,.add_letter,.edit_letter,.delete_letter').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_letter_library,.view_letter_library,.add_letter_library,.edit_letter_library,.delete_letter_library').prop('checked',true);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
			}
			else
			{
				$('.all_letter_library,.view_letter_library,.add_letter_library,.edit_letter_library,.delete_letter_library').prop('checked',false);	
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',false);	
			}
		});
		
		/*Select letter library when select letter blast*/
		
		$('.all_letter,.view_letter,.add_letter,.edit_letter,.delete_letter').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_letter_library,.view_letter_library,.add_letter_library,.edit_letter_library,.delete_letter_library').prop('checked',true);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
			}
			else
			{
				$('.all_letter_library,.view_letter_library,.add_letter_library,.edit_letter_library,.delete_letter_library').prop('checked',false);	
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',false);	
			}
		});
		
		/*Select enevelope library when select enevelop blast*/
		
		$('.all_envelope,.view_envelope,.add_envelope,.edit_envelope,.delete_envelope').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_envelope_library,.view_envelope_library,.add_envelope_library,.edit_envelope_library,.delete_envelope_library').prop('checked',true);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
			}
			else
			{
				$('.all_envelope_library,.view_envelope_library,.add_envelope_library,.edit_envelope_library,.delete_envelope_library').prop('checked',false);	
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',false);	
			}
		});
		
		/*Select label library when select label blast*/
		
		$('.all_label,.view_label,.add_label,.edit_label,.delete_label').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_template_library,.view_template_library,.all_label_library,.view_label_library,.add_label_library,.edit_label_library,.delete_label_library').prop('checked',true);
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',true);
			}
			else
			{
				$('.all_label_library,.view_label_library,.add_label_library,.edit_label_library,.delete_label_library').prop('checked',false);	
				$('.all_configuration_template_library,.view_configuration_template_library,.add_configuration_template_library,.edit_configuration_template_library,.delete_configuration_template_library').prop('checked',false);	
			}
		});
		
		/*Select conatct master library when select contacts*/
		
		$('.all_contact,.view_contact,.add_contact,.edit_contact,.delete_contact').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_configuration_contact,.view_configuration_contact,.add_configuration_contact,.edit_configuration_contact,.delete_configuration_contact').prop('checked',true);
			}
			else
			{
				$('.all_configuration_contact,.view_configuration_contact,.add_configuration_contact,.edit_configuration_contact,.delete_configuration_contact').prop('checked',false);	
			}
		});
		
		/*Select listing manager library when select listing manager*/
		
		$('.all_listing_manager,.view_listing_manager,.add_listing_manager,.edit_listing_manager,.delete_listing_manager').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_configuration_listing_manager,.view_configuration_listing_manager,.add_configuration_listing_manager,.edit_configuration_listing_manager,.delete_configuration_listing_manager').prop('checked',true);
			}
			else
			{
				$('.all_configuration_listing_manager,.view_configuration_listing_manager,.add_configuration_listing_manager,.edit_configuration_listing_manager,.delete_configuration_listing_manager').prop('checked',false);	
			}
		});
		/*Select social account library when select social*/
		
		//Changes 26/2/2015
		
		/*
		
		$('.all_social,.view_social,.add_social,.edit_social,.delete_social,.all_all_channels,.view_all_channels,.add_all_channels,.edit_all_channels,.delete_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_social_account,.view_social_account,.add_social_account,.edit_social_account,.delete_social_account').prop('checked',true);
			}
			
		});
		
		*/
		
		
		$('.all_social,.view_social,.add_social,.edit_social,.delete_social,.all_all_channels,.view_all_channels,.add_all_channels,.edit_all_channels,.delete_all_channels').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_social_account,.view_social_account,.add_social_account,.edit_social_account,.delete_social_account').prop('checked',true);
			}
			
		});
		$('.view_social').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_social_account,.view_social_account,.add_social_account,.edit_social_account,.delete_social_account').prop('checked',true);
			}
			else
			{
				$('.all_social_account,.view_social_account,.add_social_account,.edit_social_account,.delete_social_account').prop('checked',false);	
			}
		});
                
		$('.all_auto_responder,.view_auto_responder,.add_auto_responder,.edit_auto_responder,.delete_auto_responder').click(function(){
			if($(this).is(':checked'))
			{
				$('.all_email_library,.view_email_library,.add_email_library,.edit_email_library,.delete_email_library').prop('checked',true);
			}
			else
			{
				/*if($('.view_email_blast').is(':checked'))
			    {}
				else
				{ $('.all_email_library,.view_email_library,.add_email_library,.edit_email_library,.delete_email_library').prop('checked',false);	
                                    
                                }*/
			}
		});
                
                $('.all_sms_auto_responder,.view_sms_auto_responder,.add_sms_auto_responder,.edit_sms_auto_responder,.delete_sms_auto_responder').click(function(){
                    if($(this).is(':checked'))
                    {
                            $('.all_sms_texts,.view_sms_texts,.add_sms_texts,.edit_sms_texts,.delete_sms_texts').prop('checked',true);
                    }
		});
		
		$('.all_social_account').click(function(){
			if($(this).is(':checked') == false)
			{
				id = $('.all_social').val();
				$('.sub_'+id).prop('checked',false);
				$('.view_'+id).prop('checked',false);
				$('.all_social').prop('checked',false);
				
			}
		});
		
		$('.all_communications,.view_communications,.add_communications,.edit_communications,.delete_communications').click(function(){
			if($('.view_communications').is(':checked'))
			{
				$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').prop('checked',true);
				$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').attr('disabled',true);
				$('.hide_module_class').removeAttr('disabled');
				$('.view_module').removeAttr('disabled');
				$('.hide_module_class').prop('checked',true);

			}
			else
			{
				$('.view_email_library,.view_auto_responder,.view_envelope_library,.view_social_media_posts,.view_phone_call_scripts,.view_sms_texts,.view_label_library,.view_letter_library,.view_template_library,.view_bomb_bomb_library,.view_sms_auto_responder').removeAttr('disabled');
				$('.hide_module_class').attr('disabled',true);
				if($('.view_email_blast').is(':checked'))
				{
					$('.email_library').prop('checked',true);
					$('.email_library').removeAttr('disabled');
					$('.view_email_library').attr('disabled',true);
				}
				if($('.view_text_blast').is(':checked'))
				{
					$('.sms_texts').prop('checked',true);
					$('.sms_texts').removeAttr('disabled');
					$('.view_sms_texts').attr('disabled',true);
				}
				if($('.view_bomb_bomb_email_blast').is(':checked'))
				{
					$('.bomb_bomb_library').prop('checked',true);
					$('.bomb_bomb_library').removeAttr('disabled');
					$('.view_bomb_bomb_library').attr('disabled',true);
				}
			}
		});
		
		
 });
  function showloading()
 {
	
	var abc = $("#user_id").multiselect("widget").find(":checkbox").filter(':checked').length;
	if(abc > 0)
	{
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            }
		$('.parsley-form').submit();
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select atleast one user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;
	}
 }
<?php /*?><? if(!empty($editRecord)) {?>
 $("select#user_id").multiselect({
	   multiple: false,
	   header: "Select Admin",
	   noneSelectedText: "Select Admin",
	   selectedList: 1
	}).multiselectfilter();
<? } else { ?> <?php */?>
$("select#user_id").multiselect({
}).multiselectfilter();
<?php /*?>
<? } ?><?php */?>
</script>