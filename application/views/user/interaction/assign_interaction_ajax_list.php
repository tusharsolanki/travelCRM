<?php 
    /*
        @Description: user contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
$plan_id=$this->router->uri->segments[3];
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           <!-- <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'description'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('description','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_desc')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" width="15%"><a href="javascript:void(0);" onclick="applysortfilte_contact('name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_type')?></a></th>
			
			<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'plan_status_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" width="15%"><!--<a href="javascript:void(0);" onclick="applysortfilte_contact('plan_status_name','<?php echo $sorttypepass;?>')">--><?=$this->lang->line('common_label_dates')?></th>
			
            <th class="hidden-xs hidden-sm " data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('common_label_ass_to')?></th>
			
			<th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('common_label_contacts')?></th>
			
			
           </tr>
           </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
          <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                   
					  foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<?php /*?><td class="">
                              <div class="text-center">
                                 <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
								 
							  </div>												
                            </td><?php */?>
							<td class="hidden-xs hidden-sm ">
								<a href="<?= $this->config->item('user_base_url'); ?>interaction/view_record/<?= $row['id'] ?>" class="textdecoration">
									<?=!empty($row['description'])?ucfirst(strtolower($row['description'])):'';?>
                                </a>
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['name'])?ucfirst(strtolower($row['name'])):'';?></td>
							<td class="hidden-xs hidden-sm ">
							
					<?php if($row['start_type'] == '1'){
					
								echo $row['number_count']."&nbsp;".ucfirst(strtolower($row['number_type']." From Plan Start Date"));
								}
						
							if($row['start_type'] == '2')
							{ echo $row['number_count']."&nbsp;".ucfirst(strtolower($row['number_type']." After a Preceding Action "."'".$row['interaction_name']."'"));}
							
							if($row['start_type'] == '3')
							{ echo "On ".date($this->config->item('common_date_format'),strtotime($row['start_date']));}?>
							
							</td>
							<td><?php if($row['admin_name']!='') { echo ucfirst(strtolower($row['admin_name']));}else{ echo ucfirst(strtolower($row['contact_name']));}?></td>
							<td class="text-center">
							<a data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['interaction_plan_id'])?$row['interaction_plan_id']:'';?>">
							<button class="btn btn-xs btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
							</a>
							</td>
							
							<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                       </tr>
         <?php } } else {?>
		  <tr>
		  	<td colspan="7" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
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
             <option value=""><?=$this->lang->line('label_action_per_page')?></option>
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
        
         