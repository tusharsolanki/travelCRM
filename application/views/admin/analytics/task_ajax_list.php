<?php 
    /*
        @Description: Admin Tasks list
        @Author: Mohit Trivedi
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
            <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'task_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('task_label_name')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'task_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('taskdate_label_name')?></a></th>
            
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
			<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="hidden-xs hidden-sm ">
							<a title="Task View" href="<?= $this->config->item('admin_base_url');?>task/view_record/<?= $row['id'] ?>"><?=!empty($row['task_name'])?ucfirst(strtolower($row['task_name'])):'';?></a>
							
							<?php /*?><?=!empty($row['task_name'])?ucwords($row['task_name']):'';?><?php */?></td>
							<td class="hidden-xs hidden-sm "><?php if($row['task_date']=='0000-00-00'){ echo '';}else
							{?><?=!empty($row['task_date'])?date($this->config->item('common_date_format'),strtotime($row['task_date'])):'';}?></td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="3" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
            <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_tasks_per_page');?></option>
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
   </div>
  </div>
 </div>
</div>
          
         