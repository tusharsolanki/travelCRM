<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead class="select_record_table select_contacts_box_scroll">
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="selecctall">
	  </div></th>
	<th width="95%" >Contact Type</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="select_record_table select_contacts_box contact_type_table">

<?php 
if(!empty($contact_list)){
	$i=0;
	foreach($contact_list as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="checkbox-column "><div class="">
		<input type="checkbox" class="mycheckbox" value="<?=!empty($row['id'])?$row['id']:'';?>"  >
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
	<td colspan="2" id="common_tb">
		<?php 			 
			if(isset($pagination))
			{
				echo $pagination;
			}
			?>
	</td>
</tr>

</tbody>
</table>

<script>
$('.contact_type_table').on('click', 'tr', function() {
    $(this).find('td:first :checkbox').trigger('click');
	var contact_type_id = $(':checkbox', this).val();
	contact_type_checkbox(contact_type_id);
})
.on('click', '.mycheckbox', function(e) {
    e.stopPropagation();
    $(this).closest('tr').toggleClass('selected', this.checked);
	contact_type_checkbox(this.value);
});

function remove_selection()
{
	var cnt = popupcontactlist.length;
	for(i=0;i<popupcontactlist.length;i++)
	{
		$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',false);
	}
	$('#selecctall').attr('checked',false);
	popupcontactlist = Array();
	$('#count_selected').text(popupcontactlist.length + ' Record selected');
	arraydatacount = 0;
	
}
</script>