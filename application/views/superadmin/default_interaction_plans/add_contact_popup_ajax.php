<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead>
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="selecctall">
	  </div></th>
	<th width="30%" >Name</th>
	<th width="30%" >Email</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="record_table">

<?php 
if(!empty($contact_list)){
	$i=0;
	foreach($contact_list as $row){ ?>
  <tr class="add_contact_to_array <?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
	<td class="checkbox-column "><div class="">
		<input type="checkbox" class="mycheckbox" value="<?=!empty($row['id'])?$row['id']:'';?>">
	  </div></td>
	<td class="sorting_1"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class=""><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="4">No records Found.</td>
	</tr>
<?php } ?>

<tr>
	<td colspan="4" id="common_tb">
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
$('.record_table').on('click', 'tr', function() {
    $(this).find('td:first :checkbox').trigger('click');
	var contact_id = $(':checkbox', this).val();
	//alert(contact_id);
	checkbox_checked(contact_id);
	
})
.on('click', '.mycheckbox', function(e) {
    e.stopPropagation();
    $(this).closest('tr').toggleClass('selected', this.checked);
	checkbox_checked(this.value);
});

function remove_selection_to()
{
	var cnt = popupcontactlist.length;
	for(i=0;i<popupcontactlist.length;i++)
	{
		$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',false);
	}
	$('#selecctall').attr('checked',false);
	popupcontactlist = Array();
	$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
	arraydatacount = 0;
	
}

</script>