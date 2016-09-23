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
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')">Name</a></th>
             <? if(in_array('communications',$this->modules_unique_name)){ ?>
            <th width="67%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')">Communication </a></th>
            <? } ?>
		   <th width="13%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1) echo 'bgtitle '; if(strtotime($row['task_date']) < strtotime($dt)) echo 'new_red_class'; ?>"  > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_6" name="check_6[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></td>
                             <? if(in_array('communications',$this->modules_unique_name)){ ?>
                            <td class="hidden-xs hidden-sm ">
							<?php
									if(!empty($row['communication']))
									{
										if(strlen($row['communication']) > 50)
										{?>
                                        	<div class="div_dash_less_data div_dash_display_data">
												<?php echo substr(ucfirst($row['communication']),0,50)."...";?>
                                                <a href="javascript:void(0);" class="div_dash_more_data_a">Read more</a>
                                            </div>
                                            <div class="div_dash_more_data div_dash_display_none">
                                            	<?php echo ucfirst(strtolower($row['communication']));?>
                                                <a href="javascript:void(0);"  class="div_dash_less_data_a">Less</a>
                                            </div>
										<?php }
										else
										{
											echo ucfirst(strtolower($row['communication']));
										}
									}
								?>
							<?php /*?><?=!empty($row['communication'])?ucwords($row['communication']):'';?><?php */?></td>
                            <? } ?>
							<td class="hidden-xs hidden-sm text-center">
                            <?php if(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id']) && $row['is_done'] == '0'){?>
								<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-orange fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
                             <?php
							}else{
							?>
                    			<!--<input type="button" value="Done" name="iscompleted" onclick="iscompleted(<?php echo $row['id'];?>);" class="btn btn-xs btn-success" />-->
                                <a href="javascript:void(0);" class="btn" id="iscompleted<?=$i;?>" onclick="iscompleted(<?php echo $row['id'];?>);" title="Done"><i class="fa btn-xs btn-success fa-check"></i></a>
                                <?php } ?>
                                <button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['contact_name'])))?>');"><i class="fa fa-times"></i></button>
                                
                                <input type="hidden" id="sortfield_6" name="sortfield_6" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                                <input type="hidden" id="sortby_6" name="sortby_6" value="<?php if(isset($sortby)) echo $sortby;?>" />
							</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_6">
             <option value=""><?=$this->lang->line('all_tasks_per_page');?></option>
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
/*function iscompleted(id,value)
{
	$.confirm({'title': 'CONFIRM','message': " <strong>Are you sure that the task is completed"+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("admin/dashboard/is_completed");?>',
			data: {
			selectedvalue:id,disposition:value,interaction_type:'to-do'
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
		$('.disposition:radio[value='+parseInt(value)+']').attr('checked',false);
	}
	}}});
		return false;
	
}*/

</script>
         