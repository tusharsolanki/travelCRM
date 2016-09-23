<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
//pr($datalist); 
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')">Contact Name</a></th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'property_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('property_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_address')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'domain'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('domain','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_domain')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
            <th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'preferred_time'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('preferred_time','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_tab_contact_time')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th> 
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                foreach($datalist as $row){
                ?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                        <td class="hidden-xs hidden-sm "><?=!empty($row['contact_name'])?ucfirst($row['contact_name']):'N/A';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['property_name'])?$row['property_name']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['domain'])?$row['domain']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['name'])?$row['name']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['email'])?$row['email']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['phone'])? preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $row['phone']):'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['preferred_time'])? $row['preferred_time']:'';?></td>
                        <td class="hidden-xs hidden-sm text-center">
                            <a title="View <?=$this->lang->line('joomla_contact_form')?>" data-toggle="modal" class="btn btn-xs btn-success property_contact_popup_btn" href="#property_contact_popup" data-id="<?=!empty($row['id'])?$row['id']:'';?>"><i class="fa fa-search"></i></a> &nbsp; 
                        </td>
										
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                        
                    </tr>
						  
                <?php //$j++; 
                } }
            else {?>
                <tr>
                    <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
              </tr>
		  
        <?php } ?>
    </tbody>
</table>
<div class="row dt-rb" id="common_tb">
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap float-right" >
            <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
                <label>
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
                        <option value=""><?=$this->lang->line('label_property_contact_per_page');?></option>
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
         