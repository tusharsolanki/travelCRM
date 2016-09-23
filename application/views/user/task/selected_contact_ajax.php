<?php $viewname = $this->router->uri->segments[3]; ?>
<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
  <thead>
	<tr role="row">
	  <th width="32%">Name</th>
	  <th width="47%">Company Name</th>
      <?php if($viewname != 'view_record') { ?>
	  <th width="21%">Action</th>
      <?php } ?>
	</tr>
  </thead>
  <tbody aria-relevant="all" aria-live="polite" role="alert">
<?php 
if(!empty($contacts_data)){
	$i=0;
	foreach($contacts_data as $row){//pr($row); ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="sorting_1"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class="sorting_2"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
    <?php if($viewname != 'view_record') { ?>
	<td class="text-center"><a href="javascript:void(0);" class="btn btn-xs btn-primary remove_selected_contact" data-group="<?=!empty($row['task_id'])?$row['task_id']:'';?>" data-id="<?=!empty($row['id'])?$row['id']:'';?>"> <i class="fa fa-times"></i></a></td>
    <?php } ?>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="3"> No Records Found. </td>
	</tr>
<?php } ?>
  </tbody>
</table>