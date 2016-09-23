<?php 
    /*
    @Description: Joomla Leads assign plan
    @Author     : Sanjay Moghariya
    @Date       : 27-12-2014

*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>

<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <?php if(!empty($this->modules_unique_name) && in_array('auto_communication_delete',$this->modules_unique_name)){?>
      <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%"> <div class="text-center">
          <input type="checkbox" class="selecctall" id="selecctall">
        </div>
      </th>
      <?php } ?>
      <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
      <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('plan_name','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('label_assigned_iplan')?>
        </a></th>
        <? } ?>
      <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'prospect_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('prospect_type','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('joomla_assign_plan_ptype')?>
        </a></th>
      <?php /*<th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'min_price'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('min_price','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_price_range')?> <?php echo "(In ".$this->lang->line('currency').")" ?></a></th> */ ?>
      <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'status'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('status','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('joomla_assign_plan_status')?>
        </a></th>
      <? if(in_array('auto_communication_edit',$this->modules_unique_name) || in_array('auto_communication_delete',$this->modules_unique_name)){ ?>
        <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%">
      <?php echo $this->lang->line('common_label_action')?>
        </th>
      <?php } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php if(!empty($datalist) && count($datalist)>0){
                    $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){// pr($row);?>
    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
      <?php if(!empty($this->modules_unique_name) && in_array('auto_communication_delete',$this->modules_unique_name)){?>
      <td class=""><div class="text-center">
          <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
        </div></td>
      <?php } ?>
       <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
      <td class="hidden-xs hidden-sm "><a href="<?= $this->config->item('user_base_url'); ?>joomla_assign/edit_record/<?= $row['id'] ?>" class="textdecoration">
        <?=!empty($row['plan_name'])?$row['plan_name']:'';?>
        </a></td>
        <?php } ?>
      <td class="hidden-xs hidden-sm "><?=!empty($row['prospect_type'])?ucwords($row['prospect_type']):'';?></td>
      <?php /*<td class="hidden-xs hidden-sm "><?=!empty($row['min_price'])?'$'.number_format($row['min_price']):''?> - <?= !empty($row['max_price'])?'$'.number_format($row['max_price']):'';?></td> */ ?>
      <td class="hidden-xs hidden-sm "><div class="radio">
          <label class="">
          <div <?php if(!empty($row['status']) && $row['status'] == 'On'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
            <input type="radio" name="status_<?=$row['id']?>" id="joomla_contact_type" onclick="change_status('On',<?=$row['id']?>);" <?php if(!empty($row['status']) && $row['status'] == 'On'){ echo 'checked="checked"'; }?> >
            On </div>
          </label>
        </div>
        <div class="radio">
          <label class="">
          <div <?php if(!empty($row['status']) && $row['status'] == 'Off'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
            <input type="radio" name="status_<?=$row['id']?>" id="joomla_contact_type" onclick="change_status('Off',<?=$row['id']?>);" <?php if(!empty($row['status']) && $row['status'] == 'Off'){ echo 'checked="checked"'; }?> >
            Off </div>
          </label>
        </div>
        <?php //echo !empty($row['status'])?$row['status']:'' ?></td>
      <? if(in_array('auto_communication_edit',$this->modules_unique_name) || in_array('auto_communication_delete',$this->modules_unique_name)){ ?>
      <td class="hidden-xs hidden-sm text-center"><?php /*
                                            <a class="btn btn-xs btn-success" title="Copy Label"  href="<?= $this->config->item('user_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy"></i></a> &nbsp; 
                                             *  * */ ?>
        <?php if(!empty($this->modules_unique_name) && in_array('auto_communication_edit',$this->modules_unique_name)){?>
        <a class="btn btn-xs btn-success"  title="Edit Label" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp;
        <?php } ?>
        <?php if(!empty($this->modules_unique_name) && in_array('auto_communication_delete',$this->modules_unique_name)){?>
        <button class="btn btn-xs btn-primary" title="Delete Label" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['plan_name']))) ?>');"><i class="fa fa-times"></i></button>
        <?php } ?></td>
      <?php } ?>
    </tr>
    <?php } } else {?>
    <tr>
      <td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
<div class="row dt-rb" id="common_tb">
  <div class="col-sm-6">
    <div class="dataTables_paginate paging_bootstrap float-right">
      <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
        <label>
          <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
            <option value="">
            <?=$this->lang->line('label_ass_plan_per_page');?>
            </option>
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
