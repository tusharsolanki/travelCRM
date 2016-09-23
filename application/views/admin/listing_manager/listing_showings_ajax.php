<?php $viewname = $this->router->uri->segments[2];?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
   <tr role="row">
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_date')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_time')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_agentname')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_agentphone')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_agentid')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_agentemail')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('common_label_agentoffice')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="20%"><?=$this->lang->line('contact_add_notes')?></th>
	<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?php echo $this->lang->line('common_label_action')?></th>
	<?php } ?>
   </tr>
   </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
   <?php
		if(!empty($showings_trans_data) && count($showings_trans_data)>0){
		$i=1;
			foreach($showings_trans_data as $row){?>
			<tr <? if($i%2==1){ ?>class="bgtitle delete_showings_trans_record<?= $row['id'] ?>" <? }?> > 
				<td class="hidden-xs hidden-sm" id="showing_date_<?=$row['id']?>"><?=!empty($row['showings_date'])?date($this->config->item('common_date_format'),strtotime($row['showings_date'])):'';?></td>
				<td class="hidden-xs hidden-sm" id="showing_time_<?=$row['id']?>"><a data-toggle="modal" data-id="<?=$row['id']?>" class="view_showing" href="#common_basicModal"><?=!empty($row['showings_time'])?date($this->config->item('common_time_format'),strtotime($row['showings_time'])):'';?></a></td>
				<td class="hidden-xs hidden-sm" id="showing_agent_name_<?=$row['id']?>"><?=!empty($row['showings_agent_name'])?ucfirst(strtolower($row['showings_agent_name'])):'';?></td>
				<td class="hidden-xs hidden-sm" id="showing_phone_<?=$row['id']?>"><?=!empty($row['showings_agent_phone'])?$row['showings_agent_phone']:'';?></td>
        		<td class="hidden-xs hidden-sm" id="showing_agent_id_<?=$row['id']?>"><?=!empty($row['showings_agent_id'])?$row['showings_agent_id']:'';?></td>        		
                <td class="hidden-xs hidden-sm" id="showing_agent_email_<?=$row['id']?>"><?=!empty($row['showings_agent_email'])?$row['showings_agent_email']:'';?></td>             
                <td class="hidden-xs hidden-sm" id="showing_agent_office_<?=$row['id']?>"><?=!empty($row['showings_agent_office'])?ucfirst(strtolower($row['showings_agent_office'])):'';?></td>                         
				<td class="hidden-xs hidden-sm" id="showing_note_<?=$row['id']?>"><?=!empty($row['showings_notes'])?ucfirst(strtolower($row['showings_notes'])):'';?></td>
				<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
				<td class="hidden-xs hidden-sm">
				<a class="btn btn-xs btn-success" title="Edit" href="javascript:void(0);" onclick="editshowingsstransdata('<?= $row['id'] ?>')"><i class="fa fa-pencil"></i></a> &nbsp; 
				<a href="javascript:void(0);" title="Delete" class="btn btn-xs btn-primary" onclick="ajaxdeletetransdata('delete_showings_trans_record','<?= $row['id'] ?>');"><i class="fa fa-times"></i></a></td>
				<?php } ?>
			</tr>
	<?php } }else{?>
			<tr>
				<td colspan="100%">No records found.</td>
			</tr>
	<?php } ?>
  </tbody>
 </table>