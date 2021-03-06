<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>

<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter innerscrolltable">
<thead class="select_record_table select_contacts_box_scroll">
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="select_contact_bcc">
	  </div></th>
	<th width="26%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact_bcc('first_name','<?php echo $sorttypepass;?>')">Name</a></th>
      
      <th width="28%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact_bcc('company_name','<?php echo $sorttypepass;?>')">Company Name</a></th>
	<!--<th width="31%" >Name</th>
	<th width="30%" >Company Name</th>
	<th width="31%" >Email</th>-->
    <th width="57%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact_bcc('email_address','<?php echo $sorttypepass;?>')">Email</a></th>
    <input type="hidden" id="sortfield_bcc" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
	<input type="hidden" id="sortby_bcc" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="select_record_table select_contacts_box record_table_bcc">

<?php 
if(!empty($contact_bcc)){
	$i=0;
	foreach($contact_bcc as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td width="10%" class="checkbox-column"><div class="">
		<input type="checkbox" class="mycheckbox_bcc1" value="<?=!empty($row['id'])?$row['id'].'-'.$row['email_trans_id']:'';?>"  >
	  </div></td>
	<td class="sorting_1" width="26%"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td class="sorting_2" width="32%"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
	<td class="" width="57%"><?=!empty($row['email_address'])?$row['email_address']:'';?><?php if(!empty($row['email_type']) && $row['email_type'] == '1') echo "(Spouse)"; ?></td>
  </tr>
<?php } ?>
<?php }else{ ?>
	<tr class="record_smg">
		<td class="record_smg" colspan="4">No records Found.</td>
	</tr>
<?php } ?>
</tbody>
<!--<tfoot>
<tr>
	<td colspan="4" id="common_contact_bcc">
		<?php 			 
			if(isset($pagination_contact_bcc))
			{
				echo $pagination_contact_bcc;
			}
			?>
	</td>
</tr>

</tfoot>-->
</table>
<div class="row dt-rb">
      <div class="col-sm-3">
      <div class="dataTables_paginate paging_bootstrap float-right">
      <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
        <label>
        <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="contact_ser_bcc();" id="perpage_bcc" class="small_per_page_popup">
         <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('contact_perpage')?></option>
          <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
          <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
          <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
          <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
        </select>
        </label>
       </div>
       </div>
       </div>
       <div class="col-sm-9" id="common_contact_bcc">
       <?php if(isset($pagination_contact_bcc)){echo $pagination_contact_bcc;}?>
       </div>
</div>

<script>
$('.record_table_bcc').on('click', 'tr', function() {
    $(this).find('td:first :checkbox').trigger('click');
	var contact_id = $(':checkbox', this).val();
	//alert(contact_id);
	checkbox_bcc(contact_id);
})
.on('click', '.mycheckbox_bcc1', function(e) {
    e.stopPropagation();
    $(this).closest('tr').toggleClass('selected', this.checked);
	checkbox_bcc(this.value);
});

function remove_selection_bcc()
{
	var cnt = popupcontact_bcc.length;
	for(i=0;i<popupcontact_bcc.length;i++)
	{
		$('.mycheckbox_bcc1:checkbox[value='+popupcontact_bcc[i]+']').attr('checked',false);
	}
	$('#select_contact_bcc').attr('checked',false);
	popupcontact_bcc = Array();
	$('#count_selected_bcc').text(popupcontact_bcc.length + ' Record selected');
	arraydata_bcc = 0;
	
}

</script>