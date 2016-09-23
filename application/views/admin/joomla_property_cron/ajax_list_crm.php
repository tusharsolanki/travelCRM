<?php 
/*
    @Description: Joomla property cron ajax list CRM suggestion
    @Author     : Sanjay Moghariya
    @Date       : 22-06-2015
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$tabid = !empty($tabid)?$tabid:'1';
?>
 <?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass1 = 'desc';}else{$sorttypepass1 = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
                <div class="text-center">
                    <input type="checkbox" class="selecctall1" id="selecctall1">
                </div>
            </th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('joomla_property_cron_name')?></a></th>
            <?php /*
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'country'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('country','<?php echo $sorttypepass;?>')"><?=$this->lang->line('joomla_property_cron_country')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'state'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('state','<?php echo $sorttypepass;?>')"><?=$this->lang->line('joomla_property_cron_state')?></a></th>
             */?>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'neighborhood'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('neighborhood','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('joomla_property_address')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'cron_type'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('cron_type','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('joomla_property_cron_crontype')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php if(!empty($result_crm) && count($result_crm)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
            foreach($result_crm as $row){?>
                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                    <td class="">
                        <div class="text-center">
                            <input type="checkbox" class="mycheckbox1" name="check1[]" value="<?php echo  $row['id'] ?>">
                        </div>
                    </td>
                    <td class="hidden-xs hidden-sm ">
                        <a title="View Contacts" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>"><?=!empty($row['name'])?ucwords($row['name']):'';?></a>
                    </td>
                    <?php /*
                    <td class="hidden-xs hidden-sm"><?=!empty($row['country'])?$row['country']:''?></td>
                    <td class="hidden-xs hidden-sm"><?=!empty($row['state'])?$row['state']:''?></td>*/ ?>
                    <td class="hidden-xs hidden-sm">
                        <?php
                        $addr = !empty($row['neighborhood'])?$row['neighborhood']:'';
                        $addr .= !empty($row['city'])?', '.$row['city']:'';
                        $addr .= !empty($row['zip_code'])?' '.$row['zip_code']:'';
                        echo $addr;
                        ?>
                    </td>
                    <td class="hidden-xs hidden-sm"><?=!empty($row['cron_type'])?$row['cron_type']:''?></td>
                    <td class="hidden-xs hidden-sm text-center">
                        <a class="btn btn-xs btn-success" title="Edit Record" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record1/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                        <button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopupcrm('<?php echo  $row['id'] ?>','<?php echo $row['name'] ?>');"><i class="fa fa-times"></i></button>

                        <input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
                        <input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
                    </td>
                </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
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
                        <option value=""><?=$this->lang->line('label_joomla_cron_per_page')?></option>
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
       <?php 
       if(isset($pagination1))
       {
            echo $pagination1;
       }
       ?>
     </div>
</div>
         