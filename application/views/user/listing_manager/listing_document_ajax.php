<?php $viewname = $this->router->uri->segments[2];?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
   <tr role="row">
	<th class="hidden-xs hidden-sm sorting_disabled" width="10%"><?=$this->lang->line('contact_add_date_time')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="15%"><?=$this->lang->line('contact_add_doc_type')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="20%"><?=$this->lang->line('contact_add_doc_name')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="25%"><?=$this->lang->line('common_label_desc')?></th>
	<th class="hidden-xs hidden-sm sorting_disabled" width="15%"><?=$this->lang->line('contact_add_document')?></th>
	<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
	<th class="hidden-xs hidden-sm sorting_disabled" width="15%"><?php echo $this->lang->line('common_label_action')?></th>
	<?php } ?>
   </tr>
   </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
   <?php
		if(!empty($document_trans_data) && count($document_trans_data)>0){
		$i=1;
			foreach($document_trans_data as $row){?>
			<tr <? if($i%2==1){ ?>class="bgtitle delete_document_trans_record<?= $row['id'] ?>" <? }?> > 
				<td class="hidden-xs hidden-sm" id="document_date_<?=$row['id']?>"><?=!empty($row['modified_date'])?date($this->config->item('common_datetime_format'),strtotime($row['modified_date'])):'';?></td>
				<td class="hidden-xs hidden-sm" id="document_type_<?=$row['id']?>"><?=!empty($row['name'])?ucfirst(strtolower($row['name'])):'';?></td>
				<td class="hidden-xs hidden-sm" id="document_doc_name_<?=$row['id']?>">
				<a data-toggle="modal" data-id="<?=$row['id']?>" class="view_document" href="#common_basicModal">
				<?=!empty($row['doc_name'])?ucfirst(strtolower($row['doc_name'])):'';?></a></td>
				<td class="hidden-xs hidden-sm" id="document_doc_desc_<?=$row['id']?>"><?=!empty($row['doc_desc'])?$row['doc_desc']:'';?></td>
				<td class="hidden-xs hidden-sm" id="document_doc_file_<?=$row['id']?>">
					<?php if(!empty($row['doc_file'])  && file_exists($this->config->item('listing_documents_big_img_path').$row['doc_file'])){
						$result = explode('-',$row['doc_file']);
						//pr($result);exit;
						if(count($result) > 1)
						{
							unset($result[0]);
						}
						$final_file=implode('-',$result);
						?>
						<?=$final_file?>
					<?php } ?>
				</td>
				<?php if($this->router->uri->segments[3] != 'view_record'){ ?>
				<td class="hidden-xs hidden-sm">
				<?php if(!empty($row['doc_file'])  && file_exists($this->config->item('listing_documents_big_img_path').$row['doc_file'])){?>
				<a target="_blank" title="Download" class="btn btn-xs btn-success" href="<?=base_url().'uploads/listing_docs/'; ?><?= $row['doc_file'] ?>"><i class="fa fa-download"></i></a> &nbsp; 
				<?php } ?>
				<a class="btn btn-xs btn-success" title="Edit" href="javascript:void(0);" onclick="editdoctransdata('<?= $row['id'] ?>')"><i class="fa fa-pencil"></i></a> &nbsp; 
				<a href="javascript:void(0);" title="Delete" class="btn btn-xs btn-primary" onclick="ajaxdeletetransdata('delete_document_trans_record','<?= $row['id'] ?>');"><i class="fa fa-times"></i></a></td>
				<?php } ?>
			</tr>
	<?php } }else{?>
			<tr>
				<td colspan="100%">No records found.</td>
			</tr>
	<?php } ?>
  </tbody>
 </table>