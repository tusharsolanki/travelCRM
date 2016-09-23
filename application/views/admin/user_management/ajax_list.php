<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <?php if(!empty($this->modules_unique_name) && in_array('user_management_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('user_label_name')?></a></th>
			
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'user_type_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('user_type_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('user_label_role')?></a></th>
            
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'agent_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('agent_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('user_label_type')?></a></th>
			
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'user_email'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('user_email','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>

            <th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('user_add_status')?></a></th>
			
			<!--<th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="20%"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>-->
	<? if(in_array('user_management_edit',$this->modules_unique_name) || in_array('user_management_delete',$this->modules_unique_name)){ ?>
            <th width="15%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
        <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                         <?php if(!empty($this->modules_unique_name) && in_array('user_management_delete',$this->modules_unique_name)){?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
							<td class="hidden-xs hidden-sm ">
                                                            <a title="Edit User" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>" class="textdecoration"><?=!empty($row['contact_name'])?ucwords($row['contact_name']):'';?></a>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['user_type_name'])?ucfirst(strtolower($row['user_type_name'])):'';?></td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['agent_type'])?ucfirst(strtolower($row['agent_type'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['user_email'])?$row['user_email']:'';?></td>
							<td class="hidden-xs hidden-sm "><span class="status_span_<?php echo  $row['id'] ?>"><?php if(isset($row['status']) && $row['status'] == '1')
																	{ echo 'Active';}elseif($row['status'] == '2') { echo 'Inactive'; }else{ echo 'Blocked';}?></span>
                                                                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                                                                    </td>
							<!--<td class="hidden-xs hidden-sm "><?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',', ',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,',', ');
									$fruit   = array(',',',',',',',',', ');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo ucfirst(strtolower($output));
									}	
									?></td>-->
							<? if(in_array('user_management_edit',$this->modules_unique_name) || in_array('user_management_delete',$this->modules_unique_name)){ ?>
							<td class="hidden-xs hidden-sm text-center">
                            <span class="action">
<?php /*?>							<span class="pubunpub_span_<?php echo  $row['id'] ?>">
										<? 
											if(!empty($row['status']) && $row['status']==1){ ?>
                                                  <a class="btn btn-xs btn-success" href="javascript:void(0);" onclick="pubunpub_data(0,'<?php echo  $row['id'] ?>');"><i class="fa fa-check-circle"></i></a> &nbsp; 
												  <? }else{ ?>
<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="pubunpub_data(1,'<?php echo  $row['id'] ?>');"><i class="fa fa-archive"></i></a> &nbsp;<? } ?>

</span>
<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo $row['contact_name'] ?>');"><i class="fa fa-times"></i></button><?php */?>	
				 <?php /*?> <a class="btn btn-xs btn-success" title="View User" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>"><i class="fa fa-search"></i></a> &nbsp;<?php */?> 	
                 <?php  /*if(!empty($assignright_superadmin) && !empty($row['user_type']) && $row['user_type'] == 3) { ?>
				 <a title="Edit Admin Rights" class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_right/<?= $row['user_id'] ?>">Edit Rights</a> &nbsp;
                 <?php } */ ?>
				 <?php if(!empty($this->modules_unique_name) && in_array('user_management_edit',$this->modules_unique_name)){?>
                 
                <a class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>" title="Edit User"><i class="fa fa-pencil"></i></a> &nbsp; 	
                <? } ?>			
                 <?php if(!empty($this->modules_unique_name) && in_array('user_management_delete',$this->modules_unique_name)){?>				
										 <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="single_active_plans('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');" title="Archive"><i class="fa fa-archive"></i></a>
										
							<? } ?>	</span>		
										
										</td>
                                        <? } ?>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_users_per_page')?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
           </div>
          </div>
              <div class="col-sm-6">
             <?php 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
            </div>
         </div>