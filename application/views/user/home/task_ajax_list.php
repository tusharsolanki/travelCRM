<?php 
/*
    @Description: User Dashborad task list
    @Author     : Sanjay Chabhadiya
    @Date       : 12-11-14
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
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'task_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_name','<?php echo $sorttypepass;?>')"><?php //$this->lang->line('task_label_name')?>Title</a></th>
            
            <th width="48%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'desc'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('desc','<?php echo $sorttypepass;?>')"><?=$this->lang->line('task_list_desc')?></a></th>
            
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'task_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('task_date','<?php echo $sorttypepass;?>')"><?php $this->lang->line('taskdate_label_name')?>Date</a></th>
			
            <!--<th width="12%" class="hidden-xs hidden-sm text-center"><?=$this->lang->line('completed_name')?></a></th>-->
			
		    <th width="15%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1) echo 'bgtitle '; if(strtotime($row['task_date']) < strtotime($dt)) echo 'new_red_class'; ?>" > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_4" name="check_4[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
                            <a class="view_form_btn" data-toggle="modal" title="View"  href="#template_details" data-id="<?php echo  $row['id'] ?>">
										<!--<a title="Task View" href="<?= $this->config->item('user_base_url').$viewname; ?>/view_records/<?= $row['id'] ?>">--><?=!empty($row['task_name'])?ucwords($row['task_name']):'';?></a>
										</td>
                                        <td class="hidden-xs hidden-sm ">
                                        <a class="view_form_btn" data-toggle="modal" title="View"  href="#basicModal" data-id="<?php echo  $row['id'] ?>">
                                                        <?php
															if(!empty($row['description']))
															{
																if(strlen($row['description']) > 50)
																{?>
																	<div class="div_dash_less_data div_dash_display_data">
																		<?php echo substr(ucfirst($row['description']),0,50)."...";?>
																		<a href="javascript:void(0);" class="div_dash_more_data_a">Read more</a>
																	</div>
																	<div class="div_dash_more_data div_dash_display_none">
																		<?php echo ucfirst(strtolower($row['description']));?>
																		<a href="javascript:void(0);"  class="div_dash_less_data_a">Less</a>
																	</div>
																<?php }
																else
																{
																	echo ucfirst(strtolower($row['description']));
																}
															}
														?>
                                                           <?php /*?> <?=!empty($row['description'])?ucfirst($row['description']):'';?><?php */?>
                                                           </a>
                                                        </td>
							<td class="hidden-xs hidden-sm "><?php if($row['task_date']=='0000-00-00'){ echo '';}else
							{?><?=!empty($row['task_date'])?date($this->config->item('common_date_format'),strtotime($row['task_date'])):'';}?></td>
							<td class="hidden-xs hidden-sm text-center">
                            <a href="javascript:void(0);" class="btn" id="iscompleted<?=$i;?>" onclick="iscompleted(<?php echo $row['id'];?>);" title="Done"><i class="fa btn-xs btn-success fa-check"></i></a>
	                        <!--<input type="button" id="iscompleted<?=$i;?>" value="Done" name="iscompleted" onclick="iscompleted(<?php echo $row['id'];?>);" class="btn btn-xs btn-success" />-->
                            <button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['task_name'])))?>');"><i class="fa fa-times"></i></button>
							</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          <input type="hidden" id="sortfield_4" name="sortfield_4" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
          <input type="hidden" id="sortby_4" name="sortby_4" value="<?php if(isset($sortby)) echo $sortby;?>" />
          </tbody>
         </table>
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_4">
             <option value=""><?=$this->lang->line('label_tasks_per_page');?></option>
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
<script type="text/javascript">
function iscompleted(value)
{
	$.confirm({'title': 'CONFIRM','message': " <strong>Are you sure that the task is completed"+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("user/dashboard/iscompleted");?>',
			data: {
			selectedvalue:value
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },

			success: function(html){
				
			$.ajax({
                type: "POST",
                url: '<?=base_url()?>user/dashboard/daily_task/'+html,
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
		$('.complted_task_checkbox:checkbox[value='+parseInt(value)+']').attr('checked',false);
	}
	}}});
		return false;
	
}

</script>
         