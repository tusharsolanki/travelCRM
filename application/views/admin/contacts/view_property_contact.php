<?php
$viewname = $this->router->uri->segments[2];
$sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                
?> 
<?php if(isset($sortby111) && $sortby111 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">                              
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield111) && $sortfield111 == 'property_name'){if($sortby111 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact111('property_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_address')?></a></th>
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield111) && $sortfield111 == 'domain'){if($sortby111 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact111('domain','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_domain')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield111) && $sortfield111 == 'name'){if($sortby111 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact111('name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
            <th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield111) && $sortfield111 == 'email'){if($sortby111 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact111('email','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield111) && $sortfield111 == 'phone'){if($sortby111 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact111('phone','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield111) && $sortfield111 == 'preferred_time'){if($sortby111 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact111('preferred_time','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_tab_contact_time')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
            <input type="hidden" id="sortfield111" name="sortfield111" value="<?php if(isset($sortfield111)) echo $sortfield111;?>" />
            <input type="hidden" id="sortby111" name="sortby111" value="<?php if(isset($sortby111)) echo $sortby111;?>" />
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($result_property_contact) && count($result_property_contact)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                foreach($result_property_contact as $rvs){?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['property_name'])?$rvs['property_name']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['domain'])?$rvs['domain']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['name'])?$rvs['name']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['email'])?$rvs['email']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['phone'])? preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $rvs['phone']):'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['preferred_time'])?date($this->config->item('common_datetime_format'),strtotime($rvs['preferred_time'])):'';?></td>
                        <td class="hidden-xs hidden-sm text-center">
                            <a title="View Property Contact" data-toggle="modal" class="btn btn-xs btn-success property_contact_popup_btn" href="#property_contact_popup" data-id="<?=!empty($rvs['id'])?$rvs['id']:'';?>"><i class="fa fa-search"></i></a> &nbsp; 
                        </td>
                    </tr>
                <?php } 
            } else {?>
                <tr>
                  <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                </tr>

            <?php } ?>
    </tbody>
</table>
                             
<div class="row dt-rb" id="common_tb111">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages111();" id="perpage111">
             <option <?php if(empty($perpage111)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_property_contact_per_page')?></option>
              <option <?php if(!empty($perpage111) && $perpage111 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage111) && $perpage111 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage111) && $perpage111 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage111) && $perpage111 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination111))
			{
				echo $pagination111;
			}
		  	?>
            </div>
           </div>
                             
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
</script>