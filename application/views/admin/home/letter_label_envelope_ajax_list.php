<?php 
/*
    @Description: Admin Dashborad task list
    @Author     : Sanjay Moghariya
    @Date       : 22-10-14
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[3];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
 <form class="form parsley-form" enctype="multipart/form-data" name="letter_label_envelope" id="letter_label_envelope" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').'dashboard/mail_out' ?>"  data-validate="parsley" novalidate target="_blank">
            <input type="hidden" name="finalid" id="finalid" value="" />
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')">Recipients</a></th>
            
            <th width="26%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')">Communication </a></th>
            
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'desc'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version">Mail Type</th>
            
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'task_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version">Sequence</th>
            
            <th width="9%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'desc'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version">Complete</th>
            
		    <th width="13%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
            
           <?php 
		   //pr($datalist);
		   if(!empty($mailing_datalist) && count($mailing_datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($mailing_datalist as $row){
						 
						  ?>
						<tr class="<? if($i%2==1) echo 'bgtitle '; if(strtotime($row['task_date']) < strtotime($dt)) echo 'new_red_class'; ?>" > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_1" name="check_1[]" value="<?php echo  $row['interaction_master_interaction_id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
							<a title="Assigned Contacts" data-toggle="modal" class="text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['interaction_plan_interaction_id'])?$row['interaction_plan_interaction_id']:'';?>">
							<?=!empty($row['recipients'])?ucfirst(strtolower($row['recipients'])):'';?>
                            </a>
                            </td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['communication'])?ucfirst(strtolower($row['communication'])):'';?></td>
                            <td class="hidden-xs hidden-sm ">
                            <?php 
							$task_type = '';
                              if(!empty($row['interaction_type']) && $row['interaction_type'] == 1)
							  	$task_type =  'Label';
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 2)
							  	$task_type = 'Envelope';
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 5)
							  	$task_type = 'Letter';
							  
							  echo $task_type;
							?>
                            </td>
							<td class="hidden-xs hidden-sm" id="temp_name_<?php echo  $row['id'] ?>">
                            <a class="view_form_btn" data-toggle="modal" title="Template"  href="#template_details" data-id="<?php echo  $row['id'] ?>">
                             <?php
							 $message = '';
							 $category_name ='';
                              if(!empty($row['interaction_type']) && $row['interaction_type'] == 1)
							  {
							  	echo $row['label_template_name'];
								$message = $row['label_message'];
								$category_name = $row['label_category'];
							  }
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 2)
							  {
							  	echo $row['envelope_template_name'];
								$message = $row['envelope_message'];
								$category_name = $row['envelope_category'];
							  }
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 5)
							  {
							  	echo $row['letter_template_name'];
								$message = $row['letter_message'];
								$category_name = $row['letter_category'];
							  }
							?>
                            </a>
                            </td>
							<td class="hidden-xs hidden-sm text-center">
                            <input type="hidden" name="id" id="id" value="<?php echo $row['interaction_master_interaction_id'];?>"  />
                              <input type="hidden" name="mail_out_type" id="mail_out_type" value="<?=!empty($row['interaction_type'])?$row['interaction_type']:'';?>"  />
                              
                              <input type="hidden" name="contact_id" id="contact_id" value="<?=!empty($row['contact_id'])?$row['contact_id']:'';?>"  />
                            
                             <?php 
                              if(!empty($row['interaction_type']) && $row['interaction_type'] == 1)
							  	{
								?>
								<input type="hidden" name="template_id" id="template_id" value="<?=!empty($row['label_template_id'])?$row['label_template_id']:'';?>"  />
                                <input type="hidden" name="category_id" id="category_id" value="<?=!empty($row['label_category_id'])?$row['label_category_id']:'';?>"  />
								<?
								}
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 2)
							  {
								?>
								<input type="hidden" name="template_id" id="template_id" value="<?=!empty($row['envelope_template_id'])?$row['envelope_template_id']:'';?>"  />
                                
                                  <input type="hidden" name="category_id" id="category_id" value="<?=!empty($row['en_category_id'])?$row['en_category_id']:'';?>"  />
								<?
								}
							  elseif(!empty($row['interaction_type']) && $row['interaction_type'] == 5)
							  {
								?>
								<input type="hidden" name="template_id" id="template_id" value="<?=!empty($row['letter_template_id'])?$row['letter_template_id']:'';?>"  />
                                  <input type="hidden" name="category_id" id="category_id" value="<?=!empty($row['letter_category_id'])?$row['letter_category_id']:'';?>"  />
								<?
								}else{}
							?>
                              <a class="btn btn-xs btn-success" title="Complete" href="javascript:void(0);" onclick="finaldata(<?=$row['interaction_master_interaction_id']?>)">Complete</a>
                            	<!--<input type="submit" class="btn btn-xs btn-success" value="Complete" name="mail_out" title="Complete" />-->
                            </td>
							<td class="hidden-xs hidden-sm text-center">
                            <?php if(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id']) && $row['is_done'] == '0'){?>
                            	<a href="javascript:void(0)" class="btn btn-xs btn-orange"><i class="fa fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
							<?php
							}else{
							?>
                                <!--<input type="button" value="Done" name="iscompleted" onclick="iscompleted(<?php echo $row['interaction_plan_interaction_id'];?>);" class="btn btn-xs btn-success" />-->
                                <a href="javascript:void(0);" name="iscompleted" onclick="iscompleted(<?php echo $row['interaction_plan_interaction_id'];?>);" title="Done"><i class="fa btn-xs btn-success fa-check"></i></a>
                                <?php } ?>&nbsp;&nbsp;
                                <a href="javascript:void(0);" class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['interaction_master_interaction_id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['recipients'])))?>');"><i class="fa fa-times"></i></a>
                                
                                <input type="hidden" id="sortfield_1" name="sortfield_1" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                                <input type="hidden" id="sortby_1" name="sortby_1" value="<?php if(isset($sortby)) echo $sortby;?>" />
										</td>
                                <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;"><?=$message?></div>
                                <div id="temp_category_<?php echo  $row['id'] ?>" style="display:none;"><?=$category_name?></div>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          
          </tbody>
         </table>
         </form>
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_1">
             <option value=""><?=$this->lang->line('mailing_tasks_per_page');?></option>
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
			 
			if(isset($mailing_pagination))
			{
				echo $mailing_pagination;
			}
		  	?>
           </div>
         </div>
<script type="text/javascript">
/*function iscompleted(value)
{
	$.confirm({'title': 'CONFIRM','message': " <strong>Are you sure that the task is completed"+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("admin/dashboard/is_completed");?>',
			data: {
			selectedvalue:value,interaction_type:'letter'
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },

			success: function(html){
				
			$.ajax({
                type: "POST",
                url: '<?=base_url()?>admin/dashboard/<?=$viewname?>/'+html,
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
			
				$.unblockUI();
			}
		});
	}},'No'	: {'class'	: 'special',
	'action': function(){
	}
	}}});
		return false;
	
}*/
function finaldata(id)
{
	//alert()
	var myarray = new Array;
	var i=0;
	var boxes = $('input[name="check_1[]"]:checked');
	$(boxes).each(function(){
		  myarray[i]=this.value;
		  i++;
	});
	
	if(id == '0')
	{
		if(myarray.length == 0)
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to complete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;
		}
		else
		{
			$("#finalid").val(myarray);
			$("#letter_label_envelope").submit();
		}
	}
	else
		$("#finalid").val(id);
	//alert($("#finalid").val());
	$("#letter_label_envelope").submit();
}
</script>
         