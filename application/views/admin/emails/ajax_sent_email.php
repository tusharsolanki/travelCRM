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
//pr($editRecord);
?>
<?php
	if(!empty($not_send) && $not_send != 0 && $viewname == 'emails')
	{ ?>
		<h5 style="background:#CC0000; color:#FFFFFF;"><center> Email sending limit over. system could not send <?=$not_send?> emails in this campaign </center></h5>		
<?php
	}
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_name')?></a></th>
			
            <th width="29%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ect.email_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_message','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" >View</th>
			
            <?php if($viewname == 'bomb_emails') { ?>
            	<th width="7%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade">Track</th>
			<?php } ?>
            
			<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade">Resend</th>
			
			<th width="14%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade">Sent Date & Time </th>
			
             <!--<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>-->
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1){ echo 'bgtitle'; }?> <?php if($row['is_send'] == 0) { echo 'new_red_class'; } ?>" > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm">
							<a href="<?=$this->config->item('admin_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>" class="contact_name_<?=$row['id']?>">
							<?=!empty($row['first_name'])?ucwords($row['first_name']):'';?> <?=!empty($row['last_name'])?ucfirst(strtolower($row['last_name'])):'';?>
							</a>
							</td>
							<td class="hidden-xs hidden-sm ">
							<a href="<?=$this->config->item('admin_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>" class="subject_<?=$row['id']?>">
							<?=!empty($row['template_subject'])?(($row['template_subject'])):'';?></a></td>
                            
							<td class="hidden-xs hidden-sm ">
							 <a class="btn btn-xs btn-success" href="<?=$this->config->item('admin_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>" title="View"> View </a>
							 </td>
							<?php if($viewname == 'bomb_emails') { ?>
                            	<td class="hidden-xs hidden-sm">
                                <?php if(!empty($row['info'])) { ?>
                                	<a href="#basicModal" class="btn btn-xs btn-success" data-toggle="modal" onclick="email_tracking('<?=$row['info']?>','<?=$row['id']?>')"> Track </a>
								<?php } ?> 
                                </td>
                            <?php } ?>	
							<td class="hidden-xs hidden-sm ">
							<?php if($row['is_send'] == '0') { ?>
							 <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="resend_mail(<?=$row['ID']?>)" title="Resend"> Resend </a>
							 <?php } 
							 	if($row['is_email_exist'] == 0) {
							?>
								&nbsp;
								<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['template_subject']))) ?>');" title="Delete"><i class="fa fa-times"></i></button>
							<?php } ?>
							 </td>
							<td class="hidden-xs hidden-sm "> 
							<?php if($row['is_send'] == '1')
									echo !empty($row['sent_date'])?date($this->config->item('common_datetime_format'),strtotime($row['sent_date'])):'';
								else
									echo "Not send"; 
							?>
							</td>
							
										
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
		  <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										 <input type="hidden" id="id" name="id" value="<?=!empty($campaign_id)?$campaign_id:''?>" />
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
			
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_email_cam_per_page');?></option>
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
		 
<script>
function resend_mail(id)
{
	var campaign_id = $("#id").val();
	//alert(<?=$this->uri->segment(5)?>);
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/resend_mail/';?>"+campaign_id+"/<?=$this->uri->segment(5)?>",
			data: {'single_id':id},
			beforeSend: function() {
				$('#common_div').block({ message: 'Loading...' }); 
			},
			success: function(result){
				$('#common_div').unblock();
			//alert(result);
				$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('admin_base_url').$viewname.'/sent_email'?>"+result,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
         