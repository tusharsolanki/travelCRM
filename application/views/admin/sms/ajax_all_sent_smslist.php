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

 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           <!-- <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_name')?></a></th>
			
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'scr.sms_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('scr.sms_message','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
			
				 <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'stm.template_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"> Reference </th>
			
			 	<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%">View</th>
				
			 <th width="25%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'scr.sent_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('scr.sent_date','<?php echo $sorttypepass;?>')">Sent Date & Time </a></th>
			
             <!--<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>-->
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<!--<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td>-->
							<td class="hidden-xs hidden-sm ">
							<?=!empty($row['first_name'])?ucfirst(strtolower($row['first_name'])):'';?> <?=!empty($row['last_name'])?ucfirst(strtolower($row['last_name'])):'';?>
							</td>
							<td class="hidden-xs hidden-sm ">
							<?=!empty($row['sms_message'])?ucfirst(strtolower($row['sms_message'])):'';?></td>
							
							<td class="hidden-xs hidden-sm ">
							<?php
								if(!empty($row['interaction_id']))
									echo ucfirst(strtolower($row['plan_name']." >> ".$row['description']));
								else
									echo ucfirst(strtolower($row['template_name']));
							?>
								<?php //!empty($row['template_name'])?$row['template_name']:'';?>
							 </td>	
							 <td class="hidden-xs hidden-sm ">
							 <a class="btn btn-xs btn-success" href="<?=$this->config->item('admin_base_url').$viewname;?>/view_data/<?=!empty($row['sms_campaign_id'])?$row['sms_campaign_id']:'';?>/<?=$row['id']?>/all" title="View"> View </a>
							 </td>
                             <td class="hidden-xs hidden-sm ">
								<?=!empty($row['sent_date'])?date($this->config->item('common_datetime_format'),strtotime($row['sent_date'])):'';?>
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
             <option value=""><?=$this->lang->line('label_sms_cam_per_page');?></option>
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
         