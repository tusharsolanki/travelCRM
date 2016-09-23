<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
//pr($this->session->userdata('premium_iplans_sortsearchpage_data'));
?>
 <?php if(isset($sortby2) && $sortby2 == 'asc'){ $sorttypepass2 = 'desc';}else{$sorttypepass2 = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
                <div class="text-center">
                    <input type="checkbox" class="selecctall" id="selecctall2">
                </div>
            </th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield2) && $sortfield2 == 'plan_name'){if($sortby2 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact2('plan_name','<?php echo $sorttypepass2;?>')"><?=$this->lang->line('interaction_name')?></a></th>
			
            <th width="15%" class="hidden-xs hidden-sm  text-center <?php if(isset($sortfield2) && $sortfield2 == 'plan_status_name'){if($sortby2 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" ><a href="javascript:void(0);" onclick="applysortfilte_contact2('plan_status_name','<?php echo $sorttypepass2;?>')"><?=$this->lang->line('common_label_istatus')?></a></th>
            
			
            <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_contacts_assigned')?></th>
		
		<?php /*?><th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield2) && $sortfield2 == 'created_by'){if($sortby2 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact2('created_by','<?php echo $sorttypepass2;?>')"><?=$this->lang->line('common_label_inserted_by')?></a></th><?php */?>
		<th width="18%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
        
       
            <input type="hidden" id="sortfield2" name="sortfield2" value="<?php if(isset($sortfield2)) echo $sortfield2;?>" />
            <input type="hidden" id="sortby2" name="sortby2" value="<?php if(isset($sortby2)) echo $sortby2;?>" />
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($default_datalist) && count($default_datalist)>0){
                $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($default_datalist as $row){?>
                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> id="view_archive_<?php echo  $row['id'] ?>" > 
                                <td class="">
                                    <div class="text-center">
                                        <input type="checkbox" class="mycheckbox2" name="check2[]" value="<?php echo  $row['id'] ?>">
                                    </div>												
                                </td>
                                <td class="hidden-xs hidden-sm ">
                                    <a href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>" class="textdecoration">
                                        <?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></a>
                                </td>
                                <td class="hidden-xs hidden-sm text-center"><?=!empty($row['plan_status_name'])?$row['plan_status_name']:'';?></td>
                                
                                <td class="text-center">
                                    <a title="Assigned Contacts" data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
                                            <button class="btn btn-xs btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
                                    </a>
                                </td>
								<!--<td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?$row['admin_name']:$row['user_name'];?></td>-->
                                <td class="hidden-xs hidden-sm text-center">
                                    <a title="Un-Archive" class="btn btn-xs btn-success" href="javascript:void(0);" onclick="active_plan_single2('<?php echo  $row['id'] ?>','<?php echo  rawurlencode(ucfirst(strtolower($row['plan_name']))) ?>');">Un-Archive</a> 
                                </td>
                            </tr>
         <?php          } } else {?>
		  <tr>
		  	<td colspan="8" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          
    </tbody>
</table>
<div class="row dt-rb" id="common_tb2">
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap float-right">
            <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">

                <label>
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages2();" id="perpage2">
                        <option <?php if(empty($perpage2)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_communications_per_page')?></option>
                        <option <?php if(!empty($perpage2) && $perpage2 == 10){ echo 'selected="selected"';}?> value="10">10</option>
                        <option <?php if(!empty($perpage2) && $perpage2 == 25){ echo 'selected="selected"';}?> value="25">25</option>
                        <option <?php if(!empty($perpage2) && $perpage2 == 50){ echo 'selected="selected"';}?> value="50">50</option>
                        <option <?php if(!empty($perpage2) && $perpage2 == 100){ echo 'selected="selected"';}?> value="100">100</option>
                    </select>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <?php if(isset($pagination2)){echo $pagination2;}?>
    </div>
</div>