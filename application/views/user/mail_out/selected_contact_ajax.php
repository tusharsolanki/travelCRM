<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
  <thead>
	<tr role="row">
	  <th width="26%" align="left">Name</th>
	  <th width="28%" align="left">Company Name</th>
	  <th width="57%" align="left">Action</th>
	</tr>
  </thead>
  <tbody aria-relevant="all" aria-live="polite" role="alert">
<?php 
if(!empty($contacts_data)){
	$i=0;
	foreach($contacts_data as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="sorting_1" width="26%"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class="sorting_2" width="32%"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
	<td class="text-center" width="57%"><button class="btn btn-xs btn-primary remove_selected_contact" value="<?=!empty($row['id'])?$row['id']:'';?>"> <i class="fa fa-times"></i></button></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="3"> No Records Found. </td>
	</tr>
<?php } ?>

  </tbody>
</table>