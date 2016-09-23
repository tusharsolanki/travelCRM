<?php $viewname = $this->router->uri->segments[2];?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
   <tr role="row">
	<th class="hidden-xs hidden-sm sorting_disabled" width="15%"><?=$this->lang->line('common_label_date')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="25%"><?=$this->lang->line('common_label_start_time')?></th>
    <th class="hidden-xs hidden-sm sorting_disabled" width="25%"><?=$this->lang->line('common_label_end_time')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="25%"><?=$this->lang->line('contact_add_notes')?></th>
	<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
	<th class="hidden-xs hidden-sm sorting_disabled" width="15%"><?php echo $this->lang->line('common_label_action')?></th>
	<?php } ?>
   </tr>
   </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
   <?php
		if(!empty($houses_trans_data) && count($houses_trans_data)>0){
		$i=1;
			foreach($houses_trans_data as $row){?>
			<tr <? if($i%2==1){ ?>class="bgtitle delete_houses_trans_record<?= $row['id'] ?>" <? }?> > 
				<td class="hidden-xs hidden-sm" id="house_date_<?=$row['id']?>"><a data-toggle="modal" data-id="<?=$row['id']?>" class="view_house" href="#common_basicModal"><?=!empty($row['open_house_date'])?date($this->config->item('common_date_format'),strtotime($row['open_house_date'])):'';?></a></td>
				<td class="hidden-xs hidden-sm" id="house_time_<?=$row['id']?>"><?=!empty($row['open_house_time'])?date($this->config->item('common_time_format'),strtotime($row['open_house_time'])):'';?></td>
                <td class="hidden-xs hidden-sm" id="house_time_<?=$row['id']?>"><?=!empty($row['open_house_end_time'])?date($this->config->item('common_time_format'),strtotime($row['open_house_end_time'])):'';?></td>
				<td class="hidden-xs hidden-sm" id="house_note_<?=$row['id']?>"><?=!empty($row['open_house_notes'])?ucfirst(strtolower($row['open_house_notes'])):'';?></td>
				<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
				<td class="hidden-xs hidden-sm">
				<a class="btn btn-xs btn-success" title="Edit" href="javascript:void(0);" onclick="edithousesstransdata('<?= $row['id'] ?>')"><i class="fa fa-pencil"></i></a> &nbsp; 
				<a href="javascript:void(0);" title="Delete" class="btn btn-xs btn-primary" onclick="ajaxdeletetransdata('delete_houses_trans_record','<?= $row['id'] ?>');"><i class="fa fa-times"></i></a></td>
				<?php } ?>
			</tr>
	<?php } }else{?>
			<tr>
				<td colspan="100%">No records found.</td>
			</tr>
	<?php } ?>
  </tbody>
 </table>