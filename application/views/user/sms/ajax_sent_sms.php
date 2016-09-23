<?php 
    /*
        @Description: user Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
//pr($editRecord);
?>
<?php
	if(!empty($not_send) && $not_send != 0)
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
            <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_name')?></a></th>
			
            <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'sct.sms_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('sct.sms_message','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
			
				<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"> View</th>
			
			<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"> Resend </th>
			
			<th width="20%" class="hidden-xs hidden-sm sorting_disabled actionbtn7" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"> Sent Date & Time </th>
			
             <!--<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>-->
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1){ echo 'bgtitle'; }?> <?php if($row['is_send'] == '0') { echo 'new_red_class'; } ?>" > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
							<a href="<?=$this->config->item('user_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>">
							<?=!empty($row['first_name'])?ucfirst(strtolower($row['first_name'])):'';?> <?=!empty($row['last_name'])?ucfirst(strtolower($row['last_name'])):'';?>
							</a>
							</td>
							<td class="hidden-xs hidden-sm ">
							<a href="<?=$this->config->item('user_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>">
							<?=!empty($row['sms_message'])?ucfirst(strtolower($row['sms_message'])):'';?></a></td>
							<!--<td class="hidden-xs hidden-sm text-center">
							<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/add_record/<?= $row['id'] ?>"><i class="fa fa-copy"></i></a> &nbsp; 
                                        
										<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['ID'] ?>','<?php echo $row['template_name'] ?>');"><i class="fa fa-times"></i></button>
										
										
										</td>-->
							<td class="hidden-xs hidden-sm ">
							 <a class="btn btn-xs btn-success" href="<?=$this->config->item('user_base_url').$viewname;?>/view_data/<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>/<?=$row['ID']?>" title="View"> View </a>
							 </td>
							 			
							<td class="hidden-xs hidden-sm ">
							<?php if($row['is_send'] == '0') { ?>
							 <a href="#" class="btn btn-xs btn-success" onclick="resend_mail(<?=$row['ID']?>)" title="Resend"> Resend </a>
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
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
		  <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										 <input type="hidden" id="id" name="id" value="<?=!empty($campaign_id)?$campaign_id:''?>" />
          </tbody>
         </table>
         <div class="row dt-rb">
          <div class="col-sm-12">
           <div class="dataTables_paginate paging_bootstrap float-right" id="common_tb">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
			
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_sms_cam_per_page');?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
          </div>
         </div>
		 
<script>
function resend_mail(id)
{
	var campaign_id = $("#id").val();
	//alert(<?=$this->uri->segment(5)?>);
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/resend_sms/';?>"+campaign_id+"/<?=$this->uri->segment(5)?>",
			async: false,
			data: {'single_id':id},
			success: function(result){
			//alert(result);
				$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('user_base_url').$viewname.'/sent_sms'?>"+result,
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
         