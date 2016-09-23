<?php $viewname = $this->router->uri->segments[2];?>
<input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
<div id="error_div">
<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
<thead>
  <tr role="row">
	<th width="5%" >No</th>
	<th width="15%" >Error Type</th>
	<th width="50%" >Descreption</th>
	<th width="20%" >Created Date</th>
	<th width="10%" >Status</th>
  </tr>
</thead>
<tbody aria-relevant="all" aria-live="polite" role="alert">

<?php 
$err_var =$this->uri->segment(4);
if(!empty($error_list)){
	$i=!empty($err_var)?$err_var:0;
	foreach($error_list as $row){ if(!empty($row['id'])){ ?>
  <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
	<td class="sorting_1"><?=$i?></td>
	<td class="sorting_2"><?=!empty($row['type'])?ucfirst(strtolower($row['type'])):'';?></td>
	<td class=""><?=!empty($row['description'])?$row['description']:'';?></td>
	<td class=""><?php if($row['created_date']=='0000-00-00'){ echo '';}else
	{?><?=!empty($row['created_date'])?date($this->config->item('common_datetime_format'),strtotime($row['created_date'])):'';}?></td>
	<td class="">
 
	<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id']; ?>','<?php echo ucfirst(strtolower($row['type'])); ?>');"><i class="fa fa-times"></i></button></td>
  </tr>
<?php }} ?>
<?php }else{ ?>
	<tr>
		<td colspan="5">No Records Found.</td>
	</tr>
<?php } ?>

</tbody>
</table>
<div class="row dt-rb" id="common_tb1">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_error_per_page');?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
           </div>
          </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
</div>
