<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter innerscrolltable">
<thead class="select_record_table select_contacts_box_scroll">
  <tr role="row">
	<th width="10%" role="columnheader" class="checkbox-column sorting_disabled"> <div class="">
		<input type="checkbox" class="" id="selecctall">
	  </div></th>
	<!--<th width="30%" >Name</th>-->
	<th width="26%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')">Name</a></th>
      
      <th width="28%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')">Company Name</a></th>
	<!--<th width="31%" >Name</th>
	<th width="30%" >Company Name</th>
	<th width="31%" >Email</th>-->
    <th width="57%" colspan="1" rowspan="1" align="left" aria-controls="DataTables_Table_0" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" role="columnheader" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')">Email</a></th>
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
	<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert" class="select_record_table select_contacts_box record_table">

<?php 
if(!empty($contact_list)){
	$i=0;
	foreach($contact_list as $row){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td width="10%" class="checkbox-column "><div class="">
		<input type="checkbox" class="mycheckbox" value="<?=!empty($row['id'])?$row['id']:'';?>">
	  </div></td>
	<td width="26%" class="sorting_1"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
	<td width="28%" class="sorting_2"><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
	<td width="57%" class=""><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
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
	<td colspan="4" id="common_tb">
		<?php 			 
			if(isset($pagination))
			{
				echo $pagination;
			}
			?>
	</td>
</tr>

</tfoot>-->
</table>

<div class="row dt-rb" id="common_tb">
      <div class="col-sm-3">
      <div class="dataTables_paginate paging_bootstrap float-right">
      <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
        <label>
        <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="contact_search();" id="perpage" class="small_per_page_popup">
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
       <div class="col-sm-9" id="common_tb">
       <?php if(isset($pagination)){echo $pagination;}?>
       </div>
</div>
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
	$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
	arraydatacount = 0;
	
}

</script>