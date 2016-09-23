<?php 
/*
    @Description: Submitted form list
    @Author     : Sanjay Moghariya
    @Date       : 28-04-2015
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
    $viewname = $this->router->uri->segments[2];
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_name','<?php echo $sorttypepass;?>')">Contact Name</a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'created_ip'){if($sortby == 'acreated_ipsc'){echo "created_ip";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_ip','<?php echo $sorttypepass;?>')">Domain Ip</a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'form_title'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('form_title','<?php echo $sorttypepass;?>')">Form Name</a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'filled_date'){if($sortby == 'acreated_ipsc'){echo "filled_date";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('filled_date','<?php echo $sorttypepass;?>')">Date</a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php
        //pr($datalist);exit;
        if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
            foreach($datalist as $row){
                if(!empty($current_date))
                {
                    if(date('Y-m-d H:i:s',strtotime($row['created_date'])) > date('Y-m-d H:i:s',strtotime($current_date)))
                    { ?>
                        <tr class="bgtitle new_bold_class"> 
                    <?php } else {  ?>
                        <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                    <?php 
                    }
                } else { ?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                <?php } ?>
							
                <td class="hidden-xs hidden-sm ">
                    <a data-toggle="modal" href="#basicModal1"  onclick="contact_details('<?=$row['lead_id']?>')" >
                        <?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>
                    </a>
                </td>
                <td class="hidden-xs hidden-sm ">
                    <?=!empty($row['created_ip'])?ucfirst(strtolower($row['created_ip'])):'';?>
                </td>
                <td class="hidden-xs hidden-sm ">
                    <a href="<?= $this->config->item('user_base_url'); ?>lead_capturing/edit_record/<?= $row['form_id'] ?>" class="textdecoration"><?=!empty($row['form_title'])?ucfirst(strtolower($row['form_title'])):'';?></a>
                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                </td>                                         

                <td class="hidden-xs hidden-sm "><?php if($row['filled_date']=='0000-00-00'){ echo '';}else
                    {?><?=!empty($row['filled_date'])?date($this->config->item('common_datetime_format'),strtotime($row['filled_date'])):'';}?></td>
                <td class="hidden-xs hidden-sm text-center">
                    <a href="<?= $this->config->item('user_base_url'); ?>contacts/view_record/<?= $row['id'] ?>" class="btn btn-xs btn-success">View</a>
                </td> 
							
            <?php } 
        } else {?>
            <tr>
                <td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="row dt-rb" id="common_tb">
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap float-right">
            <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
                <label>
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
                        <option value=""><?=$this->lang->line('label_leads_per_page');?></option>
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