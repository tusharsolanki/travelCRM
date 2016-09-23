<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead class="select_record_table select_contacts_box_scroll">
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="select_contact_to">
	  </div></th>
		<th width="40%" >Name</th>
		<th width="40%" >Company Name</th>
		<th width="10%" >Contact No</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="record_table select_record_table select_contacts_box">

<?php 
if(!empty($contact_to)){
	$i=0;
	foreach($contact_to as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="checkbox-column "><div class="">
		<input type="checkbox" class="mycheckbox_to" value="<?=!empty($row['id'])?$row['id']:'';?>"  >
	  </div></td>
	<!--<td class="sorting_1"><?=!empty($row['first_name'])?$row['first_name']:'';?> <?=!empty($row['last_name'])?$row['last_name']:'';?></td>-->
	<td class="sorting_1"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class="sorting_2"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
	<td class=""><?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="4">No records Found.</td>
	</tr>
<?php } ?>
</tbody>
<tfoot>
<tr>
	<td colspan="4" id="common_contact_to">
		<?php 			 
			if(isset($pagination_contact_to))
			{
				echo $pagination_contact_to;
			}
			?>
	</td>
</tr>
</tfoot>

</table>
<script>
$('.record_table').on('click', 'tr', function() {
    $(this).find('td:first :checkbox').trigger('click');
	var contact_id = $(':checkbox', this).val();
	//alert(contact_id);
	checkboxchecked(contact_id);
})
.on('click', '.mycheckbox_to', function(e) {
    e.stopPropagation();
    $(this).closest('tr').toggleClass('selected', this.checked);
	checkboxchecked(this.value);
});

function remove_selection_to()
{
	var cnt = popupcontact_to.length;
	for(i=0;i<popupcontact_to.length;i++)
	{
		$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',false);
	}
	$('#select_contact_to').attr('checked',false);
	popupcontact_to = Array();
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
	arraydata_to = 0;
	
}

</script>