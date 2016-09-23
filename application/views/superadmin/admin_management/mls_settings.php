<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
    $tabid = $this->router->uri->segments[5];
else
    $tabid = 1;
	
$formAction = !empty($editRecord)?'insert_mls_settings':'insert_mls_settings'; 
if(isset($insert_data))
{
$formAction ='insert_mls_settings'; 
}
$path = $viewname.'/'.$formAction;
?>
<style>
.view_module{display:none;}
.hide_module{display:none;}
.checkall label{cursor:pointer;}
.checkall label:hover{text-decoration:underline;}
.heading{background-color:#e8f6ee;}
.hide_div .all_check,.hide_div .all{display:none;}
.div_cls {width:12%; float:left; margin-right: 5px !important;}
</style>

<div id="content">
    <div id="content-header">
        <h1>
            <?=$this->lang->line('mls_settings_header');?>
        </h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i>
                            <?php if(empty($editRecord)){ echo $this->lang->line('mls_settings_add_head');}
                                else if(!empty($insert_data)){ echo $this->lang->line('mls_settings_add_head'); } 
                                else{ echo $this->lang->line('mls_settings_edit_head'); }?>
                        </h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
                    </div>
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="text-input"><?=$this->lang->line('admin_common_label_name');?></label>
                                                <? if(!empty($admin_name)) { $dis='style="display:none;"';
                                                    echo '<br>'.$admin_name[0]['admin_name'].'  ('.$admin_name[0]['email_id'].')';
                                                }else{ $dis='';}
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row checkall">
                                            <div class="col-sm-2">
                                                <label for="check_all" id="check_all">Check All</label>
                                                <!--<input type="checkbox" id="check_all" class="" name="check_all" value="">-->
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="uncheck_all" id="uncheck_all">Uncheck All</label>
                                            </div>
                                        </div>

                                        <table class="table table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                            <thead class="heading">
                                                <tr>
                                                    <td width="15%"><label class="all"><?=$this->lang->line('mls_criteria')?></label></td>
                                                    <td width="85%"><label class="all"><?=$this->lang->line('mls_value')?></label></td>
                                                </tr>
                                            </thead>
                                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                <?php
                                                if(!empty($mls_property_type) || !empty($mls_status_list) || !empty($mls_area_list))
                                                {
                                                ?>
                                                    <tr class="" id="module_pt">
                                                        <td class="hidden-xs hidden-sm" id="pt"><?=$this->lang->line('mls_property_type')?></td>
                                                        <td class="hidden-xs hidden-sm" >
                                                            <?php
                                                            if(!empty($mls_property_type)) {
                                                                foreach($mls_property_type as $ptrow) {
                                                                ?>
                                                                    <div class="hidden-xs hidden-sm div_cls">
                                                                        <input class="all all_check main parent_view view_<?=!empty($ptrow['id'])?$ptrow['id']:'';?> div_cls" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($ptrow['id'])?$ptrow['id']:'',$assigned_property_type)) {?> checked="" <?php } }?> value="<?=!empty($ptrow['id'])?$ptrow['id'].'#'.$ptrow['name']:'';?>" name="chk_property_type[]" />
                                                                            <?=!empty($ptrow['name'])?$ptrow['name']:'';?>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="" id="module_pt">
                                                        <td class="hidden-xs hidden-sm" id="pt"><?=$this->lang->line('mls_property_status')?></td>
                                                        <td class="hidden-xs hidden-sm" >
                                                            <?php
                                                            if(!empty($mls_status_list)) {
                                                                foreach($mls_status_list as $strow) {
                                                                ?>
                                                                    <div class="hidden-xs hidden-sm div_cls">
                                                                        <input class="all all_check main parent_view view_<?=!empty($strow['id'])?$strow['id']:'';?> div_cls" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($strow['id'])?$strow['id']:'',$assigned_status)) {?> checked="" <?php } }?> value="<?=!empty($strow['id'])?$strow['id'].'#'.$strow['name']:'';?>" name="chk_status[]" />
                                                                            <?=!empty($strow['name'])?$strow['name']:'';?>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="" id="module_pt">
                                                        <td class="hidden-xs hidden-sm" id="pt"><?=$this->lang->line('mls_property_area')?></td>
                                                        <td class="hidden-xs hidden-sm" >
                                                            <?php
                                                            if(!empty($mls_area_list)) {
                                                                foreach($mls_area_list as $arearow) {
                                                                ?>
                                                                    <div class="hidden-xs hidden-sm div_cls">
                                                                            <input class="all all_check main parent_view view_<?=!empty($arearow['id'])?$arearow['id']:'';?> div_cls" type="checkbox" <?php  if(isset($editRecord)){ if(in_array(!empty($arearow['id'])?$arearow['id']:'',$assigned_area)) {?> checked="" <?php } }?> value="<?=!empty($arearow['id'])?$arearow['id']:'';?>" name="chk_area[]" />
                                                                                <?=!empty($arearow['name'])?$arearow['name']:'';?>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <?
                                            $id=$this->uri->segment(4);
                                            ?>
                                            <input type="hidden" name="id" value="<?=!empty($id)?$id:''?>" />
                                            <input type="submit" class="btn btn-secondary-green" title="Save" value="Save" onclick="return showloading();" name="submitbtn" />
                                            <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
	   
        //For check all check box
        $('#check_all').click(function(){
                 $('#DataTables_Table_0 input:checkbox').each(function(){
                           this.checked=true;
                 });
                 $("#uncheck_all").prop("checked", false);

        });

        //For uncheck all check box
        $('#uncheck_all').click(function(){
                 $('#DataTables_Table_0 input:checkbox').each(function(){
                         this.checked=false;
                 }); 
                 $("#check_all").prop("checked", false);
        });
    });
    function showloading()
    {
       if ($('#<?php echo $viewname?>').parsley().isValid()) {
           $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
       }
       $('.parsley-form').submit();
    }
</script>