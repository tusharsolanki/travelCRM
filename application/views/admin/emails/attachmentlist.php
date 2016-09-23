<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
			 	Sr.No
              	<!--<input type="checkbox" class="selecctall" id="selecctall">-->
             </div>
            </th>
           <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%">Attachment Name</th>		
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($attachment) && count($attachment)>0){
					$i = 1;
                      foreach($attachment as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                 <?=$i++?>
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm">
							<?php
								$filesize = 0;
								if(!empty($row['attachment_name']) && file_exists($this->config->item('attachment_basepath_file').$row['attachment_name']))
								{
									$filesize = filesize($this->config->item('attachment_basepath_file').$row['attachment_name']);
									$this->filesizeCount = $this->filesizeCount + $filesize;
								}
							?>
							<?=!empty($row['attachment_name'])?ucfirst(strtolower($row['attachment_name'])):'';?> </td>
							<td class="hidden-xs hidden-sm text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo ucfirst(strtolower($row['attachment_name'])) ?>','<?=$filesize?>');"><i class="fa fa-times"></i>
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb">
          <div class="col-sm-12">
           <div class="dataTables_paginate paging_bootstrap float-right" id="common_tb">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
            
            </label>
           </div>
           </div>
          </div>
         </div>
<script>
function deletepopup1(id,name,filesize)
{      
		var boxes = $('input[name="check[]"]:checked');
		if(boxes.length == '0' && id== '0')
		{return false;}
		if(id == '0')
		{
			var msg = 'Are you sure want to delete record(s)';
		}
		else
		{
			if(name.length > 50)
				name = name.substr(0, 50)+'...';
			var msg = 'Are you sure want to delete '+unescape(name)+'';
		}
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
   'action': function(){
						delete_all(id,name,filesize);
					}},'No'	: {'class'	: 'special'}}});
}

function delete_all(id,name,filesize)
{
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_attachment';?>",
		data: {'single_remove_id':id,'attachment_name':name},
		success: function(data){
			filesizeCount = filesizeCount - filesize;
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname?>/attachmentlist",
				data: {
				email_campaign_id:$("#id").val()
			},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					  },
				success: function(html){
					$("#common_div").html(html);
					$('#common_div').unblock();
				}
			});
			return false;
		}
	});
}
</script>