<?php 
/*
    @Description: User Dashborad task list
    @Author     : Sanjay Chabhadiya
    @Date       : 11-11-14
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[3];
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
            
            <th width="22%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')">Communication </a></th>
            <th width="6%" data-direction="desc" data-sortable="true" data-filterable="true"  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><?=$this->lang->line('common_label_action')?></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'desc'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version">Telephone</th>
            
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'task_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version">Script</th>
            
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'desc'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"> Disposition </th>
            
		    <th width="6%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1) echo 'bgtitle '; if(strtotime($row['task_date']) < strtotime($dt)) echo 'new_red_class'; ?>"  > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_2" name="check_2[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
							<?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>
                            </td>
                            
                            <td class="hidden-xs hidden-sm "><?=!empty($row['communication'])?ucfirst(strtolower($row['communication'])):'';?></td>
                            <td class="hidden-xs hidden-sm">
										<a href="#basicModal_email_popup1" style="display:none;" class="text_size" id="basicModal_email_popup_<?=$row['contact_id']?>" data-toggle="modal" onclick="add_email_campaign('<?=$row['contact_id']?>','<?=$row['em_id']?>')">email</a>
                                        <? if(!empty($row['em_id'])){?>
                                            <a class="btn btn-xs btn-success smaller_btn_new1"  title="New Email" href="javascript:void(0);" onclick="show_action('basicModal_email_popup_<?=$row['contact_id']?>',<?=$row['contact_id']?>);"><i class="fa fa-envelope"></i></a>
                                            <? } ?>
                                            <a class="btn btn-xs btn-success smaller_btn_new1"  title="Add Note" href="javascript:void(0);" onclick="show_action('Add_Note',<?=$row['contact_id']?>);"><i class="fa fa-paste"></i></a> <br />
                                           
                                            <a class="btn btn-xs btn-success smaller_btn_new1"  title="Set To Do" href="javascript:void(0);" onclick="show_action('set_to_do',<?=$row['contact_id']?>);"><i class="fa fa-file-text"></i></a>
                                                                              
                            </td>
                            <td class="hidden-xs hidden-sm ">
                            <a href="#basicModal1" class="text_size howler" id="basicModal" data-toggle="modal" onclick="phone_call('<?=$i?>')" >
                            <?=!empty($row['phone_no'])?$row['phone_no']:''?>
                            </a>
                            </td>
							<td class="hidden-xs hidden-sm ">
                            <a class="view_form_btn" data-toggle="modal" title="Template"  href="#template_details" data-id="<?php echo  $row['id'] ?>">
                            <?php
									if(!empty($row['calling_script']))
									{
										if(strlen($row['calling_script']) > 50)
										{?>
                                        	<div class="div_dash_less_data div_dash_display_data">
												<?php echo substr(ucfirst($row['calling_script']),0,50)."...";?>
                                                <!--<a href="javascript:void(0);" class="div_dash_more_data_a">Read more</a>-->
                                            </div>
                                            <!--<div class="div_dash_more_data div_dash_display_none">
                                            	<?php echo ucfirst($row['calling_script']);?>
                                                <a href="javascript:void(0);"  class="div_dash_less_data_a">Less</a>
                                            </div>-->
										<?php }
										else
										{
											echo ucfirst(strtolower($row['calling_script']));
										}
									}
								?>
                            <?php /*?> <?=!empty($row['calling_script'])?$row['calling_script']:''?><?php */?>
                            </a>
                            <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;"><?=!empty($row['calling_script'])?ucfirst(strtolower($row['calling_script'])):''?></div>
                            </td>
                            <td class="hidden-xs hidden-sm">
                            <?php if(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id']) && $row['is_done'] == '0'){?>
                            	&nbsp;<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-orange fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
                            <?php
							}else{
							?>
                            <input type="radio" name="disposition" class="disposition" onclick="iscompleted('<?=$row['id']?>',this.value);" value="1" />&nbsp; T &nbsp;
                            <input type="radio" name="disposition" class="disposition" onclick="iscompleted('<?=$row['id']?>',this.value);" value="2" />&nbsp; LM &nbsp;
                            <input type="radio" name="disposition" class="disposition" onclick="addtxt('<?=$row['id']?>',this.value);" value="3" />&nbsp; NA &nbsp;
                            <div class="txt_common_div_<?=$row['id']?>">
                            </div>
                            <?php } ?>
                             <?php /*if(!empty($contact_disposition_master) && count($contact_disposition_master) > 0) { 
									$i=0; // pr($contact_disposition_master);
										foreach($contact_disposition_master as $row) { ?>
								<input <?php if($i==0) { echo "checked=checked"; $i++; } ?> type="radio" name="disposition" id="disposition" value="<?=$row['id']?>" />&nbsp; <?=$row['name']?> &nbsp; 
							 <?php 
										}
									}*/
							 ?>
							</td>
							<td class="hidden-xs hidden-sm text-center">
                    			<!--<input type="checkbox" value="<?php echo $row['id'];?>" name="iscompleted" onclick="iscompleted(this.value);" class="complted_task_checkbox" />&nbsp;&nbsp;-->
                                <button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['contact_name'])))?>');"><i class="fa fa-times"></i></button>
							</td>
                          </tr>
          <?php $i++; } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <input type="hidden" id="sortfield_2" name="sortfield_2" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
         <input type="hidden" id="sortby_2" name="sortby_2" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <input class="" type="hidden" name="interaction_id" id="interaction_id" value="">
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_2">
             <option value=""><?=$this->lang->line('phone_tasks_per_page');?></option>
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

<?php
if(!empty($datalist)) { ?>
$(".start_call").show();
<?php } else { ?>
$(".start_call").hide();
<?php } ?>

$(function() {
	$( "#followup_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});

function addtxt(id,value)
{
	var inlinehtml = '';
	if($("#interaction_id").val().trim() != '')
		$('.txt_common_div_'+$("#interaction_id").val()).html('');
	$("#interaction_id").val(id);
	inlinehtml += '<div class="padding-top-10">';
	 inlinehtml += '<div class="row col-sm-8">';
	  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>';
	  inlinehtml += '<input type="text" class="form-control parsley-validated" id="task_date" name="task_date" placeholder="e.g. Date" value="<?=date('Y-m-d',strtotime(date('Y-m-d')."+1 Days"))?>">';
	 inlinehtml += '</div>';
	 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
		inlinehtml += '<div class="">';
		 //inlinehtml += '<label>&nbsp;</label>';
		 inlinehtml += '<input type="submit" title="Save" class="btn btn-secondary-green" value="Save" onclick="return validation();" name="submitbtn" />';
		inlinehtml += '</div>';
	   inlinehtml += '</div>';
	inlinehtml += '</div>';
	
	$('.txt_common_div_'+id).append(inlinehtml);
	
	$( "#task_date" ).datepicker({
		//showOn: "both",
		<?php if(!empty($dt) && $dt > date('Y-m-d')) { ?>
		minDate:0,
		<?php } else { ?>
		minDate:1,
		<?php } ?>
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+1",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'yy-mm-dd',
		buttonImageOnly: false
	});	
}

function validation()
{
	if($("#task_date").val().trim() == '')
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select date "+"<strong></strong>",
		'buttons': {'ok'	: {
				'class'	: 'btn_center alert_ok',	
				'action': function(){
					$('#task_date').focus();
		}},  }});
	}
	else
	{
		iscompleted($("#interaction_id").val(),'3');
	}
}

</script>