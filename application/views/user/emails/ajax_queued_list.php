<?php 
    /*
        @Description: User Queued List
        @Author: Sanjay Chabhadiya
        @Date: 29-09-14
    */
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
//pr($editRecord);
?>

 <?php if(isset($sort) && $sort == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
			<!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="95%" class="hidden-xs hidden-sm <?php if(isset($sortfield_name) && $sortfield_name == 'ipm.plan_name'){if($sort == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')"> Communication Plan </a></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($interaction_plan) && count($interaction_plan)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($interaction_plan as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
							<!--<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td>-->
							<td class="hidden-xs hidden-sm ">
                            <?php if($viewname == 'emails') {?>
                            	<a href="<?php echo $this->config->item('user_base_url').$viewname?>/interaction_plan_queued_list/<?=$row['id']?>"> <?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?> </a>
                            <?php } else { ?>
                            	<a href="<?php echo $this->config->item('user_base_url').$viewname?>/interaction_queued_list/<?=$row['id']?>"><?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></a>
                            <?php } ?>
							</td>			
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
		  <input type="hidden" id="sortfield_name" name="sortfield_name" value="<?php if(isset($sortfield_name)) echo $sortfield_name;?>" />
										<input type="hidden" id="sort" name="sort" value="<?php if(isset($sort)) echo $sort;?>" />
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb_interaction">
          	<div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
			
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_communications_per_page');?></option>
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
			if(isset($interaction_pagination))
			{
				echo $interaction_pagination;
			}
		  	?>
            </div>
           
         </div>
