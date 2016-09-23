<?php 
/*
    @Description: User Dashborad task list
    @Author     : Sanjay Chabhadiya
    @Date       : 11-11-14
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
//$viewname = $this->router->uri->segments[3];
$uri_segment = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';
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
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')">Recipients</a></th>
            
             <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')">Communication</a></th>
             
            <th width="35%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ecrt.sms_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('scrt.sms_message','<?php echo $sorttypepass;?>')">Text message</a></th>
			
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
                                  <input type="checkbox" class="mycheckbox_3" name="check_3[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>							</td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['communication'])?ucfirst(strtolower($row['communication'])):'';?></td>
							<td class="hidden-xs hidden-sm">
                            <a class="view_form_btn" data-toggle="modal" title="Template"  href="#template_details" data-id="<?php echo  $row['id'] ?>">
							<?php
									if(!empty($row['sms_message']))
									{
										if(strlen($row['sms_message']) > 50)
										{?>
                                        	<div class="div_dash_less_data div_dash_display_data">
												<?php echo substr(ucfirst($row['sms_message']),0,50)."...";?>
                                                <!--<a href="javascript:void(0);" class="div_dash_more_data_a">Read more</a>-->
                                            </div>
                                            <!--<div class="div_dash_more_data div_dash_display_none">
                                            	<?php echo ucfirst($row['sms_message']);?>
                                                <a href="javascript:void(0);"  class="div_dash_less_data_a">Less</a>
                                            </div>-->
										<?php }
										else
										{
											echo ucfirst(strtolower($row['sms_message']));
										}
									}
								?>
                             </a>
							<?php /*?><?=!empty($row['sms_message'])?ucfirst($row['sms_message']):'';?><?php */?></td>
							<td class="hidden-xs hidden-sm text-center">
                           <!-- <input type="button" class="btn btn-xs btn-success" onclick="resend_mail('<?=$row['id']?>','<?=$row['contact_id']?>')" value="Send" title="Send">&nbsp; -->
                           <?php
						   if((isset($row['is_default']) && $row['is_default'] == '0') || empty($row['is_default'])){?>
                           		<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-blue fa-info-circle" title="<?=$this->lang->line('phone_not_exist')?>"></i></a>
                           <?php
						   }elseif(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id']) && $row['is_done'] == '0'){?>
                           		<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-orange fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
                           <?php
						   }else{ ?>
                            	<a href="javascript:void(0);" class="btn" onclick="resend_mail('<?=$row['id']?>','<?=$row['contact_id']?>')" title="Send"><i class="fa btn-xs btn-success fa-check"></i></a>
                            <!--<input type="button" class="btn btn-xs btn-success" onclick="resend_mail('<?=$row['id']?>','<?=$row['contact_id']?>')" value="Send" title="Send">-->
                            <?php } ?>&nbsp;
										<button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['contact_name'])))?>');"><i class="fa fa-times"></i></button>
							</td>
                          </tr>
                          <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;"><?=!empty($row['sms_message'])?ucfirst($row['sms_message']):''?></div>
          <?php } ?>
          					<input type="hidden" id="sortfield_3" name="sortfield_3" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
							<input type="hidden" id="sortby_3" name="sortby_3" value="<?php if(isset($sortby)) echo $sortby;?>" />
		  					<input class="" type="hidden" name="uri_segment_3" id="uri_segment_3" value="<?=!empty($uri_segment)?$uri_segment:'0';?>">
		  		
		  <?php  } else {?>
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
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_3">
             <option value=""><?=$this->lang->line('sms_tasks_per_page');?></option>
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