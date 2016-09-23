<?php 
/*
    @Description: Country Listing
    @Author     : Sanjay Moghariya
    @Date       : 26-12-2014
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
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
            <th width="87%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'country'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('country','<?php echo $sorttypepass;?>')"><?=$this->lang->line('country_name')?></a></th>
            <th width="8%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                foreach($datalist as $row){
                //pr($row);exit;
                ?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                        <td class="">
                            <div class="text-center">
                                <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
                            </div>
                        </td>
                        <td class="hidden-xs hidden-sm "><?=!empty($row['country'])?ucwords($row['country']):'';?></td>
                        <td class="hidden-xs hidden-sm text-center">
							
                            <? /*
                            if(!empty($row['status']) && $row['status']==1){ ?>
                                <a title="Unpublish Admin" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/unpublish_record/<?= $row['id'] ?>"><i class="fa fa-check-circle"></i></a>	&nbsp;					
                            <? }else{ ?>
                                <a title="Publish Admin" class="btn btn-xs btn-primary" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/publish_record/<?= $row['id'] ?>"><i class="fa fa-times-circle"></i></a>	&nbsp;				  
                            <? } */ ?>
                            <a title="Edit Admin" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                            <button class="btn btn-xs btn-primary" title="Delete Admin" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo $row['admin_name'] ?>');"><i class="fa fa-times"></i></button>
										
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                        </td>
                    </tr>
          <?php } } else {?>
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
                        <option value=""><?=$this->lang->line('label_country_per_page');?></option>
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
         