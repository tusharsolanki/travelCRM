<?php 
    /*
        @Description: Superadmin Carousels list
        @Author     : Sanjay Moghariya
        @Date       : 30-04-2015
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];

$edit_id = !empty($editRecord[0]['id'])?$editRecord[0]['id']:0;
if(!empty($editRecord))
    $edit_id = $editRecord[0]['id'];
else if(!empty($update_id))
    $edit_id = $update_id;
else
    $edit_id = 0;
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
            <th width="60%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'carousels_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('carousels_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('label_carousels_name')?></a></th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'order_of_position'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('order_of_position','<?php echo $sorttypepass;?>')"><?=$this->lang->line('carousels_label_order')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th> 
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <div class="row dt-rt">
            <?php if(!empty($msg1)){?>
                <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg1).'</label>';
                $newdata = array('msg'  => '');
                $this->session->set_userdata('message_session', $newdata);?> </div>
            <?php } ?>
        </div>
        <?php 
        if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
            foreach($datalist as $row){
            ?>
                <tr <?php if($i%2==1){ ?>class="bgtitle" <?php }?> > 

                    <td class="">
                        <div class="text-center">
                            <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'].'@'.$row['child_record_id'] ?>">
                        </div>
                    </td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['carousels_name'])?ucfirst(strtolower($row['carousels_name'])):'';?></td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['order_of_position'])?$row['order_of_position']:0;?></td>						
                    <td class="hidden-xs hidden-sm text-left">
                        <?php 
                        if(!empty($row['status']) && $row['status']==1){ ?>
                            <a title="Unpublish Carousels" class="btn btn-xs btn-success" onclick="return status_change('0',<?= $row['id'] ?>,<?=$edit_id?>,<?=$row['child_record_id']?>)" href="javascript:void(0);"><i class="fa fa-check-circle"></i></a>&nbsp;
                        <?php }else{ ?>
                            <a title="Publish Carousels" class="btn btn-xs btn-primary" onclick="return status_change('1',<?= $row['id'] ?>,<?=$edit_id?>,<?=$row['child_record_id']?>)" href="javascript:void(0);"><i class="fa fa-times-circle"></i></a>	&nbsp;
                        <?php } ?>
                        <a title="Edit Carousels" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_carousels/<?=$edit_id?>/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                        <button class="btn btn-xs btn-primary" title="Delete Carousels" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['carousels_name']))) ?>','<?=$row['child_record_id']?>');"><i class="fa fa-times"></i></button>

                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                    </td>		
                </tr>

            <?php
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
                        <option value=""><?=$this->lang->line('carousels_label_perpage');?></option>
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
    <div class="col-sm-12 pull-left text-center margin-top-10">
        <a class="btn btn-secondary" title="Continue" href="<?php echo base_url();?>superadmin/child_admin/edit_record/<?=$edit_id?>/4">Continue</a>
        <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
     $("#div_msg1").fadeOut(4000); 
});
</script>