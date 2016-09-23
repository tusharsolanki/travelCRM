<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
  <thead>
	<tr role="row">
	  <th width="32%">Name</th>
	  <th width="47%">Company Name</th>
	  <th width="21%">Action</th>
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
	<td class="text-center"><a href="javascript:void(0);" class="btn btn-xs btn-primary remove_selected_contact" data-group="<?=!empty($row['interaction_plan_id'])?$row['interaction_plan_id']:'';?>" data-id="<?=!empty($row['id'])?$row['id']:'';?>"> <i class="fa fa-times"></i></a></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="3"> No Records Found. </td>
	</tr>
<?php } ?>
  </tbody>
</table>