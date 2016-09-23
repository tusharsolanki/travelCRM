<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead>
  <tr role="row">
	<th width="33%" >Name</th>
	<th width="33%" >Company Name</th>
	<th width="33%" >Email</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert">

<?php 
if(!empty($contact_list)){
	$i=0;
	foreach($contact_list as $row){ if(!empty($row['cid'])){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="sorting_1"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class="sorting_2"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
	<td class=""><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
  </tr>
<?php }} ?>
<?php }else{ ?>
	<tr>
		<td colspan="3">No Records Found.</td>
	</tr>
<?php } ?>

</tbody>
</table>