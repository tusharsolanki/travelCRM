<?php 
    /*
        @Description: superadmin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="37%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('plan_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('interaction_name')?></a></th>
			
          <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_user_assigned')?></th>
			
            <th width="14%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
            
            <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="10%">Update Release</th>
			<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
			<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
           </tr>
           </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
          <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> id="view_archive_<?php echo  $row['id'] ?>" > 
							<td class="">
                              <div class="text-center">
                                 <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>												
                            </td>
							<td class="hidden-xs hidden-sm ">
							<a href="<?= $this->config->item('superadmin_base_url'); ?>interaction/<?= $row['id'] ?>" class="textdecoration">
							<?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></a></td>
                            
							<td class="text-center">
								<a title="Assigned Contacts" data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
									<button class="btn btn-xs btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
								</a>
							</td>
							<td class="hidden-xs hidden-sm text-center">
										
										<a title="View Communication" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url'); ?>interaction/<?= $row['id'] ?>"><i class="fa fa-search"></i></a> &nbsp; 
                                        
                                        <a title="Edit Communication" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
										
										<!--<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id']; ?>','<?php echo $row['plan_name']; ?>');"><i class="fa fa-times"></i></button>&nbsp;-->
										
										
                                        <a title="Add to Archive" class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="active_plan_single('<?php echo  $row['id'] ?>','<?php echo  rawurlencode(ucfirst(strtolower($row['plan_name']))) ?>');"><i class="fa fa-archive"></i></a> &nbsp; 										   
</span>		
							</td>
                            
                             <td class="hidden-xs hidden-sm ">  <button class="btn btn-xs btn-success" onclick="premium_plan_released(<?=$row['id']?>)"> Publish Update </button><span class="date"><?=!empty($row['modified_date'])?date($this->config->item('common_date_format'),strtotime($row['modified_date'])):''?> </span></td>
                             
                       </tr>
         <?php } } else {?>
		  <tr>
		  	<td colspan="6" align="center"><?=$this->lang->line('superadmin_general_noreocrds')?></td>
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
             <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_communications_per_page')?></option>
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
           <?php if(isset($pagination)){echo $pagination;}?>
           </div>
         </div>
        
         