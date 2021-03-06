<?php 
    /*
        @Description: Admin Tasks list
        @Author: Mohit Trivedi
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
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
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'task_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('task_label_name')?></a></th>
			
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'task_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('taskdate_label_name')?></a></th>
			
            <th width="12%" class="hidden-xs hidden-sm text-center"><?=$this->lang->line('completed_name')?></a></th>
			
		    <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
										<a title="Task View" href="<?= $this->config->item('user_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>"><?=!empty($row['task_name'])?ucfirst($row['task_name']):'';?></a>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['task_date'])?date($this->config->item('common_date_format'),strtotime($row['task_date'])):'';?></td>
							<td class="hidden-xs hidden-sm text-center"><input <?php if($row['is_completed'] == 1){?> checked="checked" <?php } ?> type="checkbox" id="iscompleted<?=$i;?>" value="<?php echo $row['id'];?>" name="iscompleted" onclick="iscompleted(this.value);" /></td>
							<td class="hidden-xs hidden-sm text-center">
										<a class="btn btn-xs btn-success" title="Copy Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/add_record/<?= $row['id'] ?>"><i class="fa fa-copy"></i></a> &nbsp; 
                                        
										<a class="btn btn-xs btn-success" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo $row['task_name'] ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb">
          <div class="col-sm-12">
           <div class="dataTables_paginate paging_bootstrap float-right" id="common_tb">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_tasks_per_page');?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
          </div>
         </div>
<script type="text/javascript">
function iscompleted(value)
{
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("user/task/iscompleted");?>',
			data: {
			selectedvalue:value
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },

			success: function(html){
				$.unblockUI();
			}
		});
		return false;
	
}

</script>
         