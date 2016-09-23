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
//pr($this->session->userdata('iplan_view_archive_sortsearchpage_data'));
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
			
            <th class="hidden-xs hidden-sm text-center <?php if(isset($sortfield) && $sortfield == 'plan_status_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" width="15%"><a href="javascript:void(0);" onclick="applysortfilte_contact('plan_status_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_istatus')?></a></th>
			
            <!--<th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_run_campaign')?></th>-->
			
			<th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_contacts_assigned')?></th>
			
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
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
							<a href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>" class="textdecoration">
							<?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></a></td>
							<td class="hidden-xs hidden-sm text-center"><?=!empty($row['plan_status_name'])?$row['plan_status_name']:'';?></td>
							<!--<td class="text-center">
								<button class="btn btn-xs btn-success mrgr1"><i class="fa fa-play"></i></button>
                          		<button class="btn btn-xs btn-secondary mrgr1"> <i class="fa fa-pause"></i></button>
                          		<button class="btn btn-xs btn-primary"> <i class="fa fa-stop"></i></button>
							</td>-->
							<td class="text-center">
								<a data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>" title="Assigned Contacts">
									<button class="btn btn-xs btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
								</a>
							</td>
							<td class="hidden-xs hidden-sm text-center">
<!--													<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id']; ?>','<?php echo $row['plan_name']; ?>');"><i class="fa fa-times"></i></button>&nbsp;-->
                                                  <a title="Un-Archive" class="btn btn-xs btn-success" href="javascript:void(0);" onclick="active_plan_single('<?php echo  $row['id'] ?>','<?php echo  rawurlencode(ucfirst(strtolower($row['plan_name']))) ?>');">Un-Archive</a> 
							
										
										
							</td>
                       </tr>
         <?php } } else {?>
		  <tr>
		  	<td colspan="6" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
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
             <option value=""><?=$this->lang->line('label_communications_per_page')?></option>
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
        
         