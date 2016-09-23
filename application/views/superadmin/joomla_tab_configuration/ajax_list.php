<?php 
    /*
        @Description: Admin list with Joomla tab config
        @Author     : Sanjay Moghariya
        @Date       : 06-01-2015
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">
            <!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'admin_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('admin_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_id'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_id','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('superadmin_joomla_buyer_tab')?></th>
            <th width="15%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('superadmin_joomla_lead_dashboard_tab')?></th>
            <th width="15%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('superadmin_joomla_market_watch_tab')?></th>
            <th width="15%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('superadmin_joomla_contact_tab')?></th>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
            foreach($datalist as $row){
            //pr($row);exit;
            ?>
                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                    <td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?ucfirst(strtolower($row['admin_name'])):'';?></td>
                    <td class="hidden-xs hidden-sm "><?=!empty($row['email_id'])?$row['email_id']:'';?></td>
                    <td class="hidden-xs hidden-sm ">
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['is_buyer_tab'] == '1'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="is_buyer_tab_<?=$row['id']?>" id="buyer_tab" onclick="change_tab_setting('Buyer Preference','1',<?=$row['id']?>);" <?php if($row['is_buyer_tab'] == '1'){ echo 'checked="checked"'; }?> >On
                                </div>
                            </label>
                       </div>
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['is_buyer_tab'] == '0'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="is_buyer_tab_<?=$row['id']?>" id="buyer_tab" onclick="change_tab_setting('Buyer Preference','0',<?=$row['id']?>);" <?php if($row['is_buyer_tab'] == '0'){ echo 'checked="checked"'; }?> >Off
                                </div>
                            </label>
                       </div>
                    </td>
                    <td class="hidden-xs hidden-sm ">
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['lead_dashboard_tab'] == '1'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="lead_dashboard_tab_<?=$row['id']?>" id="lead_dashboard_tab" onclick="change_tab_setting('Lead Dashboard','1',<?=$row['id']?>);" <?php if($row['lead_dashboard_tab'] == '1'){ echo 'checked="checked"'; }?> >On
                                </div>
                            </label>
                       </div>
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['lead_dashboard_tab'] == '0'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="lead_dashboard_tab_<?=$row['id']?>" id="lead_dashboard_tab" onclick="change_tab_setting('Lead Dashboard','0',<?=$row['id']?>);" <?php if($row['lead_dashboard_tab'] == '0'){ echo 'checked="checked"'; }?> >Off
                                </div>
                            </label>
                       </div>
                    </td>
                    <td class="hidden-xs hidden-sm ">
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['market_watch_tab'] == '1'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="market_watch_tab_<?=$row['id']?>" id="market_watch_tab" onclick="change_tab_setting('Market Watch','1',<?=$row['id']?>);" <?php if($row['market_watch_tab'] == '1'){ echo 'checked="checked"'; }?> >On
                                </div>
                            </label>
                       </div>
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['market_watch_tab'] == '0'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="market_watch_tab_<?=$row['id']?>" id="market_watch_tab" onclick="change_tab_setting('Market Watch','0',<?=$row['id']?>);" <?php if($row['market_watch_tab'] == '0'){ echo 'checked="checked"'; }?> >Off
                                </div>
                            </label>
                       </div>
                    </td>
                    <td class="hidden-xs hidden-sm ">
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['contact_form_tab'] == '1'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="contact_form_tab_<?=$row['id']?>" id="contact_form_tab" onclick="change_tab_setting('CF','1',<?=$row['id']?>);" <?php if($row['contact_form_tab'] == '1'){ echo 'checked="checked"'; }?> >On
                                </div>
                            </label>
                       </div>
                        <div class="radio">
                            <label class="">
                                <div <?php if($row['contact_form_tab'] == '0'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                    <input type="radio" name="contact_form_tab_<?=$row['id']?>" id="contact_form_tab" onclick="change_tab_setting('CF','0',<?=$row['id']?>);" <?php if($row['contact_form_tab'] == '0'){ echo 'checked="checked"'; }?> >Off
                                </div>
                            </label>
                       </div>
                    </td>
                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            <?php } 
            } else {?>
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
                        <option value=""><?=$this->lang->line('label_admin_per_page');?></option>
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