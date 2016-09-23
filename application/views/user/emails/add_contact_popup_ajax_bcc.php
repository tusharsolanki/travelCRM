<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead class="select_record_table select_contacts_box_scroll">
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="selecctall_bcc">
	  </div></th>
	<th width="90%" >Contact Type</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="select_record_table select_contacts_box contact_type_bcc_table">

<?php 
if(!empty($contact_list_bcc)){
	$i=0;
	foreach($contact_list_bcc as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="checkbox-column "><div class="">
		<input type="checkbox" class="mycheckbox_bcc" value="<?=!empty($row['id'])?$row['id']:'';?>">
	  </div></td>
	<td class="sorting_01"><?=!empty($row['name'])?ucfirst(strtolower($row['name'])):'';?></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr class="record_smg">
		<td class="record_smg" colspan="2">No records Found.</td>
	</tr>
<?php } ?>

<tr>
	<td colspan="2" id="common_tb_bcc">
		<?php 			 
			if(isset($pagination_bcc))
			{
				echo $pagination_bcc;
			}
			?>
	</td>
</tr>

</tbody>
</table>
<script>
$('.contact_type_bcc_table').on('click', 'tr', function() {
    $(this).find('td:first :checkbox').trigger('click');
	var contact_type_id = $(':checkbox', this).val();
	contact_type_bcc_checkbox(contact_type_id);
})
.on('click', '.mycheckbox_bcc', function(e) {
    e.stopPropagation();
    $(this).closest('tr').toggleClass('selected', this.checked);
	contact_type_bcc_checkbox(this.value);
});

function remove_selected_type_bcc()
{
	var cnt = popupcontactlist_bcc.length;
	for(i=0;i<popupcontactlist_bcc.length;i++)
	{
		$('.mycheckbox_bcc:checkbox[value='+popupcontactlist_bcc[i]+']').attr('checked',false);
	}
	$('#selecctall_bcc').attr('checked',false);
	popupcontactlist_bcc = Array();
	$('#count_selected_type_bcc').text(popupcontactlist_bcc.length + ' Record selected');
	arraydatacount_bcc = 0;
	
}
</script>