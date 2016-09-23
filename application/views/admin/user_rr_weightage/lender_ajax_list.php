<?php 
/*
    @Description: Lender Weightage listing
    @Author     : Sanjay Moghariya
    @Date       : 05-01-2015
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
?>
<?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass1 = 'desc';}else{$sorttypepass1 = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
                <div class="text-center">
                    <input type="checkbox" class="selecctall" id="selecctall">
                </div>
            </th>-->
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'first_name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('first_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('lender_rr_weightage_label_name')?></a></th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'domain_name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('domain_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('cms_domain')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'user_weightage'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('user_weightage','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('agent_rr_weightage_label_weightage')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'minimum_price'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('minimum_price','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('leads_dashboard_price_range')?> <?php echo "(In ".$this->lang->line('currency').")" ?></a></th>
            <?php /*<th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'min_area'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('min_area','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('agent_rr_weightage_label_area_range')?> <?php echo "(In ".$this->lang->line('area_units').")" ?> </a></th>*/?>
            
             <?php if(!empty($this->modules_unique_name) && in_array('lead_distribution_lender_edit',$this->modules_unique_name)){?>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
            <? } ?>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php if(!empty($lender_datalist) && count($lender_datalist)>0)
        {
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
            foreach($lender_datalist as $row)
            { ?>
                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                    <td class="hidden-xs hidden-sm ">
                        <?php
                        $eid = !empty($row['email_id'])? ' ('.$row['email_id'].')':'';
                        ?>
                        <a title="View Profile" href="<?= $this->config->item('admin_base_url')?>user_management/view_record/<?= $row['id'] ?>" class="textdecoration"><?=!empty($row['agent_name'])?ucfirst(strtolower($row['agent_name'])).$eid:'';?></a>
                    </td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['domain_name'])?$row['domain_name']:'-';?></td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['user_weightage'])?$row['user_weightage']:'';?>
                     <input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
                        <input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
                    </td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['minimum_price'])?'$'.number_format($row['minimum_price']):'$0'?> - <?= !empty($row['maximum_price'])?'$'.number_format($row['maximum_price']):'$0';?></td>
                    <?php /*<td class="hidden-xs hidden-sm "><?=!empty($row['min_area'])?number_format($row['min_area']):'0'?> - <?= !empty($row['max_area'])?number_format($row['max_area']):'0';?></td> */?>
                     <?php if(!empty($this->modules_unique_name) && in_array('lead_distribution_lender_edit',$this->modules_unique_name)){?>
                    <td class="hidden-xs hidden-sm text-center">
                        <a class="btn btn-xs btn-success" title="Edit Record" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                        <?php /*
                        <button class="btn btn-xs btn-primary" title="Delete Record" disabled="disabled" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo ucwords($row['agent_name']) ?>');"><i class="fa fa-times"></i></button>
                         */ ?>
                       
                    </td>
                    <? } ?>
                </tr>
          <?php } 
            } else {?>
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
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages1();" id="perpage">
                        <option value=""><?=$this->lang->line('label_lender_per_page');?></option>
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