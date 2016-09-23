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
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
                <div class="text-center">
                    <input type="checkbox" class="selecctall" id="selecctall">
                </div>
            </th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'menu_title'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('menu_title','<?php echo $sorttypepass;?>')"><?=$this->lang->line('cms_menu_title')?></a></th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'title'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('title','<?php echo $sorttypepass;?>')"><?=$this->lang->line('cms_page_title')?></a></th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'page_url'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('page_url','<?php echo $sorttypepass;?>')"><?=$this->lang->line('cms_slug')?></a></th>
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'domain_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('domain_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('cms_domain')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th> 
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                foreach($datalist as $row){
                ?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                        <td class="">
                            <div class="text-center">
                                <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
                            </div>
                        </td>
						
                        <td class="hidden-xs hidden-sm "><?=!empty($row['menu_title'])?ucfirst(strtolower($row['menu_title'])):'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['title'])?ucfirst(strtolower($row['title'])):'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['page_url'])?ucfirst(strtolower($row['page_url'])):'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['domain_name'])?strtolower($row['domain_name']):'';?></td>
							
                        <td class="hidden-xs hidden-sm text-center">
                            <? 
                            if(!empty($row['status']) && $row['status']==1){ ?>
                                <a title="Unpublish CMS" class="btn btn-xs btn-success" onclick="return status_change('0',<?= $row['id'] ?>,'<?php echo rawurlencode(ucfirst(strtolower($row['menu_title']))) ?>')" href="#"><i class="fa fa-check-circle"></i></a>	&nbsp;					
                            <? }else{ ?>
                                <a title="Publish CMS" class="btn btn-xs btn-primary" onclick="return status_change('1',<?= $row['id'] ?>,'<?php echo rawurlencode(ucfirst(strtolower($row['menu_title']))) ?>')" href="#"><i class="fa fa-times-circle"></i></a>	&nbsp;				  
                            <? } ?>
                            <a title="Edit CMS" class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                            <button class="btn btn-xs btn-primary" title="Delete CMS" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['menu_title']))) ?>');"><i class="fa fa-times"></i></button>
                        </td>		
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
<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
<div class="row dt-rb" id="common_tb">
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap float-right" >
            <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
                <label>
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
                        <option value=""><?=$this->lang->line('contact_joomla_domain_perpage');?></option>
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
         