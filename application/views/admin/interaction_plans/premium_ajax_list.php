<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
//pr($this->session->userdata('premium_iplans_sortsearchpage_data'));
?>
 <?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass1 = 'desc';}else{$sorttypepass1 = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
         <?php if(!empty($this->modules_unique_name) && in_array('premium_plans_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
                <div class="text-center">
                    <input type="checkbox" class="selecctall" id="selecctall1">
                </div>
            </th>
            <? } ?>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'plan_name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('plan_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('interaction_name')?></a></th>
			
            <th width="15%" class="hidden-xs hidden-sm  text-center <?php if(isset($sortfield1) && $sortfield1 == 'plan_status_name'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" width="15%" ><a href="javascript:void(0);" onclick="applysortfilte_contact1('plan_status_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_istatus')?></a></th>
             <?php if(!empty($this->modules_unique_name) && in_array('play_push_stop',$this->modules_unique_name)){?>
            <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_run_campaign')?></th>
			<? } ?>
            <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_contacts_assigned')?></th>
		
		<th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'created_by'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('created_by','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_inserted_by')?></a></th>
		<th width="18%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
        <th width="18%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%">Update Plan</th>
            <input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
            <input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($premium_datalist) && count($premium_datalist)>0){
                $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($premium_datalist as $row){?>
                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> id="view_archive_<?php echo  $row['id'] ?>" > 
                             <?php if(!empty($this->modules_unique_name) && in_array('premium_plans_delete',$this->modules_unique_name)){?>
                                <td class="">
                                    <div class="text-center">
                                        <input type="checkbox" class="mycheckbox1" name="check1[]" value="<?php echo  $row['id'] ?>">
                                    </div>												
                                </td>
                                <? } ?>
                                <td class="hidden-xs hidden-sm ">
                                    <a href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>" class="textdecoration">
                                        <?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></a>
                                </td>
                                <td class="hidden-xs hidden-sm text-center"><?=!empty($row['plan_status_name'])?$row['plan_status_name']:'';?></td>
                                 <?php if(!empty($this->modules_unique_name) && in_array('play_push_stop',$this->modules_unique_name)){?>
                                <td class="text-center">
                                    <?php if($row['total_interactions'] > 0){?>

                                        <?php if(!empty($row['plan_status_name']) && (strtolower($row['plan_status_name']) == 'active')){ ?>
                                                <button class="btn btn-xs btn-success mrgr1 no_cursor" title="Play"><i class="fa fa-play"></i></button>
                                        <?php }else{?>
                                                <?php if(!empty($row['plan_status_name']) && (strtolower($row['plan_status_name']) == 'stop')){ ?>
                                                        <button class="btn btn-xs btn-secondary-new mrgr1 play_interaction_plan1" data-group="stop" data-id="<?=$row['id']?>" title="Play"><i class="fa fa-play"></i></button>
                                                <?php }else{ ?>
                                                        <button class="btn btn-xs btn-secondary-new mrgr1 play_interaction_plan1"  data-group="" data-id="<?=$row['id']?>" title="Play"><i class="fa fa-play"></i></button>
                                                <?php } ?>
                                        <?php } ?>

                                            <?php if(!empty($row['plan_status_name']) && (strtolower($row['plan_status_name']) == 'paused')){ ?>
                                                    <button class="btn btn-xs btn-warning mrgr1 no_cursor" title="Paused"> <i class="fa fa-pause"></i></button>
                                            <?php }else{?>
                                                    <button class="btn btn-xs btn-secondary-new mrgr1 pause_interaction_plan1" data-id="<?=$row['id']?>" title="Pause" > <i class="fa fa-pause"></i></button>
                                            <?php } ?>

                                            <?php if(!empty($row['plan_status_name']) && (strtolower($row['plan_status_name']) == 'stop')){ ?>
                                                    <button class="btn btn-xs btn-primary no_cursor" title="Stop"> <i class="fa fa-stop"></i></button>
                                            <?php }else{?>
                                                    <button class="btn btn-xs btn-secondary-new stop_interaction_plan1" data-id="<?=$row['id']?>" title="Stop"> <i class="fa fa-stop"></i></button>
                                            <?php } ?>

                                    <?php } ?>
                                </td>
                                <? } ?>
                                <td class="text-center">
                                    <a title="Assigned Contacts" data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
                                            <button class="btn btn-xs btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
                                    </a>
                                </td>
								<td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?ucfirst(strtolower($row['admin_name'])):ucfirst(strtolower($row['user_name']));?></td>
                               
                                <td class="hidden-xs hidden-sm text-center">
                                    <a title="View Communication" class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>"><i class="fa fa-search"></i></a> &nbsp;
                                    <?php if(!empty($this->modules_unique_name) && in_array('premium_plans_edit',$this->modules_unique_name)){?> 
                                    <a title="Edit Communication" class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                                    <? } ?>
                                    <?php if(!empty($this->modules_unique_name) && in_array('premium_plans_delete',$this->modules_unique_name)){?> 
                                    <a title="Add to Archive" class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="active_plan_single1('<?php echo  $row['id'] ?>','<?php echo  rawurlencode(ucfirst(strtolower($row['plan_name']))) ?>');"><i class="fa fa-archive"></i></a>
                                    <? } ?>
                                </td>
                                 <td class="hidden-xs hidden-sm "> <?php if(!empty($premium_plan_update) && in_array($row['p_p_id'],$premium_plan_update) && $row['version'] == '1' && $row['created_by'] == $admin_session['admin_id']) { ?> <button class="btn btn-xs btn-success" onclick="update_plans('<?=$row['p_p_id']?>')">Update</button> <?php } ?> </td>
                                 
                            </tr>
         <?php          } } else {?>
		  <tr>
		  	<td colspan="8" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          
    </tbody>
</table>
<div class="row dt-rb" id="common_tb1">
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap float-right">
            <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">

                <label>
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages1();" id="perpage1">
                        <option <?php if(empty($perpage1)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_communications_per_page')?></option>
                        <option <?php if(!empty($perpage1) && $perpage1 == 10){ echo 'selected="selected"';}?> value="10">10</option>
                        <option <?php if(!empty($perpage1) && $perpage1 == 25){ echo 'selected="selected"';}?> value="25">25</option>
                        <option <?php if(!empty($perpage1) && $perpage1 == 50){ echo 'selected="selected"';}?> value="50">50</option>
                        <option <?php if(!empty($perpage1) && $perpage1 == 100){ echo 'selected="selected"';}?> value="100">100</option>
                    </select>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <?php if(isset($pagination1)){echo $pagination1;}?>
    </div>
</div>